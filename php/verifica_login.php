<?php
session_start();
require_once 'Login.php';

// Verifica se veio via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = new Login();

    // Remove espaços e define email/senha
    $login->setEmail(trim($_POST['email'] ?? ''));
    $login->setSenha(trim($_POST['senha'] ?? ''));

    // Tenta autenticar
    $autenticado = $login->autenticar();

    // Se o login for bem-sucedido, o próprio login.php já redireciona
    if ($autenticado) {
        exit;
    } else {
        // Exibe erro (já setado dentro de Login.php)
        if (empty($_SESSION['login_error'])) {
            $_SESSION['login_error'] = "E-mail ou senha incorretos.";
        }

        header("Location: login_estrutura.php");
        exit;
    }
}

// Se não for POST, redireciona de volta para o login
header("Location: login_estrutura.php");
exit;
?>
