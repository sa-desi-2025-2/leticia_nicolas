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


  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">


  <link rel="stylesheet" href="../css/login.css">
</head>
<body>
  <div class="container-fluid vh-100 d-flex flex-column p-0">


    <div class="top-bar d-flex justify-content-end align-items-center p-3">
      <a href="cadastro.php" class="btn btn-reactive rounded-pill px-4">Cadastrar</a>
    </div>


    <div class="main-content d-flex flex-grow-1 justify-content-center align-items-center flex-wrap gap-5">


      <div class="text-center">
        <img src="../img/logo.png" alt="Logo Checkpoint" class="logo-lateral img-fluid">
      </div>


      <div class="login-box p-4 rounded shadow-lg text-white">


        <?php if (!empty($_SESSION['login_error'])): ?>
          <div class="alert alert-danger text-center">
            <?php 
              echo $_SESSION['login_error']; 
              unset($_SESSION['login_error']);
            ?>
          </div>
        <?php endif; ?>

        <form action="verifica_login.php" method="POST">
          <div class="mb-3">
            <label for="email" class="form-label">
              <img class="icon" src="https://img.icons8.com/?size=100&id=12580&format=png&color=000000" alt="email"/> Email:
            </label>
            <input type="email" id="email" name="email" class="form-control bg-transparent text-white border-light" placeholder="Digite seu email" required>
          </div>

          <div class="mb-3">
            <label for="senha" class="form-label">
              <img class="icon" src="https://img.icons8.com/?size=100&id=107272&format=png&color=000000" alt="senha"/> Senha:
            </label>
            <input type="password" id="senha" name="senha" class="form-control bg-transparent text-white border-light" placeholder="Digite sua senha" required>
          </div>

          <button type="submit" class="btn btn-reactive w-100 mt-2 fw-bold">Entrar</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
