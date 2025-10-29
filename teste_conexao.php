<?php
require_once 'conexao.php';

try {
    $conexao = new Conexao();
    echo "✅ Conexão bem-sucedida com o banco de dados!";
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage();
}
