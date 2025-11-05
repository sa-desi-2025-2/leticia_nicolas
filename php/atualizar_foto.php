<?php
session_start();
require_once __DIR__ . '/usuario.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['id_usuario'];
$usuario = new Usuario();

if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $diretorio = "../uploads/";
    if (!is_dir($diretorio)) {
        mkdir($diretorio, 0755, true);
    }

    // Gera um nome único pra imagem
    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $nomeArquivo = "perfil_" . $id . "_" . time() . "." . $ext;
    $caminhoCompleto = $diretorio . $nomeArquivo;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminhoCompleto)) {
        $usuario->atualizarFoto($id, $caminhoCompleto);
        $_SESSION['foto_perfil'] = $caminhoCompleto;
    }
}

header("Location: perfil.php");
exit;
?>
