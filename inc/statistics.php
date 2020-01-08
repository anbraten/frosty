<h2>Statistiken</h2>
<br /><br />

<h2>Bestellungen</h2>
<?php
  switch (isset($_GET['range']) ? $_GET['range'] : '') {
    case 'month':
      $since = strtotime('-1 month');
      break;

    case '3months':
      $since = strtotime('-3 months');
      break;

    case 'year':
      $since = strtotime('-12 months');
      break;
    
    default:
      $since = 'all';
      break;
  }

  $range = ($since === 'all') ? '' : 'WHERE o.date >= "'.date('Y-m-d H:m:s', $since).'"';
?>
<h3><?php echo ($since === 'all') ? 'Uhrknall' : date('d.m.Y', $since); ?> bis heute</h3>
<br />
<a href="?p=statistics&range=month">Letzer Monat</a>
<a href="?p=statistics&range=3months">Letze 3. Monate</a>
<a href="?p=statistics&range=year">Letzes Jahr</a>
<a href="?p=statistics&range=all">Gesamt</a>
<br /><br /><br />

<h3>Produkte</h3>
<?php
  $sql = 'SELECT p.name, COUNT(p.id) AS num, (COUNT(p.id) * (p.priceSell - p.priceBuy)) AS profit FROM orders o INNER JOIN orderedItems oi ON o.id=oi.orderId INNER JOIN products p ON p.id=oi.product '.$range.' GROUP BY p.id ORDER BY profit DESC';
  // echo '<pre>'.$sql.'</pre>';
  $stmt = $db->prepare($sql);
  $stmt->execute();
  $result = $stmt->get_result();
?>
<table>
  <thead>
    <tr><th>Produkt</th><th>verkaufte Anzahl</th><th>gesamt Gewinn</th></tr>
  </thead>
  <?php
    while($row = $result->fetch_assoc()) {
      echo '<tr><td>'.$row['name'].'</td><td>'.$row['num'].'</td><td>'.$row['profit'].'€</td></tr>';
    }
  ?>
</table>
<br /><br />

<h3>Kunden</h3>
<?php
  $stmt = $db->prepare('SELECT c.name, c.id, COUNT(o.id) AS num FROM orders o INNER JOIN customers c ON c.id=o.customer '.$range.' GROUP BY c.id ORDER BY num DESC');
  $stmt->execute();
  $result = $stmt->get_result();
?>
<table>
  <thead>
    <tr><th>Kunde</th><th>Bestellungen</th></tr>
  </thead>
  <?php
    while($row = $result->fetch_assoc()) {
      echo '<tr><td>'.$row['name'].'</td><td>'.$row['num'].'</td></tr>';
    }
  ?>
</table>

<br /><br />
<hr />
<br /><br />
<h2>Touren</h2>
<br /><br />

<h3>Fahrzeuge</h3>
<?php
  $stmt = $db->prepare('SELECT CONCAT(v.license_plate, " (", v.model, ")") AS name, COUNT(t.id) AS num FROM tours t INNER JOIN vehicles v ON v.id=t.vehicle GROUP BY v.id ORDER BY num DESC');
  $stmt->execute();
  $result = $stmt->get_result();
?>
<table>
  <thead>
    <tr><th>Kunde</th><th>Touren</th></tr>
  </thead>
  <?php
    while($row = $result->fetch_assoc()) {
      echo '<tr><td>'.$row['name'].'</td><td>'.$row['num'].'</td></tr>';
    }
  ?>
</table>
<br /><br />

<h3>Mitarbeiter</h3>
<?php
  $stmt = $db->prepare('SELECT e.name, COUNT(t.id) AS num FROM tours t INNER JOIN employees e ON e.id=t.employee GROUP BY e.id ORDER BY num DESC');
  $stmt->execute();
  $result = $stmt->get_result();
?>
<table>
  <thead>
    <tr><th>Kunde</th><th>Touren</th></tr>
  </thead>
  <?php
    while($row = $result->fetch_assoc()) {
      echo '<tr><td>'.$row['name'].'</td><td>'.$row['num'].'</td></tr>';
    }
  ?>
</table>
<br /><br />
