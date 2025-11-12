<?php
session_start();

require_once __DIR__ . '/gateway.php';
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/pesquisa_funcao.php';
require_once __DIR__ . '/Seguidor.php';

// Se o usuário for admin, redireciona
if ($_SESSION['tipo_usuario'] === 'admin') {
    header("Location: pagina_principal_adm.php");
    exit();
}

$seguidor = new Seguidor();
$idLogado = $_SESSION['id_usuario'] ?? 0;

$termo = $_GET['q'] ?? '';
$paginaUsuarios = intval($_GET['page_usuario'] ?? 1);
$paginaComunidades = intval($_GET['page_comunidade'] ?? 1);
$resultado = [];

if (!empty($termo)) {
    $resultado = executarPesquisa($termo, $paginaUsuarios, $paginaComunidades);
}

function criarLinkPagina($paginaAtual, $totalItens, $itensPorPagina, $paramPagina, $termo) {
    $totalPaginas = ceil($totalItens / $itensPorPagina);
    if ($paginaAtual < $totalPaginas) {
        $proximaPagina = $paginaAtual + 1;
        $queryParams = $_GET;
        $queryParams[$paramPagina] = $proximaPagina;
        $url = 'pagina_principal.php?' . http_build_query($queryParams);
        return '<a class="load-more-btn" href="' . htmlspecialchars($url) . '">Ver mais</a>';
    }
    return '';
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkpoint</title>
    <link rel="stylesheet" href="../css/pagina_principal.css">
    <link rel="stylesheet" href="../css/pesquisa.css">
    <link rel="stylesheet" href="../css/dropdown.css">
</head>
<body>

<aside class="sidebar">
    <div class="menu-icons">
        <div class="icon"></div><div class="icon"></div><div class="icon"></div><div class="icon"></div>
    </div>
    <div class="add-icon">+</div>
</aside>

<div class="top-bar">
    <div class="logo"><img src="../img/logo.png" alt="Checkpoint Logo"></div>
    <button class="btn-post">Criar Post</button>

    <div class="search-container">
        <form method="GET" action="" class="search-form">
            <input type="text" name="q" placeholder="Pesquisar usuários ou comunidades..." value="<?= htmlspecialchars($termo) ?>">
            <button class="search-btn" type="submit">🔍</button>
        </form>
    </div>

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
                <a href="perfil.php">Perfil</a>
                <a href="#">Categorias</a>
                <a href="seguidos.php">Seguidos</a>
                <a href="login_estrutura.php">Sair</a>
            </nav>
        </div>
    </div>
</div>

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
                    <?php 
                        $jaSegue = $seguidor->verificaSeguindo($idLogado, $user['id_usuario']); 
                        $textoBotao = $jaSegue ? "Seguindo" : "Seguir";
                        $classeExtra = $jaSegue ? "seguindo" : "";
                    ?>
                    <div class="user-card">
                        <span><?= htmlspecialchars($user['nome_usuario']) ?></span>
                        <button class="follow-btn <?= $classeExtra ?>" data-id="<?= $user['id_usuario'] ?>" data-tipo="usuario">
                            <?= $textoBotao ?>
                        </button>
                    </div>
                <?php endforeach; ?>
                <div class="pagination">
                    <?= criarLinkPagina($paginaUsuarios, $resultado['totalUsuarios'], 10, 'page_usuario', $termo); ?>
                </div>
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
                        <button class="follow-btn" data-id="<?= $com['id_comunidade'] ?>" data-tipo="comunidade">Seguir</button>
                    </div>
                <?php endforeach; ?>
                <div class="pagination">
                    <?= criarLinkPagina($paginaComunidades, $resultado['totalComunidades'], 10, 'page_comunidade', $termo); ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>
<?php endif; ?>

<script src="../js/principal.js"></script>
<script src="../js/seguir.js"></script>
</body>
</html>
