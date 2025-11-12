<?php
require_once 'conexao.php';
require_once 'Usuario.php';

class Login {
    private $email;
    private $senha;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
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

        $sql = "SELECT * FROM usuarios WHERE email_usuario = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $usuario = $result->fetch_assoc();

            // Conta desativada
            if (isset($usuario['ativo']) && $usuario['ativo'] == 0) {
                $_SESSION['login_error'] = "Conta desativada.";
                return false;
            }

            // Verifica senha
            if (password_verify($this->senha, $usuario['senha_hash'])) {
                $_SESSION['id_usuario']   = $usuario['id_usuario'];
                $_SESSION['nome_usuario'] = $usuario['nome_usuario'];
                $_SESSION['foto_perfil']  = !empty($usuario['foto_perfil']) 
                    ? $usuario['foto_perfil'] 
                    : '../uploads/default.png';
                
                // 🔹 Padroniza tipo de usuário para o gateway
                $tipo = strtolower($usuario['tipo_usuario']);
                $_SESSION['tipo_usuario'] = ($tipo === 'admin' || $tipo === '1') ? 'admin' : 'padrao';

                // Redireciona com base no tipo
                if ($_SESSION['tipo_usuario'] === 'admin') {
                    header("Location: pagina_principal_adm.php");
                } else {
                    header("Location: pagina_principal.php");
                }
                exit;
            } else {
                $_SESSION['login_error'] = "Senha incorreta.";
                return false;
            }
        } else {
            $_SESSION['login_error'] = "Email não encontrado.";
            return false;
        }
    }
}
?>
