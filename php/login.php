<?php
require_once 'conexao.php';

class Login {
    private $email;
    private $senha;

    public function setEmail($email) { $this->email = $email; }
    public function setSenha($senha) { $this->senha = $senha; }

    public function autenticar() {
        session_start();
        $db = new Conexao();

        $sql = "SELECT * FROM usuarios WHERE email_usuario = ?";
        $stmt = $db->getCon()->prepare($sql);
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $usuario = $result->fetch_assoc();

            if (password_verify($this->senha, $usuario['senha_hash'])) {
                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                $_SESSION['nome_usuario'] = $usuario['nome_usuario'];
                return true;
            }
        }
        return false;
    }
}
?>
