<?php
error_reporting(E_ALL);
include_once(__DIR__.'/mysql.php');

$baseUrl = '/frosty';

ob_start();
include_once(__DIR__.'/inc/customers.php');
include_once(__DIR__.'/inc/products.php');
include_once(__DIR__.'/inc/employees.php');
include_once(__DIR__.'/inc/vehicles.php');
include_once(__DIR__.'/inc/orders.php');
include_once(__DIR__.'/inc/tours.php');
$body = ob_get_clean();


include_once(__DIR__.'/template/header.php'); // header
echo $body;                                   // body
include_once(__DIR__.'/template/footer.php'); // footer

$db->close();
?>
