<?php
require_once 'conexao.php';

class Usuario {
    private $nome;
    private $email;
    private $senha;
    private $data_nascimento;

    public function setNome($nome) { $this->nome = $nome; }
    public function setEmail($email) { $this->email = $email; }
    public function setSenha($senha) { $this->senha = password_hash($senha, PASSWORD_DEFAULT); }
    public function setDataNascimento($data) { $this->data_nascimento = $data; }

    public function cadastrar() {
        $db = new Conexao();

        $sql = "SELECT * FROM usuarios WHERE email_usuario = ?";
        $stmt = $db->getCon()->prepare($sql);
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return false;
        }

        $sql = "INSERT INTO usuarios (nome_usuario, email_usuario, data_nascimento, senha_hash)
                VALUES (?, ?, ?, ?)";

        $stmt = $db->getCon()->prepare($sql);
        $stmt->bind_param("ssss", $this->nome, $this->email, $this->data_nascimento, $this->senha);

        return $stmt->execute();
    }
}
?>
