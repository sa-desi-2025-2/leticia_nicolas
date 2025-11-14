<?php
session_start();
require_once __DIR__ . '/usuario.php';
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/Seguidor.php';

$usuario = new Usuario();

// Verifica login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['id_usuario'];

// Busca dados do usuário
$dados = $usuario->buscarPorId($id);

// Pega dados do banco ou usa padrão
$fotoPerfil = $dados['foto_perfil'] ?? '../uploads/default.png';
$fotoBanner = $dados['imagem_banner'] ?? '../uploads/default_banner.png';
$bio        = $dados['bio'] ?? '';
$nome       = $dados['nome_usuario'] ?? '';
$email      = $dados['email_usuario'] ?? '';

$homeLink = ($_SESSION['tipo_usuario'] === 'admin')
    ? 'pagina_principal_adm.php'
    : 'pagina_principal.php';

// Controla qual aba abrir
$abaAtiva = $_GET['aba'] ?? 'meu_perfil';

// === CONTADORES ===
$conexao = new Conexao();
$conn = $conexao->getCon();
$seguidor = new Seguidor();

$stmtSeguindo = $conn->prepare("SELECT COUNT(*) AS seguindo FROM seguidores WHERE id_seguidor = ?");
$stmtSeguindo->bind_param("i", $id);
$stmtSeguindo->execute();
$seguindo = $stmtSeguindo->get_result()->fetch_assoc()['seguindo'] ?? 0;
$stmtSeguindo->close();

