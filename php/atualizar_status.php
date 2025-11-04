<?php
require_once 'usuario.php';

if (isset($_POST['id_usuario']) && isset($_POST['ativo'])) {
    $id_usuario = intval($_POST['id_usuario']);
    $ativo = intval($_POST['ativo']);

    $usuario = new Usuario();
    if ($usuario->atualizarStatus($id_usuario, $ativo)) {
        echo "ok";
    } else {
        echo "erro";
    }
}
?>


