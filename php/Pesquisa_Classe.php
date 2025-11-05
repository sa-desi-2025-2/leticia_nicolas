<?php
require_once 'conexao.php';

class Pesquisa {
    private $conn;
    private $itensPorPagina = 10; // padrão, pode alterar dinamicamente

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Busca usuários paginados
    public function buscarUsuarios($termo, $pagina = 1) {
        $offset = ($pagina - 1) * $this->itensPorPagina;
        $termo_esc = $this->conn->real_escape_string($termo);

        $sql = "SELECT SQL_CALC_FOUND_ROWS id_usuario, nome_usuario 
                FROM usuarios 
                WHERE nome_usuario LIKE '%$termo_esc%' 
                LIMIT $offset, $this->itensPorPagina";

        $result = $this->conn->query($sql);

        $usuarios = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $usuarios[] = $row;
            }
        }
        return $usuarios;
    }

    // Retorna o total de usuários encontrados para a busca (para paginação)
    public function totalUsuarios() {
        $result = $this->conn->query("SELECT FOUND_ROWS() as total");
        $data = $result->fetch_assoc();
        return intval($data['total']);
    }

    // Busca comunidades paginadas
    public function buscarComunidades($termo, $pagina = 1) {
        $offset = ($pagina - 1) * $this->itensPorPagina;
        $termo_esc = $this->conn->real_escape_string($termo);

        $sql = "SELECT SQL_CALC_FOUND_ROWS id_comunidade, nome_comunidade 
                FROM comunidades 
                WHERE nome_comunidade LIKE '%$termo_esc%' 
                LIMIT $offset, $this->itensPorPagina";

        $result = $this->conn->query($sql);

        $comunidades = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $comunidades[] = $row;
            }
        }
        return $comunidades;
    }

    // Retorna o total de comunidades para paginação
    public function totalComunidades() {
        $result = $this->conn->query("SELECT FOUND_ROWS() as total");
        $data = $result->fetch_assoc();
        return intval($data['total']);
    }

    // Definir quantos itens por página (opcional)
    public function setItensPorPagina($num) {
        $this->itensPorPagina = (int)$num;
    }

    public function getItensPorPagina() {
        return $this->itensPorPagina;
    }
}
