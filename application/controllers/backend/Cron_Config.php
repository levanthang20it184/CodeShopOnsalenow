<?php defined('BASEPATH') or exit('No direct script access allowed');
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
class Cron_Config extends CI_Controller
{

    public $csvMaxLineLength;

    public function __construct()
    {
        parent::__construct();

        $functionName = $this->router->method;
        if ($functionName !== 'launchCronJob' && $functionName !== 'getPriceHistoryArray' && !$this->session->has_userdata('is_admin_login')) {
            redirect('backend/auth/login');
        }

        $this->load->model('backend/common_model', 'common_model');
        $this->csvMaxLineLength = 150000;

        $this->load->model('frontend/front_model');
    }

    //-----------------------------------------------------------
    public function index()
    {
        $data['title'] = "Cron Config";

        $data['product_cnt'] = $this->common_model->get_data_count('merchant_products', ['stock =' => 0]);

        $this->load->view('backend/cron_config/index', $data);
    }

    public function change_status()
    {
        $where = array();

        $where['id'] = $this->input->post('id');
        $data = array(
            'status' => $this->input->post('status'),
        );
        return $this->common_model->update_data('cron_jobs', $where, $data);
    }

    public function deleteOutStockProducts()
    {
        return $this->common_model->deleteOutStockProducts();
    }

    public function create()
    {
        $data['merchants'] = $this->common_model->getMerchantList();
        $this->load->view('backend/cron_config/create', $data);
    }

    public function create_new_cron_job()
    {
        $categoryMaps = $this->input->post('category_map');
        $categoryMaps = json_decode($categoryMaps);

        foreach ($categoryMaps as $categoryMap) {
            $this->common_model->insertOrUpdate($categoryMap);
        }

        $data = array(
            'feed_url' => $this->input->post('feed_url'),
            'username' => $this->input->post('username'),
            'password' => $this->input->post('password'),
            'merchant_id' => $this->input->post('merchant_id'),
            'column_separator' => $this->input->post('column_separator'),
            'start_upload_at' => $this->input->post('start_upload_at'),
            'detail_as_direct' => $this->input->post('detail_as_direct') == "on" ? "yes" : "no",
            'has_title_line' => $this->input->post('has_title_line') == "on" ? "yes" : "no",
            'currency' => $this->input->post('currency'),
            'column_map' => $this->input->post('column_map'),
        );
        $result = $this->common_model->add_data('cron_jobs', $data);
        if ($result) {
            $this->session->set_flashdata('success', 'New cron job has been created successfully! ');
            redirect(base_url('backend/cron_job/cron_config'), 'refresh');
        }
    }

    public function update_cron_job()
    {
        $categoryMaps = $this->input->post('category_map');
        $categoryMaps = json_decode($categoryMaps);

        foreach ($categoryMaps as $categoryMap) {
            $this->common_model->insertOrUpdate($categoryMap);
        }

        $data = array(
            'feed_url' => $this->input->post('feed_url'),
            'username' => $this->input->post('username'),
            'password' => $this->input->post('password'),
            'merchant_id' => $this->input->post('merchant_id'),
            'column_separator' => $this->input->post('column_separator'),
            'start_upload_at' => $this->input->post('start_upload_at'),
            'detail_as_direct' => $this->input->post('detail_as_direct') == "on" ? "yes" : "no",
            'has_title_line' => $this->input->post('has_title_line') == "on" ? "yes" : "no",
            'currency' => $this->input->post('currency'),
            'column_map' => $this->input->post('column_map'),
        );

        $where = array();
        $where['id'] = $this->input->post('cron_job_id');

        $result = $this->common_model->update_data('cron_jobs', $where, $data);
        if ($result) {
            $this->session->set_flashdata('success', 'Cron job has been updated successfully! ');
            redirect(base_url('backend/cron_job/cron_config'), 'refresh');
        }
    }

    public function getPriceHistoryArray()
    {
        $rawHistory = loadPriceHistory($this->input->post('id'));

        $startDate = strtotime('-1 year');
        $endDate = strtotime('tomorrow');

        $sellingPriceArray = array();

        while ($startDate <= $endDate) {

            $price = '';
            foreach ($rawHistory as $row) {
                if ($row['history_date'] == date('Y-m-d', $startDate)) {
                    $price = $row['selling_price'];
                    break;
                }
            }

            $sellingPriceArray[] = $price;
            $startDate = strtotime('+1 day', $startDate);
        }

        echo json_encode($sellingPriceArray);
    }

