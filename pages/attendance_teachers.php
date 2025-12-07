<?php include '../includes/header.php'; ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Teachers</h1>
        </div>
    </div>

    <!-- قسم البحث -->
    <div class="row mb-3">
        <div class="col-md-6">
            <input type="text" id="teacher-search" class="form-control" placeholder="البحث بالاسم أو المادة..." onkeyup="filterTeachers()">
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

<script src="../public/js/pages/attendance_teachers.js"></script>

<?php include '../includes/footer.php'; ?>
