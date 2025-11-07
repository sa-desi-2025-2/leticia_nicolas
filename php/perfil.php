<?php
session_start();
require_once __DIR__ . '/usuario.php';


$usuario = new Usuario();

// Garante que o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['id_usuario'];

// Busca as informações atuais do usuário
$dados = $usuario->buscarPorId($id);

$fotoPerfil = $dados['foto_perfil'] ?? '../uploads/default.png';
$nome = $dados['nome_usuario'] ?? '';
$email = $dados['email_usuario'] ?? '';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkpoint - Perfil do Usuário</title>
    <link rel="stylesheet" href="../css/perfil.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

    <!-- TOPO -->
    <div class="topo">
        <div class="logo">
            <img src="../img/logo.png" alt="Checkpoint Logo">
             <a href="pagina_principal.php">                                                  <!--  ARRUMAR -->
                <img src="https://img.icons8.com/?size=100&id=14096&format=png&color=000000" alt="home">
            </a>
        </div>

        <div class="user-menu">
            <div class="user-icon">
                <img src="<?php echo htmlspecialchars($fotoPerfil); ?>" alt="Usuário">
            </div>
        </div>
    </div>

    <!-- CONTEÚDO PRINCIPAL -->
    <main>
        <div class="perfil-container">
            <div class="perfil-box">

                <!-- Lado esquerdo: nome, email, senha -->
                <div class="perfil-info">
                    
                    <!-- Atualizar nome e email -->
                    <form action="atualizar_perfil.php" method="POST">
                        <input type="hidden" name="id_usuario" value="<?php echo $id; ?>">

                        <div class="linha-campos">
                            <div class="campo">
                                <label for="nome">Nome</label>
                                <input type="text" id="nome" name="nome" 
                                       value="<?php echo htmlspecialchars($nome); ?>" required>
                            </div>

                            <div class="campo">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($email); ?>" required>
                            </div>
                        </div>

                        <button type="submit" class="btn-alterar">Alterar Dados</button>
                    </form>

                    <!-- Atualizar senha -->
                    <form action="atualizar_senha.php" method="POST" class="alterar-senha">
                        <input type="hidden" name="id_usuario" value="<?php echo $id; ?>">

                        <label for="nova_senha">Nova Senha</label>
                        <input type="password" id="nova_senha" name="nova_senha" required>

                        <label for="confirmar_senha">Confirmar Nova Senha</label>
                        <input type="password" id="confirmar_senha" name="confirmar_senha" required>

                        <button type="submit" class="btn-alterar">Alterar Senha</button>
                    </form>
                </div>

                <!-- Lado direito: foto -->
                <div class="perfil-foto">
                    <form action="atualizar_foto.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_usuario" value="<?php echo $id; ?>">
                        <img src="<?php echo htmlspecialchars($fotoPerfil); ?>" 
                             alt="Foto de perfil" class="foto-perfil">
                        <input type="file" name="foto" accept="image/*" required>
                        <button type="submit" class="btn-alterar">Alterar Foto</button>
                    </form>
                </div>

            </div>
        </div>
    </main>
</body>
</html>
