<?php
require_once 'conexao.php';
require_once 'Usuario.php';
require_once 'gateway.php';

class Login {
    private $email;
    private $senha;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); // garante que a sessão esteja ativa
        }
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

            // Verifica senha
            if (password_verify($this->senha, $usuario['senha_hash'])) {

                // ✅ Cria sessão com dados do usuário
                $_SESSION['id_usuario']   = $usuario['id_usuario'];
                $_SESSION['nome_usuario'] = $usuario['nome_usuario'];
                $_SESSION['admin']        = $usuario['tipo_usuario'];

                // ✅ Define imagem de perfil (ou padrão)
                $_SESSION['foto_perfil']  = !empty($usuario['foto_perfil'])
                    ? $usuario['foto_perfil']
                    : '../uploads/default.png';

                return true;
            }
        }

        return false;
    }
}
?>
