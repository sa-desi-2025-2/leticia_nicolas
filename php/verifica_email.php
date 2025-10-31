<?php 

include("includes/conexao.php");//conexão com o banco

if(isset($_POST["registrar"])) {
    //Busca no banco a quantidade de usuarios que tenham o mesmo email
    $querySelect = "SELECT count(*) total FROM usuarios WHERE email = {$_POST['email']}";


    //prepara a query
    $statement = $connection->prepare($querySelect);

    //executar o comando sql
    $result = $statement->execute();

    //juntar todos os resultados do select em um vetor de arrays
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    //Verificase o valor retornado é 0 (Nenhum usuário igual cadastrado)
    if($result['total'] == 0){

        $senha           = $_POST['senha'];
        $confirma_senha  = $_POST['confirma_senha'];
        if (empty($senha)) {
            $mensagem = "<span class='aviso'><b>Aviso</b>: Senha não foi alterada!</span>";
        } else if ($senha == $confirma_senha) {
            $query = "INSERT INTO usuario (nome, sobrenome, matricula, email, senha) 
                  VALUES (:nome, :sobrenome, :matricula, :email, :senha)";

            $statement = $connection->prepare($query);


            $valores = array();
            $valores[':nome'] = (isset($_POST['nome_usuario']) ? $_POST['nome_usuario'] : '');
            $valores[':email'] = (isset($_POST['email']) ? $_POST['email'] : '');
            $valores[':senha'] = (isset($_POST['senha']) ? $_POST['senha'] : '');

            $result = $statement->execute($valores);
            if(!empty($result)){
                $mensagem = "<span class='aviso'><b>Sucesso</b>: deu certo!</span>";
            }
        } else {
             $mensagem = "<span class='aviso'><b>Aviso</b>: Senha e repetir senha são diferentes!</span>";

        }

    }else{
        $mensagem = "Email ou matricula ja cadastrado";
    }

}


?>