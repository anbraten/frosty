<?php
if (isset($_GET['deleteProducts']) && $_GET['deleteProducts']) {
  $stmt = $db->prepare('DELETE FROM products where id=?');
  $stmt->bind_param('i', $_GET['deleteProducts']);
  $stmt->execute();

  header('location: '.$baseUrl);
  exit();
}

if (isset($_POST['table']) && $_POST['table'] === 'products') {
  $stmt = $db->prepare('INSERT INTO products (name, priceBuy, priceSell) VALUES(?, ?, ?)');
  $stmt->bind_param('sii', $_POST['name'], $_POST['priceBuy'], $_POST['priceSell']);
  $stmt->execute();

  header('location: '.$baseUrl);
  exit();
}

$stmt = $db->prepare('SELECT * FROM products');
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Produkte</h2>

<form method="POST">
  <input type="hidden" name="table" value="products" />
  <table>
    <thead>
      <tr><th>Name</th><th>Preis - Einkauf</th><th>Preis - Verkauf</th><th></th></tr>
    </thead>
    <?php
      while($row = $result->fetch_assoc()) {
        echo '<tr><td>'.$row['name'].'</td><td>'.$row['priceBuy'].' €</td><td>'.$row['priceSell'].' €</td><td><a href="?deleteProducts='.$row['id'].'">x</a></td></tr>';
      }
    ?>
    <tr>
      <td><input type="text" name="name" placeholder="Name" required /></td>
      <td><input type="text" name="priceBuy" placeholder="Preis - Einkauf" required /></td>
      <td><input type="text" name="priceSell" placeholder="Preis - Verkauf" required /></td>
      <td><input type="submit" value="OK"></td>
    </tr>
  </table>
</form>

<br>
<br>
