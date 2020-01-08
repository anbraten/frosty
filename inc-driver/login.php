<?php
$bossPassword = 'falsch';

if (isset($_POST['driver'])) {
  $_SESSION['driver'] = $_POST['driver'];

  reload();
}
?>

<div class="panel">
  <h2>Login - Mitarbeiter</h2>
  <form method="POST">

    <select name="driver" required>
      <option value="">---</option>
      <?php
        $stmt = $db->prepare('SELECT * FROM employees');
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
          echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
        }
      ?>
    </select>

    <input type="submit" value="Login" />
  </form>
</div>