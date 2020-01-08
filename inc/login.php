<?php
$bossPassword = 'falsch';

if (isset($_POST['login'])) {
  if ($_POST['login'] === $bossPassword) {
    $_SESSION['auth'] = true;

    header('location: '.$baseUrl);
    exit();
  } else {
    echo '<p style="color: red">Dein Passwort ist: falsch!</p>';
  }
}
?>

<div class="panel">
  <h2>Login</h2>
  <form method="POST">
    <input type="password" name="login" value="" placeholder="Passwort" />
    <input type="submit" value="Login" />
  </form>
</div>
