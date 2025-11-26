

<?php
session_start();
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/gateway.php';

$conexao = new Conexao();
$conn = $conexao->getCon();
$idLogado = $_SESSION['id_usuario'] ?? 0;


$idUsuarioFiltro = isset($_GET['id_usuario']) ? intval($_GET['id_usuario']) : null;


if (!$idUsuarioFiltro) {

    header('Content-Type: application/json; charset=utf-8');

    $sql = "
        SELECT 
            p.id_postagem, p.texto_postagem, p.imagem_postagem, p.id_categoria, p.id_usuario,
            u.nome_usuario, u.foto_perfil,
            c.nome_categoria,
            (SELECT COUNT(*) FROM reacoes r WHERE r.id_postagem = p.id_postagem AND r.tipo_reacao = 'like') AS likes,
            (SELECT COUNT(*) FROM reacoes r WHERE r.id_postagem = p.id_postagem AND r.tipo_reacao = 'dislike') AS dislikes
        FROM postagens p
        INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
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

  
        if ($idLogado) {
            $q = $conn->prepare("
                SELECT tipo_reacao 
                FROM reacoes 
                WHERE id_postagem = ? AND id_usuario = ?
                LIMIT 1
            ");
            $q->bind_param("ii", $row['id_postagem'], $idLogado);
            $q->execute();
            $rr = $q->get_result()->fetch_assoc();
            $row['minha_reacao'] = $rr["tipo_reacao"] ?? null;
            $q->close();
        } else {
            $row['minha_reacao'] = null;
        }

        $posts[] = $row;
    }

    $stmt->close();
    $conn->close();

    echo json_encode(['posts' => $posts], JSON_UNESCAPED_UNICODE);
    exit;
}





header("Content-Type: text/html; charset=UTF-8");

$sql = "
    SELECT 
        p.id_postagem,
        p.texto_postagem,
        p.imagem_postagem,
        p.id_usuario,
        u.nome_usuario,
        u.foto_perfil,
        c.nome_categoria,
        (SELECT COUNT(*) FROM reacoes r WHERE r.id_postagem = p.id_postagem AND r.tipo_reacao='like') AS likes,
        (SELECT COUNT(*) FROM reacoes r WHERE r.id_postagem = p.id_postagem AND r.tipo_reacao='dislike') AS dislikes
    FROM postagens p
    INNER JOIN usuarios u ON u.id_usuario = p.id_usuario
    LEFT JOIN categorias c ON c.id_categoria = p.id_categoria
    WHERE p.id_usuario = ?
    ORDER BY p.id_postagem DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idUsuarioFiltro);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo "<p style='color:#aaa; text-align:center;'>Nenhuma postagem encontrada.</p>";
    exit;
}

while ($row = $res->fetch_assoc()) {

    $foto = $row['foto_perfil'] ?: "default.png";


    $img = "";
    if (!empty($row['imagem_postagem'])) {
        $img = "<img src='../uploads/{$row['imagem_postagem']}' style='width:100%; border-radius:8px; margin-top:10px;'>";
    }

    echo "
    <div class='result-section post-card' data-id='{$row['id_postagem']}'>

        <div style='display:flex; align-items:center; gap:12px;'>
            <a href='perfil_usuario.php?id={$row['id_usuario']}' style='display:flex; align-items:center; gap:10px; text-decoration:none;'>
                <img src='../uploads/{$foto}' 
                     style='width:48px; height:48px; border-radius:50%; object-fit:cover; border:2px solid #00bfa5;'>
                <div style='color:#fff;'>
                    <strong style='color:#00ffc3;'>{$row['nome_usuario']}</strong><br>
                    <small style='color:#ddd;'>{$row['nome_categoria']}</small>
                </div>
            </a>
        </div>

        <div style='margin-top:12px; color:#fff; font-size:15px;'>
            ".htmlspecialchars($row['texto_postagem'])."
        </div>

        $img

        <div style='margin-top:10px; display:flex; align-items:center; gap:12px;'>

            <button class='btn-like btn btn-sm' data-id='{$row['id_postagem']}' data-tipo='like'
                    style='background:transparent; border:1px solid #00ffc3; color:#fff;'>
                👍 <span class='count-like'>{$row['likes']}</span>
            </button>

            <button class='btn-dislike btn btn-sm' data-id='{$row['id_postagem']}' data-tipo='dislike'
                    style='background:transparent; border:1px solid #ff6b6b; color:#fff;'>
                👎 <span class='count-dislike'>{$row['dislikes']}</span>
            </button>

        </div>

    </div>";
}

$stmt->close();
$conn->close();
exit;
?>
