<?php
session_start();
require_once 'Pesquisa_Classe.php';
require_once 'conexao.php';

$conexao = new Conexao();
$conn = $conexao->getCon();

$pesquisa = new Pesquisa($conn);

$termo = trim($_GET['q'] ?? '');
$paginaUsuarios = max(1, intval($_GET['page_usuario'] ?? 1));
$paginaComunidades = max(1, intval($_GET['page_comunidade'] ?? 1));

$itensPorPagina = 5;
$pesquisa->setItensPorPagina($itensPorPagina);

$usuarios = [];
$comunidades = [];
$totalUsuarios = 0;
$totalComunidades = 0;

if ($termo !== '') {
    $usuarios = $pesquisa->buscarUsuarios($termo, $paginaUsuarios);
    $totalUsuarios = $pesquisa->totalUsuarios();

    $comunidades = $pesquisa->buscarComunidades($termo, $paginaComunidades);
    $totalComunidades = $pesquisa->totalComunidades();
}

function criarLinkPagina($paginaAtual, $totalItens, $itensPorPagina, $paramPagina, $termo) {
    $totalPaginas = ceil($totalItens / $itensPorPagina);
    $links = '';

    if ($totalPaginas <= 1){
        return $links;
    }

    for ($i = 1; $i <= $totalPaginas; $i++) {
        $active = ($i == $paginaAtual) ? ' active' : '';
        $queryParams = [
            'q' => urlencode($termo),
            $paramPagina => $i
        ];
        if ($paramPagina == 'page_usuario' && isset($_GET['page_comunidade'])) {
            $queryParams['page_comunidade'] = intval($_GET['page_comunidade']);
        } elseif ($paramPagina == 'page_comunidade' && isset($_GET['page_usuario'])) {
            $queryParams['page_usuario'] = intval($_GET['page_usuario']);
        }

        $url = 'pesquisa.php?' . http_build_query($queryParams);
        $links .= '<a class="pagina-link' . $active . '" href="' . $url . '">' . $i . '</a> ';
    }
    return $links;
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

<!-- Sidebar -->
<aside class="sidebar">
    <div class="menu-icons">
        <div class="icon"></div>
        <div class="icon"></div>
        <div class="icon"></div>
        <div class="icon"></div>
    </div>
    <div class="add-icon" title="Adicionar">+</div>
</aside>

<!-- Top bar substituindo o header -->
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

<!-- Conteúdo principal -->
<div class="content">
    <?php if ($termo === ''): ?>
        <p style="text-align:center; font-size:1.2rem; color:#ccc;">Digite um termo para pesquisar usuários ou comunidades.</p>
    <?php else: ?>
        <div class="results-wrapper">
            <!-- Usuários -->
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

            <!-- Comunidades -->
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
    <?php endif; ?>
</div>

<script src="../js/principal.js"></script>
</body>
</html>
