<?php
session_start();
require_once 'Login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = new Login();
    $login->setEmail(trim($_POST['email']));
    $login->setSenha(trim($_POST['senha']));

    // tenta autenticar o usuario
    if ($login->autenticar()) {
        //se o login.php redirecionar o usuario o codigo abaixo não roda
        exit;
    } else {
        // se falhou email ou senha errados ou conta desativada
        if (empty($_SESSION['login_error'])) {
            $_SESSION['login_error'] = "E-mail ou senha incorretos";
        }

        header("Location: login_estrutura.php"); 
        exit;
    }
} else {
    header("Location: login_estrutura.php");
    exit;
}
?>
