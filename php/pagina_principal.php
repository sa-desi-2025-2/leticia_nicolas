<?php
session_start();

require_once __DIR__ . '/gateway.php';
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/pesquisa_funcao.php';
require_once __DIR__ . '/Seguidor.php';

if ($_SESSION['tipo_usuario'] === 'admin') {
    header("Location: pagina_principal_adm.php");
    exit();
}

$seguidor = new Seguidor();
$idLogado = $_SESSION['id_usuario'] ?? 0;

$conexao = new Conexao();
$conn = $conexao->getCon();

// === COMUNIDADES DO USUÁRIO ===
$comunidadesUsuario = [];
$stmtComunidades = $conn->prepare("
    SELECT c.id_comunidade, c.nome_comunidade, c.imagem_comunidade
    FROM usuarios_comunidades uc
    JOIN comunidades c ON uc.id_comunidade = c.id_comunidade
    WHERE uc.id_usuario = ?
");
$stmtComunidades->bind_param("i", $idLogado);
$stmtComunidades->execute();
$resultComunidades = $stmtComunidades->get_result();

while ($row = $resultComunidades->fetch_assoc()) {
    $comunidadesUsuario[] = $row;
}
$stmtComunidades->close();

// === CATEGORIAS ===
$stmtCheck = $conn->prepare("SELECT COUNT(*) AS total FROM usuarios_categorias WHERE id_usuario = ?");
$stmtCheck->bind_param("i", $idLogado);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result()->fetch_assoc();
$temCategorias = ($resultCheck['total'] > 0);
$stmtCheck->close();

// Todas as categorias
$categorias = [];
$stmtCategorias = $conn->prepare("SELECT id_categoria, nome_categoria FROM categorias");
$stmtCategorias->execute();
$resultCategorias = $stmtCategorias->get_result();
while ($cat = $resultCategorias->fetch_assoc()) {
    $categorias[] = $cat;
}
$stmtCategorias->close();

// Categorias já salvas pelo usuário
$categoriasSelecionadas = [];
$stmtUserCats = $conn->prepare("SELECT id_categoria FROM usuarios_categorias WHERE id_usuario = ?");
$stmtUserCats->bind_param("i", $idLogado);
$stmtUserCats->execute();
$resUserCats = $stmtUserCats->get_result();
while ($row = $resUserCats->fetch_assoc()) {
    $categoriasSelecionadas[] = $row['id_categoria'];
}
$stmtUserCats->close();

// === PESQUISA ===
$termo = $_GET['q'] ?? '';
$paginaUsuarios = intval($_GET['page_usuario'] ?? 1);
$paginaComunidades = intval($_GET['page_comunidade'] ?? 1);
$resultado = [];

if (!empty($termo)) {
    $resultado = executarPesquisa($termo, $paginaUsuarios, $paginaComunidades);
}

function criarLinkPagina($paginaAtual, $totalItens, $itensPorPagina, $paramPagina, $termo)
{
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
    <link rel="stylesheet" href="../css/sidebar_comunidades.css">
</head>
<body>

<!-- === SIDEBAR === -->
<aside class="sidebar">
    <div class="menu-icons">
        <?php if (count($comunidadesUsuario) > 0): ?>
            <?php foreach ($comunidadesUsuario as $com): ?>
                <a href="comunidade.php?id=<?= $com['id_comunidade'] ?>" class="community-icon">
                    <img src="<?= !empty($com['imagem_comunidade']) ? '../uploads/' . htmlspecialchars($com['imagem_comunidade']) : '../img/default_comunidade.png' ?>" alt="<?= htmlspecialchars($com['nome_comunidade']) ?>">
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="add-icon">+</div>
</aside>

<!-- === TOPO === -->
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
                <a href="#" id="abrirCategorias">Categorias</a>
                <a href="seguidos.php">Seguidos</a>
                <a href="login_estrutura.php">Sair</a>
            </nav>
        </div>
    </div>
</div>

<!-- === RESULTADOS === -->
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
                        <button class="follow-btn <?= $classeExtra ?>" data-id="<?= $user['id_usuario'] ?>" data-tipo="usuario"><?= $textoBotao ?></button>
                    </div>
                <?php endforeach; ?>
                <div class="pagination"><?= criarLinkPagina($paginaUsuarios, $resultado['totalUsuarios'], 10, 'page_usuario', $termo); ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- === MODAL CATEGORIAS === -->
<div class="modal-overlay" id="modalCategorias" style="display: <?= $temCategorias ? 'none' : 'flex' ?>;">
    <div class="modal-categorias">
        <h2>Escolha suas categorias favoritas</h2>
        <div class="lista-categorias">
            <?php foreach ($categorias as $cat): ?>
                <label>
                    <input type="checkbox" class="checkbox-categoria" value="<?= $cat['id_categoria'] ?>"
                        <?= in_array($cat['id_categoria'], $categoriasSelecionadas) ? 'checked' : '' ?>>
                    <?= htmlspecialchars($cat['nome_categoria']) ?>
                </label><br>
            <?php endforeach; ?>
        </div>
        <div class="modal-botoes">
            <button id="salvarCategorias">Salvar</button>
            <button id="fecharModal">Fechar</button>
        </div>
    </div>
</div>

<script src="../js/principal.js"></script>
<script src="../js/seguir.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("modalCategorias");
    const btnFechar = document.getElementById("fecharModal");
    const btnSalvar = document.getElementById("salvarCategorias");
    const btnAbrirCategorias = document.getElementById("abrirCategorias");

    // Abre modal (forçado ao iniciar, se não tiver categorias)
    <?php if (!$temCategorias): ?> modal.style.display = "flex"; <?php endif; ?>

    // Abre manualmente via menu
    btnAbrirCategorias.addEventListener("click", (e) => {
        e.preventDefault();
        modal.style.display = "flex";
    });

    btnFechar.addEventListener("click", () => modal.style.display = "none");

    btnSalvar.addEventListener("click", () => {
        const selecionadas = Array.from(document.querySelectorAll(".checkbox-categoria:checked")).map(c => c.value);
        if (selecionadas.length === 0) {
            alert("Selecione pelo menos uma categoria.");
            return;
        }

        fetch("salvar_categorias.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "categorias[]=" + selecionadas.join("&categorias[]=")
        })
        .then(res => res.json())
        .then(data => {
            if (data.sucesso) {
                alert("Categorias salvas com sucesso!");
                modal.style.display = "none";
                location.reload();
            } else {
                alert(data.mensagem || "Erro ao salvar categorias.");
            }
        })
        .catch(() => alert("Erro de comunicação com o servidor."));
    });
});
</script>
</body>
</html>
