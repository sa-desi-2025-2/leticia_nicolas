<?php
session_start();
require_once 'Login.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $login = new Login();
    $login->setEmail(trim($_POST['email']));
    $login->setSenha(trim($_POST['senha']));

    if ($login->autenticar()) {
        header("Location: pagina_principal.php");
        exit();
    } else {
        $_SESSION['login_error'] = "Email ou senha incorretos";
        header("Location: login_estrutura.php");
        exit();
    }

} else {
    header("Location: login_estrutura.php");
    exit();
}
?>
