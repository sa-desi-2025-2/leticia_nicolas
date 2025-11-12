<?php
session_start();
require_once __DIR__ . '/usuario.php';

$usuario = new Usuario();

// Verifica login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['id_usuario'];

// Busca dados
$dados = $usuario->buscarPorId($id);

$fotoPerfil = $dados['foto_perfil'] ?? '../uploads/default.png';
$fotoBanner = $dados['foto_banner'] ?? '../uploads/default_banner.png';
$bio = $dados['bio'] ?? '';
$nome = $dados['nome_usuario'] ?? '';
$email = $dados['email_usuario'] ?? '';

$homeLink = ($_SESSION['tipo_usuario'] === 'admin')
    ? 'pagina_principal_adm.php'
    : 'pagina_principal.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkpoint - Perfil do Usuário</title>
    <link rel="stylesheet" href="../css/perfil.css">
    <link rel="stylesheet" href="../css/dropdown.css">
    <link rel="stylesheet" href="../css/conta.css">
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
            <a href="#" class="icon tab-link active" data-tab="configuracoes">
                <i class="bi bi-gear"></i>
                <span></span>
            </a>
            <a href="#" class="icon tab-link" data-tab="conta">
                <i class="bi bi-person-circle"></i>
                <span></span>
            </a>
        </div>
    
    </aside>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="main-conteudo">
        <div class="perfil-container">

            <!-- ABA CONFIGURAÇÕES -->
            <div id="configuracoes" class="tab-content active">
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

            <!-- ABA CONTA (NOVO BLOCO INSERIDO) -->
            <div id="conta" class="tab-content">
                <div class="conta-section">

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
                        <h3>Editar Perfil</h3>

                        <form action="atualizar_conta.php" method="POST" enctype="multipart/form-data" class="form-conta">
    <input type="hidden" name="id_usuario" value="<?php echo $id; ?>">

    <div class="campo-bio">
        <label for="bio">Sobre (Bio):</label>
        <textarea id="bio" name="bio" placeholder="Conte um pouco sobre você..."><?php echo htmlspecialchars($bio); ?></textarea>
    </div>

    <button type="submit" class="btn-salvar">Salvar Alterações</button>
</form>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <script src="../js/dropdown.js"></script>
    <script src="../js/abas.js" defer></script>
    <script src="../js/conta.js" defer></script>
</body>
</html>
