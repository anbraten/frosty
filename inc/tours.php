<?php
if (isset($_GET['deleteTour']) && $_GET['deleteTour']) {
  $stmt = $db->prepare('DELETE FROM tours WHERE id=?');
  $stmt->bind_param('i', $_GET['deleteTour']);
  $stmt->execute();

  $stmt = $db->prepare('UPDATE orders SET tour=null WHERE tour=?');
  $stmt->bind_param('i', $_GET['deleteTour']);
  $stmt->execute();

  reload();
}

if (isset($_POST['table']) && $_POST['table'] === 'tour') {
  $stmt = $db->prepare('INSERT INTO tours (vehicle, employee) VALUES(?, ?)');
  $stmt->bind_param('ii', $_POST['vehicle'], $_POST['employee']);
  $stmt->execute();

  reload();
}

if (isset($_POST['table']) && $_POST['table'] === 'tourOrders') {
  $stmt = $db->prepare('UPDATE orders SET tour=? WHERE id=?');
  $stmt->bind_param('ii', $_POST['tour'], $_POST['order']);
  $stmt->execute();

  reload();
}

if (isset($_POST['table']) && $_POST['table'] === 'tourLengthPlanned') {
  $stmt = $db->prepare('UPDATE tours SET lengthPlanned=? WHERE id=?');
  $stmt->bind_param('ii', $_POST['lengthPlanned'], $_POST['tour']);
  $stmt->execute();

  reload();
}

$stmt = $db->prepare('SELECT t.id, t.length, t.lengthPlanned, t.time, e.name AS employee, v.model AS vehicle FROM tours t INNER JOIN employees e ON e.id=t.employee INNER JOIN vehicles v ON v.id=t.vehicle');
$stmt->execute();
$result = $stmt->get_result();

?>

<h2>Touren</h2>
<table>
  <thead>
    <tr><th>Fahrzeug</th><th>Mitarbeiter</th><th>Länge - Geplant</th><th>Länge - Tatsächlich</th><th>Dauer</th><th>Bestellungen</th><th></th></tr>
  </thead>
  <?php
    while($row = $result->fetch_assoc()) {
      echo '<tr><td>'.$row['vehicle'].'</td><td>'.$row['employee'].'</td>';

      if ($row['lengthPlanned']) {
        echo '<td>'.$row['lengthPlanned'].' km</td>';
      } else {
        echo '<td>';
        echo '<form method="POST">';
        echo '<input type="hidden" name="table" value="tourLengthPlanned" />';
        echo '<input type="hidden" name="tour" value="'.$row['id'].'" />';
        echo '<input type="text" name="lengthPlanned" />';
        echo '<input type="submit" value="OK" />';
        echo '</form>';
        echo '</td>';
      }

      if ($row['length']) {
        echo '<td>'.$row['length'].' km</td>';
      } else {
        echo '<td></td>';
      }

      if ($row['time']) {
        echo '<td>'.$row['time'].' Stunden</td>';
      } else {
        echo '<td></td>';
      }

      echo '<td>';
      $stmt = $db->prepare('SELECT c.name, o.date FROM orders o INNER JOIN customers c ON c.id=o.customer AND o.tour=? ORDER BY c.name');
      $stmt->bind_param('i', $row['id']);
      $stmt->execute();
      $result_orders1 = $stmt->get_result();

      echo '<ul>';
      while ($row_orders1 = $result_orders1->fetch_assoc()) {
        echo '<li>'.$row_orders1['name'].' - '.date("d.m.Y H:i", strtotime($row_orders1['date'])).'</li>';
      }
      echo '</ul>';

      $stmt = $db->prepare('SELECT c.name, o.id, o.date FROM orders o INNER JOIN customers c ON c.id=o.customer AND o.tour IS NULL ORDER BY c.name');
      $stmt->execute();
      $result_orders2 = $stmt->get_result();

      echo '<form method="POST">';
      echo '<input type="hidden" name="table" value="tourOrders" />';
      echo '<input type="hidden" name="tour" value="'.$row['id'].'" />';
      echo '<select name="order" required>';
      echo '<option value="">---</option>';
      while ($row_orders2 = $result_orders2->fetch_assoc()) {
        echo '<option value="'.$row_orders2['id'].'">'.$row_orders2['name'].' - '.date("d.m.Y H:i", strtotime($row_orders2['date'])).'</option>';
      }
      echo '</select>';
      echo '<input type="submit" value="OK" />';
      echo '</form>';

      echo '</td>';
      echo '<td><a href="?p=tours&deleteTour='.$row['id'].'">x</a></td></tr>';
    }
  ?>
  <tr>
    <form method="POST">
      <input type="hidden" name="table" value="tour" />
      <td>
        <select name="vehicle" required>
          <option value="">---</option>
          <?php
            $stmt = $db->prepare('SELECT * FROM vehicles');
            $stmt->execute();
            $result2 = $stmt->get_result();

            while ($row2 = $result2->fetch_assoc()) {
              echo '<option value="'.$row2['id'].'">'.$row2['model'].'</option>';
            }
          ?>
        </select>
      </td>
      <td>
        <select name="employee" required>
          <option value="">---</option>
          <?php
            $stmt = $db->prepare('SELECT * FROM employees');
            $stmt->execute();
            $result1 = $stmt->get_result();

            while ($row1 = $result1->fetch_assoc()) {
              echo '<option value="'.$row1['id'].'">'.$row1['name'].'</option>';
            }
          ?>
        </select>
      </td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td><input type="submit" value="OK"></td>
    </form>
  </tr>
</table>
<br>
<br>
