<?php
session_start();

require_once __DIR__ . '/gateway.php';
require_once __DIR__ . '/Seguidor.php';
require_once __DIR__ . '/Usuario.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$usuarioObj = new Usuario();
$seguidos = $usuarioObj->listarSeguidos($_SESSION['id_usuario']);
$seguidores = $usuarioObj->listarSeguidores($_SESSION['id_usuario']);

$nome = $_SESSION['nome_usuario'] ?? 'Usuário';
$fotoPerfil = $_SESSION['foto_perfil'] ?? '../uploads/default.png';
$homeLink = 'pagina_principal.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Seguidos - Checkpoint</title>
<link rel="stylesheet" href="../css/usuarios.css">
<link rel="stylesheet" href="../css/dropdown.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<!-- TOPO -->
<a href="<?= $homeLink ?>">
<div class="topo">
    <div class="logo">
        <img src="../img/logo.png" alt="Checkpoint Logo">
    </div>
</a>
<div class="user-menu">
    <div class="user-icon" onclick="toggleDropdown()">
        <img src="<?= htmlspecialchars($fotoPerfil) ?>" alt="Usuário Logado">
    </div>
</div>
</div>

<!-- DROPDOWN LATERAL -->
<div class="dropdown-side" id="dropdownMenu">
    <div class="profile-section">
        <img src="<?= htmlspecialchars($fotoPerfil) ?>" alt="Usuário">
        <h3><?= htmlspecialchars($nome) ?></h3>
    </div>
    <nav class="menu-links">
        <a href="<?= $homeLink ?>">
            <img src="https://img.icons8.com/?size=100&id=TZ2lKyH3LVjx&format=png&color=000000" alt="home" class="menu-icon">
            Home
        </a>
        <a href="perfil.php">
            <img src="https://img.icons8.com/?size=100&id=82751&format=png&color=000000" alt="home" class="menu-icon">
            Perfil
        </a>
        <?php if ($_SESSION['tipo_usuario'] === 'admin'): ?>
                <a href="pagina_principal_contas.php"><img src="https://img.icons8.com/?size=100&id=82535&format=png&color=000000" alt="home" class="menu-icon">Contas</a>
            <?php endif; ?>
        <a href="login_estrutura.php">
            <img src="https://img.icons8.com/?size=100&id=82792&format=png&color=000000" alt="home" class="menu-icon">
            Sair
        </a>
    </nav>
</div>
<div class="overlay" id="overlay" onclick="toggleDropdown()"></div>

<!-- CONTEÚDO PRINCIPAL -->
<main class="content">
    <div class="lists-container">
        <!-- SEGUINDO -->
        <div class="list-block">
            <h2 class="titulo">Seguindo</h2>
            <?php if (empty($seguidos)): ?>
                <p class="nenhum">Você ainda não segue ninguém</p>
            <?php else: ?>
                <div class="user-list">
                    <?php foreach ($seguidos as $user): ?>
                        <div class="user-card">
                            <div class="user-info">
                                <img src="<?= !empty($user['foto_perfil']) ? htmlspecialchars($user['foto_perfil']) : '../uploads/default.png' ?>" 
                                     class="foto-usuario" alt="Foto de <?= htmlspecialchars($user['nome_usuario']) ?>">
                                <p class="user-name">
                                    <a href="perfil_usuario.php?id=<?= $user['id_usuario'] ?>">
                                        <?= htmlspecialchars($user['nome_usuario']) ?>
                                    </a>
                                </p>
                            </div>
                            <button class="btn-desativar unfollow-btn" data-id="<?= $user['id_usuario'] ?>">Deixar de seguir</button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- SEGUIDORES -->
        <div class="list-block">
            <h2 class="titulo">Seguidores</h2>
            <?php if (empty($seguidores)): ?>
                <p class="nenhum">Ninguém está te seguindo ainda</p>
            <?php else: ?>
                <div class="user-list">
                    <?php foreach ($seguidores as $user): ?>
                        <div class="user-card">
                            <div class="user-info">
                                <img src="<?= !empty($user['foto_perfil']) ? htmlspecialchars($user['foto_perfil']) : '../uploads/default.png' ?>" 
                                     class="foto-usuario" alt="Foto de <?= htmlspecialchars($user['nome_usuario']) ?>">
                                <p class="user-name">
                                    <a href="perfil_usuario.php?id=<?= $user['id_usuario'] ?>">
                                        <?= htmlspecialchars($user['nome_usuario']) ?>
                                    </a>
                                </p>
                            </div>
                            <button class="btn-ativar follow-btn" data-id="<?= $user['id_usuario'] ?>">Seguir</button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<script src="../js/seguir.js"></script>
<script>
function toggleDropdown() {
    document.getElementById('dropdownMenu').classList.toggle('show');
    document.getElementById('overlay').classList.toggle('show');
}
</script>

</body>
</html>
