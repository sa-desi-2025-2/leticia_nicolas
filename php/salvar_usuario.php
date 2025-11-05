<?php
session_start();
require_once 'Usuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = new Usuario();
    $usuario->setNome($_POST['nome']);
    $usuario->setEmail($_POST['email']);
    $usuario->setSenha($_POST['password']);
    $usuario->setDataNascimento($_POST['idade']);

    if($usuario->cadastrar()){
        header("Location: login_estrutura.php");
        exit();
    } else {
        $_SESSION['cadastro_erro'] = "email ja cadastradi";
        header("Location: cadastro.php");
        exit();
    }
} else {
    header("Location: cadastro.php");
    exit();
}
?>
