<?php
session_start();
require_once 'usuario.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$usuario = new Usuario();

// Desativa o usuário no banco
if ($usuario->desativarConta($id_usuario)) {
    // Encerra a sessão
    session_destroy();
    echo "<script>
        alert('Sua conta foi desativada com sucesso.');
        window.location.href = 'login.php';
    </script>";
} else {
    echo "<script>
        alert('Erro ao desativar a conta. Tente novamente.');
        window.location.href = 'perfil.php';
    </script>";
}
?>
