<?php
if (session_status() === PHP_SESSION_NONE) session_start();

/* ================================
   1) Gateway – valida login e tipo
   ================================ */
require_once __DIR__ . '/gateway.php';

/* ==================================
   2) Conexão – agora pode usar $con
   ==================================*/
require_once __DIR__ . '/conexao.php';
$db = new Conexao();
$con = $db->getCon();

$idLogado = $_SESSION['id_usuario'] ?? 0;

/* ===============================
   3) Carregar TODAS as categorias
   ===============================*/
$stmt = $con->prepare("SELECT id_categoria, nome_categoria FROM categorias ORDER BY nome_categoria ASC");
$stmt->execute();
$categorias = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

/* ==========================================
   4) Categorias QUE o admin já selecionou
   ========================================== */
$stmt = $con->prepare("SELECT id_categoria FROM usuarios_categorias WHERE id_usuario = ?");
$stmt->bind_param("i", $idLogado);
$stmt->execute();
$res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$categoriasSelecionadas = array_column($res, 'id_categoria');
$temCategorias = count($categoriasSelecionadas) > 0;

/* ============================
   5) Impedir acesso de usuário
   ============================ */
if ($_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: pagina_principal.php");
    exit();
}

/* ==================================
   6) Classes adicionais da página
   ================================== */
require_once __DIR__ . '/pesquisa_funcao.php';
require_once __DIR__ . '/Seguidor.php';

$seguidor = new Seguidor();

/* ======================
   7) Sistema de pesquisa
   ====================== */
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
        $url = 'pagina_principal_adm.php?' . http_build_query($queryParams);
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
<title>Checkpoint - Administração</title>

<link rel="stylesheet" href="../css/pagina_principal.css">
<link rel="stylesheet" href="../css/pesquisa.css">
<link rel="stylesheet" href="../css/dropdown.css">
<link rel="stylesheet" href="../css/sidebar_comunidades.css">

