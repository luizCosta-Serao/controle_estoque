<?php

  class Painel {
    // Verificar se está logado
    public static function isLogin() {
      return isset($_SESSION['login']) ? true : false;
    }

    // Mensagem indicativa de sucesso ou erro
    public static function alert($type, $message) {
      if ($type === 'sucesso') {
        echo '<p class="sucesso">'.$message.'</p>';
      } else if ($type === 'erro') {
        echo '<p class="erro">'.$message.'</p>';
      } else if ($type === 'atencao') {
        echo '<p class="atencao">'.$message.'</p>';
      }
    }

    // Validador do formato da imagem
    public static function imagemValida($imagem) {
      if (
        $imagem['type'] === 'image/jpeg' ||
        $imagem['type'] === 'image/jpg' ||
        $imagem['type'] === 'image/png'
      ) {
        return true;
      } else {
        return false;
      }
    }

    // realizar upload da nova imagem e salvar na pasta uploads
    public static function uploadFile($file) {
      $formatoImagem = explode('.', $file['name'])[1];
      $nomeImagem = uniqid().'.'.$formatoImagem;
      if(move_uploaded_file($file['tmp_name'], BASE_DIR_PAINEL.'/uploads/'.$nomeImagem)) {
        return $nomeImagem;
      } else {
        return false;
      }
    }

    // função para deletar arquivo
    public static function deleteFile($file) {
      @unlink('uploads/'.$file);
    }
  }

?>