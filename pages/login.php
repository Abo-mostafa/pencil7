<?php include '../includes/header.php'; ?>
<!-- تسجيل الخروج اولا -->
<?php 
include '../hmb/conn.php';
?>

<?php

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrPhone = trim($_POST['username']);
    $password = $_POST['password'];

    // التحقق من وجود المستخدم
    $stmt = $pdo->prepare("SELECT * FROM users WHERE (username = :u OR phone = :u) AND status = 'active'");
    $stmt->execute([':u' => $usernameOrPhone]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && ($password === $user['password'] || password_verify($password, $user['password']))) {

        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'title' => $user['title'],
            'role' => $user['role']
        ];


        header("Location: dashboard.php");
        exit;
    } else {
        $error = "❌ بيانات الدخول غير صحيحة أو الحساب غير نشط.";
        session_commit(); 
    }
}

?>
<div class="overlay">

    <div class="center-wrapper">
        <div class="form-card">
            <h2>تسجيل الدخول</h2>
            <p>من فضلك أدخل بياناتك للوصول للنظام</p>
    
            <?php if ($error): ?>
                <div class="error-box"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
    
            <form method="post">
                <div class="form-group">
                    <label>اسم المستخدم أو رقم الهاتف</label>
                    <input type="text" name="username" placeholder="اكتب اسم المستخدم أو رقم الهاتف" required>
                </div>
    
                <div class="form-group">
                    <label>كلمة المرور</label>
                    <input type="password" name="password" placeholder="•••••••••••" required>
                    <a href="reset_password.php" class="forgot-link">هل نسيت كلمة المرور؟</a>
                </div>
    
                <button type="submit" class="btn-login">تسجيل الدخول</button>
            </form>
        </div>
    </div>
</div>



<?php include '../includes/footer.php'; ?>