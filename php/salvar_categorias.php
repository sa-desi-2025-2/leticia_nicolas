<?php
session_start();
require_once __DIR__ . '/conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não logado.']);
    exit();
}

$idUsuario = $_SESSION['id_usuario'];

// Verifica se vieram categorias selecionadas
if (empty($_POST['categorias']) || !is_array($_POST['categorias'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Nenhuma categoria selecionada.']);
    exit();
}

$categorias = $_POST['categorias'];

try {
    $conexao = new Conexao();
    $conn = $conexao->getCon();

    // Prepara a inserção (impede duplicação)
    $stmtInsert = $conn->prepare("
        INSERT INTO usuarios_categorias (id_usuario, id_categoria)
        SELECT ?, ? FROM DUAL
        WHERE NOT EXISTS (
            SELECT 1 FROM usuarios_categorias WHERE id_usuario = ? AND id_categoria = ?
        )
    ");

    foreach ($categorias as $idCategoria) {
        $idCategoria = intval($idCategoria);
        $stmtInsert->bind_param("iiii", $idUsuario, $idCategoria, $idUsuario, $idCategoria);
        $stmtInsert->execute();
    }

    $stmtInsert->close();
    $conn->close();

    echo json_encode(['sucesso' => true]);
    exit();
} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao salvar categorias: ' . $e->getMessage()]);
    exit();
}
?>
