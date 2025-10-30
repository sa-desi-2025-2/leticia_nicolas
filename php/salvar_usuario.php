<?php
require_once 'conexao.php';

try {
    $conexao = new Conexao();

    //pega os dados do site
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $data_nascimento = $_POST['idade'];
    $senha = $_POST['password'];

    //verifica se e menor de idade
    $data_nasc = new DateTime($data_nascimento);
    $hoje = new DateTime();
    $idade = $hoje->diff($data_nasc)->y;
    $menor_idade = ($idade < 18) ? 1 : 0;

    //hash senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    //envia pro banco os dados (inclusive se o usuario e menor ou nao de idade)
    $sql = "INSERT INTO usuarios (nome_usuario, data_nascimento, email_usuario, senha_hash, menor_idade)
            VALUES (:nome, :data, :email, :senha, :menor_idade)";
    

    // enquanto pesquisava apareceu $stmt = $conexao->prepare($sql); que serve para terceiros nao injetarem valores no banco?
    $stmt = $conexao->prepare($sql); //essa linha
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':data', $data_nascimento);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha_hash);
    $stmt->bindParam(':menor_idade', $menor_idade);
    $stmt->execute();
    // enquanto pesquisava apareceu $stmt->bindParam associa valores como $nome para :nome

    // envia o usuario para a tela de login
    header("Location: login.php");
    exit();

    
//  caso de algum problema com o codigo acima roda esse
} catch (PDOException $e) {
    echo "Erro ao cadastrar usuario:". $e->getMessage();
}
//  caso de algum problema com o codigo acima roda esse

?>
