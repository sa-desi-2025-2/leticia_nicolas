<?php
// carregar_posts.php
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/gateway.php'; // queremos saber o usuário logado (opcional)

header('Content-Type: application/json; charset=utf-8');

$conexao = new Conexao();
$conn = $conexao->getCon();
$idLogado = $_SESSION['id_usuario'] ?? 0;

// Ajuste: limita a 50 posts mais recentes por padrão
$sql = "
SELECT p.id_postagem, p.texto_postagem, p.imagem_postagem, p.id_categoria, p.id_usuario,
       u.nome_usuario, u.foto_perfil,
       c.nome_categoria,
       (SELECT COUNT(*) FROM reacoes r WHERE r.id_postagem = p.id_postagem AND r.tipo_reacao = 'like') AS likes,
       (SELECT COUNT(*) FROM reacoes r WHERE r.id_postagem = p.id_postagem AND r.tipo_reacao = 'dislike') AS dislikes
FROM postagens p
LEFT JOIN usuarios u ON p.id_usuario = u.id_usuario
LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
ORDER BY p.id_postagem DESC
LIMIT 50
";

$stmt = $conn->prepare($sql);
$stmt->execute();
$res = $stmt->get_result();

$posts = [];
while ($row = $res->fetch_assoc()) {
    $row['likes'] = (int)$row['likes'];
    $row['dislikes'] = (int)$row['dislikes'];
    // qual a reação do usuário logado para esse post?
    $row['minha_reacao'] = null;
    if ($idLogado) {
        $q = $conn->prepare("SELECT tipo_reacao FROM reacoes WHERE id_postagem = ? AND id_usuario = ? LIMIT 1");
        $q->bind_param("ii", $row['id_postagem'], $idLogado);
        $q->execute();
        $r2 = $q->get_result()->fetch_assoc();
        if ($r2) $row['minha_reacao'] = $r2['tipo_reacao'];
        $q->close();
    }
    $posts[] = $row;
}
$stmt->close();
$conn->close();

echo json_encode(['posts' => $posts], JSON_UNESCAPED_UNICODE);
