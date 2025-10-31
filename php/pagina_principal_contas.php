<?php
require_once __DIR__ . '/conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkpoint - Gerenciamento de Usuários</title>

    <!-- CSS Gamer -->
    <link rel="stylesheet" href="../css/usuarios.css">

    <!-- ÍCONES -->
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>



    <!-- CABEÇALHO -->
    <header class="header">
        <div class="logo">
            <img src="logo.png" alt="Checkpoint Logo">
        </div>

        <div class="user-menu">
            <div class="user-icon" id="userButton">
                <img src="https://img.icons8.com/?size=100&id=65342&format=png&color=000000" alt="Usuário">
            </div>
         
        </div>
    </header>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="content">
        <h2 class="titulo">Gerenciamento de Usuários</h2>

        <div class="user-list">
            <div class="user-card">
                <div class="user-info">
                    <i class="bi bi-person-circle"></i>
                    <p class="user-name">Usuário 1</p>
                </div>
                <button class="btn-ativar">Ativar</button>
            </div>

            <div class="user-card">
                <div class="user-info">
                    <i class="bi bi-person-circle"></i>
                    <p class="user-name">Usuário 2</p>
                </div>
                <button class="btn-desativar">Desativar</button>
            </div>

            <div class="user-card">
                <div class="user-info">
                    <i class="bi bi-person-circle"></i>
                    <p class="user-name">Usuário 3</p>
                </div>
                <button class="btn-ativar">Ativar</button>
            </div>

            <div class="user-card">
                <div class="user-info">
                    <i class="bi bi-person-circle"></i>
                    <p class="user-name">Usuário 4</p>
                </div>
                <button class="btn-desativar">Desativar</button>
            </div>

            <div class="user-card">
                <div class="user-info">
                    <i class="bi bi-person-circle"></i>
                    <p class="user-name">Usuário 5</p>
                </div>
                <button class="btn-ativar">Ativar</button>
            </div>
        </div>
    </main>

    <!-- JS -->
    <script src="../js/adm.js"></script>
</body>
</html>
