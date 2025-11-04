<?php
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/login.php';
require_once __DIR__ . '/sessao.php';

session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login_estrutura.php");
    exit();
}

$conexao = new Conexao();
$id_usuario = $_SESSION['id_usuario'];

$stmt = $conexao->prepare("SELECT tipo_usuario FROM usuarios WHERE id_usuario = :id LIMIT 1");
$stmt->bindParam(':id', $id_usuario);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && $user['tipo_usuario'] == 1) {
    header("Location: pagina_principal_adm.php");
    exit();
} else {
    header("Location: pagina_principal.php");
    exit();
}
?>
