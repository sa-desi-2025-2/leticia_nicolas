<?php
session_start();
require_once __DIR__ . '/conexao.php';

if (!isset($_SESSION['id_usuario'])) {
    http_response_code(403);
    echo json_encode(["erro" => "Usuário não logado"]);
    exit;
}

$idSeguidor = $_SESSION['id_usuario'];
$conexao = new Conexao();
$conn = $conexao->getCon();

try {
    // 🔹 Busca IDs de usuários seguidos
    $stmt = $conn->prepare("SELECT id_usuario FROM seguidores WHERE id_seguidor = ?");
    $stmt->execute([$idSeguidor]);
    $usuariosSeguidos = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // 🔹 Busca IDs de comunidades seguidas
    $stmt = $conn->prepare("SELECT id_comunidade FROM seguidores_comunidades WHERE id_seguidor = ?");
    $stmt->execute([$idSeguidor]);
    $comunidadesSeguidas = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // 🔸 Retorna tudo em JSON
    echo json_encode([
        "usuarios" => $usuariosSeguidos,
        "comunidades" => $comunidadesSeguidas
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["erro" => "Erro ao buscar seguidos"]);
}
?>

