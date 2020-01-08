<?php
if (isset($_GET['logout'])) {
  $_SESSION['auth'] = false;

  header('location: '.$baseUrl);
  exit();
}
?>

<?php
  $pages = [
    "welcome" => "Welcome",
    "customers" => "Kunden",
    "orders" => "Bestellungen",
    "products" => "Produkte",
    "tours" => "Touren",
    "employees" => "Mitarbeiter",
    "vehicles" => "Fahrzeuge",
    "statistics" => "Statistiken",
  ];
?>

<ul class="menu">
  <?php
    $p = $_GET['p'] ?? 'welcome';
    foreach ($pages as $page => $name) {
      echo '<li><a href="?p='.$page.'"';
      if ($p === $page) {
        echo ' class="active"';
      }
      echo '>'.$name.'</a></li>';
    }
  ?>
  <li style="float:right"><a class="active" href="<?php echo $baseUrl; ?>?logout">Logout</a></li>
  <li style="float:right"><a href="<?php echo $baseUrl; ?>/driver.php" target="_blank">Mitarbeiter Login</a></li>
</ul>
