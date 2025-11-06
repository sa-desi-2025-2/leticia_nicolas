<?php
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/login.php';

session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login_estrutura.php");
    exit();
}

$conexao = new Conexao();
$con = $conexao->getCon(); // obtém a conexão mysqli
$id_usuario = $_SESSION['id_usuario'];

$stmt = $con->prepare("SELECT tipo_usuario FROM usuarios WHERE id_usuario = ? LIMIT 1");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && $user['tipo_usuario'] == 1) {
    header("Location: pagina_principal_adm.php");
    exit();
} else {
    header("Location: pagina_principal.php");
    exit();
}

$stmt->close();
$con->close();
?>
