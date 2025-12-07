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
            const response = await fetch('../api/get_TimeLine.php?action=get_teachers');
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
            const response = await fetch('../api/get_TimeLine.php?action=get_subjects');
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
            const response = await fetch('../api/get_TimeLine.php?action=get_classes');
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
            const response = await fetch('../api/get_TimeLine.php?action=get_slots');
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
                const response = await fetch(`../api/get_TimeLine.php?action=get_timetable&class_id=${classId}&week_start=${weekStart}`);
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
                const response = await fetch(`../api/get_TimeLine.php?action=get_today_classes&class_id=${classId}`);
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
            fetch('../api/get_TimeLine.php?action=get_slots')
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
                const response = await fetch('../api/get_TimeLine.php?action=add_timetable_entry', {
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
                    fetch('../api/get_TimeLine.php?action=get_teachers').then(r => r.json()),
                    fetch('../api/get_TimeLine.php?action=get_subjects').then(r => r.json()),
                    fetch('../api/get_TimeLine.php?action=get_classes').then(r => r.json()),
                    fetch('../api/get_TimeLine.php?action=get_timetable').then(r => r.json())
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