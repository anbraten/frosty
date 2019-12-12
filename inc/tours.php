<?php
if (isset($_GET['deleteTour']) && $_GET['deleteTour']) {
  $stmt = $db->prepare('DELETE FROM tours where id=?');
  $stmt->bind_param('i', $_GET['deleteTour']);
  $stmt->execute();

  header('location: '.$baseUrl);
  exit();
}

if (isset($_POST['table']) && $_POST['table'] === 'tour') {
  $stmt = $db->prepare('INSERT INTO tours (vehicle, employee, lengthPlanned) VALUES(?, ?, ?)');
  $stmt->bind_param('iii', $_POST['vehicle'], $_POST['employee'], $_POST['lengthPlanned']);
  $stmt->execute();

  header('location: '.$baseUrl);
  exit();
}

$stmt = $db->prepare('SELECT t.id, t.length, t.lengthPlanned, t.time, e.name AS employee, v.model AS vehicle FROM tours t INNER JOIN employees e ON e.id=t.employee INNER JOIN vehicles v ON v.id=t.vehicle');
$stmt->execute();
$result = $stmt->get_result();

?>

<h2>Tours</h2>
<table>
  <thead>
    <tr><th>Fahrzeug</th><th>Mitarbeiter</th><th>Länge - Geplant</th><th>Länge - Tatsächlich</th><th>Dauer</th><th></th></tr>
  </thead>
  <?php
    while($row = $result->fetch_assoc()) {
      echo '<tr><td>'.$row['vehicle'].'</td><td>'.$row['employee'].'</td><td>'.$row['lengthPlanned'].'</td><td>'.$row['length'].'</td><td>'.$row['time'].'</td><td><a href="?deleteTour='.$row['id'].'">x</a></td></tr>';
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
      <td><input type="text" name="lengthPlanned" placeholder="Geplante Länge (km)" required /></td>
      <td></td>
      <td></td>
      <td><input type="submit" value="OK"></td>
    </form>
  </tr>
</table>
<br>
<br>
