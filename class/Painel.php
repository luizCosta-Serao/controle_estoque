<?php

  class Painel {
    public static function isLogin() {
      return isset($_SESSION['login']) ? true : false;
    }
  }

?>