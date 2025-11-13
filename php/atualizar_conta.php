<?php
session_start();
require_once __DIR__ . '/usuario.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['id_usuario'];
$usuario = new Usuario();

// Diretório de uploads
$diretorio = "../uploads/";
if (!is_dir($diretorio)) mkdir($diretorio, 0755, true);

// Função de upload de imagem
function uploadImagem($arquivo, $prefixo, $id, $diretorio) {
    if (isset($arquivo) && $arquivo['error'] === 0) {
        $ext = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
        $extPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($ext, $extPermitidas)) {
            return null;
        }

        $nomeArquivo = $prefixo . "_" . $id . "_" . time() . "." . $ext;
        $caminhoCompleto = $diretorio . $nomeArquivo;

        if (move_uploaded_file($arquivo['tmp_name'], $caminhoCompleto)) {
            return $caminhoCompleto;
        }
    }
    return null;
}

// Uploads (os nomes devem bater com os "name" dos inputs HTML)
$fotoPerfil = uploadImagem($_FILES['foto_perfil'] ?? null, 'perfil', $id, $diretorio);
$fotoBanner = uploadImagem($_FILES['foto_banner'] ?? null, 'banner', $id, $diretorio);

// Bio
$bio = $_POST['bio'] ?? '';

// Busca dados atuais (para manter nome e email)
$dados = $usuario->buscarPorId($id);
if (!$dados) {
    die("Usuário não encontrado.");
}

$nome = $dados['nome_usuario'];
$email = $dados['email_usuario'];

// Atualiza perfil completo (foto_perfil + imagem_banner + bio)
$sucesso = $usuario->atualizarPerfil($id, $nome, $email, $bio, $fotoPerfil, $fotoBanner);

if ($sucesso) {
    // Atualiza sessão com nova foto, se houver
    if ($fotoPerfil) {
        $_SESSION['foto_perfil'] = $fotoPerfil;
    }

    // Retorna para aba de conta
    header("Location: perfil.php?aba=conta&sucesso=1");
    exit;
} else {
    die("Erro ao atualizar perfil.");
}
?>