$stmtSeguidores = $conn->prepare("SELECT COUNT(*) AS seguidores FROM seguidores WHERE id_seguindo = ?");
$stmtSeguidores->bind_param("i", $id);
$stmtSeguidores->execute();
$seguidores = $stmtSeguidores->get_result()->fetch_assoc()['seguidores'] ?? 0;
$stmtSeguidores->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkpoint - Perfil do Usuário</title>
    <link rel="stylesheet" href="../css/perfil.css">

    <link rel="stylesheet" href="../css/dropdown.css">

    <link rel="stylesheet" href="../css/sidebar_perfil.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

    <!-- TOPO -->
    <div class="topo">
        <div class="logo">
            <img src="../img/logo.png" alt="Checkpoint Logo">
        </div>

        <!-- MENU DO USUÁRIO -->
        <div class="user-menu">
            <div class="user-icon" id="userButton">
                <img src="<?php echo htmlspecialchars($fotoPerfil); ?>" alt="Usuário">
            </div>

            <div class="dropdown-side" id="dropdownMenu">
                <div class="profile-section">
                    <img src="<?php echo htmlspecialchars($fotoPerfil); ?>" alt="Usuário">
                    <h3><?= htmlspecialchars($nome) ?></h3>
                </div>
                <nav class="menu-links">
                    <a href="<?= $homeLink ?>">
                        <img src="https://img.icons8.com/?size=100&id=14096&format=png&color=000000" alt="home" class="menu-icon">
                        Home
                    </a>
                </nav>
            </div>
        </div>
    </div>

    <!-- MENU LATERAL -->
    <aside class="sidebar">
        <div class="menu-icons">
            <a href="#" class="icon tab-link <?= ($abaAtiva === 'meu_perfil') ? 'active' : ''; ?>" data-tab="meu_perfil" title="Meu Perfil">
                <i class="bi bi-person-badge"></i>
            </a>
            <a href="#" class="icon tab-link <?= ($abaAtiva === 'configuracoes') ? 'active' : ''; ?>" data-tab="configuracoes" title="Configurações">
                <i class="bi bi-gear"></i>
            </a>
            <a href="#" class="icon tab-link <?= ($abaAtiva === 'conta') ? 'active' : ''; ?>" data-tab="conta" title="Conta">
                <i class="bi bi-person-circle"></i>
            </a>
        </div>
    </aside>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="main-conteudo">
        <div class="perfil-container">

            <!-- ABA MEU PERFIL -->
            <div id="meu_perfil" class="tab-content <?= ($abaAtiva === 'meu_perfil') ? 'active' : ''; ?>">
                <section class="perfil-visual">
                    <div class="banner" style="background-image: url('<?= !empty($fotoBanner) ? htmlspecialchars($fotoBanner) : '../img/banner_default.jpg' ?>');">
                    </div>

                    <div class="perfil-info">
                        <img class="foto-perfil" src="<?= htmlspecialchars($fotoPerfil); ?>" alt="Foto do usuário">
                        <h2><?= htmlspecialchars($nome) ?></h2>

                        <div class="contadores">
                            <span><strong><?= $seguindo ?></strong> Seguindo</span>
                            <span><strong><?= $seguidores ?></strong> Seguidores</span>
                        </div>

                        <p class="bio"><?= !empty($bio) ? htmlspecialchars($bio) : 'Sem bio.' ?></p>

                        <a href="?aba=conta" class="btn-editar-perfil">✏️ Editar Perfil</a>
                    </div>
                </section>
            </div>

            <!-- ABA CONFIGURAÇÕES -->
            <div id="configuracoes" class="tab-content <?= ($abaAtiva === 'configuracoes') ? 'active' : ''; ?>">
                <div class="perfil-box">
                    <div class="perfil-info">
                        <form action="atualizar_perfil.php" method="POST">
                            <input type="hidden" name="id_usuario" value="<?php echo $id; ?>">

                            <div class="linha-campos">
                                <div class="campo">
                                    <label for="nome">Nome</label>
                                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required>
                                </div>

                                <div class="campo">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                                </div>
                            </div>

                            <button type="submit" class="btn-alterar">Alterar Dados</button>
                        </form>

                        <form action="atualizar_senha.php" method="POST" class="alterar-senha">
                            <input type="hidden" name="id_usuario" value="<?php echo $id; ?>">

                            <label for="nova_senha">Nova Senha</label>
                            <input type="password" id="nova_senha" name="nova_senha" required>

                            <label for="confirmar_senha">Confirmar Nova Senha</label>
                            <input type="password" id="confirmar_senha" name="confirmar_senha" required>

                            <button type="submit" class="btn-alterar">Alterar Senha</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ABA CONTA -->
            <div id="conta" class="tab-content <?= ($abaAtiva === 'conta') ? 'active' : ''; ?>">
                <div class="conta-section">
                    <form action="atualizar_conta.php?aba=conta" method="POST" enctype="multipart/form-data" class="form-conta">
                        <input type="hidden" name="id_usuario" value="<?php echo $id; ?>">

                        <div class="banner-wrapper">
                            <img id="previewBanner" src="<?php echo htmlspecialchars($fotoBanner); ?>" alt="Banner do usuário">
                            <label for="foto_banner" class="banner-upload">Alterar Banner</label>
                            <input type="file" name="foto_banner" id="foto_banner" accept="image/*" style="display: none;">
                        </div>

                        <div class="foto-perfil-wrapper">
                            <img id="previewPerfil" src="<?php echo htmlspecialchars($fotoPerfil); ?>" alt="Foto de Perfil">
                        </div>
                        <label for="foto_perfil" class="foto-upload">Alterar Foto de Perfil</label>
                        <input type="file" name="foto_perfil" id="foto_perfil" accept="image/*" style="display: none;">

                        <div class="conta-info">
                     

                            <div class="campo-bio">
                                <label for="bio">Sobre (Bio):</label>
                                <textarea id="bio" name="bio" placeholder="Conte um pouco sobre você..."><?php echo htmlspecialchars($bio); ?></textarea>
                            </div>

                            <button type="submit" class="btn-salvar">Salvar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </main>

    <script src="../js/dropdown.js"></script>
    <script src="../js/abas.js" defer></script>
    <script src="../js/conta.js" defer></script>
</body>
</html>
