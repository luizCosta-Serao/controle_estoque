<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PAINEL; ?>/css/style.css">
  <title>Login - Painel de Controle</title>
</head>
<body>
  <section class="login">
    <h1>Efeture o Login</h1>
    <form class="form-login" method="post">
      <?php
        if (isset($_POST['action'])) {
          $user = $_POST['user'];
          $password = $_POST['password'];
  
          $sql = MySql::connect()->prepare("SELECT * FROM `usuarios_admin` WHERE user = ? AND password = ?");
          $sql->execute(array($user, $password));
          if ($sql->rowCount() === 1) {
            $_SESSION['login'] = true;
            $_SESSION['user'] = $user;
            $_SESSION['password'] = $password;
            header('Location:'.INCLUDE_PATH_PAINEL);
          } else {
            echo '<div class="box-erro">Usuário ou Senha incorretos</div>';
          }
        }
      ?>
  
      <label for="user">User</label>
      <input type="text" name="user" id="user">
  
      <label for="password">Password</label>
      <input type="password" name="password" id="password">
  
      <input type="submit" name="action" value="Login">
    </form>
  </section>
</body>
</html>