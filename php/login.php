<?php
require_once 'conexao.php';
require_once 'Usuario.php';

class Login {
    private $email;
    private $senha;

    public function __construct() {

    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }

    public function autenticar() {
        $db = new Conexao();
        $conn = $db->getCon();

        $sql = "SELECT * FROM usuarios WHERE email_usuario = ? AND ativo = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $usuario = $result->fetch_assoc();

            if (password_verify($this->senha, $usuario['senha_hash'])) {
                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                $_SESSION['nome_usuario'] = $usuario['nome_usuario'];
                $_SESSION['admin'] = $usuario['tipo_usuario'];
                return true;
            }
        }
        return false;
    }
}
?>
