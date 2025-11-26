<?php
session_start();

require_once __DIR__ . '/gateway.php';
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/Seguidor.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$idUsuario = $_SESSION['id_usuario'];


$db = new Conexao();
$conn = $db->getCon();

$sql = "
    SELECT c.id_comunidade, c.nome_comunidade, c.foto_comunidade
    FROM usuarios_comunidades uc
    JOIN comunidades c ON uc.id_comunidade = c.id_comunidade
    WHERE uc.id_usuario = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();
$comunidades = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comunidades Participadas - Checkpoint</title>
    <link rel="stylesheet" href="../css/usuarios.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<div class="topo">
    <div class="logo">
        <img src="../img/logo.png" alt="Checkpoint Logo">
        <a href="pagina_principal.php">
            <img src="https://img.icons8.com/?size=100&id=14096&format=png&color=000000" class="home-icon" alt="home">
        </a>
    </div>
    <div class="user-menu">
        <div class="user-icon">
            <img src="<?= $_SESSION['foto_perfil'] ?? '../uploads/default.png' ?>" alt="Usuário Logado">
        </div>
    </div>
</div>

<main class="content">
    <h2 class="titulo">Comunidades que você participa</h2>

    <?php if (empty($comunidades)): ?>
        <p class="nenhum">Você ainda não participa de nenhuma comunidade</p>
    <?php else: ?>
        <div class="user-list">
            <?php foreach ($comunidades as $com): ?>
                <div class="community-card">
                    <div class="user-info">
                        <img src="<?= !empty($com['foto_comunidade']) ? htmlspecialchars($com['foto_comunidade']) : '../uploads/default.png' ?>" 
                             class="foto-usuario" alt="Foto da comunidade <?= htmlspecialchars($com['nome_comunidade']) ?>">
                        <p class="user-name"><?= htmlspecialchars($com['nome_comunidade']) ?></p>
                    </div>

                    <button 
                        class="btn-desativar unfollow-btn"
                        data-id="<?= $com['id_comunidade'] ?>"
                        data-tipo="comunidade"
                    >
                        Sair
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<script src="../js/seguir.js"></script>
</body>
</html>
