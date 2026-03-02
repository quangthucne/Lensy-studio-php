<?php
require_once 'config/db.php';
require_once 'helpers/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['fullName']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if (empty($fullName) || empty($email) || empty($password)) {
        $error = "Vui lòng điền đầy đủ thông tin.";
    } elseif ($password !== $confirmPassword) {
        $error = "Mật khẩu không khớp.";
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email này đã được đăng ký.";
        } else {
            // Create user
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password_hash) VALUES (?, ?, ?)");
            try {
                $stmt->execute([$fullName, $email, $hash]);
                $userId = $pdo->lastInsertId();
                
                // Assign 'customer' role default
                $roleStmt = $pdo->prepare("SELECT id FROM roles WHERE name = 'customer'");
                $roleStmt->execute();
                $roleId = $roleStmt->fetchColumn();
                
                if ($roleId) {
                    $assignStmt = $pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
                    $assignStmt->execute([$userId, $roleId]);
                }

                $_SESSION['user_id'] = $userId;
                $_SESSION['user_name'] = $fullName;
                redirect('index.php');
            } catch (PDOException $e) {
                $error = "Lỗi đăng ký: " . $e->getMessage();
            }
        }
    }
}
?>
<?php include 'components/head.php'; ?>
<?php include 'components/header.php'; ?>

<main class="w-full bg-background text-foreground min-h-screen flex items-center justify-center py-20">
    <div class="w-full max-w-md p-8 space-y-8 bg-card rounded-lg shadow-lg border border-border">
        <div class="text-center">
            <h1 class="text-3xl font-bold">Đăng Ký</h1>
            <p class="text-muted-foreground mt-2">Tạo tài khoản mới tại Lensy Studio</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-destructive/10 text-destructive p-3 rounded-md text-sm text-center">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form class="space-y-6" method="POST">
            <div>
                <label for="fullName" class="block text-sm font-medium mb-2">Họ và Tên</label>
                <input type="text" id="fullName" name="fullName" required value="<?php echo htmlspecialchars($fullName ?? ''); ?>"
                    class="w-full px-4 py-2 rounded-md border border-border bg-background focus:ring-2 focus:ring-primary focus:outline-none">
            </div>
            
            <div>
                <label for="email" class="block text-sm font-medium mb-2">Email</label>
                <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($email ?? ''); ?>"
                    class="w-full px-4 py-2 rounded-md border border-border bg-background focus:ring-2 focus:ring-primary focus:outline-none">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium mb-2">Mật Khẩu</label>
                <input type="password" id="password" name="password" required 
                    class="w-full px-4 py-2 rounded-md border border-border bg-background focus:ring-2 focus:ring-primary focus:outline-none">
            </div>

            <div>
                <label for="confirmPassword" class="block text-sm font-medium mb-2">Nhập Lại Mật Khẩu</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required 
                    class="w-full px-4 py-2 rounded-md border border-border bg-background focus:ring-2 focus:ring-primary focus:outline-none">
            </div>

            <button type="submit" class="w-full py-3 px-4 bg-primary text-primary-foreground font-semibold rounded-md hover:bg-primary/90 transition-colors">
                Đăng Ký
            </button>
        </form>

        <p class="text-center text-sm text-muted-foreground">
            Đã có tài khoản? 
            <a href="login.php" class="text-primary hover:underline font-medium">Đăng nhập</a>
        </p>
    </div>
</main>

<?php include 'components/footer.php'; ?>
