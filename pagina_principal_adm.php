<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkpoint</title>
    <link rel="stylesheet" href="../leticia_nicolas/css/pagina_principal.css">
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
            <img src="logo.png" alt="Checkpoint Logo">
        </div>

        <button class="btn-post">Criar Post</button>

        <div class="search-container">
            <input type="text" placeholder="Hinted search text">
            <button class="search-btn">🔍</button>
        </div>

        <!-- ÍCONE DO USUÁRIO -->
        <div class="user-menu">
            <div class="user-icon" id="userButton">
                <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="User">
            </div>
            <div class="dropdown" id="dropdownMenu">
                <a href="#">Perfil</a>
                <a href="#">Contas</a>
                <a href="#">Seguidos</a>
               
                <a href="#">Sair</a>
            </div>
        </div>
    </header>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="content">
        <div class="user-card">
            <div class="user-avatar"></div>
            <div class="user-info">
                <p class="user-name">FULANO</p>
                <a href="#" class="follow-btn">Seguir</a>
            </div>
        </div>
    </main>

    <script>
        // Mostrar ou esconder o menu do perfil
        const userButton = document.getElementById('userButton');
        const dropdownMenu = document.getElementById('dropdownMenu');

        userButton.addEventListener('click', () => {
            dropdownMenu.classList.toggle('show');
        });

        // Fecha o menu ao clicar fora
        window.addEventListener('click', (event) => {
            if (!userButton.contains(event.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
    </script>

</body>
</html>
