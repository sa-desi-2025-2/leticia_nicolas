<?php

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

    <!-- CABEÇALHO -->
    <header class="header">
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
                <img src="https://img.icons8.com/?size=100&id=65342&format=png&color=000000" alt="User">
            </div>
            <div class="dropdown" id="dropdownMenu">
                <a href="#">Perfil</a>
                <a href="#">Seguidos</a>
                <a href="login.php">Sair</a>

             
            </div>
        </div>
    </header>



    <script src="../js/adm.js"></script>

</body>
</html>
