<?php


// مسار ملفات الموارد (عدل إذا تغيرت البنية)
$cssPath = 'public/css/style.css';
$jsPath  = 'public/js/main.js';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>p i n c e l</title>
    <link rel="stylesheet" href="<?= htmlspecialchars($cssPath) ?>">
    <link rel="stylesheet" href="public/css/styleScchadual.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


</head>
<body>
    <header class="topbar" role="banner">
        <div class="brand">
            <div class="cover_logo">
                <img class="logo" src="public/images/logo2.jpg" alt="logo" style="">
            </div>
            <div class="title">
                <h1 class="text-center ">p i n c e l </h1>
            </div>
        </div>

        <div class="topnav" style="display:flex; align-items:center;">

        </div>
    </header>

    <!-- بداية المحتوى العام -->
    <main style="padding:18px;">




        <div class="container center  ">
            <div class="login-card border" >
                <img src="public/images/logo.jpg" alt=""width="300px">
            </div>
        </div>

        <div class="container center  ">
            <div class="p-t2">
                <a   href="pages/login.php"><button class="btn p-r2">تسجيل دخول</button></a>
                <a  href="pages/tables.php"><button class="btn p-r2" >جداول الحصص</button></a>
                <a  href="pages/tables.php"><button class="btn p-r2" >من نحن </button></a>

            </div>
        </div>


    <?php
// includes/footer.php
// إغلاق الوسم الرئيسي ثم الفوتر العام
?>
    </main>

    <footer >
        <div>© <?= date('Y') ?>smart pencil Hazem</div>
        <div style="font-size:12px; color:#666;">جميع الحقوق محفوظة</div>
    </footer>

    <!-- ملفات جافاسكريبت عامة -->
    <script src="/public/js/main.js"></script>
</body>
</html>
