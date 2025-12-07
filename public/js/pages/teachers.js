// public/js/pages/teachers.js

// متغير لتخزين جميع بيانات المدرسين بعد الجلب الأولي
let allTeachers = [];


function getallTeachers() {
        fetch('../api/get_teachers.php')
        .then(response => response.json())
        .then(teachers => {
            // تخزين البيانات في المتغير العام
            allTeachers = teachers;
            // عرض كافة المدرسين لأول مرة
            displayTeachers(allTeachers);
        })
        .catch(error => console.error('Error fetching teachers:', error));
}

// دالة لعرض المدرسين (تستخدم في الجلب والفلترة)
function displayTeachers(teachersToShow) {
    const teacherContainer = document.getElementById('teacher-container');
    teacherContainer.innerHTML = ''; // مسح المحتوى السابق

    if (Array.isArray(teachersToShow) && teachersToShow.length > 0) {
        teachersToShow.forEach(teacher => {
            const teacherItem = document.createElement('div');
            teacherItem.className = 'col-md-4 mb-4';

            const primaryImagePath = `../assets/tech/${teacher.teacher_id}.jpg`;
            const defaultImagePath = `../assets/tech/default_user.jpg`;

            teacherItem.innerHTML = `
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <img 
                            src="${primaryImagePath}" 
                            alt="Teacher Image" 
                            class="rounded-circle mb-3" 
                            style="width: 80px; height: 80px; object-fit: cover;"
                            onerror="this.onerror=null; this.src='${defaultImagePath}';" 
                        >
                        <h5 class="card-title">${teacher.name}</h5>
                        <p class="card-text">
                            <strong>Phone:</strong> ${teacher.phone}<br>
                            <strong>Subjects:</strong> ${teacher.subjects_name}<br>
                        </p>
                        <a href="#" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            `;
            
            teacherContainer.appendChild(teacherItem);
        });
    } else {
        teacherContainer.innerHTML = '<p class="alert alert-info col-12">No teachers found matching your criteria.</p>';
    }
}

// دالة الفلترة (يتم استدعاؤها عند كل ضغطة زر في حقل البحث)
function filterTeachers() {
    const searchTerm = document.getElementById('teacher-search').value.toLowerCase();
    
    const filteredTeachers = allTeachers.filter(teacher => {
        // البحث بالاسم
        const nameMatch = teacher.name.toLowerCase().includes(searchTerm);
        // البحث باسم المادة (subjects_name هو الحقل الجديد الذي أنشأناه في PHP)
        const subjectsMatch = teacher.subjects_name.toLowerCase().includes(searchTerm);

        return nameMatch || subjectsMatch;
    });

    displayTeachers(filteredTeachers);
}


// عند تحميل الصفحه
window.addEventListener('load', () => {
    getallTeachers();
});
