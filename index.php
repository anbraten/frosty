<?php
error_reporting(E_ALL);
session_start();
include_once(__DIR__.'/mysql.php');

$baseUrl = '/frosty';

function reload() {
  global $baseUrl;

  if (isset($_GET['p'])) {
    header('location: '.$baseUrl.'/?p='.$_GET['p']);
  } else {
    header('location: '.$baseUrl);
  }

  exit();
}

ob_start();

$p = isset($_GET['p']) ? $_GET['p'] : '';
$file = __DIR__.'/inc/'.$p.'.php';

if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
  include_once(__DIR__.'/inc/login.php');
} else {
  include_once(__DIR__.'/inc/protected.php');

  echo '<div class="content">';
  if (file_exists($file)) {
    include_once($file);
  } else {
    include_once(__DIR__.'/inc/welcome.php');
  }
  echo '</div>';
}
$body = ob_get_clean();


include_once(__DIR__.'/template/header.php'); // header
echo $body;                                   // body
include_once(__DIR__.'/template/footer.php'); // footer

$db->close();
?>
