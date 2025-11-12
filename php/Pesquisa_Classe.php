<?php
require_once 'conexao.php';

class Pesquisa {
    private $conn;
    private $itensPorPagina = 10;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function setItensPorPagina($num) {
        $this->itensPorPagina = (int)$num;
    }

    public function getItensPorPagina() {
        return $this->itensPorPagina;
    }

    // === BUSCA DE USUÁRIOS ===
    public function buscarUsuarios($termo, $pagina = 1) {
        $termo_esc = $this->conn->real_escape_string($termo);
        $offset = ($pagina - 1) * $this->itensPorPagina;

        $sql = "SELECT id_usuario, nome_usuario 
                FROM usuarios 
                WHERE nome_usuario LIKE '%$termo_esc%'";

        // Se não for admin, exibe apenas usuários ativos (se existir a coluna)
        if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] !== 'admin') {
            $sql .= " AND (ativo = 1 OR ativo IS NULL)";
        }

        $sql .= " LIMIT {$this->itensPorPagina} OFFSET $offset";

        $result = $this->conn->query($sql);
        $usuarios = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $usuarios[] = $row;
            }
        }

        return $usuarios;
    }

    public function totalUsuarios($termo = '') {
        $termo_esc = $this->conn->real_escape_string($termo);
        $sql = "SELECT COUNT(*) AS total FROM usuarios WHERE nome_usuario LIKE '%$termo_esc%'";

        if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] !== 'admin') {
            $sql .= " AND (ativo = 1 OR ativo IS NULL)";
        }

        $result = $this->conn->query($sql);
        $data = $result->fetch_assoc();
        return intval($data['total']);
    }

    // === BUSCA DE COMUNIDADES ===
    public function buscarComunidades($termo, $pagina = 1) {
        $termo_esc = $this->conn->real_escape_string($termo);
        $offset = ($pagina - 1) * $this->itensPorPagina;

        $sql = "SELECT id_comunidade, nome_comunidade 
                FROM comunidades 
                WHERE nome_comunidade LIKE '%$termo_esc%' 
                LIMIT {$this->itensPorPagina} OFFSET $offset";

        $result = $this->conn->query($sql);
        $comunidades = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $comunidades[] = $row;
            }
        }

        return $comunidades;
    }

    public function totalComunidades($termo = '') {
        $termo_esc = $this->conn->real_escape_string($termo);
        $sql = "SELECT COUNT(*) AS total FROM comunidades WHERE nome_comunidade LIKE '%$termo_esc%'";
        $result = $this->conn->query($sql);
        $data = $result->fetch_assoc();
        return intval($data['total']);
    }
}
?>
