<?php
require_once __DIR__ . '/conexao.php';

class Usuario {
    private $nome;
    private $email;
    private $senha;
    private $data_nascimento;

    // ---------- SETTERS ----------
    public function setNome($nome) { 
        $this->nome = trim($nome); 
    }

    public function setEmail($email) { 
        $this->email = trim($email); 
    }

    public function setSenha($senha) { 
        // Criptografa a senha de forma segura
        $this->senha = password_hash($senha, PASSWORD_DEFAULT); 
    }

    public function setDataNascimento($data) { 
        $this->data_nascimento = $data; 
    }

    // ---------- Cadastrar novo usuário ----------
    public function cadastrar() {
        $db = new Conexao();
        $conn = $db->getCon();

        // Verifica se o e-mail já está em uso
        $sql = "SELECT id_usuario FROM usuarios WHERE email_usuario = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Erro na preparação da consulta: " . $conn->error);
        }

        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            return false; // Já existe usuário com esse e-mail
        }

        // Insere novo usuário
        $sql = "INSERT INTO usuarios (nome_usuario, email_usuario, data_nascimento, senha_hash)
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Erro na preparação do INSERT: " . $conn->error);
        }

        $stmt->bind_param("ssss", $this->nome, $this->email, $this->data_nascimento, $this->senha);
        return $stmt->execute();
    }

    // ---------- Listar usuários ----------
    public function listarUsuarios() {
        $db = new Conexao();
        $conn = $db->getCon();

        $sql = "SELECT id_usuario, nome_usuario, email_usuario, tipo_usuario, ativo, foto_perfil 
                FROM usuarios";
        $result = $conn->query($sql);

        if (!$result) {
            die("Erro ao buscar usuários: " . $conn->error);
        }

        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }

        return $usuarios;
    }

    // ---------- Ativar / Desativar usuário ----------
    public function alterarStatus($id_usuario, $novoStatus) {
        $db = new Conexao();
        $conn = $db->getCon();
    
        $sql = "UPDATE usuarios SET ativo = ? WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Erro na preparação do UPDATE: " . $conn->error);
        }

        $stmt->bind_param("ii", $novoStatus, $id_usuario);
        return $stmt->execute();
    }

    // ---------- Buscar usuário por ID ----------
    public function buscarPorId($id_usuario) {
        $db = new Conexao();
        $conn = $db->getCon();

        $sql = "SELECT * FROM usuarios WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Erro na preparação da busca: " . $conn->error);
        }

        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result ? $result->fetch_assoc() : null;
    }

    // ---------- Atualizar nome e e-mail ----------
    public function atualizarDados($id_usuario, $nome, $email) {
        $db = new Conexao();
        $conn = $db->getCon();

        $sql = "UPDATE usuarios SET nome_usuario = ?, email_usuario = ? WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Erro na preparação do UPDATE: " . $conn->error);
        }

        $stmt->bind_param("ssi", $nome, $email, $id_usuario);
        return $stmt->execute();
    }

    // ---------- Atualizar foto de perfil ----------
    public function atualizarFoto($id_usuario, $caminho) {
        $db = new Conexao();
        $conn = $db->getCon();

        $sql = "UPDATE usuarios SET foto_perfil = ? WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Erro na preparação do UPDATE: " . $conn->error);
        }

        $stmt->bind_param("si", $caminho, $id_usuario);
        return $stmt->execute();
    }

    // ---------- Atualizar senha ----------
    public function atualizarSenha($id_usuario, $nova_senha) {
        $db = new Conexao();
        $conn = $db->getCon();

        $hash = password_hash($nova_senha, PASSWORD_DEFAULT);

        $sql = "UPDATE usuarios SET senha_hash = ? WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Erro na preparação do UPDATE: " . $conn->error);
        }

        $stmt->bind_param("si", $hash, $id_usuario);
        return $stmt->execute();
    }
}
?>
