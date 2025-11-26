<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/conexao.php";

if (!isset($_GET['id'])) {
    die("Comunidade não encontrada.");
}

$idComunidade = (int) $_GET['id'];

$conexao = new Conexao();
$conn = $conexao->getCon();

/* ============================
   BUSCAR DADOS DA COMUNIDADE
================================ */
$stmt = $conn->prepare("
    SELECT c.*, cat.nome_categoria
    FROM comunidades c
    LEFT JOIN categorias cat ON cat.id_categoria = c.id_categoria
    WHERE c.id_comunidade = ?
");
$stmt->bind_param("i", $idComunidade);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Comunidade não encontrada.");
}

$comunidade = $result->fetch_assoc();
$stmt->close();

/* ============================
   CONTAR MEMBROS
================================ */
$stmt = $conn->prepare("
    SELECT COUNT(*) AS total 
    FROM usuarios_comunidades 
    WHERE id_comunidade = ?
");
$stmt->bind_param("i", $idComunidade);
$stmt->execute();
$total_membros = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

/* ============================
   VERIFICAR SE O USUÁRIO PARTICIPA
================================ */
$usuarioParticipa = false;

if (isset($_SESSION['id_usuario'])) {
    $idUsuario = $_SESSION['id_usuario'];

    $stmt = $conn->prepare("
        SELECT 1 FROM usuarios_comunidades
        WHERE id_usuario = ? AND id_comunidade = ?
    ");
    $stmt->bind_param("ii", $idUsuario, $idComunidade);
    $stmt->execute();
    $usuarioParticipa = $stmt->get_result()->num_rows > 0;
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($comunidade['nome_comunidade']) ?></title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include "menu.php"; ?>

<div class="perfil-container">

    <div class="perfil-header">
        <img src="<?= !empty($comunidade['imagem_comunidade']) 
                    ? '../uploads/' . $comunidade['imagem_comunidade'] 
                    : '../img/default_comunidade.png' ?>" 
             class="foto-perfil-comunidade">

        <h2><?= htmlspecialchars($comunidade['nome_comunidade']) ?></h2>

        <p><?= htmlspecialchars($comunidade['descricao_comunidade']) ?></p>

        <span class="categoria">
            Categoria: <?= htmlspecialchars($comunidade['nome_categoria'] ?? "Sem categoria") ?>
        </span>

        <span class="membros"><?= $total_membros ?> membros</span>

        <?php if (isset($_SESSION['id_usuario'])): ?>
            <form action="participar_comunidade.php" method="POST">
                <input type="hidden" name="id_comunidade" value="<?= $idComunidade ?>">

                <?php if ($usuarioParticipa): ?>
                    <button class="sair-btn">Sair da comunidade</button>
                <?php else: ?>
                    <button class="entrar-btn">Participar</button>
                <?php endif; ?>
            </form>
        <?php endif; ?>
    </div>

    <hr>

    <div class="posts-comunidade">
        <h3>Postagens da comunidade</h3>

        <?php
        /* =====================================
           LISTAR POSTAGENS DA COMUNIDADE
        ====================================== */
        $stmt = $conn->prepare("
            SELECT p.*, u.nome_usuario, u.foto_perfil
            FROM postagens p
            JOIN usuarios u ON u.id_usuario = p.id_usuario
            WHERE p.id_comunidade = ?
            ORDER BY p.data_postagem DESC
        ");
        $stmt->bind_param("i", $idComunidade);
        $stmt->execute();
        $posts = $stmt->get_result();

        if ($posts->num_rows === 0) {
            echo "<p>Ainda não há postagens nesta comunidade.</p>";
        } else {
            while ($post = $posts->fetch_assoc()):
        ?>
        <div class="post">
            <div class="post-autor">
                <img src="../uploads/<?= htmlspecialchars($post['foto_perfil']) ?>" 
                     class="foto-post">
                <span><?= htmlspecialchars($post['nome_usuario']) ?></span>
            </div>

            <p><?= nl2br(htmlspecialchars($post['conteudo'])) ?></p>

            <?php if (!empty($post['imagem_post'])): ?>
                <img src="../uploads/<?= htmlspecialchars($post['imagem_post']) ?>" 
                     class="foto-postagem">
            <?php endif; ?>
        </div>
        <?php 
            endwhile;
        }
        ?>
    </div>
</div>

</body>
</html>
