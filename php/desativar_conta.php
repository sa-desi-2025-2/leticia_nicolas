<?php
session_start();
require_once 'usuario.php';


if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$usuario = new Usuario();

