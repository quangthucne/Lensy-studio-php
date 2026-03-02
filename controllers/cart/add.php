<?php
session_start();
require_once '../../config/config.php';
require_once '../../config/db.php';
require_once '../../helpers/functions.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $productId = isset($data['product_id']) ? (int)$data['product_id'] : 0;
    $quantity = isset($data['quantity']) ? (int)$data['quantity'] : 1;
    
    if ($productId <= 0 || $quantity <= 0) {
         echo json_encode(['success' => false, 'message' => 'Invalid product or quantity']);
         exit;
    }

    try {
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        $sessionId = session_id();

        // Check if item already exists
        if ($userId) {
            $checkStmt = $pdo->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
            $checkStmt->execute([$userId, $productId]);
        } else {
            $checkStmt = $pdo->prepare("SELECT id, quantity FROM cart WHERE session_id = ? AND user_id IS NULL AND product_id = ?");
            $checkStmt->execute([$sessionId, $productId]);
        }
        
        $item = $checkStmt->fetch();
        
        if ($item) {
             // Update quantity
             $newQuantity = $item['quantity'] + $quantity;
             $updateStmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
             $updateStmt->execute([$newQuantity, $item['id']]);
        } else {
             // Insert new record
             $insertStmt = $pdo->prepare("INSERT INTO cart (user_id, session_id, product_id, quantity) VALUES (?, ?, ?, ?)");
             $insertStmt->execute([$userId, $sessionId, $productId, $quantity]);
        }

        echo json_encode(['success' => true, 'message' => 'Added to cart']);

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
