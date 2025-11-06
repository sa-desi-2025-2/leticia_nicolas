<?php
session_start(); // ✅ necessário para carregar a imagem da sessão
require_once __DIR__ . '/conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkpoint</title>
    <link rel="stylesheet" href="../css/pagina_principal.css">
</head>
<body>

    <!-- MENU LATERAL -->
    <aside class="sidebar">
        <div class="menu-icons">
            <div class="icon"></div>
            <div class="icon"></div>
            <div class="icon"></div>
            <div class="icon"></div>
        </div>
        <div class="add-icon">+</div>
    </aside>

    <!-- TOPO -->
    <div class="top-bar">
        <div class="logo">
            <img src="../img/logo.png" alt="Checkpoint Logo">
        </div>

        <button class="btn-post">Criar Post</button>

        <div class="search-container">
            <input type="text" placeholder="Hinted search text">
            <button class="search-btn">🔍</button>
        </div>

        <!-- ÍCONE DO USUÁRIO -->
        <div class="user-menu">
            <div class="user-icon" id="userButton">
                <!--Foto do usuário logado -->
                <img src="<?php echo $_SESSION['foto_perfil'] ?? '../uploads/default.png'; ?>" alt="Usuário">
            </div>
            <div class="dropdown" id="dropdownMenu">
                <a href="perfil.php">Perfil</a>
                <a href="perfil.php">Categorias</a>
                <a href="pagina_principal_contas.php">Contas</a>
                <a href="#">Seguidos</a>
                <a href="login_estrutura.php">Sair</a>
            </div>
        </div>
    </div>

    <script src="../js/principal.js"></script>
</body>
</html>
