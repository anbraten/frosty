<?php
if (isset($_GET['deleteOrder']) && $_GET['deleteOrder']) {
  $stmt = $db->prepare('DELETE FROM orders where id=?');
  $stmt->bind_param('i', $_GET['deleteOrder']);
  $stmt->execute();

  reload();
}

if (isset($_POST['table']) && $_POST['table'] === 'order') {
  $stmt = $db->prepare('INSERT INTO orders (customer, date) VALUES(?, ?)');
  $stmt->bind_param('is', $_POST['customer'], $_POST['date']);
  $stmt->execute();

  reload();
}

if (isset($_POST['table']) && $_POST['table'] === 'orderedItems') {
  $stmt = $db->prepare('INSERT INTO orderedItems (orderId, product) VALUES(?, ?)');
  $stmt->bind_param('ii', $_POST['orderId'], $_POST['product']);
  $stmt->execute();

  reload();
}

$stmt = $db->prepare('SELECT o.id, o.date, c.name FROM orders o INNER JOIN customers c ON c.id=o.customer');
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Bestellungen</h2>
<table>
  <thead>
    <tr><th>Kunde</th><th>Datum</th><th>Produkte</th><th></th></tr>
  </thead>
  <?php
    while($row = $result->fetch_assoc()) {
      echo '<tr><td>'.$row['name'].'</td><td>'.$row['date'].'</td><td><ul>';

      $stmt = $db->prepare('SELECT p.name FROM orderedItems o INNER JOIN products p ON p.id=o.product AND o.orderId=? ORDER BY p.name');
      $stmt->bind_param('i', $row['id']);
      $stmt->execute();
      $result1 = $stmt->get_result();
      while($row1 = $result1->fetch_assoc()) {
        echo '<li>'.$row1['name'].'</li>';
      }
      echo '</ul>';
      
      echo '<form method="POST">';
      echo '<input type="hidden" name="table" value="orderedItems" />';
      echo '<input type="hidden" name="orderId" value="'.$row['id'].'" />';
      echo '<select name="product" required>';
      echo '<option value="">---</option>';
      $stmt = $db->prepare('SELECT * FROM products');
      $stmt->execute();
      $result2 = $stmt->get_result();
      while ($row2 = $result2->fetch_assoc()) {
        echo '<option value="'.$row2['id'].'">'.$row2['name'].'</option>';
      }      
      echo '</select>';
      echo '<input type="submit" value="OK" />';
      echo '</form>';

      echo '</td><td><a href="?p=orders&deleteOrder='.$row['id'].'">x</a></td></tr>';
    }
  ?>
  <tr>
    <form method="POST">
      <input type="hidden" name="table" value="order" />
      <td>
        <select name="customer" required>
          <option value="">---</option>
          <?php
            $stmt = $db->prepare('SELECT * FROM customers');
            $stmt->execute();
            $result3 = $stmt->get_result();

            while ($row3 = $result3->fetch_assoc()) {
              echo '<option value="'.$row3['id'].'">'.$row3['name'].'</option>';
            }
          ?>
        </select>
      </td>
      <td><input type="datetime-local" name="date" placeholder="Datum" required /></td>
      <td></td>
      <td><input type="submit" value="OK" /></td>
    </form>
  </tr>
</table>
<br>
<br>


