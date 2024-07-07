<?php
  // Obtendo dados de um produto da tabela estoque
  $id = $_GET['id'];
  $sql = MySql::connect()->prepare("SELECT * FROM `estoque` WHERE id = ?");
  $sql->execute(array($id));
  if ($sql->rowCount() === 0) {
    Painel::alert('erro', 'O produto que você deseja editar não existe');
    die();
  }
  $infoProduto = $sql->fetch();

  // Obtendo as imagens do produto
  $pegaImagens = MySql::connect()->prepare("SELECT * FROM `estoque_imagens` WHERE produto_id = ?");
  $pegaImagens->execute(array($id));
  $pegaImagens = $pegaImagens->fetchAll();

  // Deletar imagem do produto
  if (isset($_GET['deletar_imagem'])) {
    $idDeleteImagem = $_GET['deletar_imagem'];

    $imagem = MySql::connect()->prepare("SELECT * FROM `estoque_imagens` WHERE id = ?");
    $imagem->execute(array($idDeleteImagem));
    $imagem = $imagem->fetch();

    @unlink(BASE_DIR_PAINEL.'/uploads/'.$imagem['imagem']);

    $sql = MySql::connect()->prepare("DELETE FROM `estoque_imagens` WHERE id = ?");
    $sql->execute(array($idDeleteImagem));

    Painel::alert('sucesso', 'A imagem foi deletada com sucesso');
    header('Location: '.INCLUDE_PATH_PAINEL.'editar-produto?id='.$id);
    die();
  }
?>

<section class="imagens-produto">
  <?php
    // Iterando as imagens do produto
    foreach ($pegaImagens as $key => $value) {
      // Inserindo as imagens na página
  ?>
    <div class="single-imagem-produto">
      <img src="<?php echo INCLUDE_PATH_PAINEL; ?>uploads/<?php echo $value['imagem'] ?>" alt="">
      <a class="btn-excluir" href="<?php echo INCLUDE_PATH_PAINEL; ?>editar-produto?id=<?php echo $id ?>&deletar_imagem=<?php echo $value['id']; ?>">Excluir</a>
    </div>
  <?php } ?>

</section>

<form method="post" enctype="multipart/form-data">
  <?php
    if (isset($_POST['acao'])) {
      // Obtendo valores dos inputs
      $nome = $_POST['nome'];
      $descricao = $_POST['descricao'];
      $largura = $_POST['largura'];
      $altura = $_POST['altura'];
      $comprimento = $_POST['comprimento'];
      $peso = $_POST['peso'];
      $quantidade = $_POST['quantidade'];

      // variáveis para upload de imagens
      $imagens = [];
      $amountFiles = count($_FILES['imagem']['name']);

      // variável indicativa de sucesso
      $sucesso = true;

      // Se usuário tiver inserido imagem
      if($_FILES['imagem']['name'][0] !== '') {
        // Verificando se images são válidas
        for ($i=0; $i < $amountFiles; $i++) {
          $imagemAtual = [
            'type' => $_FILES['imagem']['type'][$i],
            'size' => $_FILES['imagem']['size'][$i],
          ];
          if (Painel::imagemValida($imagemAtual) === false) {
            $sucesso = false;
            Painel::alert('erro', 'Uma das imagens selecionadas é inválida');
            break;
          }
        }
      }

      // Se não tiver nenhum problema
      if ($sucesso) {
        // Se usuário tiver selecionado imagens
        if ($_FILES['imagem']['name'][0] !== '') {
          for ($i=0; $i < $amountFiles; $i++) {
            // Fazer o upload das imagens
            $imagemAtual = [
              'tmp_name' => $_FILES['imagem']['tmp_name'][$i],
              'name' => $_FILES['imagem']['name'][$i],
            ];
            $imagens[] = Painel::uploadFile($imagemAtual);
          }

          foreach ($imagens as $key => $value) {
            // Inserindo cada imagem na tabela estoque_imagens
            MySql::connect()->exec("INSERT INTO `estoque_imagens` VALUES (null, $id, '$value')");
          }
        }

        // Atualizando a tabela estoque
        $sql = MySql::connect()->prepare("UPDATE `estoque` SET nome = ?, descricao = ?, altura = ?, largura = ?, comprimento = ?, peso = ?, quantidade = ? WHERE id = ?");
        $sql->execute(array($nome, $descricao, $altura, $largura, $comprimento, $peso, $quantidade, $id));

        Painel::alert('sucesso', 'Você atualizou seu produto com sucesso!');

        // Atualizando a página para novo valores aparecerem
        header('Location: '.INCLUDE_PATH_PAINEL.'editar-produto?id='.$id);
        die();
      }
    }
  ?>

  <div>
    <label for="nome">Nome do Produto</label>
    <input type="text" name="nome" id="nome" value="<?php echo $infoProduto['nome']; ?>">
  </div>

  <div>
    <label for="descricao">Descrição do Produto</label>
    <textarea name="descricao" id="descricao"><?php echo $infoProduto['descricao'] ?></textarea>
  </div>

  <div>
    <label for="largura">Largura do Produto</label>
    <input type="number" name="largura" id="largura" min="0" max="900" step="1" value="<?php echo $infoProduto['largura']; ?>">
  </div>

  <div>
    <label for="altura">Altura do Produto</label>
    <input type="number" name="altura" id="altura" min="0" max="900" step="1" value="<?php echo $infoProduto['altura']; ?>">
  </div>

  <div>
    <label for="comprimento">Comprimento do Produto</label>
    <input type="number" name="comprimento" id="comprimento" min="0" max="900" step="1" value="<?php echo $infoProduto['comprimento']; ?>">
  </div>

  <div>
    <label for="peso">Peso do Produto</label>
    <input type="number" name="peso" id="peso" min="0" max="900" step="1" value="<?php echo $infoProduto['peso']; ?>">
  </div>

  <div>
    <label for="quantidade">Quantidade do Produto</label>
    <input type="number" name="quantidade" id="quantidade" min="0" max="900" step="1" value="<?php echo $infoProduto['quantidade']; ?>">
  </div>

  <div>
    <label for="imagem">Selecione as Imagens</label>
    <input multiple type="file" name="imagem[]" id="imagem">
  </div>

  <input type="submit" name="acao" id="acao" value="Cadastrar Produto">
</form>