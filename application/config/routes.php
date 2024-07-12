<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes with
| underscores in the controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'home';

//Admin Route
$route['admin'] = 'backend/dashboard';

$route['backend/dashboard'] = 'backend/dashboard';
$route['backend/menus'] = 'backend/menus/index';
$route['backend/seo/product'] = 'backend/seo/product';
$route['backend/category'] = 'backend/category/index';
$route['backend/banners'] = 'backend/banners/index';
$route['backend/brand'] = 'backend/brand/index';
$route['backend/product'] = 'backend/product/index';
$route['backend/cms'] = 'backend/cms/index';
$route['backend/contact'] = 'backend/contact';
$route['backend/merchant'] = 'backend/merchant/index';
$route['backend/profile'] = 'backend/profile/index';
$route['backend/newsletter'] = 'backend/newsletter/index';
$route['backend/general_settings'] = 'backend/general_settings/index';
$route['backend/profile/change_pwd'] = 'backend/profile/change_pwd';
$route['backend/cron_job/cron_config'] = 'backend/Cron_Config/index';
$route['backend/cron_job/cron_report'] = 'backend/Cron_Report/index';
$route['backend/cron_job/product_history'] = 'Cron/cron_new_discounts';

$route['aboutus'] = 'home/aboutus';
$route['contact_us'] = 'home/contact_us';

$route['category/?(:any)?/?(:any)?'] = 'home/category';
$route['brand/?(:any)?/?(:any)?'] = 'home/brand';
$route['allbrand'] = 'home/getAllBrand';

$route['blogs'] = 'home/blogs';
$route['pages/(:any)'] = 'home/pages';

$route['products/(:num)'] = 'products/products_filter';
$route['products'] = 'products/products_filter';
$route['productbrand'] = 'products/getProductByBrand';

$route['product/(:any)'] = 'products/index';
$route['products/products_list'] = 'products/products_list';
$route['products/products_bigsale/(:num)'] = 'products/products_bigsale';
$route['products/products_bigsale'] = 'products/products_bigsale';
$route['products/products_discount_thisweek'] = 'products/products_new_discounts';
$route['products/products_discount_thisweek/(:num)'] = 'products/products_new_discounts';
$route['products/search_list'] = 'products/search_list';
$route['products/product_compare_data'] = 'products/product_compare_data';
$route['products/product_compare'] = 'products/product_compare';

$route['home/getAllBrand'] = 'home/getAllBrand';
$route['home/filters'] = 'home/filters';
$route['home/getAllBrandsBySearchText'] = 'home/getAllBrandsBySearchText';
$route['home/add_newsletter'] = 'home/add_newsletter';

$route['(:any)\.html'] = "common/redirect/$1";
$route['(:any)/(:any)\.html'] = "common/redirect/$1/$2";
$route['(:any)/(:any)\.html/(:any)'] = "common/redirect/$1/$2";
$route['(:any)'] = 'common/index/$1';
$route['(:any)/(:any)'] = "common/index/$1/$2";
$route['(:any)/(:any)/(:num)'] = "common/index/$1/$2/$3";

$route['404_override'] = 'home/error_404';
$route['translate_uri_dashes'] = FALSE;