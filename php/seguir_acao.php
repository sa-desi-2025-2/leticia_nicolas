<?php
require_once __DIR__ . '/conexao.php';

class Seguidor {
    private $conn;

    public function __construct() {
        $db = new Conexao();
        $this->conn = $db->getCon();
    }

    public function seguir($idSeguidor, $idSeguido, $tipo = 'usuario') {
        if ($tipo === 'usuario') {
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
                return ['status' => 'ok', 'seguindo' => false, 'tipo' => 'usuario'];
            } else {
                $stmtIns = $this->conn->prepare("INSERT INTO seguidores (id_seguidor, id_seguindo) VALUES (?, ?)");
                $stmtIns->bind_param("ii", $idSeguidor, $idSeguido);
                $stmtIns->execute();
                return ['status' => 'ok', 'seguindo' => true, 'tipo' => 'usuario'];
            }

        } elseif ($tipo === 'comunidade') {
            // Usa a tabela usuarios_comunidades
            $stmt = $this->conn->prepare("SELECT 1 FROM usuarios_comunidades WHERE id_usuario = ? AND id_comunidade = ?");
            $stmt->bind_param("ii", $idSeguidor, $idSeguido);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Já participa → sair
                $stmtDel = $this->conn->prepare("DELETE FROM usuarios_comunidades WHERE id_usuario = ? AND id_comunidade = ?");
                $stmtDel->bind_param("ii", $idSeguidor, $idSeguido);
                $stmtDel->execute();
                return ['status' => 'ok', 'seguindo' => false, 'tipo' => 'comunidade'];
            } else {
                // Ainda não participa → entrar
                $stmtIns = $this->conn->prepare("INSERT INTO usuarios_comunidades (id_usuario, id_comunidade) VALUES (?, ?)");
                $stmtIns->bind_param("ii", $idSeguidor, $idSeguido);
                $stmtIns->execute();
                return ['status' => 'ok', 'seguindo' => true, 'tipo' => 'comunidade'];
            }
        }

        return ['status' => 'erro', 'mensagem' => 'Tipo inválido.'];
    }
}

// === AÇÃO DIRETA ===
session_start();
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Usuário não autenticado.']);
    exit;
}

$idSeguidor = $_SESSION['id_usuario'];
$idSeguido = intval($_POST['id_seguido'] ?? 0);
$tipo = $_POST['tipo'] ?? 'usuario';

$seguidor = new Seguidor();
$resultado = $seguidor->seguir($idSeguidor, $idSeguido, $tipo);

header('Content-Type: application/json');
echo json_encode($resultado);
