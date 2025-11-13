<?php 
session_start();
require_once __DIR__ . '/conexao.php';
header('Content-Type: application/json; charset=utf-8');

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não logado.']);
    exit();
}

$idUsuario = intval($_SESSION['id_usuario']);

// Verifica se vieram categorias selecionadas (obrigatório ter pelo menos 1)
if (empty($_POST['categorias']) || !is_array($_POST['categorias'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Nenhuma categoria selecionada.']);
    exit();
}

$categorias = array_map('intval', $_POST['categorias']);
if (count($categorias) === 0) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Selecione pelo menos uma categoria.']);
    exit();
}

try {
    $conexao = new Conexao();
    $conn = $conexao->getCon();

    // Usar transação para segurança
    $conn->begin_transaction();

    // Remover as antigas preferências do usuário
    $stmtDel = $conn->prepare("DELETE FROM usuarios_categorias WHERE id_usuario = ?");
    $stmtDel->bind_param("i", $idUsuario);
    $stmtDel->execute();
    $stmtDel->close();

    // Preparar inserção
    $stmtIns = $conn->prepare("INSERT INTO usuarios_categorias (id_usuario, id_categoria) VALUES (?, ?)");
    foreach ($categorias as $idCategoria) {
        $stmtIns->bind_param("ii", $idUsuario, $idCategoria);
        $stmtIns->execute();
    }
    $stmtIns->close();

    $conn->commit();
    $conn->close();

    echo json_encode(['sucesso' => true]);
    exit();
} catch (Exception $e) {
    if (isset($conn) && $conn->errno) {
        $conn->rollback();
    }
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao salvar categorias: ' . $e->getMessage()]);
    exit();
}
?>
