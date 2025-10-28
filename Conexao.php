<?php
class Conexao extends PDO
{
    private $nome;
    private $usuario;
    private $senha;
    private $servidor;

    public function __construct()
    {
        $this->nome = 'checkpoint_sa';
        $this->usuario = 'root';
        $this->senha = '';
        $this->servidor = 'localhost';

        $dsn = "mysql:host={$this->servidor};dbname={$this->nome};charset=utf8";

        try {
            parent::__construct($dsn, $this->usuario, $this->senha);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo("SUCESSO!");
            exit();
        } catch (PDOException $erro) {
            die("ERRO NA CONEXÃO: " . $erro->getMessage());
        }
    }
}
