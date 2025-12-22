<?php include '../includes/header.php'; ?>
<link rel="stylesheet" href="../public/css/styleScchadual.css">

<div class="container">
        <header>
            <h1>نظام إدارة الجدول الدراسي المتكامل</h1>
            <p class="subtitle">إدارة الفصول، المدرسين، والمواد الدراسية في نظام واحد</p>
        </header>

        <div class="stats">
            <div class="stat-card">
                <h3 id="total-classes">0</h3>
                <p>إجمالي الحصص</p>
            </div>
            <div class="stat-card">
                <h3 id="total-subjects">0</h3>
                <p>عدد المواد</p>
            </div>
            <div class="stat-card">
                <h3 id="total-teachers">0</h3>
                <p>عدد المدرسين</p>
            </div>
            <div class="stat-card">
                <h3 id="total-classrooms">0</h3>
                <p>عدد الفصول</p>
            </div>
        </div>

        <div class="today-classes">
            <h2><i class="fas fa-calendar-day"></i> حصص اليوم</h2>
            <div id="today-classes-list" class="loading">
                جاري تحميل حصص اليوم...
            </div>
        </div>

        <div class="dashboard">
            <div class="controls">
                <div class="tabs">
                    <div class="tab active" data-tab="add-class">إضافة حصة</div>
                    <div class="tab" data-tab="manage-data">إدارة البيانات</div>
                </div>

                <div class="tab-content active" id="add-class">
                    <h2>إضافة حصة جديدة</h2>
                    <div class="form-group">
                        <label for="class-select">الفصل</label>
                        <select id="class-select">
                            <option value="">اختر الفصل</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="subject-select">المادة الدراسية</label>
                        <select id="subject-select">
                            <option value="">اختر المادة</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="teacher-select">المدرس</label>
                        <select id="teacher-select">
                            <option value="">اختر المدرس</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="day-select">اليوم</label>
                        <select id="day-select">
                            <option value="sunday">الأحد</option>
                            <option value="monday">الإثنين</option>
                            <option value="tuesday">الثلاثاء</option>
                            <option value="wednesday">الأربعاء</option>
                            <option value="thursday">الخميس</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="slot-select">الوقت</label>
                        <select id="slot-select">
                            <option value="">اختر الوقت</option>
                        </select>
                    </div>
                    <button id="add-class-btn">إضافة الحصة</button>
                </div>

                <div class="tab-content" id="manage-data">
                    <h2>إدارة البيانات</h2>
                    <div class="form-group">
                        <button id="add-teacher-btn">إضافة مدرس جديد</button>
                    </div>
                    <div class="form-group">
                        <button id="add-subject-btn">إضافة مادة جديدة</button>
                    </div>
                    <div class="form-group">
                        <button id="add-classroom-btn">إضافة فصل جديد</button>
                    </div>
                </div>
            </div>

            <div class="timetable-container">
                <div class="class-selector">
                    <div>
                        <label for="current-class-select">اختر الفصل:</label>
                        <select id="current-class-select">
                            <option value="">جميع الفصول</option>
                        </select>
                    </div>
                    <div class="week-navigation">
                        <button id="prev-week"><i class="fas fa-chevron-right"></i> الأسبوع السابق</button>
                        <span id="current-week">الأسبوع الحالي</span>
                        <button id="next-week">الأسبوع التالي <i class="fas fa-chevron-left"></i></button>
                    </div>
                </div>

                <h2>الجدول الدراسي</h2>
                <div id="timetable-loading" class="loading">جاري تحميل الجدول...</div>
                <table class="timetable" id="timetable" style="display: none;">
                    <thead>
                        <tr>
                            <th>الوقت</th>
                            <th>الأحد</th>
                            <th>الإثنين</th>
                            <th>الثلاثاء</th>
                            <th>الأربعاء</th>
                            <th>الخميس</th>
                        </tr>
                    </thead>
                    <tbody id="timetable-body">
                    </tbody>
                </table>
            </div>
        </div>


    </div>
<!-- D:\xampp\htdocs\pencil7\public\js\pages\schaduale.js -->
    <script src="../public/js/pages/schaduale.js"></script>



<?php include '../includes/footer.php'; ?>