<?php
if (isset($_GET['logout'])) {
  $_SESSION['driver'] = false;

  reload();
}

if (isset($_POST['table']) && $_POST['table'] === 'tourLength') {
  $stmt = $db->prepare('UPDATE tours SET length=? WHERE id=? AND employee=?');
  $stmt->bind_param('iii', $_POST['length'], $_POST['tour'], $_SESSION['driver']);
  $stmt->execute();

  reload();
}

if (isset($_POST['table']) && $_POST['table'] === 'tourTime') {
  $stmt = $db->prepare('UPDATE tours SET time=? WHERE id=? AND employee=?');
  $stmt->bind_param('iii', $_POST['time'], $_POST['tour'], $_SESSION['driver']);
  $stmt->execute();

  reload();
}

?>

<ul class="menu">
  <li style="float:right"><a class="active" href="<?php echo $baseUrl; ?>?logout">Logout</a></li>
</ul>

<div class="content">
  <?php
    $stmt = $db->prepare('SELECT e.name FROM employees e WHERE id=?');
    $stmt->bind_param('i', $_SESSION['driver']);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
      echo '<h2>Hallo, '.$row['name'].'</h2>';
    }
  ?>

  <br /><br />

  <h2>Touren</h2>
  <?php
    $stmt = $db->prepare('SELECT t.id, t.length, t.lengthPlanned, t.time, v.model AS vehicle FROM tours t INNER JOIN employees e ON e.id=t.employee INNER JOIN vehicles v ON v.id=t.vehicle WHERE t.employee=? AND t.lengthPlanned IS NOT NULL');
    $stmt->bind_param('i', $_SESSION['driver']);
    $stmt->execute();
    $result = $stmt->get_result();
  ?>
<table>
  <thead>
    <tr><th>Fahrzeug</th><th>Länge - Geplant</th><th>Länge - Tatsächlich</th><th>Dauer</th><th>Bestellungen</th><th></th></tr>
  </thead>
  <?php
    while($row = $result->fetch_assoc()) {
      echo '<tr><td>'.$row['vehicle'].'</td><td>'.$row['lengthPlanned'].'</td>';

      if ($row['length']) {
        echo '<td>'.$row['length'].'</td>';
      } else {
        echo '<td>';
        echo '<form method="POST">';
        echo '<input type="hidden" name="table" value="tourLength" />';
        echo '<input type="hidden" name="tour" value="'.$row['id'].'" />';
        echo '<input type="text" name="length" />';
        echo '<input type="submit" value="OK" />';
        echo '</form>';
        echo '</td>';
      }

      if ($row['time']) {
        echo '<td>'.$row['time'].'</td>';
      } else {
        echo '<td>';
        echo '<form method="POST">';
        echo '<input type="hidden" name="table" value="tourTime" />';
        echo '<input type="hidden" name="tour" value="'.$row['id'].'" />';
        echo '<input type="text" name="time" />';
        echo '<input type="submit" value="OK" />';
        echo '</form>';
        echo '</td>';
      }

      echo '<td>';
      $stmt = $db->prepare('SELECT c.name, c.street, c.postalcode, c.city FROM orders o INNER JOIN customers c ON c.id=o.customer AND o.tour=? ORDER BY c.postalcode');
      $stmt->bind_param('i', $row['id']);
      $stmt->execute();
      $result_orders = $stmt->get_result();

      echo '<table>';
      while ($row_orders = $result_orders->fetch_assoc()) {
        echo '<tr><td>'.$row_orders['name'].'</td><td>'.$row_orders['street'].'</td><td>'.$row_orders['postalcode'].' '.$row_orders['city'].'</td></tr>';
      }
      echo '</table>';

      echo '</td>';
      echo '</tr>';
    }
  ?>
  </table>
  <br>
  <br>
</div>

