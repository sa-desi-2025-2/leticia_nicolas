<?php
require_once __DIR__ . '/conexao.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['id_usuario'])) {
    header("Location: login_estrutura.php");
    exit();
}

$conexao = new Conexao();
$con = $conexao->getCon();
$id_usuario = $_SESSION['id_usuario'];


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


$_SESSION['tipo_usuario'] = $user['tipo_usuario'];

$stmt->close();
$con->close();
?>
