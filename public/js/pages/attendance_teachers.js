// public/js/pages/teachers.js

let allTeachers = [];

// ======================
// تنسيق الوقت: HH:MM
// ======================
function formatTime(dt) {
    if (!dt) return '—';
    const d = new Date(dt);
    if (isNaN(d.getTime())) return dt;
    return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}

// ======================
// جلب المدرسين
// ======================
function getAllTeachers() {
    fetch('../api/get_teachers.php')
        .then(res => res.json())
        .then(teachers => {
            allTeachers = Array.isArray(teachers) ? teachers : [];
            displayTeachers(allTeachers);
        })
        .catch(err => console.error('Error fetching teachers:', err));
}

// ======================
// عرض المدرسين
// ======================
function displayTeachers(teachersToShow) {
    const container = document.getElementById('teacher-container');
    if (!container) return;
    container.innerHTML = '';

    if (!Array.isArray(teachersToShow) || teachersToShow.length === 0) {
        container.innerHTML = '<p class="alert alert-info">لا يوجد مدرسين لعرضهم.</p>';
        return;
    }

    teachersToShow.forEach(teacher => {
        const html = generateTeacherCardHTML(teacher);
        container.insertAdjacentHTML('beforeend', html);
    });
}

// ======================
// توليد كارت المدرس
// ======================
function generateTeacherCardHTML(teacher) {
    const id = teacher.teacher_id ?? '';
    const name = teacher.name ?? 'اسم غير معروف';
    const phone = teacher.phone ?? '—';
    const subjects = teacher.subjects_name ?? '';

    const status = teacher.attendance_status ?? 'none';
    const attendanceTime = teacher.attendance_time ?? null;
    const leaveTime = teacher.leave_time ?? null;
    let description = teacher.description ?? '';
    let cardBorder = 'border-secondary';
    let badge = `<span class="badge bg-secondary">لم يسجل</span>`;
    let actionButtons = `
        <button class="btn btn-primary btn-sm" onclick="presence(${id})" > presence </button>
        <button class="btn btn-secondary btn-sm" onclick="withdrawal(${id})})">انصراف</button>
        <button class="btn btn-danger btn-sm" onclick="absence(${id})">غياب</button>
    `;

    let statusDetails = `<p class="text-muted mt-2 mb-1">الحالة: لم يتم تسجيل اليوم</p>`;

    if (status === 'presence') {
        cardBorder = 'border-warning';
        badge = `<span class="badge bg-warning text-dark">حضر (لم ينصرف)</span>`;
        actionButtons = `<button class="btn btn-secondary btn-sm" onclick="withdrawal(${id})">انصراف</button>`;
        statusDetails = `<p class="text-warning mt-2 mb-1">
            حضر الساعة: ${attendanceTime}<br>
            <small>في انتظار تسجيل الانصراف</small>
        </p>`;
    } else if (status === 'with_leave') {
        cardBorder = 'border-success';
        badge = `<span class="badge bg-success">حضر وانصرف</span>`;
        actionButtons = `<button class="btn btn-secondary btn-sm" disabled>اكتمل اليوم</button>`;
        statusDetails = `<p class="text-success mt-2 mb-1">
            حضر الساعة: ${attendanceTime}<br>
            انصرف الساعة: ${leaveTime}
        </p>`;
    } else if (status === 'absence') {
        cardBorder = 'border-danger';
        badge = `<span class="badge bg-danger">غائب</span>`;
        actionButtons = `
            <button class="btn btn-primary btn-sm" onclick="presence(${id})">حضور</button>
            <button class="btn btn-secondary btn-sm" onclick="withdrawal(${id})">انصراف</button  
            <button class="btn btn-danger btn-sm" onclick="absence(${id})">غياب</button>
        `;
        statusDetails = `<p class="text-danger mt-2 mb-1">
            حالة: غياب<br>
            <small>السبب: ${description || '—'}</small>
        </p>`;
    }

    const imgPath = `../assets/tech/${id}.jpg`;
    const defaultImg = `../assets/tech/default_user.jpg`;

    return `
        <div class="col-md-4 mb-4" id="card-${id}">
            <div class="card h-100 shadow-sm ${cardBorder}">
                <div class="card-body text-center">
                    ${badge}
                    <br>
                    <img src="${imgPath}" onerror="this.src='${defaultImg}'"
                        class="rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                    <h5>${name}</h5>
                    ${statusDetails}
                    <p class="mt-2"><strong>الهاتف:</strong> ${phone}<br><strong>المواد:</strong> ${subjects}</p>
                    <div class="mt-2">${actionButtons}</div>
                </div>
            </div>
        </div>
    `;
}

// ======================
// تحديث كارت واحد فقط
// ======================
function updateCard(teacher_id) {
    fetch('../api/get_teachers.php')
        .then(res => res.json())
        .then(list => {
            const teacher = list.find(t => String(t.teacher_id) === String(teacher_id));
            if (!teacher) return;
            const el = document.getElementById('card-' + teacher_id);
            if (el) el.outerHTML = generateTeacherCardHTML(teacher);
        })
        .catch(err => console.error(err));
}

// ======================
// دوال التعامل مع API
// ======================
function presence(teacher_id) {
    fetch(`../api/attendance_teachers.php?action=presence`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ teacher_id })
    })
        .then(res => res.json())
        .then(data => {
            alert(data.message || 'تم تسجيل الحضور');
            updateCard(teacher_id);
        });
}

function withdrawal(teacher_id) {
    fetch(`../api/attendance_teachers.php?action=withdrawal`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ teacher_id })
    })
        .then(res => res.json())
        .then(data => {
            alert(data.message || 'تم تسجيل الانصراف');
            updateCard(teacher_id);
        });
}

function absence(teacher_id) {
    const type = prompt('سبب الغياب (اذن / مرضي / اخرى):');
    if (!type) return;
    const details = prompt('تفاصيل إضافية (اختياري):') || '';
    const description = `${type}${details ? ' - ' + details : ''}`;

    fetch(`../api/attendance_teachers.php?action=absence`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ teacher_id, description })
    })
        .then(res => res.json())
        .then(data => {
            alert(data.message || 'تم تسجيل الغياب');
            updateCard(teacher_id);
        });
}

// ======================
// فلترة المدرسين
// ======================
function filterTeachers() {
    const searchTerm = document.getElementById('teacher-search').value.toLowerCase();
    const filtered = allTeachers.filter(t =>
        t.name.toLowerCase().includes(searchTerm) ||
        (t.subjects_name && t.subjects_name.toLowerCase().includes(searchTerm))
    );
    displayTeachers(filtered);
}

// ======================
// عند تحميل الصفحة
// ======================
window.addEventListener('load', getAllTeachers);

// ======================
// عند تغيير البحث
// ======================
document.getElementById('teacher-search').addEventListener('input', filterTeachers);