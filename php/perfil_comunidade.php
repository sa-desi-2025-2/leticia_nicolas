<?php
require_once "Conexao.php";
session_start();

if (!isset($_GET['id'])) {
    die("Comunidade não encontrada.");
}

$id_comunidade = intval($_GET['id']);

/* ----------------- BUSCAR DADOS DA COMUNIDADE ----------------- */
$sql = $conn->prepare("
    SELECT c.*, cat.nome_categoria 
    FROM comunidades c
    LEFT JOIN categorias cat ON c.id_categoria = cat.id_categoria
    WHERE id_comunidade = ?
");
$sql->execute([$id_comunidade]);

$comunidade = $sql->fetch(PDO::FETCH_ASSOC);

if (!$comunidade) {
    die("Comunidade inexistente.");
}

/* ----------------- VERIFICAR SE O USUÁRIO PARTICIPA ----------------- */
$participa = false;
if (isset($_SESSION['id_usuario'])) {
    $sql2 = $conn->prepare("
        SELECT * FROM usuarios_comunidades
        WHERE id_usuario = ? AND id_comunidade = ?
    ");
    $sql2->execute([$_SESSION['id_usuario'], $id_comunidade]);

    $participa = $sql2->rowCount() > 0;
}

/* ----------------- CONTAR MEMBROS ----------------- */
$sql3 = $conn->prepare("SELECT COUNT(*) AS total FROM usuarios_comunidades WHERE id_comunidade = ?");
$sql3->execute([$id_comunidade]);
$total_membros = $sql3->fetch()['total'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($comunidade['nome_comunidade']) ?></title>
    <link rel="stylesheet" href="../css/perfil_comunidade.css">
    </head>
<body>

<?php include "menu.php"; ?>

<div class="perfil-container">

    <div class="perfil-header">
        <img src="../uploads/<?= $comunidade['imagem_comunidade'] ?: 'default.png' ?>" 
             class="foto-perfil-comunidade">

        <h2><?= htmlspecialchars($comunidade['nome_comunidade']) ?></h2>
        <p><?= htmlspecialchars($comunidade['descricao_comunidade']) ?></p>

        <span class="categoria">Categoria: <?= htmlspecialchars($comunidade['nome_categoria']) ?></span>
        <span class="membros"><?= $total_membros ?> membros</span>

        <?php if (isset($_SESSION['id_usuario'])): ?>
            <form action="participar_comunidade.php" method="POST">
                <input type="hidden" name="id_comunidade" value="<?= $id_comunidade ?>">

                <?php if ($participa): ?>
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
        $sql4 = $conn->prepare("
            SELECT p.*, u.nome_usuario, u.foto_perfil
            FROM postagens p
            JOIN usuarios u ON p.id_usuario = u.id_usuario
            WHERE id_comunidade = ?
            ORDER BY data_postagem DESC
        ");
        $sql4->execute([$id_comunidade]);

        if ($sql4->rowCount() == 0) {
            echo "<p>Esta comunidade ainda não possui postagens.</p>";
        } else {
            while ($post = $sql4->fetch(PDO::FETCH_ASSOC)) {
                echo "
                <div class='post'>
                    <div class='post-autor'>
                        <img src='../uploads/{$post['foto_perfil']}' class='foto-post'>
                        <span>{$post['nome_usuario']}</span>
                    </div>

                    <p>{$post['conteudo']}</p>

                    " . (!empty($post['imagem_post']) ? 
                        "<img src='../uploads/{$post['imagem_post']}' class='foto-postagem'>" : ""
                    ) . "
                </div>";
            }
        }
        ?>
    </div>

</div>

</body>
</html>
