<?php
session_start();
require_once __DIR__ . '/usuario.php';

$usuario = new Usuario();
$id = $_SESSION['id_usuario'];

$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';

if (!empty($nome) && !empty($email)) {
    $usuario->atualizarDados($id, $nome, $email);
    header("Location: perfil.php?atualizado=1");
    exit;
} else {
    echo "Preencha todos os campos!";
}
