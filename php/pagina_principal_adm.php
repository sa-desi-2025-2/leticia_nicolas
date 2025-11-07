<?php
session_start();
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/pesquisa_funcao.php'; // 🔍 lógica da pesquisa

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
    <title>Checkpoint - Administração</title>
    <link rel="stylesheet" href="../css/pagina_principal.css">
    <link rel="stylesheet" href="../css/pesquisa.css">
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
            <form method="GET" action="">
                <input 
                    type="text" 
                    name="q" 
                    placeholder="Pesquisar usuários ou comunidades..." 
                    value="<?= htmlspecialchars($termo) ?>"
                >
                <button class="search-btn" type="submit">🔍</button>
            </form>
        </div>

        <!-- ✅ MENU DO USUÁRIO -->
        <div class="user-menu">
            <div class="user-icon" id="userButton">
                <img src="<?php echo $_SESSION['foto_perfil'] ?? '../uploads/default.png'; ?>" alt="Usuário">
            </div>
            <div class="dropdown" id="dropdownMenu">
                <a href="perfil.php">Perfil</a>
                <a href="">Categorias</a>
                <a href="pagina_principal_contas.php">Contas</a> <!-- Link adicional do ADM -->
                <a href="seguidos.php">Seguidos</a>
                <a href="login_estrutura.php">Sair</a>
            </div>
        </div>
    </div>

    <!-- RESULTADOS DA PESQUISA -->
    <?php if (!empty($termo)): ?>
        <div class="content">
            <div class="results-wrapper">
                <!-- Usuários -->
                <div class="result-section">
                    <h2>Usuários encontrados (<?= $resultado['totalUsuarios'] ?>)</h2>
                    <?php if (count($resultado['usuarios']) === 0): ?>
                        <p class="no-results">Nenhum usuário encontrado.</p>
                    <?php else: ?>
                        <?php foreach ($resultado['usuarios'] as $user): ?>
                            <div class="user-card">
                                <span><?= htmlspecialchars($user['nome_usuario']) ?></span>
                                <button 
                                    class="follow-btn" 
                                    data-id="<?= $user['id_usuario'] ?>" 
                                    data-tipo="usuario"
                                >
                                    Seguir
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Comunidades -->
                <div class="result-section">
                    <h2>Comunidades encontradas (<?= $resultado['totalComunidades'] ?>)</h2>
                    <?php if (count($resultado['comunidades']) === 0): ?>
                        <p class="no-results">Nenhuma comunidade encontrada.</p>
                    <?php else: ?>
                        <?php foreach ($resultado['comunidades'] as $com): ?>
                            <div class="community-card">
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
            </div>
        </div>
    <?php endif; ?>

    <!-- Scripts -->
    <script src="../js/principal.js"></script>
    <script src="../js/seguir.js"></script>
</body>
</html>
