<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Adjust paths since we are in admin/includes
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../helpers/functions.php';

// Force Admin or Staff Check
requireRole($pdo, ['admin', 'staff']);

$currentUser = getCurrentUser($pdo);
$current_page = basename($_SERVER['PHP_SELF'], ".php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lensy Studio - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#0f172a', // Slate 900 for Admin
                        secondary: '#3b82f6', // Blue 500
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 min-h-screen flex">
    <!-- Sidebar (includedin index/layout usually, but let's separate) -->
    <?php include __DIR__ . '/sidebar.php'; ?>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Top Header -->
        <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-6">
            <h1 class="text-xl font-bold text-gray-800">Admin Console</h1>
            
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <?php if (!empty($currentUser['avatar_url'])): ?>
                        <img class="h-8 w-8 rounded-full object-cover" src="../<?php echo htmlspecialchars($currentUser['avatar_url']); ?>" alt="">
                    <?php else: ?>
                        <div class="h-8 w-8 rounded-full bg-primary text-white flex items-center justify-center font-bold">
                            <?php echo strtoupper(substr($currentUser['full_name'], 0, 1)); ?>
                        </div>
                    <?php endif; ?>
                    <span class="text-sm font-medium text-gray-700"><?php echo htmlspecialchars($currentUser['full_name']); ?></span>
                </div>
                <a href="../logout.php" class="text-sm text-red-600 hover:text-red-800 font-medium">Logout</a>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto p-6">
