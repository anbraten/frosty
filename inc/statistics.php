<h2>Statistiken</h2>

<?php
  if (isset($_POST['start']) && isset($_POST['end'])) {
    $_GET['start'] = strtotime($_POST['start']);
    $_GET['end'] = strtotime($_POST['end']);
  }

  $start = isset($_GET['start']) ? $_GET['start'] : 0; // 0 = 01.01.1970, 00:00 UTC
  $end = isset($_GET['end']) ? $_GET['end'] : time();

  $range = 'WHERE o.date BETWEEN "'.date('Y-m-d 00:00:00', $start).'" AND "'.date('Y-m-d 23:59:00', $end).'"';
?>
<br />
<a href="?p=statistics&start=<?php echo time(); ?>">Heute</a>
<a href="?p=statistics&start=<?php echo strtotime('-1 day'); ?>&end=<?php echo strtotime('-1 day'); ?>">Gestern</a>
<a href="?p=statistics&start=<?php echo strtotime('-1 month'); ?>">Letzer Monat</a>
<a href="?p=statistics&start=<?php echo strtotime('-3 months'); ?>">Letze 3. Monate</a>
<a href="?p=statistics&start=<?php echo strtotime('-12 months'); ?>">Letzes Jahr</a>
<a href="?p=statistics">Gesamt</a>
<br /><br />
<form action="?p=statistics" method="POST">
  <input type="date" name="start" placeholder="Start" />
  <input type="date" name="end" placeholder="Ende" />
  <input type="submit" value="Anzeigen" />
<form>
<br /><br />
<h3><?php echo date('d.m.Y', $start); ?> bis <?php echo date('d.m.Y', $end); ?></h3>
<br /><br /><br />

<h2>Produkte</h2>
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

<h2>Kunden</h2>
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

<h2>Fahrzeuge</h2>
<?php
  $stmt = $db->prepare('SELECT CONCAT(v.license_plate, " (", v.model, ")") AS name, COUNT(t.id) AS num, SUM(t.length) AS length FROM tours t INNER JOIN vehicles v ON v.id=t.vehicle INNER JOIN orders o ON o.tour=t.id '.$range.' AND t.length IS NOT NULL AND t.time IS NOT NULL GROUP BY v.id ORDER BY num DESC');
  $stmt->execute();
  $result = $stmt->get_result();
?>
<table>
  <thead>
    <tr><th>Kunde</th><th>Touren</th><th>Gefahrene Kilometer</th></tr>
  </thead>
  <?php
    while($row = $result->fetch_assoc()) {
      echo '<tr><td>'.$row['name'].'</td><td>'.$row['num'].'</td><td>'.$row['length'].' km</td></tr>';
    }
  ?>
</table>
<br /><br />

<h2>Mitarbeiter</h2>
<?php
  $sql = 'SELECT e.name, COUNT(t.id) AS num, SUM(t.length) AS length, SUM(t.lengthPlanned) AS lengthPlanned, (SUM(t.time) * e.salary) AS earnings, SUM(t.time) AS hours FROM tours t INNER JOIN employees e ON e.id=t.employee INNER JOIN orders o ON o.tour=t.id '.$range.' AND t.length IS NOT NULL AND t.time IS NOT NULL GROUP BY e.id ORDER BY num DESC';
  // echo $sql;
  $stmt = $db->prepare($sql);
  $stmt->execute();
  $result = $stmt->get_result();
?>
<table>
  <thead>
    <tr><th>Mitarbeiter</th><th>Touren</th><th>Kilometer (gefahren / geschätzt)</th><th>Gearbeitete Stunden</th><th>Verdienst</th></tr>
  </thead>
  <?php
    while($row = $result->fetch_assoc()) {
      echo '<tr><td>'.$row['name'].'</td><td>'.$row['num'].'</td><td>'.$row['length'].' km / '.$row['lengthPlanned'].' km</td><td>'.$row['hours'].' Stunden</td><td>'.$row['earnings'].' €</td></tr>';
    }
  ?>
</table>
