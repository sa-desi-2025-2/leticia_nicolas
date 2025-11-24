<?php
session_start();

require_once __DIR__ . '/gateway.php';
require_once __DIR__ . '/usuario.php';


if ($_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: pagina_principal.php");
    exit();
}

$usuario = new Usuario();
$usuarios = $usuario->listarUsuarios();

$homeLink = "pagina_principal_adm.php";

$fotoLogado = $_SESSION['foto_perfil'] ?? '../uploads/default.png';
$nomeLogado = $_SESSION['nome_usuario'] ?? 'Usuário';
$idLogado   = $_SESSION['id_usuario'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkpoint - Gerenciamento de Usuários</title>

    <link rel="stylesheet" href="../css/usuarios.css">
    <link rel="stylesheet" href="../css/dropdown.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>


<div class="topo">


    <a href="<?= $homeLink ?>" class="logo">
        <img src="../img/logo.png" alt="Checkpoint Logo">
    </a>


    <div class="user-menu">
        <div class="user-icon" id="userButton">
            <img src="<?= $fotoLogado ?>" alt="Usuário Logado">
        </div>
    </div>

</div>



<div id="dropdownMenu" class="dropdown-side">

    <div class="profile-section">
        <img src="<?= $fotoLogado ?>" alt="Foto do Usuário">

        <h3>
            <a href="perfil_usuario.php?id=<?= $idLogado ?>" style="color:white; text-decoration:none;">
                <?= $nomeLogado ?>
            </a>
        </h3>
    </div>

    <div class="menu-links">
    <a href="<?= $homeLink ?>">
            <img src="https://img.icons8.com/?size=100&id=TZ2lKyH3LVjx&format=png&color=000000" alt="home" class="menu-icon">
            Home
</a>
        <a href="perfil.php"><img src="https://img.icons8.com/?size=100&id=82751&format=png&color=000000" alt="home" class="menu-icon">Perfil</a>

    
        <a href="seguidos.php"><img src="https://img.icons8.com/?size=100&id=85445&format=png&color=000000" alt="home" class="menu-icon">Seguidos</a>
        <a href="login_estrutura.php">
            <img class="menu-icon" src="https://img.icons8.com/?size=100&id=82792&format=png&color=000000">
            Sair
        </a>
    </div>
</div>


<div id="overlay" class="overlay"></div>



<main class="content">
    <h2 class="titulo">Gerenciamento de Usuários</h2>

    <div class="user-list">
        <?php foreach ($usuarios as $user): ?>
            <div class="user-card">
                <div class="user-info">

                   
                    <img 
                        src="<?= !empty($user['foto_perfil']) ? htmlspecialchars($user['foto_perfil']) : '../uploads/default.png' ?>" 
                        alt="Foto de <?= htmlspecialchars($user['nome_usuario']) ?>" 
                        class="foto-usuario"
                    >

                    <a 
    class="user-name" 
    href="perfil_usuario.php?id=<?= $user['id_usuario'] ?>"
    style="text-decoration:none; color:black; font-weight:600;"
>
    <?= htmlspecialchars($user['nome_usuario']) ?>
</a>

                </div>

                
                <button 
                    id="btnUsuario<?= $user['id_usuario'] ?>" 
                    class="<?= $user['ativo'] == 1 ? 'btn-desativar' : 'btn-ativar' ?>" 
                    data-id="<?= $user['id_usuario'] ?>"
                >
                    <?= $user['ativo'] == 1 ? 'Desativar' : 'Ativar' ?>
                </button>
            </div>
        <?php endforeach; ?>
    </div>
</main>



<script src="../js/adm.js"></script>
<script src="../js/principal.js"></script>

</body>
</html>
