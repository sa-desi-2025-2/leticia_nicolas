<?php
session_start();

require_once __DIR__ . '/gateway.php';
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/pesquisa_funcao.php';
require_once __DIR__ . '/Seguidor.php';

// Se o usuário for admin, redireciona para a página de admin
if ($_SESSION['tipo_usuario'] === 'admin') {
    header("Location: pagina_principal_adm.php");
    exit();
}

// 🔹 Instancia a classe de seguidores
$seguidor = new Seguidor();
$idLogado = $_SESSION['id_usuario'] ?? 0;

$termo = $_GET['q'] ?? '';
$paginaUsuarios = intval($_GET['page_usuario'] ?? 1);
$paginaComunidades = intval($_GET['page_comunidade'] ?? 1);
$resultado = [];

if (!empty($termo)) {
    $resultado = executarPesquisa($termo, $paginaUsuarios, $paginaComunidades);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkpoint</title>

    <!-- CSS principal -->
    <link rel="stylesheet" href="../css/pagina_principal.css">
    <link rel="stylesheet" href="../css/pesquisa.css">

    <!-- CSS do dropdown lateral -->
    <link rel="stylesheet" href="../css/dropdown.css">
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

        <!-- 🔍 PESQUISA FUNCIONAL -->
        <div class="search-container">
            <form method="GET" action="" class="search-form">
                <input 
                    type="text" 
                    name="q" 
                    placeholder="Pesquisar usuários ou comunidades..." 
                    value="<?= htmlspecialchars($termo) ?>"
                >
                <button class="search-btn" type="submit">🔍</button>
            </form>
        </div>

        <!-- ✅ DROPDOWN LATERAL DO USUÁRIO -->
        <div class="user-menu">
            <div class="user-icon" id="userButton">
                <img src="<?php echo $_SESSION['foto_perfil'] ?? '../uploads/default.png'; ?>" alt="Usuário">
            </div>

            <div class="dropdown-side" id="dropdownMenu">
                <div class="profile-section">
                    <img src="<?php echo $_SESSION['foto_perfil'] ?? '../uploads/default.png'; ?>" alt="Usuário">
                    <h3><?= htmlspecialchars($_SESSION['nome_usuario'] ?? 'Usuário') ?></h3>
                </div>
                <nav class="menu-links">
                    <a href="perfil.php"><img src="https://img.icons8.com/?size=100&id=114064&format=png&color=000000" alt="perfil"class="menu-icon"> Perfil </a>
                    <a href="#">    <img src="https://img.icons8.com/?size=100&id=Y4iiHf14d1s-&format=png&color=000000" 
         alt="categorias" 
         class="menu-icon">Categorias</a>
                    <a href="seguidos.php"><img src="https://img.icons8.com/?size=100&id=779&format=png&color=000000" 
         alt="seguidos" 
         class="menu-icon"> Seguidos</a>
                    <a href="login_estrutura.php"><img src="https://img.icons8.com/?size=100&id=22112&format=png&color=000000" 
         alt="sair" 
         class="menu-icon"> Sair</a>
                </nav>
            </div>
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- RESULTADOS DA PESQUISA -->
    <?php if (!empty($termo)): ?>
        <div class="content">
            <div class="results-wrapper">

                <!-- Usuários -->
                <div class="result-section">
                    <h2>Usuários encontrados (<?= $resultado['totalUsuarios'] ?>)</h2>
                    <div class="user-list">
                        <?php if (count($resultado['usuarios']) === 0): ?>
                            <p class="no-results">Nenhum usuário encontrado.</p>
                        <?php else: ?>
                            <?php foreach ($resultado['usuarios'] as $index => $user): ?>
                                <?php 
                                    $jaSegue = $seguidor->verificaSeguindo($idLogado, $user['id_usuario']); 
                                    $textoBotao = $jaSegue ? "Seguindo" : "Seguir";
                                    $classeExtra = $jaSegue ? "seguindo" : "";
                                ?>
                                <div class="user-card" style="<?= $index >= 5 ? 'display:none;' : '' ?>">
                                    <span><?= htmlspecialchars($user['nome_usuario']) ?></span>
                                    <button 
                                        class="follow-btn <?= $classeExtra ?>" 
                                        data-id="<?= $user['id_usuario'] ?>" 
                                        data-tipo="usuario"
                                    >
                                        <?= $textoBotao ?>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <?php if(count($resultado['usuarios']) > 5): ?>
                        <button class="load-more-btn" data-target="user-list">Ver mais usuários</button>
                    <?php endif; ?>
                </div>

                <!-- Comunidades -->
                <div class="result-section">
                    <h2>Comunidades encontradas (<?= $resultado['totalComunidades'] ?>)</h2>
                    <div class="community-list">
                        <?php if (count($resultado['comunidades']) === 0): ?>
                            <p class="no-results">Nenhuma comunidade encontrada.</p>
                        <?php else: ?>
                            <?php foreach ($resultado['comunidades'] as $index => $com): ?>
                                <div class="community-card" style="<?= $index >= 5 ? 'display:none;' : '' ?>">
                                    <span><?= htmlspecialchars($com['nome_comunidade']) ?></span>
                                    <button 
                                        class="follow-btn" 
                                        data-id="<?= $com['id_comunidade'] ?>" 
                                        data-tipo="comunidade"
                                    >
                                        Seguir
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <?php if(count($resultado['comunidades']) > 5): ?>
                        <button class="load-more-btn" data-target="community-list">Ver mais comunidades</button>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    <?php endif; ?>

    <!-- Scripts -->
    <script src="../js/principal.js"></script>
    <script src="../js/seguir.js"></script>

</body>
</html>
