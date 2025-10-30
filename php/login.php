<?php
session_start();

require_once __DIR__ . '/conexao.php';

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- CSS personalizado -->
  <link rel="stylesheet" href="../css/login.css">
</head>
<body>
  <div class="container-fluid vh-100 d-flex flex-column p-0">

    <!-- Barra superior -->
    <header class="d-flex justify-content-end align-items-center p-3">
      <a href="cadastro.php" class="btn btn-outline-light rounded-pill px-4">Cadastrar</a>
    </header>

    <!-- Área de login -->
    <div class="d-flex flex-grow-1 justify-content-center align-items-center flex-wrap gap-5">
      
      <!-- Logo lateral -->
      <div class="text-center">
        <img src="logo.png" alt="Logo Checkpoint" class="logo-lateral img-fluid">
      </div>

      <!-- Caixa de login -->
      <div class="login-box p-4 rounded shadow-lg">
        <form action="verifica_login.php" method="POST" class="text-white">
          <div class="mb-3">
            <label for="nome" class="form-label">
              <img class="icon" src="https://img.icons8.com/?size=100&id=11730&format=png&color=ffffff" alt="nome"/> Email:
            </label>
            <input type="email" id="email" name="email" class="form-control bg-transparent text-white border-light" placeholder="Digite seu nome" required>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">
              <img class="icon" src="https://img.icons8.com/?size=100&id=Ww1lcGqgduif&format=png&color=ffffff" alt="senha"/> Senha:
            </label>
            <input type="password" id="senha" name="senha" class="form-control bg-transparent text-white border-light" placeholder="Digite sua senha" required>
          </div>

          <button type="submit" class="btn btn-reactive w-100 mt-2 fw-bold">
  Entrar
</button>


          <div class="text-center mt-3">
            <a href="recuperar_senha.php" class="text-info text-decoration-none">Esqueceu a senha?</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
