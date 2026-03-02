<?php require_once 'components/header.php'; ?>
<?php requirePermission($pdo, 'manage_users'); ?>
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Quản lý Người Dùng</h2>
    <p class="text-gray-600">Quản lý tài khoản và phân quyền.</p>
</div>

<?php
// Fetch all roles for dropdown
$rolesStmt = $pdo->query("SELECT * FROM roles");
$allRoles = $rolesStmt->fetchAll();

$search = trim($_GET['search'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;

$whereClause = '';
$params = [];
if ($search !== '') {
    $whereClause = "WHERE u.full_name LIKE ? OR u.email LIKE ? OR u.phone LIKE ?";
    $params = ["%$search%", "%$search%", "%$search%"];
}

// Count total
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM users u $whereClause");
$countStmt->execute($params);
$totalRecords = $countStmt->fetchColumn();
$totalPages = ceil($totalRecords / $limit);

// Fetch users
$stmt = $pdo->prepare("
    SELECT u.*, MAX(r.id) as role_id, MAX(r.name) as role_slug, MAX(r.display_name) as role_name
    FROM users u
    LEFT JOIN user_roles ur ON u.id = ur.user_id
    LEFT JOIN roles r ON ur.role_id = r.id
    $whereClause
    GROUP BY u.id
    ORDER BY u.id DESC
    LIMIT $limit OFFSET $offset
");
$stmt->execute($params);
?>

<form method="GET" class="flex gap-2 mb-4">
    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Tìm tên, email, sđt..." class="border border-gray-300 rounded-md p-2 text-sm w-64 focus:ring-indigo-500 focus:border-indigo-500">
    <button type="submit" class="bg-indigo-600 border border-transparent text-white rounded-md px-4 py-2 text-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Tìm kiếm</button>
    <?php if($search): ?>
        <a href="users.php" class="text-sm text-gray-500 self-center hover:text-gray-700 underline">Xóa bộ lọc</a>
    <?php endif; ?>
</form>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Người Dùng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Liên Hệ</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vai Trò</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if ($stmt->rowCount() > 0): ?>
                <?php while ($user = $stmt->fetch()): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        #<?php echo $user['id']; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="h-10 w-10 flex-shrink-0">
                                <?php if (!empty($user['avatar_url'])): ?>
                                    <img class="h-10 w-10 rounded-full object-cover" src="../<?php echo htmlspecialchars($user['avatar_url']); ?>" alt="">
                                <?php else: ?>
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-600">
                                        <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($user['full_name']); ?></div>
                                <div class="text-xs text-gray-500">Tham gia: <?php echo date('d/m/Y', strtotime($user['created_at'])); ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900"><?php echo htmlspecialchars($user['email']); ?></div>
                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($user['phone']); ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php if ($user['role_slug'] === 'admin'): ?>
                            <span class="text-gray-500 font-medium flex items-center">
                                <i data-lucide="shield" class="w-4 h-4 mr-1"></i> Quản trị viên
                            </span>
                        <?php else: ?>
                        <form action="../controllers/admin_update_user_role.php" method="POST" class="flex items-center gap-2">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <select name="role_id" class="text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-1">
                                <?php foreach ($allRoles as $role): ?>
                                    <option value="<?php echo $role['id']; ?>" <?php echo ($user['role_id'] == $role['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($role['display_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Cập nhật</button>
                        </form>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                         <?php if ($user['id'] != $_SESSION['user_id']): ?>
                            <a href="#" class="text-red-600 hover:text-red-900 ml-3">Vô hiệu hóa</a>
                        <?php else: ?>
                            <span class="text-gray-400 italic">Tài khoản của bạn</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr>
                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Không tìm thấy người dùng nào.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalPages > 1): ?>
    <div class="px-6 py-4 border-t border-gray-200">
        <nav class="flex items-center justify-between">
            <div class="hidden sm:block">
                <p class="text-sm text-gray-700">
                    Hiển thị trang <span class="font-medium"><?php echo $page; ?></span> trên <span class="font-medium"><?php echo $totalPages; ?></span>
                </p>
            </div>
            <div class="flex-1 flex justify-between sm:justify-end">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Trang trước
                    </a>
                <?php endif; ?>
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Trang sau
                    </a>
                <?php endif; ?>
            </div>
        </nav>
    </div>
    <?php endif; ?>
</div>
<?php require_once 'components/footer.php'; ?>
