<?php
session_start();

require_once __DIR__ . '/gateway.php';
require_once __DIR__ . '/Seguidor.php';

$seguidor = new Seguidor();
$seguidos = $seguidor->listarSeguidos($_SESSION['id_usuario']);

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$seguidor = new Seguidor();
$seguidos = $seguidor->listarSeguidos($_SESSION['id_usuario']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguidos - Checkpoint</title>
    <link rel="stylesheet" href="../css/usuarios.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<div class="topo">
    <div class="logo">
        <img src="../img/logo.png" alt="Checkpoint Logo">
        <a href="pagina_principal.php">
            <img src="https://img.icons8.com/?size=100&id=14096&format=png&color=000000" class="home-icon" alt="home">
        </a>
    </div>
    <div class="user-menu">
        <div class="user-icon">
            <img src="<?= $_SESSION['foto_perfil'] ?? '../uploads/default.png' ?>" alt="Usuário Logado">
        </div>
    </div>
</div>

<main class="content">
    <h2 class="titulo">Usuários Seguidos</h2>

    <?php if (empty($seguidos)): ?>
        <p class="nenhum">Você ainda não segue ninguém</p>
    <?php else: ?>
        <div class="user-list">
            <?php foreach ($seguidos as $user): ?>
                <div class="user-card">
                    <div class="user-info">
                        <img src="<?= !empty($user['foto_perfil']) ? htmlspecialchars($user['foto_perfil']) : '../uploads/default.png' ?>" 
                             class="foto-usuario" alt="Foto de <?= htmlspecialchars($user['nome_usuario']) ?>">
                        <p class="user-name"><?= htmlspecialchars($user['nome_usuario']) ?></p>
                    </div>

                    <button 
                        class="btn-desativar unfollow-btn"
                        data-id="<?= $user['id_usuario'] ?>"
                    >
                        Deixar de seguir
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<script src="../js/seguir.js"></script>
</body>
</html>
