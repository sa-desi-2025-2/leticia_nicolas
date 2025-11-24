<?php
require_once __DIR__ . '/conexao.php';

class Usuario {
    private $nome;
    private $email;
    private $senha;
    private $data_nascimento;


    public function setNome($nome) { 
        $this->nome = trim($nome); 
    }

    public function setEmail($email) { 
        $this->email = trim($email); 
    }

    public function setSenha($senha) { 
        $this->senha = password_hash($senha, PASSWORD_DEFAULT); 
    }

    public function setDataNascimento($data) { 
        $this->data_nascimento = $data; 
    }

   
    public function cadastrar() {
        $db = new Conexao();
        $conn = $db->getCon();

        $sql = "SELECT id_usuario FROM usuarios WHERE email_usuario = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) die("Erro na preparação da consulta: " . $conn->error);

        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            return false;
        }

        $sql = "INSERT INTO usuarios (nome_usuario, email_usuario, data_nascimento, senha_hash)
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) die("Erro na preparação do INSERT: " . $conn->error);

        $stmt->bind_param("ssss", $this->nome, $this->email, $this->data_nascimento, $this->senha);
        return $stmt->execute();
    }


    public function listarUsuarios() {
        $db = new Conexao();
        $conn = $db->getCon();

        $sql = "SELECT id_usuario, nome_usuario, email_usuario, tipo_usuario, ativo, foto_perfil, imagem_banner, bio 
                FROM usuarios";
        $result = $conn->query($sql);

        if (!$result) die("Erro ao buscar usuários: " . $conn->error);

        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }
        return $usuarios;
    }


    public function alterarStatus($id_usuario, $novoStatus) {
        $db = new Conexao();
        $conn = $db->getCon();

        $sql = "UPDATE usuarios SET ativo = ? WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) die("Erro na preparação do UPDATE: " . $conn->error);

        $stmt->bind_param("ii", $novoStatus, $id_usuario);
        return $stmt->execute();
    }


    public function buscarPorId($id_usuario) {
        $db = new Conexao();
        $conn = $db->getCon();

        $sql = "SELECT * FROM usuarios WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) die("Erro na preparação da busca: " . $conn->error);

        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result ? $result->fetch_assoc() : null;
    }

 
    public function atualizarDados($id_usuario, $nome, $email) {
        $db = new Conexao();
        $conn = $db->getCon();

        $sql = "UPDATE usuarios SET nome_usuario = ?, email_usuario = ? WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) die("Erro na preparação do UPDATE: " . $conn->error);

        $stmt->bind_param("ssi", $nome, $email, $id_usuario);
        return $stmt->execute();
    }


    public function atualizarFoto($id_usuario, $caminho) {
        $db = new Conexao();
        $conn = $db->getCon();

        $sql = "UPDATE usuarios SET foto_perfil = ? WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) die("Erro na preparação do UPDATE: " . $conn->error);

        $stmt->bind_param("si", $caminho, $id_usuario);
        return $stmt->execute();
    }


    public function atualizarBanner($id_usuario, $caminho) {
        $db = new Conexao();
        $conn = $db->getCon();

        $sql = "UPDATE usuarios SET imagem_banner = ? WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) die("Erro na preparação do UPDATE: " . $conn->error);

        $stmt->bind_param("si", $caminho, $id_usuario);
        return $stmt->execute();
    }

    public function atualizarBio($id_usuario, $bio) {
        $db = new Conexao();
        $conn = $db->getCon();

        $sql = "UPDATE usuarios SET bio = ? WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) die("Erro na preparação do UPDATE: " . $conn->error);

        $stmt->bind_param("si", $bio, $id_usuario);
        return $stmt->execute();
    }

 
    public function atualizarSenha($id_usuario, $nova_senha) {
        $db = new Conexao();
        $conn = $db->getCon();

        $hash = password_hash($nova_senha, PASSWORD_DEFAULT);

        $sql = "UPDATE usuarios SET senha_hash = ? WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) die("Erro na preparação do UPDATE: " . $conn->error);

        $stmt->bind_param("si", $hash, $id_usuario);
        return $stmt->execute();
    }

    public function atualizarPerfil($id, $nome, $email, $bio, $fotoPerfil = null, $fotoBanner = null) {
        $db = new Conexao();
        $conn = $db->getCon();

        $sql = "UPDATE usuarios SET nome_usuario = ?, email_usuario = ?, bio = ?";
        $params = [$nome, $email, $bio];
        $tipos = "sss";

        if ($fotoPerfil) {
            $sql .= ", foto_perfil = ?";
            $params[] = $fotoPerfil;
            $tipos .= "s";
        }

        if ($fotoBanner) {
            $sql .= ", imagem_banner = ?";
            $params[] = $fotoBanner;
            $tipos .= "s";
        }

        $sql .= " WHERE id_usuario = ?";
        $params[] = $id;
        $tipos .= "i";

        $stmt = $conn->prepare($sql);
        if (!$stmt) die("Erro na preparação do UPDATE: " . $conn->error);

        $stmt->bind_param($tipos, ...$params);
        return $stmt->execute();
    }


    public static function uploadImagem($arquivo, $prefixo, $id, $diretorio = "../uploads/") {
        if (!is_dir($diretorio)) mkdir($diretorio, 0755, true);

        if (isset($arquivo) && $arquivo['error'] === 0) {
            $ext = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
            $nomeArquivo = $prefixo . "_" . $id . "_" . time() . "." . $ext;
            $caminhoCompleto = $diretorio . $nomeArquivo;

            if (move_uploaded_file($arquivo['tmp_name'], $caminhoCompleto)) {
                return $caminhoCompleto;
            }
        }
        return null;
    }

    public function listarSeguidores($idUsuario) {
        $db = new Conexao();
        $conn = $db->getCon();

        $sql = "SELECT u.id_usuario, u.nome_usuario, u.foto_perfil
                FROM seguidores s
                JOIN usuarios u ON s.id_seguidor = u.id_usuario
                WHERE s.id_seguindo = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) die("Erro na preparação da consulta: " . $conn->error);

        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();

        $seguidores = [];
        while ($row = $result->fetch_assoc()) {
            $seguidores[] = $row;
        }

        return $seguidores;
    }


    public function listarSeguidos($idUsuario) {
        $db = new Conexao();
        $conn = $db->getCon();

        $sql = "SELECT u.id_usuario, u.nome_usuario, u.foto_perfil
                FROM seguidores s
                JOIN usuarios u ON s.id_seguindo = u.id_usuario
                WHERE s.id_seguidor = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) die("Erro na preparação da consulta: " . $conn->error);

        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();

        $seguidos = [];
        while ($row = $result->fetch_assoc()) {
            $seguidos[] = $row;
        }

        return $seguidos;
    }
}
?>
