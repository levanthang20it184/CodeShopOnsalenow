<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

$servername = "localhost";
$username = "onsale_w";
$password = "KJHGUIe8g8JHGll8341vz";
$database = "onsalenow_db";

// $servername = "192.168.130.54";
// $username = "pony";
// $password = "ponydollar";
// $database = "myprojec_onsale";

// Create a MySQLi connection
$currentDateTime = date('Y-m-d H:i:s');
echo "start date and time: " . $currentDateTime . PHP_EOL;
$connection = new mysqli($servername, $username, $password, $database);

// Check if the connection was successful
if ($connection->connect_error) {
  die("Connection failed: " . $connection->connect_error);
}

$query = "DELETE FROM price_history_this_week;";
$result = $connection->query($query);
echo 'drop table' . PHP_EOL;

$query = "DELETE FROM price_history_temp;";
$result = $connection->query($query);
echo 'drop temporary table' . PHP_EOL;

if ($result === false) {
  die("Error executing query: " . $connection->error);
}

// Create the `price_history_this_week` table
$query = "CREATE TABLE IF NOT EXISTS `price_history_this_week` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `product_id` INT NOT NULL,
  `history_date` DATE NOT NULL,
  `selling_price` FLOAT NOT NULL,
  `cost_price` FLOAT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `history_date` (`history_date`)
) ENGINE=MyISAM";
$result = $connection->query($query);
echo 'table created' . PHP_EOL;

$query = "CREATE TABLE IF NOT EXISTS `price_history_temp` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `product_id` INT NOT NULL,
  `history_date` DATE NOT NULL,
  `selling_price` FLOAT NOT NULL,
  `cost_price` FLOAT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `history_date` (`history_date`)
) ENGINE=MyISAM";
$result = $connection->query($query);
echo 'temp table created' . PHP_EOL;
// Clear the `price_history_this_week` table

$query = "
  INSERT INTO price_history_temp (product_id, history_date, selling_price, cost_price, id)
  SELECT distinct product_id, history_date, selling_price, cost_price, id
  FROM price_history
  WHERE history_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
  AND history_date < CURDATE()
  ORDER BY history_date DESC
";

$result = $connection->query($query);
if ($result === false) {
  die("Error executing query: " . $connection->error);
}
echo 'temp table build' . PHP_EOL;
$today = date('Y-m-d');
$query = "
  SELECT distinct product_id, history_date, selling_price, cost_price, id
  FROM price_history
  WHERE history_date = '$today'
  ORDER BY product_id DESC, history_date DESC;
";
echo $query;

echo 'base table query' . PHP_EOL;
$result = $connection->query($query);
if ($result === false) {
  die("Error executing query: " . $connection->error);
}
$ids = [];
$before_id = NULL;
while ($row = $result->fetch_assoc()) {
  $product_id = $row["product_id"];
  if ($product_id == $before_id) {
    $before_id = $product_id;
    continue;
  }
  $before_id = $product_id;
  $selling_price = $row["selling_price"];
  $query = "SELECT selling_price FROM price_history_temp WHERE product_id=$product_id ORDER BY history_date DESC";
  $result_temp = $connection->query($query);
  while ($_row = $result_temp->fetch_assoc()) {
    if ($_row) {
      if ($selling_price < $_row['selling_price']) {
        $ids[] = $row["id"];
        break;
      }
      if ($selling_price > $_row['selling_price']) {
        break;
      }
    }
  }
}

echo "Total IDs collected: " . count($ids) . PHP_EOL;

$ids_imploded = implode(',', $ids);
$query_insert = "
  INSERT INTO price_history_this_week (product_id, history_date, selling_price, cost_price, brand_id, category_id, subCategory_id, name, slug, image, description, status, merchant_id, currency, options, merchant_store_url, stock, id)
  SELECT DISTINCT ci_products.id, price_history.history_date, price_history.selling_price, price_history.cost_price, ci_products.brand_id, ci_products.category_id, ci_products.subCategory_id, ci_products.name, ci_products.slug, ci_products.image, ci_products.description, ci_products.status, merchant_products.merchant_id, merchant_products.currency, merchant_products.options, merchant_products.merchant_store_url, merchant_products.stock, price_history.id AS id
  FROM price_history
  INNER JOIN merchant_products ON price_history.product_id = merchant_products.id
  INNER JOIN ci_products ON ci_products.name_wp = merchant_products.name_wp
  WHERE price_history.id IN ($ids_imploded)
  AND merchant_products.stock = 1
  ORDER BY price_history.history_date;
";

$result = $connection->query($query_insert);
if ($result === false) {
  die("Error executing query: " . $connection->error);
}

echo "Calculated rows: " . count($ids) . PHP_EOL;

$query_count = "
  SELECT COUNT(*) AS count
  FROM price_history_this_week
  WHERE id IN ($ids_imploded);
";

$result_count = $connection->query($query_count);
if ($result_count === false) {
  die("Error executing count query: " . $connection->error);
}

$row_count = $result_count->fetch_assoc();
echo "Inserted rows: " . $row_count['count'] . PHP_EOL;


$mail = new PHPMailer(true); // Passing `true` enables exceptions
try {
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->Port = 587;
  $mail->SMTPSecure = 'tls';
  $mail->SMTPAuth = true;
  $mail->Username = 'onsalenow.ie.mail@gmail.com';
  $mail->Password = 'jzyibitjnqgqfupj';
  //Recipients
  $mail->setFrom('onsalenow.ie.mail@gmail.com', 'SV2-New discount report'); //This is the email your form sends From
  $mail->addAddress('mncolgan@gmail.com'); // Add a recipient address
  $mail->addAddress('hoangleduy27901@gmail.com'); // Add a recipient address
  //Content
  $mail->isHTML(true); // Set email format to HTML
  $mail->Subject = 'New Discount Report';
  $mail->Body = $row_count['count'] . " products have been added to new discounts";
  $mail->send();
  echo 'Message has been sent';
} catch (Exception $e) {
  echo 'Message could not be sent.';
  echo 'Mailer Error: ' . $mail->ErrorInfo;
}

echo "Finished adding" . PHP_EOL;

$connection->close();
$currentDateTime = date('Y-m-d H:i:s');
echo "end date and time: " . $currentDateTime;



function deleteDirectory($dir)
{
  if (!file_exists($dir)) {
    echo "Directory does not exist." . PHP_EOL;
    return false;
  }

  if (!is_dir($dir)) {
    echo "Provided path is not a directory." . PHP_EOL;
    return false;
  }

  $files = array_diff(scandir($dir), array('.', '..'));
  foreach ($files as $file) {
    $path = $dir . DIRECTORY_SEPARATOR . $file;
    if (is_dir($path)) {
      deleteDirectory($path);
    } else {
      unlink($path);
    }
  }

  return rmdir($dir);
}

function loadPage($url)
{
  $content = file_get_contents($url);
  if ($content !== false) {
    echo "Page loaded successfully." . PHP_EOL;
  } else {
    echo "Failed to load the page." . PHP_EOL;
  }
}

$directoryPath = '/var/www/onsalenow/application/cache/db_cache/products';

if (deleteDirectory($directoryPath)) {
  echo "Directory $directoryPath has been successfully deleted." . PHP_EOL;
  loadPage("http://144.76.15.121/products/products_bigsale");
  loadPage("http://144.76.15.121/Fashion");
} else {
  echo "Failed to delete directory $directoryPath." . PHP_EOL;
}

?>