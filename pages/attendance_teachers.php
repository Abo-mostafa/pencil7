<?php include '../includes/header.php'; ?>
<link rel="stylesheet" href="../public/css/cam.css">
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Teachers</h1>
        </div>
    </div>

    <!-- قسم البحث -->
    <div class="row mb-3">
        <div class="col-md-6">
            <input type="text" id="teacher-search" class="form-control" placeholder="البحث بالاسم أو المادة..."
                onkeyup="filterTeachers()">
        </div>
        <div class="col-md-6">
            <button id="qr-btn"> start scan QR 📱📸</button>
            <input type="text" id="qr-input" class="form-control" placeholder="qr code ....">

            <!-- قسم ماسح QR -->
            <div id="scanner-section" class="scanner-section">
                <div class="camera-container">
                    <video id="qr-video" playsinline></video>
                    <div class="scanner-overlay">
                        <div class="scanner-frame">
                            <div class="frame-corner top-left"></div>
                            <div class="frame-corner top-right"></div>
                            <div class="frame-corner bottom-left"></div>
                            <div class="frame-corner bottom-right"></div>
                            <div class="scan-line"></div>
                        </div>
                    </div>
                </div>
                <div class="scanner-info">
                    <p>وجه الكاميرا نحو رمز QR واضعه داخل الإطار</p>
                    <p>سيتم قراءة الرمز تلقائياً وإدخاله في حقل البحث</p>
                </div>
            </div>


        </div>
    </div>

    <!-- حاوية رئيسية قابلة للتمرير (Scrollable Container) -->
    <!-- ارتفاع ثابت وتمرير عمودي -->
    <div class="scrollable-container" style="max-height: 600px; overflow-y: auto; padding-right: 15px;">
        <!-- إضافة كلاس 'row' هنا لاستخدام نظام الأعمدة بشكل صحيح -->
        <div id="teacher-container" class="row">
            <!-- سيتم ملء هذا القسم بواسطة JavaScript -->
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
<script src="../public/js/pages/attendance_teachers.js"></script>
<script src="../public/js/pages/scanqr.js"></script>

<?php include '../includes/footer.php'; ?>