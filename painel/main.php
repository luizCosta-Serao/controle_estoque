<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PAINEL; ?>/css/style.css">
  <title>Painel de Controle</title>
</head>
<body>
  <header class="header">
    <?php
      if (isset($_GET['loggout'])) {
        session_destroy();
        header('Location: '.INCLUDE_PATH_PAINEL);
      }
    ?>
    <p>Seja bem vindo <?php echo $_SESSION['user'] ?></p>
    <a href="<?php echo INCLUDE_PATH_PAINEL; ?>?loggout">
      Sair
    </a>
  </header>
  <section class="container">
    <aside class="sidebar">
        <a href="<?php echo INCLUDE_PATH_PAINEL; ?>">Início</a>
        <h2>Controle do Estoque</h2>
        <a href="<?php echo INCLUDE_PATH_PAINEL; ?>cadastrar-produto">Cadastrar Produto</a>
    </aside>
    <div class="content">
      <?php
        $url = isset($_GET['url']) ? $_GET['url'] : 'home';
        if (file_exists('pages/'.$url.'.php')) {
          include('pages/'.$url.'.php');
        } else {
          include('pages/home.php');
        }
      ?>
    </div>
  </section>
</body>
</html>