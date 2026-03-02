<?php include 'components/head.php'; ?>
<?php include 'components/header.php'; ?>

<?php
if (!isLoggedIn()) {
    redirect('login.php');
}

$user = getCurrentUser($pdo);
?>

<main class="w-full bg-background text-foreground min-h-screen py-10">
    <div class="container mx-auto px-4 max-w-3xl">
        <h1 class="text-3xl font-bold mb-8">My Profile</h1>

        <div class="bg-card border border-border rounded-lg shadow-sm p-6">
            <div class="flex items-center gap-6 mb-8">
                <div class="relative">
                    <?php if (!empty($user['avatar_url'])): ?>
                        <img src="<?php echo htmlspecialchars($user['avatar_url']); ?>" alt="Avatar" class="w-24 h-24 rounded-full object-cover border-2 border-primary">
                    <?php else: ?>
                        <div class="w-24 h-24 rounded-full bg-muted flex items-center justify-center text-2xl font-bold text-muted-foreground border-2 border-border">
                            <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div>
                    <h2 class="text-xl font-semibold"><?php echo htmlspecialchars($user['full_name']); ?></h2>
                    <p class="text-muted-foreground"><?php echo htmlspecialchars($user['email']); ?></p>
                     <p class="text-sm text-primary mt-1 capitalize"><?php 
                        // Fetch role name for display (optional, but nice)
                        // Simple query again or just rely on session if we stored it, but we didn't. 
                        // Let's just say "Member" or leave it. 
                        echo "Member since " . date('M Y', strtotime($user['created_at'])); 
                    ?></p>
                </div>
            </div>

            <form action="controllers/update_profile.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium mb-2">Full Name</label>
                        <input type="text" name="full_name" required value="<?php echo htmlspecialchars($user['full_name']); ?>"
                            class="w-full px-4 py-2 rounded-md border border-border bg-background focus:ring-2 focus:ring-primary focus:outline-none">
                    </div>
                
                    <div>
                        <label class="block text-sm font-medium mb-2">Phone Number</label>
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>"
                            class="w-full px-4 py-2 rounded-md border border-border bg-background focus:ring-2 focus:ring-primary focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Change Avatar</label>
                    <input type="file" name="avatar" accept="image/*"
                        class="w-full text-sm text-foreground
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-primary file:text-primary-foreground
                        hover:file:bg-primary/90">
                    <p class="text-xs text-muted-foreground mt-1">Max size 5MB. Formats: JPG, PNG, WEBP.</p>
                </div>

                <hr class="border-border">

                <div>
                    <h3 class="font-medium mb-4">Change Password <span class="text-sm font-normal text-muted-foreground">(Leave blank to keep current)</span></h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">New Password</label>
                            <input type="password" name="password"
                                class="w-full px-4 py-2 rounded-md border border-border bg-background focus:ring-2 focus:ring-primary focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Confirm Password</label>
                            <input type="password" name="confirm_password"
                                class="w-full px-4 py-2 rounded-md border border-border bg-background focus:ring-2 focus:ring-primary focus:outline-none">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-primary text-primary-foreground px-6 py-2 rounded-md font-medium hover:bg-primary/90 transition-colors">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include 'components/footer.php'; ?>
