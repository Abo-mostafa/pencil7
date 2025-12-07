<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة الجدول الدراسي المتكامل</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* نفس الـ CSS السابق - يمكن وضعه في ملف منفصل */
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --accent-color: #e74c3c;
            --light-color: #f9f9f9;
            --dark-color: #2c3e50;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: var(--dark-color);
            line-height: 1.6;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 20px 0;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
            text-align: center;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .dashboard {
            display: grid;
            grid-template-columns: 1fr 3fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .controls {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: var(--shadow);
        }

        .tabs {
            display: flex;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .tab {
            padding: 10px 15px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
        }

        .tab.active {
            border-bottom: 3px solid var(--primary-color);
            color: var(--primary-color);
            font-weight: 600;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
            font-weight: 600;
        }

        button:hover {
            background-color: #2980b9;
        }

        .timetable-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: var(--shadow);
            overflow-x: auto;
        }

        .class-selector {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }

        .week-navigation {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .week-navigation button {
            width: auto;
            padding: 8px 15px;
        }

        .timetable {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        .timetable th, .timetable td {
            padding: 12px 15px;
            text-align: center;
            border: 1px solid #e1e1e1;
        }

        .timetable th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
        }

        .timetable tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .timetable tr:hover {
            background-color: #e9f7fe;
        }

        .subject {
            padding: 8px;
            border-radius: 5px;
            color: white;
            font-weight: 500;
            margin: 2px 0;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: var(--shadow);
            text-align: center;
        }

        .stat-card h3 {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .stat-card p {
            color: var(--dark-color);
            font-weight: 600;
        }

        footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        .today-classes {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: var(--shadow);
            margin-bottom: 20px;
        }

        .today-classes h2 {
            color: var(--primary-color);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .class-item {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .class-item:last-child {
            border-bottom: none;
        }

        .loading {
            text-align: center;
            padding: 20px;
            color: var(--primary-color);
        }

        @media (max-width: 768px) {
            .dashboard {
                grid-template-columns: 1fr;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .class-selector {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
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

        <footer>
            <p>تم التطوير باستخدام أحدث تقنيات الويب | نظام إدارة الجدول الدراسي المتكامل &copy; 2023</p>
        </footer>
    </div>

    <script>
        // المتغيرات العامة
        let currentWeekStart = new Date();
        currentWeekStart.setDate(currentWeekStart.getDate() - currentWeekStart.getDay()); // بداية الأسبوع

        // تهيئة التطبيق
        document.addEventListener('DOMContentLoaded', function() {
            loadInitialData();
            setupEventListeners();
        });

        // تحميل البيانات الأولية
        async function loadInitialData() {
            try {
                await Promise.all([
                    loadTeachers(),
                    loadSubjects(),
                    loadClasses(),
                    loadSlots(),
                    loadTimetable(),
                    loadTodayClasses()
                ]);
                updateStats();
            } catch (error) {
                console.error('Error loading initial data:', error);
            }
        }

        // إعداد مستمعي الأحداث
        function setupEventListeners() {
            // التنقل بين التبويبات
            document.querySelectorAll('.tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                    
                    this.classList.add('active');
                    document.getElementById(this.dataset.tab).classList.add('active');
                });
            });

            // إضافة حصة جديدة
            document.getElementById('add-class-btn').addEventListener('click', addTimetableEntry);

            // تغيير الفصل
            document.getElementById('current-class-select').addEventListener('change', loadTimetable);

            // التنقل بين الأسابيع
            document.getElementById('prev-week').addEventListener('click', () => navigateWeek(-1));
            document.getElementById('next-week').addEventListener('click', () => navigateWeek(1));
        }

        // تحميل المدرسين
        async function loadTeachers() {
            const response = await fetch('api/get_TimeLine.php?action=get_teachers');
            const teachers = await response.json();
            
            const select = document.getElementById('teacher-select');
            select.innerHTML = '<option value="">اختر المدرس</option>';
            
            teachers.forEach(teacher => {
                const option = document.createElement('option');
                option.value = teacher.teacher_id;
                option.textContent = teacher.name;
                select.appendChild(option);
            });
        }

        // تحميل المواد الدراسية
        async function loadSubjects() {
            const response = await fetch('api/get_TimeLine.php?action=get_subjects');
            const subjects = await response.json();
            
            const select = document.getElementById('subject-select');
            select.innerHTML = '<option value="">اختر المادة</option>';
            
            subjects.forEach(subject => {
                const option = document.createElement('option');
                option.value = subject.subject_id;
                option.textContent = subject.subject_name;
                option.dataset.color = subject.color;
                select.appendChild(option);
            });
        }

        // تحميل الفصول
        async function loadClasses() {
            const response = await fetch('api/get_TimeLine.php?action=get_classes');
            const classes = await response.json();
            
            const select1 = document.getElementById('class-select');
            const select2 = document.getElementById('current-class-select');
            
            select1.innerHTML = '<option value="">اختر الفصل</option>';
            select2.innerHTML = '<option value="">جميع الفصول</option>';
            
            classes.forEach(classItem => {
                [select1, select2].forEach(select => {
                    const option = document.createElement('option');
                    option.value = classItem.class_id;
                    option.textContent = classItem.class_name;
                    select.appendChild(option);
                });
            });
        }

        // تحميل مواعيد الحصص
        async function loadSlots() {
            const response = await fetch('api/get_TimeLine.php?action=get_slots');
            const slots = await response.json();
            
            const select = document.getElementById('slot-select');
            select.innerHTML = '<option value="">اختر الوقت</option>';
            
            slots.forEach(slot => {
                const option = document.createElement('option');
                option.value = slot.slot_id;
                option.textContent = `${slot.start_time} - ${slot.end_time}`;
                select.appendChild(option);
            });
        }

        // تحميل الجدول الدراسي
        async function loadTimetable() {
            const classId = document.getElementById('current-class-select').value;
            const weekStart = formatDate(currentWeekStart);
            
            document.getElementById('timetable-loading').style.display = 'block';
            document.getElementById('timetable').style.display = 'none';
            
            try {
                const response = await fetch(`api/get_TimeLine.php?action=get_timetable&class_id=${classId}&week_start=${weekStart}`);
                const timetable = await response.json();
                
                renderTimetable(timetable);
                updateWeekDisplay();
                
                document.getElementById('timetable-loading').style.display = 'none';
                document.getElementById('timetable').style.display = 'table';
            } catch (error) {
                console.error('Error loading timetable:', error);
            }
        }

        // تحميل حصص اليوم
        async function loadTodayClasses() {
            const classId = document.getElementById('current-class-select').value;
            
            try {
                const response = await fetch(`api/get_TimeLine.php?action=get_today_classes&class_id=${classId}`);
                const todayClasses = await response.json();
                renderTodayClasses(todayClasses);
            } catch (error) {
                console.error('Error loading today classes:', error);
            }
        }

        // عرض الجدول الدراسي
        function renderTimetable(timetable) {
            const tbody = document.getElementById('timetable-body');
            tbody.innerHTML = '';
            
            // تحميل مواعيد الحصص أولاً
            fetch('api/get_TimeLine.php?action=get_slots')
                .then(response => response.json())
                .then(slots => {
                    slots.forEach(slot => {
                        const row = document.createElement('tr');
                        
                        // خلية الوقت
                        const timeCell = document.createElement('td');
                        timeCell.textContent = `${slot.start_time} - ${slot.end_time}`;
                        timeCell.style.fontWeight = '600';
                        row.appendChild(timeCell);
                        
                        // خلايا الأيام
                        const days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday'];
                        days.forEach(day => {
                            const dayCell = document.createElement('td');
                            const classData = timetable.find(item => 
                                item.slot_id == slot.slot_id && item.day_of_week === day
                            );
                            
                            if (classData) {
                                const subjectDiv = document.createElement('div');
                                subjectDiv.className = 'subject';
                                subjectDiv.style.backgroundColor = classData.color;
                                subjectDiv.innerHTML = `
                                    <strong>${classData.subject_name}</strong><br>
                                    ${classData.teacher_name}<br>
                                    ${classData.class_name}
                                `;
                                dayCell.appendChild(subjectDiv);
                            }
                            
                            row.appendChild(dayCell);
                        });
                        
                        tbody.appendChild(row);
                    });
                });
        }

        // عرض حصص اليوم
        function renderTodayClasses(classes) {
            const container = document.getElementById('today-classes-list');
            
            if (classes.length === 0) {
                container.innerHTML = '<p>لا توجد حصص لهذا اليوم</p>';
                return;
            }
            
            container.innerHTML = classes.map(classItem => `
                <div class="class-item">
                    <div>
                        <strong>${classItem.subject_name}</strong> - ${classItem.teacher_name}
                    </div>
                    <div>
                        ${classItem.start_time} - ${classItem.end_time}
                        ${classItem.class_name ? `(${classItem.class_name})` : ''}
                    </div>
                </div>
            `).join('');
        }

        // إضافة حصة جديدة
        async function addTimetableEntry() {
            const classId = document.getElementById('class-select').value;
            const subjectId = document.getElementById('subject-select').value;
            const teacherId = document.getElementById('teacher-select').value;
            const day = document.getElementById('day-select').value;
            const slotId = document.getElementById('slot-select').value;
            
            if (!classId || !subjectId || !teacherId || !slotId) {
                alert('يرجى ملء جميع الحقول');
                return;
            }
            
            const subjectSelect = document.getElementById('subject-select');
            const selectedSubject = subjectSelect.options[subjectSelect.selectedIndex];
            const subjectColor = selectedSubject.dataset.color;
            
            const data = {
                class_id: classId,
                subject_id: subjectId,
                teacher_id: teacherId,
                slot_id: slotId,
                day_of_week: day,
                effective_date: formatDate(currentWeekStart),
                academic_year: '2023-2024'
            };
            
            try {
                const response = await fetch('api/get_TimeLine.php?action=add_timetable_entry', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert('تم إضافة الحصة بنجاح');
                    loadTimetable();
                    loadTodayClasses();
                    updateStats();
                    
                    // تفريغ الحقول
                    document.getElementById('class-select').value = '';
                    document.getElementById('subject-select').value = '';
                    document.getElementById('teacher-select').value = '';
                    document.getElementById('slot-select').value = '';
                } else {
                    alert('حدث خطأ أثناء إضافة الحصة');
                }
            } catch (error) {
                console.error('Error adding timetable entry:', error);
                alert('حدث خطأ أثناء إضافة الحصة');
            }
        }

        // التنقل بين الأسابيع
        function navigateWeek(direction) {
            currentWeekStart.setDate(currentWeekStart.getDate() + (direction * 7));
            loadTimetable();
        }

        // تحديث عرض الأسبوع
        function updateWeekDisplay() {
            const weekEnd = new Date(currentWeekStart);
            weekEnd.setDate(weekEnd.getDate() + 6);
            
            document.getElementById('current-week').textContent = 
                `أسبوع ${formatDate(currentWeekStart)} - ${formatDate(weekEnd)}`;
        }

        // تحديث الإحصائيات
        async function updateStats() {
            try {
                const [teachers, subjects, classes, timetable] = await Promise.all([
                    fetch('api/get_TimeLine.php?action=get_teachers').then(r => r.json()),
                    fetch('api/get_TimeLine.php?action=get_subjects').then(r => r.json()),
                    fetch('api/get_TimeLine.php?action=get_classes').then(r => r.json()),
                    fetch('api/get_TimeLine.php?action=get_timetable').then(r => r.json())
                ]);
                
                document.getElementById('total-teachers').textContent = teachers.length;
                document.getElementById('total-subjects').textContent = subjects.length;
                document.getElementById('total-classrooms').textContent = classes.length;
                document.getElementById('total-classes').textContent = timetable.length;
            } catch (error) {
                console.error('Error updating stats:', error);
            }
        }

        // دالة مساعدة لتنسيق التاريخ
        function formatDate(date) {
            return date.toISOString().split('T')[0];
        }
    </script>
</body>
</html>