    public function launchCronJob($launch = true)
    {
        ini_set('memory_limit', '20048M');

        try {

            $start_time = time();
            ini_set('max_execution_time', 5000);
            // Get the IP address of the current request
            $ip_address = $this->input->ip_address();

            $data = array(
                'ip_address' => $ip_address,
                'created_at' => date('Y-m-d h:m:s'),
            );

            $isForce = $this->input->get('isForce') ?? 'true';

            $this->common_model->add_data('cron_job_history', $data);
            log_message('info', 'Job history added successfully.');
            $result = $this->common_model->getAllCronJob();

            $totalProductInsertedCnt = 0;
            $totalProductUpdatedCnt = 0;
            $totalMerchantProductInsertedCnt = 0;
            $totalMerchantProductUpdatedCnt = 0;
            $totalSkippedProductCnt = 0;
            $totalUnApprovedBrandCnt = 0;
            $totalUnApprovedBrandNames = [];
            $totalFoundNewCategoryProduct = 0;

            $detailReports = [];
            $skippedMerchantCnt = 0;
            $errorMerchantCnt = 0;
            $downErrorMerchants = [];

            foreach ($result as $no => $cronJob) {
                log_message('info', "Processing cron job {$no}");

                echo $no;
                $data = $this->loadProduct(intval($cronJob["id"]), $isForce != 'false');

                if (isset($data[0]) && $data[0] == 'error') {
                    log_message('error', "Error in cron job {$no}: {$data[1]}");

                    $errorMerchantCnt++;
                    $downErrorMerchants[] = $data[1];

                } else if (isset($data[0]) && $data[0] == 'same') {                    
                    log_message('info', "Skipped cron job {$no}");

                    $skippedMerchantCnt++;
                } else {
                    $totalProductInsertedCnt += $data['productInsertedCnt'];
                    $totalProductUpdatedCnt += $data['productUpdatedCnt'];
                    $totalMerchantProductInsertedCnt += $data['merchantInsertedCnt'];
                    $totalMerchantProductUpdatedCnt += $data['merchantUpdatedCnt'];
                    $totalSkippedProductCnt += $data['totalSkippedProductCnt'];
                    $totalUnApprovedBrandCnt += $data['unApprovedBrandCnt'];
                    $totalFoundNewCategoryProduct += $data['newFoundCategoryCnt'];

                    $totalUnApprovedBrandNames = array_merge($totalUnApprovedBrandNames, $data['unApprovedBrandNames']);

                    $detail = "<b>" . $data['merchant_name'] . "</b>: <b>"
                        . $data['productInsertedCnt'] . "</b> products added, <b>"
                        . $data['productUpdatedCnt'] . "</b> products updated, <b>"
                        . $data['merchantInsertedCnt'] . "</b> merchant products added, <b>"
                        . $data['merchantUpdatedCnt'] . "</b> merchant products updated, <b>"
                        . $data['totalSkippedProductCnt'] . "</b> products skipped.<br/>"
                        . ($data['newFoundCategoryCnt'] > 0 ? "Not added due to unmapped category: <b>" . $data['newFoundCategoryCnt'] . "</b><br/>" : "")
                        . ($data['newFoundCategoryCnt'] > 0 ? "New found category and sub category: <b>" . implode(", ", $data['newFoundCategoryList']) . "</b><br/>" : "");

                    $detailReports[] = $detail;
                }
            }

            $this->calculateTopProduct();
            $this->calculateTopCategory();
            $this->calculateCategoryLowPrice();
            $this->calculateBrandLowPrice();
            $count = $this->common_model->addPriceHistory();
            $this->sendNotificationEmail($count);

            log_message('info', 1);


            $summaryParts = array(
                "Total added products count: <b>{$totalProductInsertedCnt}</b>",
                "Total updated products count: <b>{$totalProductUpdatedCnt}</b>",
                "Total added merchant products count: <b>{$totalMerchantProductInsertedCnt}</b>",
                "Total updated merchant products count: <b>{$totalMerchantProductUpdatedCnt}</b>",
                "Total skipped products count: <b>{$totalSkippedProductCnt}</b>",
            );

            if ($totalUnApprovedBrandCnt > 0) {
                $summaryParts[] = "Not added due to unapproved brands: <b>{$totalUnApprovedBrandCnt}</b>";
                $summaryParts[] = "Unapproved brand list: <b>" . implode(", ", $totalUnApprovedBrandNames) . "</b>";
            }

            if ($totalFoundNewCategoryProduct > 0) {
                $summaryParts[] = "Not added due to unmapped category: <b>{$totalFoundNewCategoryProduct}</b>";
            }

            $summaryParts[] = "Total skipped merchant feed count: <b>{$skippedMerchantCnt}</b>";

            if ($errorMerchantCnt > 0) {
                $summaryParts[] = "Download error merchant list: <b>" . implode(", ", $downErrorMerchants) . "</b>";
            }

            $summary = implode("<br/>", $summaryParts);
            $detail = implode("<br/>", $detailReports);
            log_message('info', 2);


            $cnt = $this->createSiteMap();
            $detail .= "<br/>" . $cnt . " Sitemap files generated successfully.<br/><a href='http://test.onsalenow.ie/sitemap.xml'>Click here to see the sitemap index file.</a>";
            log_message('info', 3);

            // calculate time gap
            $end_time = time();

            $diff = $end_time - $start_time;

            // Format difference to h:m:s
            $hours = floor($diff / (60 * 60));
            $minutes = floor(($diff - $hours * (60 * 60)) / 60);
            $seconds = floor($diff % 60);

            if ($hours > 0) {
                $formatted = sprintf("%d hr %02d min %02d sec", $hours, $minutes, $seconds);
            } else if ($minutes > 0) {
                $formatted = sprintf("%02d min %02d sec", $minutes, $seconds);
            } else {
                $formatted = sprintf("%02d sec", $seconds);
            }
            log_message('info', 4);

            $this->load->library('email');

            $email = "<h1>Cron Report at " . date('Y-m-d') . " (" . $formatted . ")</h1>";
            $email .= ("<h2>Summary:</h2>" . $summary . "<br/><h2>Detail:</h2> " . $detail);


            $config = array(
                'protocol' => 'smtp',
                'smtp_host' => 'smtp.gmail.com',
                'smtp_user' => 'onsalenow.ie.mail@gmail.com',
                'smtp_pass' => 'jzyibitjnqgqfupj',
                'smtp_crypto' => 'tls', // Add this line
                'charset' => 'utf-8',
                'wordwrap' => TRUE,
                'smtp_port' => '587',
                'mailtype' => 'html',
            );

            $this->email->initialize($config);
            $this->email->from('onsalenow.ie.mail@gmail.com', 'SV2-CronReport');
            // $this->email->to(['hoangleduy27901@gmail.com']);

            $this->email->to(['mncolgan@gmail.com', 'hoangleduy27901@gmail.com']);
            $this->email->subject("Cron Report at " . date('Y-m-d'));
            $this->email->message($email);

            if (!$this->email->send()) {
                echo $this->email->print_debugger();

            } else {
                echo "Email sent";
                log_message('info', 'Email sent successfully');
            }
            log_message('info', 'Cron job finished successfully');
            log_message('info', 'Cron job duration: ' . $formatted); // Added log for cron job duration

            echo 'All active cronJobs finished successfully.';
        } catch (Exception $e) {
            log_message('error', "Exception caught during cron job: {$e->getMessage()}");
            echo ($e->getMessage());
        }
        echo 'Memory usage after: ' . memory_get_usage() . ' bytes' ;

    }

