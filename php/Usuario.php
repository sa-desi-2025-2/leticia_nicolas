<?php
require_once 'conexao.php';

class Usuario {
    private $nome;
    private $email;
    private $senha;
    private $data_nascimento;

    // ---------- SETTERS ----------
    public function setNome($nome) { 
        $this->nome = $nome; 
    }

    public function setEmail($email) { 
        $this->email = $email; 
    }

    public function setSenha($senha) { 
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
        $sql = "SELECT * FROM usuarios WHERE email_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return false; // já existe usuário com esse e-mail
        }

        // Cadastra novo usuário
        $sql = "INSERT INTO usuarios (nome_usuario, email_usuario, data_nascimento, senha_hash)
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $this->nome, $this->email, $this->data_nascimento, $this->senha);

        return $stmt->execute();
    }

    // ---------- Listar usuários (para painel admin) ----------
    public function listarUsuarios() {
        $db = new Conexao();
        $conn = $db->getCon();

        $sql = "SELECT id_usuario, nome_usuario, email_usuario, tipo_usuario, ativo FROM usuarios";
        $result = $conn->query($sql);

        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }

        return $usuarios;
    }

    // ---------- Ativar / Desativar usuário ----------
    // Na classe Usuario:
    public function alterarStatus($id_usuario, $novoStatus) {
        $db = new Conexao();
        $conn = $db->getCon();
    
        $sql = "UPDATE usuarios SET ativo = ? WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $novoStatus, $id_usuario);
    
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
}
?>
