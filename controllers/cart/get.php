<?php
session_start();
require_once '../../config/config.php';
require_once '../../config/db.php';
require_once '../../helpers/functions.php';

header('Content-Type: application/json');

try {
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $sessionId = session_id();

    if ($userId) {
        $stmt = $pdo->prepare("
            SELECT c.id as cart_id, p.id as product_id, p.name, p.rental_price_per_day as price, p.image_url as image, c.quantity
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id = ?
            ORDER BY c.created_at ASC
        ");
        $stmt->execute([$userId]);
    } else {
        $stmt = $pdo->prepare("
            SELECT c.id as cart_id, p.id as product_id, p.name, p.rental_price_per_day as price, p.image_url as image, c.quantity
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.session_id = ? AND c.user_id IS NULL
            ORDER BY c.created_at ASC
        ");
        $stmt->execute([$sessionId]);
    }
    
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $cartItems]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
