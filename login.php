<?php
// Inicia a sessão (caso queira armazenar login futuramente)
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
 
  <link rel="stylesheet" href="login.css">
</head>
<body>
  <div class="container">
    <!-- Barra superior -->
    <header>
      <div class="logo"></div>
      <a href="cadastro.html">
        <button type="button" class="btn">cadastrar</button>
      </a>
    </header>


    <div class="login-area">
      <!-- Logo à esquerda -->
      <div class="logo-lateral">
        <img src="logo.png
        " alt="Logo Checkpoint">
      </div>

      <!-- Tela de login -->
      <div class="login-box">
        <form action="verifica_login.php" method="POST">
          
          <label for="nome">
            <img class="icon" src="https://img.icons8.com/?size=100&id=11730&format=png&color=000000" alt="nome"/>
            Nome:
          </label>
          <input type="text" id="nome" name="nome" placeholder="Digite seu nome" required>

          <label for="email">
            <img class="icon" src="https://img.icons8.com/?size=100&id=Ww1lcGqgduif&format=png&color=000000" alt="email"/>
            Email:
          </label>
          <input type="email" id="email" name="email" placeholder="Digite seu email" required>

          <button type="submit" class="btn-submit">Entrar</button>

          <!-- Link Esqueceu a senha -->
          <div class="forgot-password">
            <a href="recuperar_senha.php">Esqueceu a senha?</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