<!-- BOOTSTRAP (CDN) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Pequeno CSS do modal (mantive parecido com versão usuário para consistência) -->
<style>
.modal-overlay, #modalCategorias { display: none; }
.modal-categorias { background: #fff; padding: 25px; border-radius: 12px; width: 420px; max-height: 85vh; overflow-y: auto; box-sizing: border-box; }
.modal-botoes { margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px; }
#salvarCategorias, #fecharModal { padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; }
#salvarCategorias { background: #007bff; color: white; }
#fecharModal { background: #777; color: white; }
</style>
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

  
    <button class="btn-post" data-bs-toggle="modal" data-bs-target="#criarPostModal">Criar Post</button>

    <div class="search-container">
        <form method="GET" action="">
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
                <a href="perfil.php"><img src="https://img.icons8.com/?size=100&id=82751&format=png&color=000000" class="menu-icon" alt="Perfil">Perfil</a>
           
                <a href="#" id="abrirCategorias"><img src="https://img.icons8.com/?size=100&id=99515&format=png&color=000000" class="menu-icon" alt="Categorias">Categorias</a>
                <a href="pagina_principal_contas.php"><img src="https://img.icons8.com/?size=100&id=82535&format=png&color=000000" class="menu-icon" alt="Contas">Contas</a>
                <a href="seguidos.php"><img src="https://img.icons8.com/?size=100&id=85445&format=png&color=000000" class="menu-icon" alt="Seguidos">Seguidos</a>
                <a href="login_estrutura.php"><img src="https://img.icons8.com/?size=100&id=82792&format=png&color=000000" class="menu-icon" alt="Sair">Sair</a>
            </nav>
        </div>
    </div>
</div>


<div class="content" style="margin-top:20px;">
  <div id="postsContainer" class="results-wrapper" data-user-id="<?= $idLogado ?>">

  </div>
</div>

<?php if (!empty($termo)): ?>
<div class="content">
    <div class="results-wrapper">

        <div class="result-section">
            <h2>Usuários encontrados (<?= $resultado['totalUsuarios'] ?? 0 ?>)</h2>
            <?php if (empty($resultado['usuarios'])): ?>
                <p class="no-results">Nenhum usuário encontrado.</p>
            <?php else: ?>
                <?php foreach ($resultado['usuarios'] as $user): ?>
                    <?php 
                        $jaSegue = $seguidor->verificaSeguindo($idLogado, $user['id_usuario']); 
                        $textoBotao = $jaSegue ? "Seguindo" : "Seguir";
                        $classeExtra = $jaSegue ? "seguindo" : "";
                    ?>
                    <div class="user-card">
                        <div class="user-info">
                            <img class="foto-mini" src="<?= !empty($user['foto_perfil']) ? htmlspecialchars($user['foto_perfil']) : '../uploads/default.png' ?>" alt="Foto de <?= htmlspecialchars($user['nome_usuario']) ?>">
                            <a class="nome-link" href="perfil_usuario.php?id=<?= $user['id_usuario'] ?>"><?= htmlspecialchars($user['nome_usuario']) ?></a>
                        </div>
                        <button class="follow-btn <?= $classeExtra ?>" data-id="<?= $user['id_usuario'] ?>" data-tipo="usuario"><?= $textoBotao ?></button>
                    </div>
                <?php endforeach; ?>
                <div class="pagination">
                    <?= criarLinkPagina($paginaUsuarios, $resultado['totalUsuarios'], 10, 'page_usuario', $termo); ?>
                </div>
            <?php endif; ?>
        </div>


        <div class="result-section">
            <h2>Comunidades encontradas (<?= $resultado['totalComunidades'] ?? 0 ?>)</h2>
            <?php if (empty($resultado['comunidades'])): ?>
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


<?php if ($idLogado > 0): ?>
<div id="modalCategorias" class="modal-overlay" style="display: <?= $temCategorias ? 'none' : 'flex' ?>;">
    <div class="modal-categorias" role="dialog" aria-modal="true">
        <h2>Escolha suas categorias favoritas</h2>
        <form id="formCategorias">
            <div class="lista-categorias">
                <?php foreach ($categorias as $cat): ?>
                    <label class="categoria-item">
                        <input class="checkbox-categoria" type="checkbox" name="categorias[]" value="<?= (int)$cat['id_categoria'] ?>" <?= in_array($cat['id_categoria'], $categoriasSelecionadas) ? 'checked' : '' ?>>
                        <?= htmlspecialchars($cat['nome_categoria']) ?>
                    </label><br>
                <?php endforeach; ?>
            </div>

            <div class="modal-botoes">
                <button type="button" id="salvarCategorias">Salvar</button>
                <button type="button" id="fecharModal">Fechar</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>


<div class="modal fade" id="criarPostModal" tabindex="-1" aria-labelledby="criarPostModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content text-dark">
      <div class="modal-header">
        <h5 class="modal-title" id="criarPostModalLabel">Criar nova postagem</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <form id="formCriarPost" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="mb-3">
            <label for="textoPost" class="form-label">Texto</label>
            <textarea id="textoPost" name="texto_postagem" class="form-control" rows="4" maxlength="255" required></textarea>
          </div>

          <div class="mb-3">
            <label for="categoriaPost" class="form-label">Categoria</label>
            <select id="categoriaPost" name="id_categoria" class="form-select" required>
              <option value="">Selecione uma categoria</option>
              <?php foreach ($categorias as $cat): ?>
                <option value="<?= (int)$cat['id_categoria'] ?>"><?= htmlspecialchars($cat['nome_categoria']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
            
          <div class="mb-3">
            <label for="imagemPost" class="form-label">Imagem (opcional) — JPG/PNG/GIF até 5MB</label>
            <input class="form-control" type="file" id="imagemPost" name="imagem_postagem" accept="image/*">
            <div id="previewWrapper" class="mt-2" style="display:none;">
              <p class="mb-1">Pré-visualização:</p>
              <img id="previewImage" src="#" alt="preview" style="max-width:100%; border-radius:8px;"/>
            </div>
          </div>
            
        </div>
        <div class="modal-footer">
          <div id="postFeedback" class="me-auto text-success" style="display:none;"></div>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Publicar</button>
        </div>
      </form>
    </div>
  </div>
</div>


<div id="overlayCriarPost" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:-1; pointer-events:none;"></div>


<script src="../js/principal.js"></script>
<script src="../js/seguir.js"></script>


<script>
    const mostrarModalCategorias = <?= $temCategorias ? 'false' : 'true' ?>;
</script>

<
<script src="../js/modal_categoria.js"></script>
<script src="../js/posts.js"></script>

</body>
</html>
