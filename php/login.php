<?php
require_once 'conexao.php';
require_once 'Usuario.php';

class Login {
    private $email;
    private $senha;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); // garante que a sessão esteja ativa
        }
    }

    // ---------- SETTERS ----------
    public function setEmail($email) {
        $this->email = $email;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }

    // ---------- AUTENTICAÇÃO ----------
    public function autenticar() {
        $db = new Conexao();
        $conn = $db->getCon();

        // Busca usuário ativo pelo e-mail
        $sql = "SELECT * FROM usuarios WHERE email_usuario = ? AND ativo = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verifica se encontrou o usuário
        if ($result->num_rows === 1) {
            $usuario = $result->fetch_assoc();

            // Verifica a senha
            if (password_verify($this->senha, $usuario['senha_hash'])) {

                // ✅ Cria sessão com dados do usuário
                $_SESSION['id_usuario']   = $usuario['id_usuario'];
                $_SESSION['nome_usuario'] = $usuario['nome_usuario'];
                $_SESSION['admin']        = $usuario['tipo_usuario']; // 'admin' ou 'usuario'
                $_SESSION['foto_perfil']  = !empty($usuario['foto_perfil'])
                    ? $usuario['foto_perfil']
                    : '../uploads/default.png';

                // ✅ Redireciona conforme o tipo de usuário
                if ($usuario['tipo_usuario'] === 'admin') {
                    header("Location: pagina_principal_adm.php"); // página do administrador
                } else {
                    header("Location: pagina_principal.php"); // página comum
                }
                exit; // encerra execução após redirecionamento
            }
        }

        // ❌ Se falhar
        return false;
    }
}
?>