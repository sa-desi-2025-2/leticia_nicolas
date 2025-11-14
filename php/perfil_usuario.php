<?php
session_start();
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/Seguidor.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$idLogado = $_SESSION['id_usuario'];
$tipoUsuario = $_SESSION['tipo_usuario'] ?? 'user';
$homeLink = $tipoUsuario === 'admin' ? 'pagina_principal_adm.php' : 'pagina_principal.php';

// ID do usuário cujo perfil será exibido
if (!isset($_GET['id'])) {
    header("Location: $homeLink");
    exit;
}
$idUsuarioPerfil = intval($_GET['id']);

$conexao = new Conexao();
$conn = $conexao->getCon();

// Buscar dados do usuário
$sql = "SELECT nome_usuario, foto_perfil, imagem_banner, bio FROM usuarios WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idUsuarioPerfil);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
if (!$usuario) {
    header("Location: $homeLink");
    exit;
}

// Contagem de seguidores e seguindo
$sqlSeguindo = "SELECT COUNT(*) AS seguindo FROM seguidores WHERE id_seguidor = ?";
$stmtSeguindo = $conn->prepare($sqlSeguindo);
$stmtSeguindo->bind_param("i", $idUsuarioPerfil);
$stmtSeguindo->execute();
$seguindo = $stmtSeguindo->get_result()->fetch_assoc()['seguindo'] ?? 0;

$sqlSeguidores = "SELECT COUNT(*) AS seguidores FROM seguidores WHERE id_seguindo = ?";
$stmtSeguidores = $conn->prepare($sqlSeguidores);
$stmtSeguidores->bind_param("i", $idUsuarioPerfil);
$stmtSeguidores->execute();
$seguidores = $stmtSeguidores->get_result()->fetch_assoc()['seguidores'] ?? 0;

// Verificar se já segue
$sqlCheckFollow = "SELECT 1 FROM seguidores WHERE id_seguidor = ? AND id_seguindo = ?";
$stmtCheck = $conn->prepare($sqlCheckFollow);
$stmtCheck->bind_param("ii", $idLogado, $idUsuarioPerfil);
$stmtCheck->execute();
$jaSegue = $stmtCheck->get_result()->num_rows > 0;

// Fechar conexões
$stmt->close();
$stmtSeguindo->close();
$stmtSeguidores->close();
$stmtCheck->close();
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
            <img src="<?= htmlspecialchars($_SESSION['foto_perfil'] ?? '../uploads/default.png') ?>" alt="Usuário Logado">
        </div>
        <div class="dropdown-side" id="dropdownMenu">
            <div class="profile-section">
                <img src="<?= htmlspecialchars($_SESSION['foto_perfil'] ?? '../uploads/default.png') ?>" alt="Usuário Logado">
                <h3><?= htmlspecialchars($_SESSION['nome_usuario'] ?? 'Usuário') ?></h3>
            </div>
            <nav class="menu-links">
                <a href="<?= $homeLink ?>">Home</a>
                <a href="perfil.php?id=<?= $idLogado ?>">Perfil</a>
                <a href="#" id="abrirCategorias">Categorias</a>
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

        <h2><?= htmlspecialchars($usuario['nome_usuario']) ?></h2>
        <p class="bio"><?= htmlspecialchars($usuario['bio'] ?? 'Sem biografia adicionada.') ?></p>

        <div class="follow-stats">
            <span><strong><?= $seguindo ?></strong> Seguindo</span>
            <span><strong><?= $seguidores ?></strong> Seguidores</span>
        </div>

        <!-- BOTÃO AJAX -->
        <?php if ($idUsuarioPerfil !== $idLogado): ?>
        <div class="follow-action">
            <?php if ($jaSegue): ?>
                <button class="btn-desativar unfollow-btn" data-id="<?= $idUsuarioPerfil ?>">Deixar de seguir</button>
            <?php else: ?>
                <button class="btn-ativar follow-btn" data-id="<?= $idUsuarioPerfil ?>">Seguir</button>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </div>
</div>

<script src="../js/seguir.js"></script>
<script src="../js/dropdown.js"></script>

</body>
</html>
