<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="../css/cadastro.css">
</head>
<body>
    <div class="container">
        <!-- Barra superior -->
        <header>
            <div class="logo"></div>
            <a href="login_estrutura.php">
                <button type="button" class="btn">Login</button>
            </a>
        </header>

        <!-- Tela de cadastro -->
        <div class="main-content">
            <div class="logo-side"></div>

            <div class="login-box">

       
                <?php if(!empty($_SESSION['cadastro_erro'])): ?>
                    <div class="alert alert-danger text-center">
                        <?php 
                            echo $_SESSION['cadastro_erro'];
                            unset($_SESSION['cadastro_erro']); // limpa a mensagem
                        ?>
                    </div>
                <?php endif; ?>
                <!-- Fim mensagem de erro -->

                <form id="loginForm" action="salvar_usuario.php" method="POST">
                    <label for="nome">
                        <img class="icon" src="https://img.icons8.com/?size=100&id=11730&format=png&color=ffffff" alt="nome"/>
                        Nome:
                    </label>
                    <input type="text" id="nome" name="nome" placeholder="Digite seu nome" required>
                    
                    <label for="email">
                        <img class="icon" src="https://img.icons8.com/?size=100&id=Ww1lcGqgduif&format=png&color=ffffff" alt="email"/>
                        Email:
                    </label>
                    <input type="email" id="email" name="email" placeholder="Digite seu email" required>
                    
                    <label for="idade">
                        <img class="icon" src="https://img.icons8.com/?size=100&id=1663&format=png&color=ffffff" alt="idade"/>
                        Data Nascimento:
                    </label>
                    <input type="date" id="idade" name="idade" required>
                    
                    <label for="password">
                        <img class="icon" src="https://img.icons8.com/?size=100&id=15437&format=png&color=ffffff" alt="senha"/>
                        Senha:
                    </label>
                    <input type="password" id="password" name="password" placeholder="Digite sua senha" required>

                    <button type="submit" class="btn-submit">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
