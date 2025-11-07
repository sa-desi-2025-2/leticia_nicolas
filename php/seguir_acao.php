<?php
session_start();
require_once __DIR__ . '/Seguidor.php';

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Usuário não logado']);
    exit;
}

$idSeguidor = $_SESSION['id_usuario'];
$idSeguido  = intval($_POST['id_seguido'] ?? 0);

if ($idSeguido <= 0) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'ID inválido']);
    exit;
}

$seguidor = new Seguidor();
$resposta = $seguidor->seguir($idSeguidor, $idSeguido);

header('Content-Type: application/json');
echo json_encode($resposta);
?>
