<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/conexao.php";

$idLogado = $_SESSION['id_usuario'] ?? 0;

$conexao = new Conexao();
$conn = $conexao->getCon();

$comunidadesUsuario = [];

$stmt = $conn->prepare("
    SELECT c.id_comunidade, c.nome_comunidade, c.imagem_comunidade
    FROM usuarios_comunidades uc
    JOIN comunidades c ON uc.id_comunidade = c.id_comunidade
    WHERE uc.id_usuario = ?
");
$stmt->bind_param("i", $idLogado);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $comunidadesUsuario[] = $row;
}
$stmt->close();
?>

<aside class="sidebar-comunidades">
    <div class="menu-comunidades">
        <?php if (count($comunidadesUsuario) > 0): ?>
            <?php foreach ($comunidadesUsuario as $com): ?>
                <a href="comunidade.php?id=<?= (int)$com['id_comunidade'] ?>" class="comunidade-icone">
                    <img src="<?= !empty($com['imagem_comunidade']) ? '../uploads/' . htmlspecialchars($com['imagem_comunidade']) : '../img/default_comunidade.png' ?>"
                         alt="<?= htmlspecialchars($com['nome_comunidade']) ?>">
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="nenhuma-comunidade">Nenhuma comunidade ainda.</p>
        <?php endif; ?>
    </div>

    <div class="add-icon" id="btnAbrirCriarComunidade">+</div>
</aside>
