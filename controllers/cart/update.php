<?php
session_start();
require_once '../../config/config.php';
require_once '../../config/db.php';
require_once '../../helpers/functions.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $cartId = isset($data['cart_id']) ? (int)$data['cart_id'] : 0;
    $quantity = isset($data['quantity']) ? (int)$data['quantity'] : 1;
    
    if ($cartId <= 0 || $quantity <= 0) {
         echo json_encode(['success' => false, 'message' => 'Invalid cart item or quantity']);
         exit;
    }

    try {
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        $sessionId = session_id();

        // Verify ownership
        if ($userId) {
            $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
            $success = $stmt->execute([$quantity, $cartId, $userId]);
        } else {
            $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND session_id = ? AND user_id IS NULL");
            $success = $stmt->execute([$quantity, $cartId, $sessionId]);
        }
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Cart updated']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Cart item not found or not owned by you']);
        }

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
