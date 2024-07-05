<section class="cadastrar-produto">
  <h1 class="title">Cadastrar Produto</h1>

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

        // obtendo todas as imagens selecionadas
        $imagens = array();
        // obtendo a quantidade de imagens selecionadas
        $amountFiles = count($_FILES['imagem']['name']);

        $sucesso = true;

        // Se tiver selecionado uma imagem
        if ($_FILES['imagem']['name'][0] !== '') {
          // Loop nas imagens
          for ($i=0; $i < $amountFiles; $i++) {
            // obtendo o type e size de cada imagem 
            $imagemAtual = [
              'type' => $_FILES['imagem']['type'][$i],
              'size' => $_FILES['imagem']['size'][$i],
            ];
            // Se imagem tiver um formato inválido
            if (Painel::imagemValida($imagemAtual) === false) {
              $sucesso = false;
              Painel::alert('erro', 'Uma das imagens selecionadas é inválida');
              break;
            }
          }
        } else {
          // Se não tiver selecionado nenhuma imagem
          $sucesso = false;
          Painel::alert('erro', 'Você precisa selecionar pelo menos uma imagem');
        }

        if ($sucesso) {
          // Loop por cada imagem
          for ($i=0; $i < $amountFiles; $i++) {
            // obtendo o tmp_name e o name de cada imagem para fazer o upload 
            $imagemAtual = [
              'tmp_name' => $_FILES['imagem']['tmp_name'][$i],
              'name' => $_FILES['imagem']['name'][$i],
            ];
            $imagens[] = Painel::uploadFile($imagemAtual);
          }

          // Inserindo no banco de dados as informações do produto
          $sql = MySql::connect()->prepare("INSERT INTO `estoque` VALUES (null, ?, ?, ?, ?, ?, ?, ?)");
          $sql->execute(array($nome, $descricao, $largura, $altura, $comprimento, $peso, $quantidade));

          // Obtendo o id da última inserção no banco de dados
          $lastId = MySql::connect()->lastInsertId();
          // Loop foreach nas imagens
          foreach ($imagens as $key => $value) {
            // Inserindo cada imagem no banco de dados
            MySql::connect()->exec("INSERT INTO `estoque_imagens` VALUES (null, $lastId, '$value')");
          }

          Painel::alert('sucesso', 'Produto cadastrado com sucesso');

        }

      }
    ?>

    <div>
      <label for="nome">Nome do Produto</label>
      <input type="text" name="nome" id="nome">
    </div>

    <div>
      <label for="descricao">Descrição do Produto</label>
      <textarea name="descricao" id="descricao"></textarea>
    </div>

    <div>
      <label for="largura">Largura do Produto</label>
      <input type="number" name="largura" id="largura" min="0" max="900" step="5" value="0">
    </div>

    <div>
      <label for="altura">Altura do Produto</label>
      <input type="number" name="altura" id="altura" min="0" max="900" step="5" value="0">
    </div>

    <div>
      <label for="comprimento">Comprimento do Produto</label>
      <input type="number" name="comprimento" id="comprimento" min="0" max="900" step="5" value="0">
    </div>

    <div>
      <label for="peso">Peso do Produto</label>
      <input type="number" name="peso" id="peso" min="0" max="900" step="5" value="0">
    </div>

    <div>
      <label for="quantidade">Quantidade do Produto</label>
      <input type="number" name="quantidade" id="quantidade" min="0" max="900" step="5" value="0">
    </div>

    <div>
      <label for="imagem">Selecione as Imagens</label>
      <input multiple type="file" name="imagem[]" id="imagem">
    </div>

    <input type="submit" name="acao" id="acao" value="Cadastrar Produto">
  </form>
</section>