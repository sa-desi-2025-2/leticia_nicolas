<?php
// reagir.php
require_once __DIR__ . '/gateway.php';
require_once __DIR__ . '/conexao.php';
header('Content-Type: application/json; charset=utf-8');

$id_usuario = $_SESSION['id_usuario'] ?? 0;
if (!$id_usuario) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não autenticado.']);
    exit;
}

$id_postagem = intval($_POST['id_postagem'] ?? 0);
$tipo_reacao = $_POST['tipo_reacao'] ?? '';

if ($id_postagem <= 0 || !in_array($tipo_reacao, ['like', 'dislike'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados inválidos.']);
    exit;
}

$conexao = new Conexao();
$conn = $conexao->getCon();

// Verifica existência de reação anterior
$stmt = $conn->prepare("SELECT id_reacao, tipo_reacao FROM reacoes WHERE id_usuario = ? AND id_postagem = ? LIMIT 1");
$stmt->bind_param("ii", $id_usuario, $id_postagem);
$stmt->execute();
$res = $stmt->get_result();
$exist = $res->fetch_assoc();
$stmt->close();

if ($exist) {
    if ($exist['tipo_reacao'] === $tipo_reacao) {
        // mesmo tipo: remover (toggle off)
        $del = $conn->prepare("DELETE FROM reacoes WHERE id_reacao = ?");
        $del->bind_param("i", $exist['id_reacao']);
        $del->execute();
        $del->close();
        $minha_reacao = null;
    } else {
        // diferente: atualizar para o novo tipo
        $up = $conn->prepare("UPDATE reacoes SET tipo_reacao = ? WHERE id_reacao = ?");
        $up->bind_param("si", $tipo_reacao, $exist['id_reacao']);
        $up->execute();
        $up->close();
        $minha_reacao = $tipo_reacao;
    }
} else {
    // inserir nova reação
    $ins = $conn->prepare("INSERT INTO reacoes (id_usuario, id_postagem, tipo_reacao) VALUES (?, ?, ?)");
    $ins->bind_param("iis", $id_usuario, $id_postagem, $tipo_reacao);
    $ins->execute();
    $ins->close();
    $minha_reacao = $tipo_reacao;
}

// recalcula contadores
$q1 = $conn->prepare("SELECT COUNT(*) AS likes FROM reacoes WHERE id_postagem = ? AND tipo_reacao = 'like'");
$q1->bind_param("i", $id_postagem);
$q1->execute();
$likes = $q1->get_result()->fetch_assoc()['likes'] ?? 0;
$q1->close();

$q2 = $conn->prepare("SELECT COUNT(*) AS dislikes FROM reacoes WHERE id_postagem = ? AND tipo_reacao = 'dislike'");
$q2->bind_param("i", $id_postagem);
$q2->execute();
$dislikes = $q2->get_result()->fetch_assoc()['dislikes'] ?? 0;
$q2->close();

$conn->close();

echo json_encode([
    'sucesso' => true,
    'likes' => (int)$likes,
    'dislikes' => (int)$dislikes,
    'minha_reacao' => $minha_reacao
], JSON_UNESCAPED_UNICODE);
