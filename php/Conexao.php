<?php
class Conexao {
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $db = "checkpoint_sa";
    private $con;

    public function __construct() {
        $this->con = new mysqli($this->host, $this->user, $this->pass, $this->db);
        if ($this->con->connect_error) {
            die("Erro de conexão: " . $this->con->connect_error);
        }
    }

    public function getCon() {
        return $this->con;
    }
}
?>