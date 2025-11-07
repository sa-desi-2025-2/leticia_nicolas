<?php
require_once 'conexao.php';

class Pesquisa {
    private $conn;
    private $itensPorPagina = 10; // padrão

    public function __construct($conn) {
        $this->conn = $conn;
    }


    //  BUSCA DE USUÁRIOS
 
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

    public function totalUsuarios() {
        $result = $this->conn->query("SELECT FOUND_ROWS() as total");
        $data = $result->fetch_assoc();
        return intval($data['total']);
    }

    //  BUSCA DE COMUNIDADES

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

    public function totalComunidades() {
        $result = $this->conn->query("SELECT FOUND_ROWS() as total");
        $data = $result->fetch_assoc();
        return intval($data['total']);
    }


    //  CONFIGURAÇÃO DE PAGINAÇÃO
  
    public function setItensPorPagina($num) {
        $this->itensPorPagina = (int)$num;
    }

    public function getItensPorPagina() {
        return $this->itensPorPagina;
    }


    //BUSCA DE SEGUIDOS (quem o usuário logado segue)
    
    public function buscarSeguidos($idUsuario, $termo = '', $pagina = 1) {
        $offset = ($pagina - 1) * $this->itensPorPagina;
        $termoLike = '%' . $termo . '%';

        $sql = "
            SELECT u.id_usuario, u.nome_usuario, u.foto_perfil
            FROM seguidores s
            JOIN usuarios u ON s.id_seguindo = u.id_usuario
            WHERE s.id_seguidor = ?
            AND u.nome_usuario LIKE ?
            LIMIT ? OFFSET ?
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('isii', $idUsuario, $termoLike, $this->itensPorPagina, $offset);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function totalSeguidos($idUsuario, $termo = '') {
        $termoLike = '%' . $termo . '%';
        $sql = "
            SELECT COUNT(*) AS total
            FROM seguidores s
            JOIN usuarios u ON s.id_seguindo = u.id_usuario
            WHERE s.id_seguidor = ?
            AND u.nome_usuario LIKE ?
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('is', $idUsuario, $termoLike);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'] ?? 0;
    }

    // BUSCA DE SEGUIDORES (quem segue o usuário logado)

    public function buscarSeguidores($idUsuario, $termo = '', $pagina = 1) {
        $offset = ($pagina - 1) * $this->itensPorPagina;
        $termoLike = '%' . $termo . '%';

        $sql = "
            SELECT u.id_usuario, u.nome_usuario, u.foto_perfil
            FROM seguidores s
            JOIN usuarios u ON s.id_seguidor = u.id_usuario
            WHERE s.id_seguindo = ?
            AND u.nome_usuario LIKE ?
            LIMIT ? OFFSET ?
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('isii', $idUsuario, $termoLike, $this->itensPorPagina, $offset);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function totalSeguidores($idUsuario, $termo = '') {
        $termoLike = '%' . $termo . '%';
        $sql = "
            SELECT COUNT(*) AS total
            FROM seguidores s
            JOIN usuarios u ON s.id_seguidor = u.id_usuario
            WHERE s.id_seguindo = ?
            AND u.nome_usuario LIKE ?
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('is', $idUsuario, $termoLike);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'] ?? 0;
    }
}
