<?php
// criar_post.php
require_once __DIR__ . '/gateway.php'; // garante sessão
require_once __DIR__ . '/conexao.php';

header('Content-Type: application/json; charset=utf-8');

$id_usuario = $_SESSION['id_usuario'] ?? 0;
if (!$id_usuario) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não autenticado.']);
    exit;
}

// valida texto e categoria
$texto = trim($_POST['texto_postagem'] ?? '');
$id_categoria = intval($_POST['id_categoria'] ?? 0);

if ($texto === '' && empty($_FILES['imagem_postagem']['name'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Adicione texto ou imagem.']);
    exit;
}
if ($id_categoria <= 0) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Selecione uma categoria válida.']);
    exit;
}

$conexao = new Conexao();
$conn = $conexao->getCon();

// processa upload se existir
$nome_arquivo = null;
if (!empty($_FILES['imagem_postagem']['name'])) {
    $arquivo = $_FILES['imagem_postagem'];
    // validações básicas
    $maxSize = 5 * 1024 * 1024; // 5MB
    if ($arquivo['size'] > $maxSize) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Imagem excede 5MB.']);
        exit;
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $arquivo['tmp_name']);
    finfo_close($finfo);

    $permitidos = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif'];
    if (!array_key_exists($mime, $permitidos)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Formato inválido. Use JPG/PNG/GIF.']);
        exit;
    }

    $ext = $permitidos[$mime];
    $nome_arquivo = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;

    $destino_dir = __DIR__ . '/../uploads/';
    if (!is_dir($destino_dir)) {
        if (!mkdir($destino_dir, 0755, true)) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Falha ao criar diretório de uploads.']);
            exit;
        }
    }
    $destino = $destino_dir . $nome_arquivo;
    if (!move_uploaded_file($arquivo['tmp_name'], $destino)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Falha ao enviar a imagem.']);
        exit;
    }
}

// insere no banco
$stmt = $conn->prepare("INSERT INTO postagens (texto_postagem, imagem_postagem, id_categoria, id_usuario) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssii", $texto, $nome_arquivo, $id_categoria, $id_usuario);
if ($stmt->execute()) {
    echo json_encode(['sucesso' => true, 'mensagem' => 'Post criado.']);
} else {
    // se falha, remove arquivo subido
    if ($nome_arquivo && file_exists(__DIR__ . '/../uploads/' . $nome_arquivo)) {
        unlink(__DIR__ . '/../uploads/' . $nome_arquivo);
    }
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao salvar postagem.']);
}
$stmt->close();
$conn->close();
