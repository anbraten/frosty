<?php
if (isset($_GET['deleteCustomer']) && $_GET['deleteCustomer']) {
  $stmt = $db->prepare('DELETE FROM customers where id=?');
  $stmt->bind_param('i', $_GET['deleteCustomer']);
  $stmt->execute();

  reload();
}

if (isset($_POST['table']) && $_POST['table'] === 'customer') {
  $stmt = $db->prepare('INSERT INTO customers (name, street, city, postalcode) VALUES(?, ?, ?, ?)');
  $stmt->bind_param('sssi', $_POST['name'], $_POST['street'], $_POST['city'], $_POST['postalcode']);
  $stmt->execute();

  reload();
}

$stmt = $db->prepare('SELECT * FROM customers');
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Kunden</h2>
<form method="POST">
  <input type="hidden" name="table" value="customer" />
  <table>
    <thead>
      <tr><th>Name</th><th>Straße</th><th>Stadt</th><th>PLZ</th><th></th></tr>
    </thead>
    <?php
      while($row = $result->fetch_assoc()) {
        echo '<tr><td>'.$row['name'].'</td><td>'.$row['street'].'</td><td>'.$row['postalcode'].'</td><td>'.$row['city'].'</td><td><a href="?p=customers&deleteCustomer='.$row['id'].'">x</a></td></tr>';
      }
    ?>
    <tr>
      <td><input type="text" name="name" placeholder="Name" required /></td>
      <td><input type="text" name="street" placeholder="Straße" required /></td>
      <td><input type="text" name="postalcode" placeholder="PLZ" required /></td>
      <td><input type="text" name="city" placeholder="Stadt" required /></td>
      <td><input type="submit" value="OK"></td>
    </tr>
  </table>
</form>
<br>
<br>
