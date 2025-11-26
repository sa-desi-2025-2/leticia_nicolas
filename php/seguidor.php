<?php
require_once __DIR__ . '/conexao.php';

class Seguidor {
    private $conn;

    public function __construct() {
        $db = new Conexao();
        $this->conn = $db->getCon();
    }


    public function seguir($idSeguidor, $idSeguido) {
        if ($idSeguidor === $idSeguido) {
            return ['status' => 'erro', 'mensagem' => 'Você não pode seguir a si mesmo.'];
        }


        $stmt = $this->conn->prepare("SELECT 1 FROM seguidores WHERE id_seguidor = ? AND id_seguindo = ?");
        $stmt->bind_param("ii", $idSeguidor, $idSeguido);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
       
            $stmtDel = $this->conn->prepare("DELETE FROM seguidores WHERE id_seguidor = ? AND id_seguindo = ?");
            $stmtDel->bind_param("ii", $idSeguidor, $idSeguido);
            $stmtDel->execute();

            return ['status' => 'ok', 'seguindo' => false];
        } else {
       
            $stmtIns = $this->conn->prepare("INSERT INTO seguidores (id_seguidor, id_seguindo) VALUES (?, ?)");
            $stmtIns->bind_param("ii", $idSeguidor, $idSeguido);
            $stmtIns->execute();

            return ['status' => 'ok', 'seguindo' => true];
        }
    }

    public function listarSeguidos($idUsuario) {
        $sql = "
            SELECT u.id_usuario, u.nome_usuario, u.foto_perfil
            FROM seguidores s
            JOIN usuarios u ON s.id_seguindo = u.id_usuario
            WHERE s.id_seguidor = ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

   
    public function verificaSeguindo($idSeguidor, $idSeguido) {
        $stmt = $this->conn->prepare("SELECT 1 FROM seguidores WHERE id_seguidor = ? AND id_seguindo = ?");
        $stmt->bind_param("ii", $idSeguidor, $idSeguido);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
    
}
?>
