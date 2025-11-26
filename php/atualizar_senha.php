<?php
session_start();
require_once __DIR__ . '/usuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    if (!isset($_SESSION['id_usuario'])) {
        echo "<script>alert('Sessão expirada. Faça login novamente.'); window.location.href='login.php';</script>";
        exit;
    }

    $id_usuario = $_SESSION['id_usuario'];
    $nova_senha = $_POST['nova_senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';

    if (empty($nova_senha) || empty($confirmar_senha)) {
        echo "<script>alert('Preencha todos os campos!'); history.back();</script>";
        exit;
    }

    if ($nova_senha !== $confirmar_senha) {
        echo "<script>alert('As senhas não coincidem!'); history.back();</script>";
        exit;
    }

    $usuario = new Usuario();
    $resultado = $usuario->atualizarSenha($id_usuario, $nova_senha);

    if ($resultado) {
        echo "<script>alert('Senha alterada com sucesso!'); window.location.href='perfil.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar senha. Tente novamente.'); history.back();</script>";
    }
}
?>
