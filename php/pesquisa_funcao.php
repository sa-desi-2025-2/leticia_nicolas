<?php
require_once 'Pesquisa_Classe.php';
require_once 'conexao.php';

function executarPesquisa($termo, $paginaUsuarios = 1, $paginaComunidades = 1, $itensPorPagina = 5) {
    $conexao = new Conexao();
    $conn = $conexao->getCon();

    $pesquisa = new Pesquisa($conn);
    $pesquisa->setItensPorPagina($itensPorPagina);

    $usuarios = [];
    $comunidades = [];
    $totalUsuarios = 0;
    $totalComunidades = 0;

    if (trim($termo) !== '') {
        $usuarios = $pesquisa->buscarUsuarios($termo, $paginaUsuarios);
        $totalUsuarios = $pesquisa->totalUsuarios();

        $comunidades = $pesquisa->buscarComunidades($termo, $paginaComunidades);
        $totalComunidades = $pesquisa->totalComunidades();
    }

    return [
        'usuarios' => $usuarios,
        'comunidades' => $comunidades,
        'totalUsuarios' => $totalUsuarios,
        'totalComunidades' => $totalComunidades
    ];
}
?>
