<?php
session_start();
require_once 'Login.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = new Login();


    $login->setEmail(trim($_POST['email'] ?? ''));
    $login->setSenha(trim($_POST['senha'] ?? ''));


    $autenticado = $login->autenticar();

    if ($autenticado) {
        exit;
    } else {
     
        if (empty($_SESSION['login_error'])) {
            $_SESSION['login_error'] = "E-mail ou senha incorretos.";
        }

        header("Location: login_estrutura.php");
        exit;
    }
}

header("Location: login_estrutura.php");
exit;
?>
