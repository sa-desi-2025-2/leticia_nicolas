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
  <link rel="stylesheet" href="../leticia_nicolas/css/login.css">
</head>
<body>
  <div class="container-fluid vh-100 d-flex flex-column p-0">

    <!-- Barra superior -->
    <header class="d-flex justify-content-end align-items-center p-3">
      <a href="cadastro.html" class="btn btn-outline-light rounded-pill px-4">Cadastrar</a>
    </header>
    <!--  -->

    <!-- Área de login -->
    <div class="d-flex flex-grow-1 justify-content-center align-items-center flex-wrap gap-5">
      <!--  -->

      <!-- Logo lateral -->
      <div class="text-center">
        <img src="logo.png" alt="Logo Checkpoint" class="logo-lateral img-fluid">
      </div>
      <!--  -->

      <!-- Caixa de login -->
      <div class="login-box p-4 rounded shadow-lg">
      <!--  -->

        <!-- Exibe erro se existir -->
        <?php if (!empty($_SESSION['login_error'])): ?>
          <div class="alert alert-danger text-center">
            <?php 
              echo $_SESSION['login_error']; 
              unset($_SESSION['login_error']);
            ?>
          </div>
        <?php endif; ?>
        <!-- fim da verificacao de erro -->

        <!-- email -->
        <form action="verifica_login.php" method="POST" class="text-white"> <!-- action manda as informacoes para verifica_login -->
          <div class="mb-3">
            <label for="nome" class="form-label">
              <img class="icon" src="https://img.icons8.com/?size=100&id=11730&format=png&color=ffffff" alt="email"/> Email:
            </label>
            <input type="email" id="email" name="email" class="form-control bg-transparent text-white border-light" placeholder="Digite seu email" required>
          </div>
        <!-- email -->

        <!-- senha -->
          <div class="mb-3">
            <label for="email" class="form-label">
              <img class="icon" src="https://img.icons8.com/?size=100&id=Ww1lcGqgduif&format=png&color=ffffff" alt="senha"/> Senha:
            </label>
            <input type="password" id="senha" name="senha" class="form-control bg-transparent text-white border-light" placeholder="Digite sua senha" required>
          </div>
        <!-- senha -->

        <!-- botao para fazer login -->
        <button type="submit" class="btn btn-reactive w-100 mt-2 fw-bold">
            Entrar
          </button>
        <!-- botao para fazer login -->
         
        </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
