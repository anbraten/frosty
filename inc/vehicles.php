<?php
if (isset($_GET['deleteVehicles']) && $_GET['deleteVehicles']) {
  $stmt = $db->prepare('DELETE FROM vehicles where id=?');
  $stmt->bind_param('i', $_GET['deleteVehicles']);
  $stmt->execute();

  reload();
}

if (isset($_POST['table']) && $_POST['table'] === 'vehicles') {
  $stmt = $db->prepare('INSERT INTO vehicles (model, license_plate) VALUES(?, ?)');
  $stmt->bind_param('ss', $_POST['model'], $_POST['license_plate']);
  $stmt->execute();

  reload();
}

$stmt = $db->prepare('SELECT * FROM vehicles');
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Fahrzeuge</h2>
<form method="POST">
  <input type="hidden" name="table" value="vehicles" />
  <table>
    <thead>
      <tr><th>Modell</th><th>Kennzeichen</th><th></th></tr>
    </thead>
    <?php
      while($row = $result->fetch_assoc()) {
        echo '<tr><td>'.$row['model'].'</td><td>'.$row['license_plate'].'</td><td><a href="?p=vehicles&deleteVehicles='.$row['id'].'">x</a></td></tr>';
      }
    ?>
    <tr>
      <td><input type="text" name="model" placeholder="Modell" required /></td>
      <td><input type="text" name="license_plate" placeholder="Kennzeichen" required /></td>
      <td><input type="submit" value="OK"></td>
    </tr>
  </table>
</form>
<br>
<br>
