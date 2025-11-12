<?php
session_start();
require_once 'gateway.php';
require_once 'Pesquisa_Classe.php';
require_once 'conexao.php';

$conexao = new Conexao();
$conn = $conexao->getCon();
$pesquisa = new Pesquisa($conn);

$termo = trim($_GET['q'] ?? '');
$paginaUsuarios = max(1, intval($_GET['page_usuario'] ?? 1));
$paginaComunidades = max(1, intval($_GET['page_comunidade'] ?? 1));

$itensPorPagina = 10;
$pesquisa->setItensPorPagina($itensPorPagina);

$usuarios = [];
$comunidades = [];
$totalUsuarios = 0;
$totalComunidades = 0;

if ($termo !== '') {
    $usuarios = $pesquisa->buscarUsuarios($termo, $paginaUsuarios);
    $totalUsuarios = $pesquisa->totalUsuarios($termo);

    $comunidades = $pesquisa->buscarComunidades($termo, $paginaComunidades);
    $totalComunidades = $pesquisa->totalComunidades($termo);
}

function criarLinkPagina($paginaAtual, $totalItens, $itensPorPagina, $paramPagina, $termo) {
    $totalPaginas = ceil($totalItens / $itensPorPagina);
    if ($paginaAtual < $totalPaginas) {
        $proximaPagina = $paginaAtual + 1;
        $queryParams = $_GET;
        $queryParams[$paramPagina] = $proximaPagina;
        $url = 'pesquisa.php?' . http_build_query($queryParams);
        return '<a class="ver-mais-btn" href="' . htmlspecialchars($url) . '">Ver mais</a>';
    }
    return '';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Pesquisa - Checkpoint</title>
<link rel="stylesheet" href="../css/pagina_principal.css" />
<link rel="stylesheet" href="../css/pesquisa.css" />
</head>
<body>
<aside class="sidebar">
    <div class="menu-icons">
        <div class="icon"></div>
        <div class="icon"></div>
        <div class="icon"></div>
        <div class="icon"></div>
    </div>
    <div class="add-icon" title="Adicionar">+</div>
</aside>

<div class="top-bar">
    <div class="logo">
        <img src="../img/logo.png" alt="Checkpoint Logo" />
    </div>

    <div class="search-container">
        <form method="GET" action="pesquisa.php" style="display:flex; align-items:center;">
            <input 
                type="text" 
                name="q" 
                placeholder="Digite um termo para pesquisar..." 
                autocomplete="off" 
                value="<?= htmlspecialchars($termo) ?>" 
            />
            <button type="submit" class="search-btn" title="Pesquisar">🔍</button>
        </form>
    </div>
</div>

<div class="content">
    <div class="results-wrapper">
        <div class="result-section">
            <h2>Usuários encontrados (<?= $totalUsuarios ?>)</h2>
            <?php if (count($usuarios) === 0): ?>
                <p class="no-results">Nenhum usuário encontrado.</p>
            <?php else: ?>
                <?php foreach ($usuarios as $user): ?>
                    <div class="user-card"><?= htmlspecialchars($user['nome_usuario']) ?></div>
                <?php endforeach; ?>
                <div class="pagination">
                    <?= criarLinkPagina($paginaUsuarios, $totalUsuarios, $itensPorPagina, 'page_usuario', $termo); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="result-section">
            <h2>Comunidades encontradas (<?= $totalComunidades ?>)</h2>
            <?php if (count($comunidades) === 0): ?>
                <p class="no-results">Nenhuma comunidade encontrada.</p>
            <?php else: ?>
                <?php foreach ($comunidades as $com): ?>
                    <div class="community-card"><?= htmlspecialchars($com['nome_comunidade']) ?></div>
                <?php endforeach; ?>
                <div class="pagination">
                    <?= criarLinkPagina($paginaComunidades, $totalComunidades, $itensPorPagina, 'page_comunidade', $termo); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script src="../js/principal.js"></script>
</body>
</html>
