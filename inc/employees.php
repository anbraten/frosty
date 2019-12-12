<?php
if (isset($_GET['deleteEmployees']) && $_GET['deleteEmployees']) {
  $stmt = $db->prepare('DELETE FROM employees where id=?');
  $stmt->bind_param('i', $_GET['deleteEmployees']);
  $stmt->execute();

  header('location: '.$baseUrl);
  exit();
}

if (isset($_POST['table']) && $_POST['table'] === 'employees') {
  $stmt = $db->prepare('INSERT INTO employees (name, salary) VALUES(?, ?)');
  $stmt->bind_param('si', $_POST['name'], $_POST['salary']);
  $stmt->execute();

  header('location: '.$baseUrl);
  exit();
}

$stmt = $db->prepare('SELECT * FROM employees');
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Mitarbeiter</h2>
<form method="POST">
  <input type="hidden" name="table" value="employees" />
  <table>
    <thead>
      <tr><th>Name</th><th>Stundenlohn</th><th></th></tr>
    </thead>
    <?php
      while($row = $result->fetch_assoc()) {
        echo '<tr><td>'.$row['name'].'</td><td>'.$row['salary'].'€</td><td><a href="?deleteEmployees='.$row['id'].'">x</a></td></tr>';
      }
    ?>
    <tr>
      <td><input type="text" name="name" placeholder="Name" required /></td>
      <td><input type="text" name="salary" placeholder="Gehalt" required /></td>
      <td><input type="submit" value="OK"></td>
    </tr>
  </table>
</form>
<br>
<br>
