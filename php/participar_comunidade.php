<?php
require_once "Conexao.php";
session_start();

if (!isset($_SESSION['id_usuario'])) {
    die("Necessário login.");
}

$id_usuario = $_SESSION['id_usuario'];
$id_comunidade = intval($_POST['id_comunidade']);

/* VERIFICAR SE JÁ PARTICIPA */
$sql = $conn->prepare("
    SELECT * FROM usuarios_comunidades
    WHERE id_usuario = ? AND id_comunidade = ?
");
$sql->execute([$id_usuario, $id_comunidade]);

if ($sql->rowCount() > 0) {
    // sair
    $delete = $conn->prepare("
        DELETE FROM usuarios_comunidades
        WHERE id_usuario = ? AND id_comunidade = ?
    ");
    $delete->execute([$id_usuario, $id_comunidade]);

} else {
    // entrar
    $insert = $conn->prepare("
        INSERT INTO usuarios_comunidades (id_usuario, id_comunidade)
        VALUES (?, ?)
    ");
    $insert->execute([$id_usuario, $id_comunidade]);
}

header("Location: perfil_comunidade.php?id=" . $id_comunidade);
exit();
