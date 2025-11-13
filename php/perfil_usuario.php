<?php
session_start();
require_once __DIR__ . '/conexao.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$idLogado = $_SESSION['id_usuario'];

$conexao = new Conexao();
$conn = $conexao->getCon();

// === BUSCAR DADOS DO USUÁRIO ===
$sql = "SELECT nome_usuario, foto_perfil, imagem_banner, bio FROM usuarios WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idLogado);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

// === BUSCAR CONTAGEM DE SEGUIDORES E SEGUINDO ===
$sqlSeguindo = "SELECT COUNT(*) AS seguindo FROM seguidores WHERE id_seguidor = ?";
$stmtSeguindo = $conn->prepare($sqlSeguindo);
$stmtSeguindo->bind_param("i", $idLogado);
$stmtSeguindo->execute();
$seguindo = $stmtSeguindo->get_result()->fetch_assoc()['seguindo'] ?? 0;

$sqlSeguidores = "SELECT COUNT(*) AS seguidores FROM seguidores WHERE id_seguindo = ?";
$stmtSeguidores = $conn->prepare($sqlSeguidores);
$stmtSeguidores->bind_param("i", $idLogado);
$stmtSeguidores->execute();
$seguidores = $stmtSeguidores->get_result()->fetch_assoc()['seguidores'] ?? 0;

$stmt->close();
$stmtSeguindo->close();
$stmtSeguidores->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuário</title>
    <link rel="stylesheet" href="../css/perfil_usuario.css">
    <link rel="stylesheet" href="../css/dropdown.css">
</head>
<body>
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="menu-icons">
            <div class="icon"></div>
            <div class="icon"></div>
            <div class="icon"></div>
            <div class="icon"></div>
        </div>
        <div class="add-icon">+</div>
    </aside>

    <!-- TOPO COM DROPDOWN -->
    <div class="top-bar">
        <div class="logo"><img src="../img/logo.png" alt="Logo"></div>
        <div class="user-menu">
            <div class="user-icon" id="userButton">
                <img src="<?= htmlspecialchars($usuario['foto_perfil'] ?? '../uploads/default.png') ?>" alt="Usuário">
            </div>
            <div class="dropdown-side" id="dropdownMenu">
                <div class="profile-section">
                    <img src="<?= htmlspecialchars($usuario['foto_perfil'] ?? '../uploads/default.png') ?>" alt="Usuário">
                    <h3><?= htmlspecialchars($usuario['nome_usuario'] ?? 'Usuário') ?></h3>
                </div>
                <nav class="menu-links">
                    <a href="perfil_usuario.php">Perfil</a>
                    <a href="configuracoes.php">Configurações</a>
                    <a href="seguidos.php">Seguidos</a>
                    <a href="login_estrutura.php">Sair</a>
                </nav>
            </div>
        </div>
    </div>

    <!-- PERFIL -->
    <div class="profile-container">
        <div class="banner">
            <img src="<?= htmlspecialchars($usuario['imagem_banner'] ?? '../uploads/default_banner.jpg') ?>" alt="Banner do Usuário">
        </div>
        <div class="profile-info">
            <div class="profile-pic">
                <img src="<?= htmlspecialchars($usuario['foto_perfil'] ?? '../uploads/default.png') ?>" alt="Foto de Perfil">
            </div>
            <h2><?= htmlspecialchars($usuario['nome_usuario'] ?? 'Usuário') ?></h2>
            <p class="bio"><?= htmlspecialchars($usuario['bio'] ?? 'Sem biografia adicionada.') ?></p>

            <div class="follow-stats">
                <span><strong><?= $seguindo ?></strong> Seguindo</span>
                <span><strong><?= $seguidores ?></strong> Seguidores</span>
            </div>
        </div>
    </div>

    <script src="../js/dropdown.js"></script>
</body>
</html>
