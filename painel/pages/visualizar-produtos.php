<?php
  $sql = MySql::connect()->prepare("SELECT * FROM `estoque`");
  $sql->execute();
  if ($sql->rowCount() >= 1) {
?>
<section>
  <h1 class="title">Produtos Cadastrados</h1>
  <form method="post">
    <div>
      <label for="search">Procure por um produto</label>
      <input type="text" name="search" id="search">
    </div>
    <input type="submit" name="search_product" value="Procurar">
  </form>
</section>

<section class="visualizar-produtos">
  <?php
    if (!isset($_POST['search_product'])) {
      $produtos = MySql::connect()->prepare("SELECT * FROM `estoque`");
      $produtos->execute();
      $produtos = $produtos->fetchAll();
    } else {
      $search = $_POST['search'];
      $produtos = MySql::connect()->prepare("SELECT * FROM `estoque` WHERE nome LIKE '%$search%' OR descricao LIKE '%$search%'");
      $produtos->execute();
      $produtos = $produtos->fetchAll();       
    }
    
    foreach ($produtos as $key => $value) {
      if ($value['quantidade'] !== 0) {
  ?>
    <div class="single-produto">
      <div class="info-single-produto">
        <?php
          if ($value['quantidade'] < '10') {
            Painel::alert('atencao', 'Atenção: este item possui menos de 10 unidades');
          }
        ?>
        <p><b>Nome do Produto:</b> <?php echo $value['nome'] ?></p>
        <p><b>Descrição:</b> <?php echo $value['descricao'] ?></p>
        <p><b>Largura:</b> <?php echo $value['largura'] ?>cm</p>
        <p><b>altura:</b> <?php echo $value['altura'] ?>cm</p>
        <p><b>Comprimento:</b> <?php echo $value['comprimento'] ?></p>
        <p><b>Peso: </b><?php echo $value['peso'] ?></p>
        <div class="quantidade-atual">
          <form method="post">
            <?php
              if (isset($_POST['atualizar'])) {
                $quantidade = $_POST['quantidade'];
                $id = $_POST['id'];
                $sql = MySql::connect()->prepare("UPDATE `estoque` SET quantidade = ? WHERE id = ?");
                $sql->execute(array($quantidade, $id));
                header('Location: '.INCLUDE_PATH_PAINEL.'visualizar-produtos');
                die();
              }
            ?>
            <label for="quantidade_produto">Quantidade Atual</label>
            <input type="number" name="quantidade" id="quantidade" min="0" max="900" step="1" value="<?php echo $value['quantidade'] ?>">
            <input type="hidden" name="id" value="<?php echo $value['id'] ?>">
            <input type="submit" name="atualizar" value="Atualizar">
          </form>
        </div>
        <a class="btn-excluir" href="">Excluir</a>
        <a class="btn-editar" href="">Editar</a>
      </div>
      <div class="img-single-produto">
        <?php
          $img_produto = MySql::connect()->prepare("SELECT * FROM `estoque_imagens` WHERE produto_id = ?");
          $img_produto->execute(array($value['id']));
          $img_produto = $img_produto->fetchAll()[0]['imagem'];
        ?>
        <img src="<?php echo INCLUDE_PATH_PAINEL; ?>uploads/<?php echo $img_produto ?>" alt="">
      </div>
    </div>
  <?php
      }
    }
  ?>
</section>


<section class="produtos-esgotados">
  <h1 style="margin-top: 40px" class="title">Produtos Esgotados</h1>
  <?php
    $produtos = MySql::connect()->prepare("SELECT * FROM `estoque` WHERE quantidade = 0");
    $produtos->execute();
    $produtos = $produtos->fetchAll();
    
    foreach ($produtos as $key => $value) {
  ?>
    <div class="single-produto">
      <div class="info-single-produto">
        <?php
          if ($value['quantidade'] < '10') {
            Painel::alert('atencao', 'Atenção: Produto esgotado');
          }
        ?>
        <p><b>Nome do Produto:</b> <?php echo $value['nome'] ?></p>
        <p><b>Descrição:</b> <?php echo $value['descricao'] ?></p>
        <p><b>Largura:</b> <?php echo $value['largura'] ?>cm</p>
        <p><b>altura:</b> <?php echo $value['altura'] ?>cm</p>
        <p><b>Comprimento:</b> <?php echo $value['comprimento'] ?></p>
        <p><b>Peso: </b><?php echo $value['peso'] ?></p>
        <div class="quantidade-atual">
          <form method="post">
            <?php
              if (isset($_POST['atualizar'])) {
                $quantidade = $_POST['quantidade'];
                
                $sql = MySql::connect()->prepare("UPDATE `estoque` SET quantidade = ? WHERE id = ?");
                $sql->execute(array($quantidade, $value['id']));

                header('Location: '.INCLUDE_PATH_PAINEL.'visualizar-produtos');
                die();
              }
            ?>
            <label for="quantidade_produto">Quantidade Atual</label>
            <input type="number" name="quantidade" id="quantidade" min="0" max="900" step="1" value="<?php echo $value['quantidade'] ?>">
            <input type="hidden" name="id" value="<?php echo $value['id'] ?>">
            <input type="submit" name="atualizar" value="Atualizar">
          </form>
        </div>
        <a class="btn-excluir" href="">Excluir</a>
        <a class="btn-editar" href="">Editar</a>
      </div>
      <div class="img-single-produto">
        <?php
          $img_produto = MySql::connect()->prepare("SELECT * FROM `estoque_imagens` WHERE produto_id = ?");
          $img_produto->execute(array($value['id']));
          $img_produto = $img_produto->fetchAll()[0]['imagem'];
        ?>
        <img src="<?php echo INCLUDE_PATH_PAINEL; ?>uploads/<?php echo $img_produto ?>" alt="">
      </div>
    </div>
  <?php } ?>
</section>
<?php } else { ?>
  <h1 class="title">Produtos em Falta</h1>
  <?php Painel::alert('atencao', 'Atenção: Nenhum produto cadastrado no momento'); ?>
<?php } ?>