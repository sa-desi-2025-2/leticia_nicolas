<?php
session_start();
require_once __DIR__ . '/conexao.php';

// 🔹 Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$conexao = new Conexao();
$conn = $conexao->getCon();

// 🔹 Busca todos os usuários que o atual segue
$id_usuario = $_SESSION['id_usuario'];
$sql = "
    SELECT u.id_usuario, u.nome_usuario, u.foto_perfil
    FROM seguidores s
    JOIN usuarios u ON s.id_seguindo = u.id_usuario
    WHERE s.id_seguidor = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario); // "i" = inteiro
$stmt->execute();
$result = $stmt->get_result();
$seguidos = $result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguidos - Checkpoint</title>

    <link rel="stylesheet" href="../css/usuarios.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<!-- TOPO -->
<div class="topo">
    <div class="logo">
        <img src="../img/logo.png" alt="Checkpoint Logo">

        <!-- 🔙 Voltar à página principal -->
        <a href="pagina_principal.php" title="Voltar à página inicial">
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
    <h2 class="titulo">Usuários Seguidos</h2>

    <?php if (empty($seguidos)): ?>
        <p class="nenhum">Você ainda não segue ninguém 😔</p>
    <?php else: ?>
        <div class="user-list">
            <?php foreach ($seguidos as $user): ?>
                <div class="user-card">
                    <div class="user-info">
                        <!-- ✅ Foto de perfil -->
                        <img 
                            src="<?= !empty($user['foto_perfil']) ? htmlspecialchars($user['foto_perfil']) : '../uploads/default.png' ?>" 
                            alt="Foto de <?= htmlspecialchars($user['nome_usuario']) ?>" 
                            class="foto-usuario"
                        >
                        <p class="user-name"><?= htmlspecialchars($user['nome_usuario']) ?></p>
                    </div>

                    <!-- 🔹 Botão de deixar de seguir -->
                    <button 
                        class="follow-btn ativo" 
                        data-id="<?= $user['id_usuario'] ?>" 
                        data-tipo="usuario"
                    >
                        ✅ Seguindo
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<script src="../js/seguir.js"></script>
</body>
</html>
