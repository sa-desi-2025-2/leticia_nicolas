<?php
session_start();
require_once __DIR__ . "/conexao.php";

// --- DEBUG OPCIONAL (remover depois) ---
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

if (!isset($_SESSION["id_usuario"])) {
    echo json_encode(["erro" => "Usuário não autenticado"]);
    exit;
}

$idUsuario = $_SESSION["id_usuario"];

$con = new Conexao();
$conn = $con->getCon();

// DADOS DO FORMULÁRIO
$nome = $_POST["nome_comunidade"] ?? "";
$descricao = $_POST["descricao_comunidade"] ?? "";
$idCategoria = $_POST["id_categoria"] ?? "";
$maiorIdade = isset($_POST["maior_idade"]) ? 1 : 0;

// VERIFICAÇÃO DE CAMPOS
if (empty($nome) || empty($descricao) || empty($idCategoria)) {
    echo json_encode(["erro" => "Preencha todos os campos!"]);
    exit;
}

// UPLOAD DE IMAGEM
$nomeImagem = null;

if (!empty($_FILES["imagem_comunidade"]["name"])) {

    $ext = strtolower(pathinfo($_FILES["imagem_comunidade"]["name"], PATHINFO_EXTENSION));
    $permitidos = ["jpg", "jpeg", "png", "gif"];

    if (!in_array($ext, $permitidos)) {
        echo json_encode(["erro" => "Formato de imagem inválido"]);
        exit;
    }

    $nomeImagem = "com_" . time() . "." . $ext;

    move_uploaded_file($_FILES["imagem_comunidade"]["tmp_name"], "../uploads/" . $nomeImagem);
}

// INSERIR COMUNIDADE
$stmt = $conn->prepare("
    INSERT INTO comunidades 
    (nome_comunidade, descricao_comunidade, maior_idade, id_categoria, imagem_comunidade)
    VALUES (?, ?, ?, ?, ?)
");

$stmt->bind_param("ssiss", $nome, $descricao, $maiorIdade, $idCategoria, $nomeImagem);

$stmt->execute();

$idComunidade = $stmt->insert_id;
$stmt->close();

// ADICIONAR USUÁRIO NA COMUNIDADE
$stmt2 = $conn->prepare("
    INSERT INTO usuarios_comunidades (id_usuario, id_comunidade)
    VALUES (?, ?)
");

$stmt2->bind_param("ii", $idUsuario, $idComunidade);
$stmt2->execute();
$stmt2->close();

// RESPOSTA FINAL
echo json_encode([
    "sucesso" => true,
    "id" => $idComunidade,
    "imagem" => $nomeImagem ?: "default_comunidade.png"
]);
