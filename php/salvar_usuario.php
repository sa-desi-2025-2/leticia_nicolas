<?php
require_once 'conexao.php';

try {
    $conexao = new Conexao();

    // Pega os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $data_nascimento = $_POST['idade'];
    $senha = $_POST['password'];

    // Calcula se o usuário é menor de idade
    $data_nasc = new DateTime($data_nascimento);
    $hoje = new DateTime();
    $idade = $hoje->diff($data_nasc)->y;
    $menor_idade = ($idade < 18) ? 1 : 0;

    // Insere no banco
    $sql = "INSERT INTO usuarios (nome_usuario, data_nascimento, email_usuario, senha_usuario, menor_idade)
            VALUES (:nome, :data, :email, :senha, :menor_idade)";
    
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':data', $data_nascimento);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);
    $stmt->bindParam(':menor_idade', $menor_idade);
    $stmt->execute();

    // Redireciona para a página de login após cadastrar com sucesso
    header("Location: login.php");
    exit();

} catch (PDOException $e) {
    echo "<h2 style='color:red;'>❌ Erro ao cadastrar usuário: " . $e->getMessage() . "</h2>";
}
?>
