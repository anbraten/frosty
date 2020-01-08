<?php
error_reporting(E_ALL);
session_start();
include_once(__DIR__.'/mysql.php');

$baseUrl = '/frosty/driver.php';

function reload() {
  global $baseUrl;

  header('location: '.$baseUrl);
  exit();
}

ob_start();

if (!isset($_SESSION['driver']) || !$_SESSION['driver']) {
  include_once(__DIR__.'/inc-driver/login.php');
} else {
  include_once(__DIR__.'/inc-driver/protected.php');
}
$body = ob_get_clean();


include_once(__DIR__.'/template/header.php'); // header
echo $body;                                   // body
include_once(__DIR__.'/template/footer.php'); // footer

$db->close();
?>
