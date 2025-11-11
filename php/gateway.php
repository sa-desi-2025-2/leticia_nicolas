<?php
require_once __DIR__ . '/conexao.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login_estrutura.php");
    exit();
}

$conexao = new Conexao();
$con = $conexao->getCon();
$id_usuario = $_SESSION['id_usuario'];

// Busca o tipo do usuário
$stmt = $con->prepare("SELECT tipo_usuario FROM usuarios WHERE id_usuario = ? LIMIT 1");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    session_destroy();
    header("Location: login_estrutura.php");
    exit();
}

// Armazena tipo de usuário na sessão
$_SESSION['tipo_usuario'] = $user['tipo_usuario'];

$stmt->close();
$con->close();
?>