    private function sendNotificationEmail($count)
    {
        // Load the email library
        $this->load->library('email');
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.gmail.com',
            'smtp_user' => 'onsalenow.ie.mail@gmail.com',
            'smtp_pass' => 'jzyibitjnqgqfupj',
            'smtp_crypto' => 'tls', // Add this line
            'charset' => 'utf8',
            'wordwrap' => TRUE,
            'smtp_port' => '587',
            'mailtype' => 'html',
        );
        // Prepare email content
        $emailContent = "<h1>Server 2 Cron Report at " . date('Y-m-d') . "</h1>";
        $emailContent = "The price history has been successfully updated. <strong> $count </strong> records were added.";


        // Set up email parameters
        $this->email->initialize($config);
        $this->email->from('onsalenow.ie.mail@gmail.com', 'SV2-CronReport');
        // $this->email->to(['hoangleduy27901@gmail.com']);
        $this->email->to(['mncolgan@gmail.com', 'hoangleduy27901@gmail.com']);
        $this->email->subject('Server 2 Price History Update');
        $this->email->message($emailContent);

        // Send the email
        if (!$this->email->send()) {
            log_message('error', 'Failed to send email: ' . $this->email->print_debugger());
        } else {
            log_message('info', 'Email sent successfully');
        }
    }

    public function createSiteMap()
    {
        // current time
        $dateTime = new DateTime('now', new DateTimeZone('UTC'));
        $formattedDateTime = $dateTime->format('Y-m-d\TH:i:sP');

        $siteMap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n\t<url>\n\t\t<loc>http://test.onsalenow.ie</loc>\n\t\t<lastmod>" . $formattedDateTime . "</lastmod>\n\t\t<priority>1.00</priority>\n\t</url>\n\t<url>\n\t\t<loc>http://test.onsalenow.ie/pages/about-us</loc>\n\t\t<lastmod>" . $formattedDateTime . "</lastmod>\n\t\t<priority>0.80</priority>\n\t</url>\n\t<url>\n\t\t<loc>http://test.onsalenow.ie/contact_us</loc>\n\t\t<lastmod>" . $formattedDateTime . "</lastmod>\n\t\t<priority>0.80</priority>\n\t</url>\n\t<url>\n\t\t<loc>http://test.onsalenow.ie/products/products_list</loc>\n\t\t<lastmod>" . $formattedDateTime . "</lastmod>\n\t\t<priority>0.80</priority>\n\t</url>\n";

        // brands
        $brandList = $this->common_model->getBrandData();

        foreach ($brandList as $brand) {
            $siteMap .= ("\t<url>\n\t\t<loc>http://test.onsalenow.ie/" . $brand['slug'] . "</loc>\n\t\t<lastmod>" . $formattedDateTime . "</lastmod>\n\t\t<priority>0.80</priority>\n\t</url>\n");
        }

        // category & sub category
        $categoryList = $this->front_model->getCategorywithsub();
        $categoryList = json_decode($categoryList, true);

        foreach ($categoryList as $category) {
            $siteMap .= ("\t<url>\n\t\t<loc>http://test.onsalenow.ie/" . $category['slug'] . "</loc>\n\t\t<lastmod>" . $formattedDateTime . "</lastmod>\n\t\t<priority>0.80</priority>\n\t</url>\n");

            foreach ($category['subCategories'] as $subCategory) {
                $siteMap .= ("\t<url>\n\t\t<loc>http://test.onsalenow.ie/" . $category['slug'] . '/' . $subCategory['slug'] . "</loc>\n\t\t<lastmod>" . $formattedDateTime . "</lastmod>\n\t\t<priority>0.80</priority>\n\t</url>\n");
            }
        }

        $siteMap .= "</urlset>";

        $sitemapFile = fopen('sitemap1.xml', 'w');
        fwrite($sitemapFile, $siteMap);
        fclose($sitemapFile);

        $siteMapFileIndex = 2;

        // product
        $siteMap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n";

        $productList = $this->common_model->get_product_data(null, 0);
        $cnt = 0;
        foreach ($productList as $product) {
            if ($cnt > 30000) {
                $siteMap .= "</urlset>";
                $sitemapFile = fopen('sitemap' . $siteMapFileIndex . '.xml', 'w');
                fwrite($sitemapFile, $siteMap);
                fclose($sitemapFile);

                $siteMapFileIndex++;
                $cnt = 0;
                $siteMap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n";
            } else {
                $siteMap .= ("\t<url>\n\t\t<loc>http://test.onsalenow.ie/" . $product->categorySlug . '/' . $product->slug . "</loc>\n\t\t<lastmod>" . $formattedDateTime . "</lastmod>\n\t\t<priority>0.80</priority>\n\t</url>\n");
                $cnt++;
            }
        }

        $siteMap .= "</urlset>";
        $sitemapFile = fopen('sitemap' . $siteMapFileIndex . '.xml', 'w');
        fwrite($sitemapFile, $siteMap);
        fclose($sitemapFile);

        // sitemap index file
        $siteMap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n";

        for ($i = 1; $i <= $siteMapFileIndex; $i++) {
            $siteMap .= ("\t<sitemap>\n\t\t<loc>http://test.onsalenow.ie/sitemap" . $i . ".xml</loc>\n\t\t<lastmod>" . $formattedDateTime . "</lastmod>\n\t</sitemap>\n");
        }

        $siteMap .= "</sitemapindex>";
        $sitemapFile = fopen('sitemap.xml', 'w');
        fwrite($sitemapFile, $siteMap);
        fclose($sitemapFile);

        return $siteMapFileIndex;
    }

    public function calculateTopCategory()
    {
        // reset is_top
        $this->common_model->resetIsTopOfCategory();

        // pick top category id list
        $category_ids = $this->common_model->getTopCategoryList();

        // set is_top status
        $this->common_model->setIsTopOfCategory($category_ids);
    }

    public function calculateTopProduct()
    {
        // reset is_top
        $this->common_model->resetIsTopOfProduct();

        // pick top product id list
        $product_ids = $this->common_model->getTopProductList();

        // set is_top status
        $this->common_model->setIsTopOfProduct($product_ids);

        // set a_top as 0 if m_top is 1
        $this->common_model->setATopAs0MTop();
    }

    public function calculateCategoryLowPrice()
    {
        $this->common_model->resetLowPriceOfCategory();
    }

    public function calculateBrandLowPrice()
    {
        $this->common_model->resetLowPriceOfBrand();
    }

    public function manualOnetimeLoad()
    {
        $data = $this->loadProduct($this->input->post('id'), true);
        if (is_array($data) && count($data) > 0) {

            if (isset($data[0]) && $data[0] == 'error') {
                echo 'Feeding canceled due to download error.';
            } else if (isset($data[0]) && $data[0] == 'same') {
                echo 'Feeding canceled due to same feed file.';
            } else {
                $detail = $data['productInsertedCnt'] . ' products added, '
                    . $data['productUpdatedCnt'] . ' products updated, '
                    . $data['merchantInsertedCnt'] . ' merchant products added, '
                    . $data['merchantUpdatedCnt'] . ' merchant products updated, '
                    . $data['totalSkippedProductCnt'] . ' products skipped. '
                    . ($data['unApprovedBrandCnt'] > 0 ? $data['unApprovedBrandCnt'] . ' products not imported due to unapproved brand. Here are unapproved brand names. [' . implode(", ", $data['unApprovedBrandNames']) . ']' : '')
                    . ($data['newFoundCategoryCnt'] > 0 ? $data['newFoundCategoryCnt'] . ' products not imported due to unmapped category. Here are unmapped category and sub category. [' . implode(", ", $data['newFoundCategoryList']) . ']' : '');

                echo $detail;
            }
        } else {
            echo 'No data returned from loadProduct.';
        }
        echo 'Memory usage after: ' . memory_get_usage() . ' bytes' ;

    }

    public function loadProduct($id, $forceLoad = false)
    {
        ini_set('memory_limit', '20048M');

        
        try {
            $ret_val = 0;
            // echo 'load-product';

            // remove old csv, gz files
            $cmd = 'rm -rf ./uploads/feed' . $id . '.csv';
            system($cmd);

            // set max execution time
            ini_set('max_execution_time', 3200);
            $start_time = microtime(true);

            $cronJob = $this->common_model->getCronJob($id);
            $column_maps = json_decode($cronJob["column_map"]);

            if (substr($cronJob["feed_url"], -4) == ".csv") {
                $data = file_get_contents($cronJob["feed_url"]);
                $feedFile = fopen("./uploads/feed" . $id . ".csv", "w") or die("Unable to open file. Cron job id09: " . $id);
                fwrite($feedFile, $data);
                fclose($feedFile);
                echo 'csv';
            } elseif (substr($cronJob["feed_url"], -8) == ".csv.zip") {
                $cmd = 'rm -rf ./uploads/feed' . $id . '.csv.zip';
                system($cmd);
                $data = file_get_contents($cronJob["feed_url"]);
                $zipFile = fopen("./uploads/feed" . $id . ".csv.zip", "w") or die("Unable to open file. Cron job id3: " . $id);
                fwrite($zipFile, $data);
                fclose($zipFile);

                $storagePath = ('./uploads');
                $cmd = "unzip ./uploads/feed" . $id . ".csv.zip -d $storagePath";
                exec($cmd, $output, $returnValue);
                if ($returnValue === 0) {
                    echo "Unzip operation was successful.";
                    // Rename the unzipped file
                    $files = glob("./uploads/*.tmp");
                    $destination = "./uploads/feed" . $id . ".csv";

                    foreach ($files as $file) {
                        if (file_exists($destination)) {
                            echo "Destination file already exists.";
                        } else {
                            if (!@rename($file, $destination)) {
                                echo "Failed to rename file. Check if PHP has necessary permissions.";
                            }
                        }
                    }
                } else {
                    echo "Unzip operation failed.";
                }
                if ($returnValue === 0) {
                    echo "Unzip operation was successful.";
                } else {
                    echo "Unzip operation failed.";
                }
                echo ('csv.zip' . $ret_val);

            } else {
                $cmd = 'rm -rf ./uploads/feed' . $id . '.csv.gz';
                system($cmd);

                $data = file_get_contents($cronJob["feed_url"]);
                $gzFile = fopen("./uploads/feed" . $id . ".csv.gz", "w") or die("Unable to open file. Cron job id3: " . $id);
                fwrite($gzFile, $data);
                fclose($gzFile);

                $cmd = 'gzip -df ./uploads/feed' . $id . '.csv.gz';
                system($cmd, $ret_val);
                echo ('csv.gz' . $ret_val);

            }



            if ($ret_val == 2) {
                $merchantId = $cronJob["merchant_id"];
                $merchantName = $this->common_model->getMerchantName($merchantId);
                $errorMessage = 'Error occurred with merchant: ' . $merchantName;
                log_message('error', $errorMessage);
                return ['error', $merchantName];
            } else {
                echo ('opened');
                $csvFilename = "./uploads/feed" . $id . ".csv";
                $gzFilename = "./uploads/feed" . $id . ".csv.gz";
                $zipFilename = "./uploads/feed" . $id . ".csv";
                if (file_exists($gzFilename) && is_readable($gzFilename)) {
                    $csvFile = gzopen($gzFilename, "r") or die("Unable to open CSV.GZ file. Cron job id: " . $id);
                    $fileSize = filesize($gzFilename);
                } elseif (file_exists($csvFilename) && is_readable($csvFilename)) {
                    $csvFile = fopen($csvFilename, "r") or die("Unable to open CSV file. Cron job id: " . $id);
                    $fileSize = filesize($csvFilename);
                } elseif (file_exists($zipFilename) && is_readable($zipFilename)) {
                    $csvFile = fopen($zipFilename, "r") or die("Unable to open CSV.zip file. Cron job id: " . $id);
                    $fileSize = filesize($zipFilename);

                } else {
                    die("Neither CSV nor CSV.GZ file exists or is readable. Cron job id: " . $id);
                }
                $end_time = microtime(true);

                $execution_time = $end_time - $start_time;
                log_message('info', "The function open csv took {$execution_time} seconds to complete.");

                // log_message('debug', $id . 'debug: ' . $fileSize);

                if (!$forceLoad && $cronJob['last_uploaded_size'] == $fileSize) {
                    return ['same'];
                }

                $hasTitleLine = ($cronJob['has_title_line'] == 'yes');

                $row = 1;

                $totalSkippedProductCnt = 0;

                $unApprovedBrandCnt = 0;
                $unApprovedBrandNames = [];
                $newFoundCategoryCnt = 0;
                $newFoundCategoryList = [];

                $maxLineCnt = 100;
                $currentCnt = 0;

                $productInsertedCnt = 0;
                $productUpdatedCnt = 0;

                $merchantInsertedCnt = 0;
                $merchantUpdatedCnt = 0;


                $allProducts = [];
                $allMerchantProducts = [];

                $productNameWPList = [];

                while (($data = fgetcsv($csvFile, $this->csvMaxLineLength, $cronJob['column_separator'])) !== false) {
                    if ($row == 1) {
                        $this->common_model->update_data('merchant_products', ['cron_job_id' => $id], ['stock' => 0]);
                    }

                    if ($hasTitleLine && $row == 1) {
                        $row++;

                        continue;
                    }

                    $row++;

                    $brandInfo = $this->getBrandInfo($data, $column_maps);
                    $brandId = $brandInfo[0];
                    $brandName = $brandInfo[1];
                    $category_id = -1;
                    $subCategory_id= -1;
                    $depAndcat = $this->getDepAndCat($data, $column_maps);
                    // if (!empty($depAndcat) && isset($depAndcat["osn_category_id"])) {
                    //     if ($depAndcat["osn_category_id"] === "NULL") {
                    //         continue;
                    //     }

                    //     $category_id = intval($depAndcat["osn_category_id"]);
                    //     $subCategory_id = intval($depAndcat["osn_subCategory_id"]);
                    // } else {
                    //     log_message('error', 'No category or subcategory');

                    // }

                    if ($depAndcat["osn_category_id"] == "NULL") {
                        continue;
                    }

                    $category_id = intval($depAndcat["osn_category_id"]);
                    $subCategory_id = intval($depAndcat["osn_subCategory_id"]);

                    if ($category_id == -1 || $subCategory_id == -1) {
                        $totalSkippedProductCnt++;
                    } else {
                        if ($category_id == 0 || $subCategory_id == 0) {
                            $newFoundCategoryCnt++;
                            $newCatAndSubCat = $this->getDepAndCatNames($data, $column_maps);
                            if (!in_array($newCatAndSubCat, $newFoundCategoryList)) {
                                $newFoundCategoryList[] = $newCatAndSubCat;
                            }

                            continue;
                        }

                        $productName = $this->getProductName($data, $column_maps);

                        if ($productName == '') {
                            continue;
                        }

                        $productDescription = $this->getProductDescription($data, $column_maps);
                        $productImgUrl = $this->getProductImgUrl($data, $column_maps);
                        $directBuyLink = $this->getDirectBuyLink($data, $column_maps);

                        $merchantPrice = $this->getMerchantPrice($data, $column_maps);
                        $salePrice = $this->getSalePrice($data, $column_maps);
                        $size = $this->getAttribute($data, $column_maps, "Size");
                        $color = $this->getAttribute($data, $column_maps, "Color");
                        $option = $this->getAttribute($data, $column_maps, "Option");

                        $isApproved = $this->common_model->isApproved($brandId);
                        // $isApproved == 1;
                        // ci.brand need to be approved | status = 1
                        if ($isApproved == 1) {
                            list($name_wp, $options) = $this->common_model->extract_params_from_product_name($productName);

                            $name_wp = $this->common_model->getSluganize($name_wp);

                            // check product name duplication
                            $productIdentifier = $name_wp . $directBuyLink;

                            if (in_array($productIdentifier, $productNameWPList)) {
                                continue;
                            } else {
                                array_push($productNameWPList, $name_wp);
                            }

                            $data_products = array(
                                'brand_id' => $brandId,
                                'category_id' => $category_id,
                                'subCategory_id' => $subCategory_id,
                                'name' => $productName,
                                'description' => $productDescription,
                                'image' => $productImgUrl,
                                'slug' => $this->common_model->getSlug($productName),
                                'status' => '1',
                                'name_wp' => $name_wp,
                                'cron_job_id' => $id,
                                'size' => $size,
                                'color' => $color,
                                'option' => $option,
                            );

                            $allProducts[] = $data_products;

                            $data_merchant = array(
                                'merchant_id' => $cronJob["merchant_id"],
                                'name_wp' => $name_wp,
                                'selling_price' => $this->getNumber($merchantPrice),
                                'cost_price' => $this->getNumber($salePrice),
                                'currency' => $cronJob["currency"],
                                'merchant_store_url' => $directBuyLink,
                                'stock' => '1',
                                'options' => $this->common_model->getOption($options),
                                'updated_at' => date('Y-m-d h:m:s'),
                                'cron_job_id' => $id,
                                'size' => $size,
                                'color' => $color,
                                'option' => $option,

                            );

                            $allMerchantProducts[] = $data_merchant;
                            $currentCnt++;

                            if ($currentCnt > $maxLineCnt) {

                                // insert data to db
                                $result = $this->common_model->addOrUpdateProduct($allProducts);

                                $productInsertedCnt += $result[0];
                                $productUpdatedCnt += $result[1];

                                $result = $this->common_model->addOrUpdateMerchant($allMerchantProducts);

                                $merchantInsertedCnt += $result[0];
                                $merchantUpdatedCnt += $result[1];

                                $allProducts = [];
                                $allMerchantProducts = [];
                                $currentCnt = 0;
                                // dd($allProducts);
                            }
                        } else {

                            if ($brandName == "") {
                                continue;
                            }

                            $unApprovedBrandCnt++;

                            if (!in_array($brandName, $unApprovedBrandNames)) {
                                $unApprovedBrandNames[] = $brandName;
                            }

                            // add new brand to db
                            $this->common_model->addNewBrandIfUnexist($brandName);
                        }
                    }
                }

                fclose($csvFile);

                if ($currentCnt != 0) {
                    // insert data to db                    

                    $result = $this->common_model->addOrUpdateProduct($allProducts);

                    $productInsertedCnt += $result[0];
                    $productUpdatedCnt += $result[1];

                    $result = $this->common_model->addOrUpdateMerchant($allMerchantProducts);

                    $merchantInsertedCnt += $result[0];
                    $merchantUpdatedCnt += $result[1];
                }

                // update cron job history
                $feedInfo = array(
                    'last_uploaded_size' => $fileSize,
                    'last_uploaded_at' => date("Y-m-d h:m:s"),
                );

                $this->common_model->update_data("cron_jobs", ["id" => $id], $feedInfo);

                $detail = $productInsertedCnt . ' products added, '
                    . $productUpdatedCnt . ' products updated, '
                    . $merchantInsertedCnt . ' merchant products added, '
                    . $merchantUpdatedCnt . ' merchant products updated, '
                    . $totalSkippedProductCnt . ' products skipped. '
                    . ($unApprovedBrandCnt > 0 ? $unApprovedBrandCnt . ' products not imported due to unapproved brand. Here are unapproved brand names. [' . implode(", ", $unApprovedBrandNames) . '], ' : '')
                    . ($newFoundCategoryCnt > 0 ? $newFoundCategoryCnt . ' products not imported due to unmapped category. Here are unmapped category and sub category. [' . implode(", ", $newFoundCategoryList) . ']' : '');

                $data = array(
                    'merchant_name' => $this->common_model->getMerchantName($cronJob["merchant_id"]),
                    'detail' => $detail,
                    'date' => date("Y-m-d h:m:s"),
                );

                $this->common_model->add_data('cron_report', $data);
                $data = array(
                    'merchant_name' => $this->common_model->getMerchantName($cronJob["merchant_id"]),
                    'productInsertedCnt' => $productInsertedCnt,
                    'productUpdatedCnt' => $productUpdatedCnt,
                    'merchantInsertedCnt' => $merchantInsertedCnt,
                    'merchantUpdatedCnt' => $merchantUpdatedCnt,
                    'totalSkippedProductCnt' => $totalSkippedProductCnt,
                    'unApprovedBrandCnt' => $unApprovedBrandCnt,
                    'unApprovedBrandNames' => $unApprovedBrandNames,
                    'newFoundCategoryCnt' => $newFoundCategoryCnt,
                    'newFoundCategoryList' => $newFoundCategoryList,
                );
                return $data;
            }

        } catch (Exception $e) {
            echo 'error';
        }
        echo 'Memory usage after: ' . memory_get_usage() . ' bytes' ;

    }
    

    public function getNumber($numberString)
    {
        preg_match_all('!\d+\.*\d*!', $numberString, $onlyNumber);
        return implode('', $onlyNumber[0]);
    }

    public function getSalePrice($row, $column_maps)
    {
        $dataIndex = $this->getDataIndex("Sale Price", $column_maps);
        if ($dataIndex !== 'null') {
            return $row[$dataIndex];
        }
        return 0;
    }

    public function getMerchantPrice($row, $column_maps)
    {
        $dataIndex = $this->getDataIndex("Merchant Price", $column_maps);
        if ($dataIndex !== 'null') {
            return $row[$dataIndex];
        }
        return 0;
    }

    public function getDirectBuyLink($row, $column_maps)
    {
        $dataIndex = $this->getDataIndex("Direct Buy Link", $column_maps);
        if ($dataIndex !== 'null') {
            return $row[$dataIndex];
        }
        return "";
    }

    public function getProductDetailsURL($row, $column_maps)
    {
        $dataIndex = $this->getDataIndex("Product Details URL", $column_maps);
        if ($dataIndex !== 'null') {
            return $row[$dataIndex];
        }
        return "";
    }

    public function getProductImgUrl($row, $column_maps)
    {
        $dataIndex = $this->getDataIndex("Product Image URL", $column_maps);
        if ($dataIndex !== 'null') {
            return $row[$dataIndex];
        }
        return "";
    }

    public function getProductDescription($row, $column_maps)
    {
        $dataIndex = $this->getDataIndex("Product Description", $column_maps);
        if ($dataIndex !== 'null') {
            return $row[$dataIndex];
        }
        return "";
    }

    public function getProductName($row, $column_maps)
    {
        $dataIndex = $this->getDataIndex("Product Name", $column_maps);
        if ($dataIndex !== 'null') {
            return $row[$dataIndex];
        }
        return "";
    }

    public function getBrandInfo($row, $column_maps)
    {
        $dataIndex = $this->getDataIndex("Brand", $column_maps);

        if ($dataIndex !== 'null') {
            $brandName = $row[$dataIndex];

            return [$this->common_model->getBrandId($brandName), $brandName];
        }
        return [0, ''];
    }

    public function getDepAndCat($row, $column_maps)
    {
        $categoryIndex = $this->getDataIndex("Category", $column_maps);
        $subCategoryIndex = $this->getDataIndex("SubCategory", $column_maps);


        $categoryName = $row[$categoryIndex];
        $subCategoryName = $row[$subCategoryIndex];

        $depAndCat = $this->common_model->getDepAndCat($categoryName, $subCategoryName);
        return $depAndCat;
    }

    public function getDepAndCatNames($row, $column_maps)
    {
        $categoryIndex = $this->getDataIndex("Category", $column_maps);
        $subCategoryIndex = $this->getDataIndex("SubCategory", $column_maps);

        $categoryName = $row[$categoryIndex];
        $subCategoryName = $row[$subCategoryIndex];

        return "(" . $categoryName . ", " . $subCategoryName . ")";
    }

    public function getDataIndex($dataName, $column_maps)
    {
        foreach ($column_maps as $column_map) {
            if ($column_map[1] == $dataName) {
                return intval($column_map[0]) - 1;
            }
        }
        return 'null';
    }

    public function getAttribute($row, $column_maps, $attribute_name)
    {
        $dataIndex = $this->getDataIndex($attribute_name, $column_maps);
        if ($dataIndex !== 'null') {
            return $row[$dataIndex];
        }
        return "";
    }

    public function edit()
    {
        $data['title'] = 'Cron Job';
        $id = $this->uri->segment(4);
        $data['cronJob'] = $this->common_model->get_single_data('cron_jobs', ['id' => $id]);
        $data['merchants'] = $this->common_model->getMerchantList();

        // if($this->input->post('submit')){
        //     $this->form_validation->set_rules('brand_name', 'Brand Name', 'trim|required');
        //     $this->form_validation->set_rules('description', 'Description', 'trim|required');
        //     $this->form_validation->set_rules('c_type', 'Type', 'trim|required');
        // }

        // else{
        $this->load->view('backend/cron_config/edit', $data);
        // }
    }

    public function cronjob_datatable()
    {
        $cronJobs = $this->common_model->getCronJobList();
        $data = array();

        $i = 0;
        foreach ($cronJobs as $row) {
            $status = ($row['status'] == 1) ? 'checked' : '';

            $data[] = array(
                ++$i,
                $row['feed_url'],
                $row['merchant_name'],
                $row['start_upload_at'],
                $row['last_uploaded_at'],
                $row['last_uploaded_size'] > 0 ? $row['last_uploaded_size'] . " bytes" : '',
                $row['currency'],
                '<input class="tgl_checkbox tgl-ios" data-id="' . $row['id'] . '" id="cb_' . $row['id'] . '"type="checkbox" ' . $status . '><label for="cb_' . $row['id'] . '"></label>',
                '<a title="View" class="view btn btn-sm btn-info" href="' . base_url('backend/Cron_Config/edit/' . $row['id']) . '"> <i class="fa fa-eye"></i></a>' .
                '<button style="margin-left: 5px" title="View" class="view btn btn-sm btn-info" onclick="loadProduct(' . $row['id'] . ')"> <i class="fa fa-gavel"></i></button>' .
                '<button style="margin-left: 5px" title="View" class="view btn btn-sm btn-info" onclick="removeCron(' . $row['id'] . ',' . $i . ')"> <i class="fa fa-trash"></i></button>',
            );
        }
        $records['data'] = $data;
        echo json_encode($records);
    }

    public function check_url_to_download($feedUrl)
    {
        $ok = false;
        $url = strtolower(trim($feedUrl));
        if (substr($url, 0, 7) == 'http://') {
            $ok = true;
        } elseif (substr($url, 0, 8) == 'https://') {
            $ok = true;
        }
        return $ok;
    }

    public function load_column_info()
    {
        $feedUrl = $this->input->post('feed_url');
        if ($this->check_url_to_download($feedUrl)) {

            $result = $this->downloadAndLoadColumn($feedUrl);

            if ($result == "fail") {
                echo json_encode(["Something went wrong.", 'error']);
            } else {
                echo json_encode([$result, 'success']);
            }
        } else {
            echo json_encode(['Feed url is invalid. Please check and update.', 'error']);
        }
    }

    public function downloadAndLoadColumn($feedUrl)
    {
        set_time_limit(3000);
        $ret_val = 0;
        $data = file_get_contents($feedUrl);
        if (substr($feedUrl, -4) == ".csv" || strpos($feedUrl, 'export.admitad.com') !== false) {
            $feedFile = fopen("./uploads/feed.csv", "w") or die("Unable to open file CSV!");
            fwrite($feedFile, $data);
            fclose($feedFile);

        } elseif (substr($feedUrl, -7) == ".csv.gz" || strpos($feedUrl, 'productdata.awin.com') !== false) {
            $gzFile = fopen("./uploads/feed.csv.gz", "w") or die("Unable to open file CSV.GZ!");
            fwrite($gzFile, $data);
            fclose($gzFile);
            if (file_exists('./uploads/feed.csv')) {
                $cmd = 'rm ./uploads/feed.csv';
                system($cmd);
            }
            $buffer_size = 10240;
            $out_file_name = './uploads/feed.csv';

            $file = gzopen('./uploads/feed.csv.gz', 'rb');
            $out_file = fopen($out_file_name, 'wb');

            while (!gzeof($file)) {
                fwrite($out_file, gzread($file, $buffer_size));
            }

            fclose($out_file);
            gzclose($file);


        } elseif (substr($feedUrl, -8) == ".csv.zip" || strpos($feedUrl, 'feeds.performancehorizon.com') !== false) {
            $zipFile = fopen("./uploads/feed.csv.zip", "w") or die("Unable to open file CSV.GZ!");
            fwrite($zipFile, $data);
            fclose($zipFile);
            if (file_exists('./uploads/feed.csv')) {
                $cmd = 'rm ./uploads/feed.csv';
                system($cmd);
            }

            $storagePath = ('./uploads');
            $cmd = "find $storagePath -type f -name '*.tmp' -delete";
            exec($cmd);
            $cmd = "unzip ./uploads/feed.csv.zip -d $storagePath && mv $storagePath/*.tmp $storagePath/feed.csv";
            $output = [];
            $returnValue = 0;
            exec($cmd, $output, $returnValue);
        } else {
            die("Unsupported file type: " . $feedUrl);

        }

        if ($ret_val == 1) {
            return "fail";
        } else {
            $csvFile = fopen("./uploads/feed.csv", "r") or die("Unable to open csv file123!");
            $titleLine = [];
            $firstLine = [];

            if ($this->input->post('has_title_line')) {
                $titleLine = fgetcsv($csvFile, $this->csvMaxLineLength, $this->input->post('column_separator'));
            }

            $firstLine = fgetcsv($csvFile, $this->csvMaxLineLength, $this->input->post('column_separator'));

            fclose($csvFile);

            $result = [];

            $result[] = $titleLine;
            $result[] = $firstLine;

            $cmd = 'rm ./uploads/feed.csv';
            system($cmd);

            return $result;
        }
    }
    // public function load_category_info($page, $perPage)
    // {

    //     $feedUrl = $this->input->post('feed_url');
    //     set_time_limit(0);
    //     if ($this->check_url_to_download($feedUrl)) {

    //         $result = $this->downloadAndLoadCategory($feedUrl);
    //         if ($result == "fail") {
    //             echo json_encode([$result, 'error']);
    //         } else {
    //             // get category
    //             $osnCategorys = $this->common_model->get_OSNCatAndSubCat("ci_category");

    //             // get subCategory
    //             $osnSubCategories = $this->common_model->get_OSNCatAndSubCat("ci_subcategory");

    //             echo json_encode([$osnCategorys, $osnSubCategories, $result, 'success']);
    //         }
    //     } else {
    //         echo json_encode(['Feed url is invalid. Please check and update123123.', 'error']);
    //     }
    // }
    function load_category_info()
    {
        $feedUrl = $this->input->post('feed_url');
        set_time_limit(0);
        if ($this->check_url_to_download($feedUrl)) {

            $result = $this->downloadAndLoadCategory($feedUrl);
            if ($result == "fail") {
                echo json_encode([$result, 'error']);
            } else {
                // get category
                $osnCategorys = $this->common_model->get_OSNCatAndSubCat("ci_category");

                // get subCategory
                $osnSubCategories = $this->common_model->get_OSNCatAndSubCat("ci_subcategory");

                echo json_encode([$osnCategorys, $osnSubCategories, $result, 'success']);
            }
        } else {
            echo json_encode(['Feed url is invalid. Please check and update123123.', 'error']);
        }
    }

    public function downloadAndLoadCategory($feedUrl)
    {
        set_time_limit(3000);

        $ret_val = 0;

        if (substr($feedUrl, -4) == ".csv" || strpos($feedUrl, 'export.admitad.com') !== false) {
            $data = file_get_contents($feedUrl);

            $feedFile = fopen("./uploads/feed.csv", "w") or die("Unable to open file!");
            fwrite($feedFile, $data);
            fclose($feedFile);
        } elseif (substr($feedUrl, -7) == ".csv.gz" || strpos($feedUrl, 'productdata.awin.com') !== false) {
            $data = file_get_contents($feedUrl);

            $gzFile = fopen("./uploads/feed.csv.gz", "w") or die("Unable to open file CSV.GZ!");
            fwrite($gzFile, $data);
            fclose($gzFile);
            if (file_exists('./uploads/feed.csv')) {
                $cmd = 'rm ./uploads/feed.csv';
                system($cmd);
            }

            $buffer_size = 10240;
            $out_file_name = './uploads/feed.csv';

            $file = gzopen('./uploads/feed.csv.gz', 'rb');
            $out_file = fopen($out_file_name, 'wb');

            while (!gzeof($file)) {
                fwrite($out_file, gzread($file, $buffer_size));
            }

            fclose($out_file);
            gzclose($file);

        } elseif (substr($feedUrl, -8) == ".csv.zip" || strpos($feedUrl, 'feeds.performancehorizon.com') !== false) {
            $data = file_get_contents($feedUrl);
            $zipFile = fopen("./uploads/feed.csv.zip", "w") or die("Unable to open file CSV.GZ!");
            fwrite($zipFile, $data);
            fclose($zipFile);
            if (file_exists('./uploads/feed.csv')) {
                $cmd = 'rm ./uploads/feed.csv';
                system($cmd);
            }
            $storagePath = ('./uploads');
            $cmd = "unzip ./uploads/feed.csv.zip -d $storagePath && mv $storagePath/*.tmp $storagePath/feed.csv";
            $output = [];
            $returnValue = 0;
            exec($cmd, $output, $returnValue);

        }
        // elseif (substr($feedUrl, -8) == ".csv.zip") {
        //     $data = file_get_contents($feedUrl);
        //     $zipFile = fopen("./uploads/feed.csv.zip", "w") or die ("Unable to open file CSV.GZ!");
        //     fwrite($zipFile, $data);
        //     fclose($zipFile);
        //     if (file_exists('./uploads/feed.csv')) {
        //         $cmd = 'rm ./uploads/feed.csv';
        //         system($cmd);
        //     }
        //     // $storagePath = ('./uploads');
        //     $output = shell_exec('unzip ./uploads/feed.csv.zip -d ./uploads/');
        //     if ($output !== null) {
        //         echo 'Unzip operation was successful.';
        //     } else {
        //         echo 'Failed to unzip the file.';
        //     }
        //     // $cmd = "unzip ./uploads/feed.csv.zip -d $storagePath && mv $storagePath/*.tmp $storagePath/feed.csv";
        //     // $output = [];
        //     // $returnValue = 0;
        //     // exec($cmd, $output, $returnValue);
        //     $files = glob("./uploads/*.tmp");
        //     $destination = "./uploads/feed.csv";
        // } else {
        //     die ("Unsupported file type: " . $feedUrl);

        // }

        // $csvFile = "./uploads/feed.csv";

        // // Check if the file exists
        // if (!file_exists($csvFile)) {
        //     die("File not found: $csvFile");
        // }

        if ($ret_val == 1) {
            return "fail";
        } else {
            $csvFile = fopen("./uploads/feed.csv", "r") or die("Unable to open csv file321!");

            $hasTitleLine = $this->input->post('has_title_line');

            $csvDepsAndCats = [];

            $row = 1;

            // This loops through the lines
            while (($data = fgetcsv($csvFile, $this->csvMaxLineLength, $this->input->post('column_separator'))) !== false) {

                if ($hasTitleLine && $row == 1) {
                    $row++;
                    continue;
                }

                $rowDepsAndCats = [$data[intval($this->input->post('categoryIndex'))], $data[intval($this->input->post('subCategoryIndex'))]];

                // check duplicate
                $exist = false;
                foreach ($csvDepsAndCats as $itemDepsAndCats) {
                    if ($itemDepsAndCats[0] == $rowDepsAndCats[0] && $itemDepsAndCats[1] == $rowDepsAndCats[1]) {
                        $exist = true;
                        break;
                    }
                }

                if (!$exist) {
                    $osnCategoryId = -1;
                    $osnSubCategoryId = -1;
                    $result = $this->common_model->getCategoryList($rowDepsAndCats[0], $rowDepsAndCats[1]);

                    if (count($result) != 0) {
                        $osnCategoryId = $result[0]["osn_category_id"];
                        $osnSubCategoryId = $result[0]["osn_subCategory_id"];
                    }

                    array_push($rowDepsAndCats, $osnCategoryId, $osnSubCategoryId);
                    $csvDepsAndCats[] = $rowDepsAndCats;
                }

                $row++;
            }
            fclose($csvFile);

            return $csvDepsAndCats;
        }
        return "fail";
    }

    public function removeCronJob()
    {
        $id = $this->input->post('id');

        $this->common_model->removeCronJob($id);

        echo 'CronJob and all product related to this cronjob deleted successfully.';
    }
}
