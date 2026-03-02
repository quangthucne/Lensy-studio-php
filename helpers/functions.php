<?php
// Helper functions

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

function getCurrentUser($pdo) {
    if (isLoggedIn()) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
    }
    return null;
}

function hasRole($pdo, $roleNames) {
    if (!isLoggedIn()) {
        return false;
    }
    
    if (!is_array($roleNames)) {
        $roleNames = [$roleNames];
    }
    
    // Create placeholders for the IN clause
    $placeholders = implode(',', array_fill(0, count($roleNames), '?'));
    
    $sql = "
        SELECT 1
        FROM roles r
        JOIN user_roles ur ON r.id = ur.role_id
        WHERE ur.user_id = ? AND r.name IN ($placeholders)
    ";
    
    $params = array_merge([$_SESSION['user_id']], $roleNames);
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch() !== false;
}

function hasPermission($pdo, $permissionSlug) {
    if (!isLoggedIn()) {
        return false;
    }

    // Admin has full system access
    if (hasRole($pdo, 'admin')) {
        return true;
    }

    $stmt = $pdo->prepare("
        SELECT 1
        FROM permissions p
        JOIN role_permissions rp ON p.id = rp.permission_id
        JOIN user_roles ur ON rp.role_id = ur.role_id
        WHERE ur.user_id = ? AND p.slug = ?
    ");
    $stmt->execute([$_SESSION['user_id'], $permissionSlug]);
    return $stmt->fetch() !== false;
}

function requireRole($pdo, $roles) {
    if (!hasRole($pdo, $roles)) {
        header("HTTP/1.0 403 Forbidden");
        $roleStr = is_array($roles) ? implode(', ', $roles) : $roles;
        echo "Access Denied. You need to be one of the following roles: $roleStr to view this page. <a href='/index.php'>Go Home</a>";
        exit();
    }
}

function requirePermission($pdo, $permission) {
    if (!hasPermission($pdo, $permission)) {
        header("HTTP/1.0 403 Forbidden");
        echo "Access Denied. You do not have permission: $permission. <a href='/index.php'>Go Home</a>";
        exit();
    }
}

function redirect($url) {
    header("Location: " . $url);
    exit();
}

/**
 * Format price to currency format (VND)
 */
function formatPrice($price) {
    return number_format($price, 0, ',', '.') . ' VND';
}

/**
 * Flash message helper
 */
function setFlashMessage($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type, // 'success', 'error', 'info', 'warning'
        'message' => $message
    ];
}

function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $msg = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $msg;
    }
    return null;
}

/**
 * Handle image upload
 * 
 * @param array $file The $_FILES['input_name'] array
 * @param string $targetDir Relative path to upload directory (default: assets/uploads/)
 * @return string Relative path to the uploaded file
 * @throws Exception If upload fails
 */
function uploadImage($file, $targetDir = 'assets/uploads/') {
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('File upload error code: ' . $file['error']);
    }

    // Validate file type
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $fileInfo = pathinfo($file['name']);
    $extension = strtolower($fileInfo['extension']);
    
    if (!in_array($extension, $allowedTypes)) {
        throw new Exception('Invalid file type. Allowed: ' . implode(', ', $allowedTypes));
    }

    // Validate file size (max 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        throw new Exception('File is too large. Max 5MB.');
    }

    // Ensure target directory exists
    // We assume this function is called from a script in 'controllers/' or root, so we need to handle paths correctly.
    // If called from 'controllers/', we might need to go up one level. 
    // Best to treat $targetDir as relative to the project root, and internal logic handles the "../" if needed.
    // However, for simplicity in this project structure where actions are in 'controllers/', let's assume we pass the path relative to where the script is running, OR we normalize.
    
    // Let's standardise: storage is always in project_root/assets/uploads.
    // Scripts in /controllers/ need to write to ../assets/uploads.
    // Scripts in root need to write to assets/uploads.
    
    // To be safe, let's use the absolute path for file operations, but return relative path for DB.
    // project root is dirname(__DIR__) since functions.php is in includes/
    $rootDir = dirname(__DIR__); 
    $absoluteTargetDir = $rootDir . '/' . trim($targetDir, '/');
    
    if (!is_dir($absoluteTargetDir)) {
        if (!mkdir($absoluteTargetDir, 0755, true)) {
            throw new Exception('Failed to create upload directory.');
        }
    }

    // Generate unique filename
    $fileName = time() . '_' . uniqid() . '.' . $extension;
    $targetPath = $absoluteTargetDir . '/' . $fileName;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        // Return path relative to project root for DB storage
        // If $targetDir was "assets/uploads/", we return "assets/uploads/filename"
        return trim($targetDir, '/') . '/' . $fileName;
    } else {
        throw new Exception('Failed to move uploaded file.');
    }
}
?>
