     <?php


     require_once __DIR__ . '/usuario.php'; 

     header('Content-Type: application/json; charset=utf-8');

     if (isset($_POST['id']) && isset($_POST['status'])) {
         $id = (int) $_POST['id'];
         $status = (int) $_POST['status'];

         $usuario = new Usuario();
         $resultado = $usuario->alterarStatus($id, $status);

         if ($resultado) {
             echo json_encode(['success' => true]);
         } else {
             echo json_encode(['success' => false, 'error' => 'Erro ao atualizar no banco. Verifique a conexão ou SQL.']);
         }
     } else {
         echo json_encode(['success' => false, 'error' => 'Dados inválidos: id ou status ausentes.']);
     }
     ?>
     