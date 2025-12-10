<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE)
    session_start();

// helper: user info (قد لا يكون مسجل الدخول في صفحات عامة مثل login)
$user = $_SESSION['user'] ?? null;
$username = $_SESSION['user']['username'] ?? 'Guest';

echo $username;
// مسار ملفات الموارد (عدل إذا تغيرت البنية)
$cssPath = '../public/css/style.css';
$jsPath = '../public/js/main.js';
?>
<script>window.currentUser = "<?php echo $_SESSION['user']['username']; ?>";</script>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>p i n c e l</title>
    <link rel="stylesheet" href="<?= htmlspecialchars($cssPath) ?>">
    <link rel="stylesheet" href="../public/css/styleScchadual.css">
    <link rel="stylesheet" href="../public/css/bootstrap.min.css.map">
    <link rel="stylesheet" href="../public/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


</head>

<body>
    <header class="topbar" role="banner">
        <div class="brand">
            <div class="cover_logo">
                <img class="logo" src="../public/images/logo2.jpg" alt="logo" style="">
            </div>
            <div class="title">
                <h1 class="text-center ">p i n c e l </h1>
                <div style="font-size:12px; color:#666;">لوحة الإدارة</div>
            </div>
        </div>

        <div class="topnav" style="display:flex; align-items:center;">
            <!-- روابط عامة متاحة للجميع (قبل / بعد تسجيل الدخول) -->
            <a href="../pages/dashboard.php">الرئيسية</a>

            <?php if ($user): ?>
                <!-- روابط تظهر لمستخدم مسجل فقط، ويمكن تقييدها أدناه حسب الدور -->
                <?php if (in_array($user['role'], ['admin', 'modir', 'moshref'])): ?>
                    <a href="../pages/students.php">الطلاب</a>
                    <a href="../pages/teachers.php">المدرسين</a>
                <?php endif; ?>

                <?php if (in_array($user['role'], ['admin', 'moshref', 'modir', 'nazir'])): ?>
                    <a href="../pages/attendance_teachers.php">حضور المدرسين</a>
                    <a href="../pages/schedule.php">الجدول</a>
                <?php endif; ?>

                <!-- admin فقط له التحكم بالمستخدمين -->
                <?php if ($user['role'] === 'admin'): ?>
                    <a href="../pages/users.php">إدارة المستخدمين</a>
                <?php endif; ?>

                <span style="width:10px;"></span>
                <div class="user-info" aria-label="معلومات المستخدم">
                    مرحبًا، <strong><?= htmlspecialchars($user['username']) ?></strong>
                    <div style="font-size:12px; color:#777;">
                        التصنيف: <?= htmlspecialchars($user['role']) ?>
                        &nbsp;|&nbsp;
                        <a href="../pages/profile.php">الملف</a>
                        &nbsp;|&nbsp;
                        <a href="../logout.php" style="color:#b30000;">خروج</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="../index.php?logout">تسجيل دخول</a>
            <?php endif; ?>

            <!-- ===================================== -->
            <!-- ========================================= -->
            <?php
            // تضمّن هذا الملف في الheader أو في اللوحة الجانبية
            ?>
            <div id="users-sidebar">
                <h4>المتصلون</h4>
                <ul id="users-list"></ul>
            </div>
            <script>
                // 1) إرسال heartbeat للسيرفر كل 5 ثواني
                setInterval(() => {
                    fetch('../api/onlineUser.php', {
                        method: 'POST',
                        credentials: 'include'  // مهم جداً عشان السيشن يوصلك
                    });
                }, 5000);

                // 2) تحميل قائمة المتصلين
                async function fetchUsers() {
                    let res = await fetch('../api/onlineUser.php', {
                        credentials: 'include' // مفضل تضيفه هنا كمان
                    });
                    let users = await res.json();
                    const me = window.currentUser;
                    
                    const ul = document.getElementById('users-list');
                    if (!ul) return;
                    ul.innerHTML = '';

                    users.forEach(u => {
                        if (u.users === me) return; // استبعاد نفسي
                        let li = document.createElement('li');
                        li.innerHTML = `<button class="chat-open" data-user="${u.users}">${u.users}</button>`;
                        ul.appendChild(li);
                    });

                    document.querySelectorAll('.chat-open').forEach(b => {
                        b.onclick = () => {
                            location.href = '../pages/chat.php?user=' + encodeURIComponent(b.dataset.user);
                        };
                    });
                }

                setInterval(fetchUsers, 1000);
                fetchUsers();
            </script>

            <!-- ============================================= -->
        </div>
    </header>

    <!-- بداية المحتوى العام -->
    <main style="padding:18px;">