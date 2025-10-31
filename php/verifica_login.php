<?php
session_start();
require_once __DIR__ . '/conexao.php';
$con = new Conexao();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    try {
        $sql = "SELECT id_usuario, email_usuario, senha_hash, ativo
                FROM usuarios 
                WHERE email_usuario = :email LIMIT 1";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha_hash'])) {
            if ($usuario['ativo'] == '0') {
                $_SESSION['login_error'] = "Usuario desativado";
                header("Location: login.php");
                exit;
            }

            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['email_usuario'] = $usuario['email_usuario'];
            $_SESSION['ativo'] = $usuario['ativo'];

            header("Location: ../php/pagina_principal.php");
            exit;
        } else {
            $_SESSION['login_error'] = "Email ou senha incorretos.";
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
