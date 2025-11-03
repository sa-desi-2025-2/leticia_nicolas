<?php
require_once 'conexao.php';

class Usuario {

    private $nome_usuario;
    private $email_usuario;
    private $senha_hash;
    private $data_nascimento;
    private $menor_idade;
    private $pdo;

    public function __construct()
    {
        $this->pdo = new Conexao();
    }

    public function setNome($nome) {
        $this->nome_usuario = trim($nome);
    }

    public function setEmail($email) {
        $this->email_usuario = trim($email);
    }

    public function setSenha($senha) {
        $this->senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    }

    public function setDataNascimento($data) {
        $this->data_nascimento = $data;
        $this->calcularMenorIdade();
    }

    private function calcularMenorIdade() {
        $data_nasc = new DateTime($this->data_nascimento);
        $hoje = new DateTime();
        $idade = $hoje->diff($data_nasc)->y;
        $this->menor_idade = ($idade < 18) ? 1 : 0;
    }

    public function cadastrar() {
        if(!$this->validarEmail()) {
            return false; // email já existe
        }

        $sql = "INSERT INTO usuarios (nome_usuario, data_nascimento, email_usuario, senha_hash, menor_idade)
                VALUES (:nome, :data, :email, :senha, :menor_idade)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':nome', $this->nome_usuario);
        $stmt->bindParam(':data', $this->data_nascimento);
        $stmt->bindParam(':email', $this->email_usuario);
        $stmt->bindParam(':senha', $this->senha_hash);
        $stmt->bindParam(':menor_idade', $this->menor_idade);

        return $stmt->execute();
    }

    private function validarEmail() {
        $sql = "SELECT id_usuario FROM usuarios WHERE email_usuario = :email LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':email', $this->email_usuario);
        $stmt->execute();
        return $stmt->rowCount() == 0;
    }
}
?>
