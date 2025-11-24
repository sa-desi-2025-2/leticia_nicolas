<?php
require_once __DIR__ . '/conexao.php';

$conexao = new Conexao();
$conn = $conexao->getCon();

if (!isset($_POST['id_postagem'])) {
    echo "erro";
    exit;
}

$id = intval($_POST['id_postagem']);

// 🔥 REMOVE TODAS AS REAÇÕES LIGADAS AO POST
$stmtReacoes = $conn->prepare("DELETE FROM reacoes WHERE id_postagem = ?");
$stmtReacoes->bind_param("i", $id);
$stmtReacoes->execute();
$stmtReacoes->close();

// 🔥 DEPOIS REMOVE O POST
$stmt = $conn->prepare("DELETE FROM postagens WHERE id_postagem = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "ok";
} else {
    echo "erro";
}
