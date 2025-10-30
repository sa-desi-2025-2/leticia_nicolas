<?php
session_start();
require_once __DIR__ . '/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    try {
        $con = new Conexao();

        $sql = "SELECT id_usuario, email_usuario, senha_hash 
                FROM usuarios 
                WHERE email_usuario = :email LIMIT 1";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($senha, $user['senha_hash'])) {
            $_SESSION['user_id'] = $user['id_usuario'];
            $_SESSION['user_email'] = $user['email_usuario'];
            $_SESSION['logado'] = true;

            header("Location: pagina_principal.php");
            exit;
        } else {
            $_SESSION['login_error'] = "Email ou senha incorretos";
            header("Location: login.php");
            exit;
        }
    } catch (Exception $e) {
        echo "Erro ao tentar logar: " . $e->getMessage();
    }
} else {
    header("Location: login.php");
    exit;
}
