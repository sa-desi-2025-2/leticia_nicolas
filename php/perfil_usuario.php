<?php
session_start();
require_once __DIR__ . '/gateway.php';
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/Seguidor.php';

if (!isset($_GET['id'])) {
    header("Location: pagina_principal.php");
    exit();
}

$idPerfil = intval($_GET['id']);
$idLogado = $_SESSION['id_usuario'] ?? 0;

$conexao = new Conexao();
$conn = $conexao->getCon();
$seguidor = new Seguidor();

// === BUSCA DADOS DO USUÁRIO ===
$stmt = $conn->prepare("SELECT nome_usuario, foto_perfil, imagem_banner, bio FROM usuarios WHERE id_usuario = ?");
$stmt->bind_param("i", $idPerfil);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>Usuário não encontrado.</p>";
    exit();
}
$usuario = $result->fetch_assoc();
$stmt->close();

// === CONTADORES ===
$stmtSeguindo = $conn->prepare("SELECT COUNT(*) AS seguindo FROM seguidores WHERE id_seguidor = ?");
$stmtSeguindo->bind_param("i", $idPerfil);
$stmtSeguindo->execute();
$seguindo = $stmtSeguindo->get_result()->fetch_assoc()['seguindo'] ?? 0;
$stmtSeguindo->close();

$stmtSeguidores = $conn->prepare("SELECT COUNT(*) AS seguidores FROM seguidores WHERE id_seguindo = ?");
$stmtSeguidores->bind_param("i", $idPerfil);
$stmtSeguidores->execute();
$seguidores = $stmtSeguidores->get_result()->fetch_assoc()['seguidores'] ?? 0;
$stmtSeguidores->close();

// === VERIFICA SE LOGADO SEGUE O PERFIL ===
$jaSegue = $seguidor->verificaSeguindo($idLogado, $idPerfil);
$textoBotao = $jaSegue ? "Seguindo" : "Seguir";
$classeExtra = $jaSegue ? "seguindo" : "";

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($usuario['nome_usuario']) ?> - Perfil</title>
    <link rel="stylesheet" href="../css/pagina_principal.css">
    <link rel="stylesheet" href="../css/dropdown.css">
   
</head>
<body>

<!-- === SIDEBAR === -->
<aside class="sidebar">
    <div class="menu-icons">
        <div class="icon"></div><div class="icon"></div><div class="icon"></div><div class="icon"></div>
    </div>
    <div class="add-icon">+</div>
</aside>

<!-- === TOPBAR === -->
<div class="top-bar">
    <div class="logo"><img src="../img/logo.png" alt="Checkpoint"></div>
    <div class="user-menu">
        <div class="user-icon" id="userButton">
            <img src="<?= $_SESSION['foto_perfil'] ?? '../uploads/default.png' ?>" alt="Usuário">
        </div>
        <div class="dropdown-side" id="dropdownMenu">
            <div class="profile-section">
                <img src="<?= $_SESSION['foto_perfil'] ?? '../uploads/default.png' ?>" alt="Usuário">
                <h3><?= htmlspecialchars($_SESSION['nome_usuario'] ?? 'Usuário') ?></h3>
            </div>
            <nav class="menu-links">
                <a href="perfil.php">Perfil</a>
                <a href="#">Categorias</a>
                <a href="seguidos.php">Seguidos</a>
                <a href="login_estrutura.php">Sair</a>
            </nav>
        </div>
    </div>
</div>

<!-- === PERFIL === -->
<main class="perfil-container">
    <div class="banner" style="background-image: url('<?= !empty($usuario['imagem_banner']) ? '../uploads/' . htmlspecialchars($usuario['imagem_banner']) : '../img/banner_default.jpg' ?>');">
    </div>

    <div class="perfil-info">
        <img class="foto-perfil" src="<?= !empty($usuario['foto_perfil']) ? htmlspecialchars($usuario['foto_perfil']) : '../uploads/default.png' ?>" alt="Foto do usuário">
        <h2><?= htmlspecialchars($usuario['nome_usuario']) ?></h2>

        <div class="contadores">
            <span><strong><?= $seguindo ?></strong> Seguindo</span>
            <span><strong><?= $seguidores ?></strong> Seguidores</span>
        </div>

        <p class="bio"><?= !empty($usuario['bio']) ? htmlspecialchars($usuario['bio']) : 'Sem bio.' ?></p>

        <?php if ($idLogado != $idPerfil): ?>
            <button class="follow-btn <?= $classeExtra ?>" data-id="<?= $idPerfil ?>" data-tipo="usuario">
                <?= $textoBotao ?>
            </button>
        <?php endif; ?>
    </div>
</main>

<script src="../js/dropdown.js"></script>
<script src="../js/seguir.js"></script>
<script src="../js/perfil_usuario.js"></script>
</body>
</html>
