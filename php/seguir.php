<?php
session_start();
require_once __DIR__ . '/conexao.php';

// ✅ Verifica login
if (!isset($_SESSION['id_usuario'])) {
    http_response_code(403);
    echo "Usuário não logado";
    exit;
}

$idSeguidor = $_SESSION['id_usuario'];
$idSeguido = intval($_POST['id'] ?? 0);
$tipo = $_POST['tipo'] ?? 'usuario';

if ($idSeguido <= 0) {
    http_response_code(400);
    echo "ID inválido";
    exit;
}

$conexao = new Conexao();
$conn = $conexao->getCon();

try {
    if ($tipo === 'usuario') {
        // 🔍 Verifica se já segue o usuário
        $stmt = $conn->prepare("SELECT 1 FROM seguidores WHERE id_usuario = ? AND id_seguidor = ?");
        $stmt->execute([$idSeguido, $idSeguidor]);

        if ($stmt->rowCount() > 0) {
            // ❌ Deixar de seguir
            $conn->prepare("DELETE FROM seguidores WHERE id_usuario = ? AND id_seguidor = ?")
                  ->execute([$idSeguido, $idSeguidor]);
            echo "unfollowed";
        } else {
            // ✅ Começar a seguir
            $conn->prepare("INSERT INTO seguidores (id_usuario, id_seguidor) VALUES (?, ?)")
                  ->execute([$idSeguido, $idSeguidor]);
            echo "followed";
        }
    } 
    elseif ($tipo === 'comunidade') {
        // 🔍 Verifica se já segue a comunidade
        $stmt = $conn->prepare("SELECT 1 FROM seguidores_comunidades WHERE id_comunidade = ? AND id_seguidor = ?");
        $stmt->execute([$idSeguido, $idSeguidor]);

        if ($stmt->rowCount() > 0) {
            // ❌ Deixar de seguir
            $conn->prepare("DELETE FROM seguidores_comunidades WHERE id_comunidade = ? AND id_seguidor = ?")
                  ->execute([$idSeguido, $idSeguidor]);
            echo "unfollowed";
        } else {
            // ✅ Começar a seguir
            $conn->prepare("INSERT INTO seguidores_comunidades (id_comunidade, id_seguidor) VALUES (?, ?)")
                  ->execute([$idSeguido, $idSeguidor]);
            echo "followed";
        }
    } 
    else {
        http_response_code(400);
        echo "Tipo inválido";
    }
} catch (Exception $e) {
    http_response_code(500);
    echo "Erro no servidor";
}
?>
