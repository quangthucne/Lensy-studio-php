<?php
require_once 'config/db.php';
$stmt = $pdo->query("SELECT * FROM services");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
