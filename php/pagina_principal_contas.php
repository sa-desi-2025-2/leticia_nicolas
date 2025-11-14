<?php
session_start();

require_once __DIR__ . '/gateway.php';
require_once __DIR__ . '/usuario.php';

// Bloqueia quem não for admin
if ($_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: pagina_principal.php");
    exit();
}

$usuario = new Usuario();
$usuarios = $usuario->listarUsuarios();

$usuario = new Usuario();
$usuarios = $usuario->listarUsuarios(); // busca todos os usuários
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkpoint - Gerenciamento de Usuários</title>

    <link rel="stylesheet" href="../css/usuarios.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<!-- TOPO -->
<a href="<?= $homeLink ?>">
<div class="topo">
    <div class="logo">
        <img src="../img/logo.png" alt="Checkpoint Logo">
    </div>
        <!-- 🔙 Voltar à página principal do ADM -->
        <a href="pagina_principal_adm.php" title="Voltar à página inicial">
            <img src="https://img.icons8.com/?size=100&id=14096&format=png&color=000000" alt="home" class="home-icon">
        </a>
    </div>

    <div class="user-menu">
        <div class="user-icon">
           
            <img src="<?php echo $_SESSION['foto_perfil'] ?? '../uploads/default.png'; ?>" alt="Usuário Logado">
        </div>
    </div>
</div>

<!-- CONTEÚDO PRINCIPAL -->
<main class="content">
    <h2 class="titulo">Gerenciamento de Usuários</h2>

    <div class="user-list">
        <?php foreach ($usuarios as $user): ?>
            <div class="user-card">
                <div class="user-info">
                    <!-- ✅ Foto de perfil do usuário -->
                    <img 
                        src="<?= !empty($user['foto_perfil']) ? htmlspecialchars($user['foto_perfil']) : '../uploads/default.png' ?>" 
                        alt="Foto de <?= htmlspecialchars($user['nome_usuario']) ?>" 
                        class="foto-usuario"
                    >
                    <p class="user-name"><?= htmlspecialchars($user['nome_usuario']) ?></p>
                </div>

                <!-- Botão de ativar/desativar -->
                <button 
                    id="btnUsuario<?= $user['id_usuario'] ?>" 
                    class="<?= $user['ativo'] == 1 ? 'btn-desativar' : 'btn-ativar' ?>" 
                    data-id="<?= $user['id_usuario'] ?>"
                >
                    <?= $user['ativo'] == 1 ? 'Desativar' : 'Ativar' ?>
                </button>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<script src="../js/adm.js"></script>
<script src="../js/principal.js"></script>
</body>
</html>
