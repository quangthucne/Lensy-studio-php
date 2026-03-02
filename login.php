<?php
require_once 'config/db.php';
require_once 'helpers/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Vui lòng nhập email và mật khẩu.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            if ($user['status'] !== 'active') {
                $error = "Tài khoản của bạn đã bị khóa.";
            } else {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                redirect('index.php');
            }
        } else {
            $error = "Email hoặc mật khẩu không đúng.";
        }
    }
}
?>
<?php include 'components/head.php'; ?>
<?php include 'components/header.php'; ?>

<main class="w-full bg-background text-foreground min-h-screen flex items-center justify-center py-20">
    <div class="w-full max-w-md p-8 space-y-8 bg-card rounded-lg shadow-lg border border-border">
        <div class="text-center">
            <h1 class="text-3xl font-bold">Đăng Nhập</h1>
            <p class="text-muted-foreground mt-2">Chào mừng trở lại Lensy Studio</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-destructive/10 text-destructive p-3 rounded-md text-sm text-center">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form class="space-y-6" method="POST">
            <div>
                <label for="email" class="block text-sm font-medium mb-2">Email</label>
                <input type="email" id="email" name="email" required 
                    class="w-full px-4 py-2 rounded-md border border-border bg-background focus:ring-2 focus:ring-primary focus:outline-none">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium mb-2">Mật Khẩu</label>
                <input type="password" id="password" name="password" required 
                    class="w-full px-4 py-2 rounded-md border border-border bg-background focus:ring-2 focus:ring-primary focus:outline-none">
            </div>

            <button type="submit" class="w-full py-3 px-4 bg-primary text-primary-foreground font-semibold rounded-md hover:bg-primary/90 transition-colors">
                Đăng Nhập
            </button>
        </form>

        <p class="text-center text-sm text-muted-foreground">
            Chưa có tài khoản? 
            <a href="register.php" class="text-primary hover:underline font-medium">Đăng ký ngay</a>
        </p>
    </div>
</main>

<?php include 'components/footer.php'; ?>
