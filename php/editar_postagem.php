<?php
require_once __DIR__ . '/conexao.php';

$conexao = new Conexao();
$conn = $conexao->getCon();

if (!isset($_POST['id_postagem'], $_POST['texto_postagem'])) {
    echo "erro";
    exit;
}

$id = intval($_POST['id_postagem']);
$texto = trim($_POST['texto_postagem']);

$stmt = $conn->prepare("UPDATE postagens SET texto_postagem = ? WHERE id_postagem = ?");
$stmt->bind_param("si", $texto, $id);

if ($stmt->execute()) {
    echo "ok";
} else {
    echo "erro";
}
