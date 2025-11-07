<?php
session_start();
require_once __DIR__ . '/conexao.php';

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Usuário não logado']);
    exit;
}

$idSeguidor = $_SESSION['id_usuario'];
$idSeguido = intval($_POST['id_seguido'] ?? 0);

if ($idSeguido <= 0 || $idSeguido === $idSeguidor) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'ID inválido']);
    exit;
}

$conexao = new Conexao();
$conn = $conexao->getCon();

// Verifica se já segue
$sqlCheck = "SELECT * FROM seguidores WHERE id_seguidor = ? AND id_seguido = ?";
$stmt = $conn->prepare($sqlCheck);
$stmt->execute([$idSeguidor, $idSeguido]);
$jaSegue = $stmt->fetch(PDO::FETCH_ASSOC);

if ($jaSegue) {
    // Se já segue, desfaz o follow
    $sqlDelete = "DELETE FROM seguidores WHERE id_seguidor = ? AND id_seguido = ?";
    $stmt = $conn->prepare($sqlDelete);
    $stmt->execute([$idSeguidor, $idSeguido]);
    echo json_encode(['status' => 'ok', 'seguindo' => false]);
} else {
    // Se não segue, adiciona
    $sqlInsert = "INSERT INTO seguidores (id_seguidor, id_seguido) VALUES (?, ?)";
    $stmt = $conn->prepare($sqlInsert);
    $stmt->execute([$idSeguidor, $idSeguido]);
    echo json_encode(['status' => 'ok', 'seguindo' => true]);
}
