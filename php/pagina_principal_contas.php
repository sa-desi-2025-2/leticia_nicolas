<?php
require_once __DIR__ . '/usuario.php';

$usuario = new Usuario();
$usuarios = $usuario->listarUsuarios(); // busca todos os usuários
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkpoint - Gerenciamento de Usuários</title>

    <link rel="stylesheet" href="../css/usuarios.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<div class="topo">
    <div class="logo">
        <img src="../img/logo.png" alt="Checkpoint Logo">
        <a href="pagina_principal_adm.php">
            <img src="https://img.icons8.com/?size=100&id=14096&format=png&color=000000" alt="home">
        </a>
    </div>

    <div class="user-menu">
        <div class="user-icon">
            <img src="https://img.icons8.com/?size=100&id=65342&format=png&color=000000" alt="Usuário">
        </div>
    </div>
</div>

<main class="content">
    <h2 class="titulo">Gerenciamento de Usuários</h2>

    <div class="user-list">
    <?php foreach ($usuarios as $user): ?>
    <div class="user-card">
        <div class="user-info">
            <i class="bi bi-person-circle"></i>
            <p class="user-name"><?= htmlspecialchars($user['nome_usuario']) ?></p>
        </div>

        <!-- Botão de ativar/desativar com ID único -->
        <button 
            id="btnUsuario<?= $user['id_usuario'] ?>" 
            class="<?= $user['ativo'] == 1 ? 'btn-desativar' : 'btn-ativar' ?>" 
            data-id="<?= $user['id_usuario'] ?>"
        >
            <?= $user['ativo'] == 1 ? 'Desativar' : 'Ativar' ?>
        </button>
    </div>
<?php endforeach; ?>
    </div>
</main>

<script src="../js/adm.js"></script>
</body>
</html>



<!-- meuBotao =  document.getElementById("myBtn");

if (meuBotao.disable === true){
	meuBotao.disable = false
}else{
	meuBotao.disable = true
} -->
