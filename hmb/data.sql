

-- يتم ادخال المستخدم عن طريق الادمن فقط 
-- و بشكل افتراضى يتم وضع كلمة المرور 0000 الى ان يقوم المستخدم بتغيرها
-- ============================================================================

CREATE table users (
    id int primary key auto_increment,
    username varchar(120),
    title varchar(120),
    phone varchar(100),
    password varchar(100),
    role varchar(100),
    status varchar(100)
);

insert into users (username, title, phone, password, role, status) values
('admin', 'مبرمجاتي', '01095776426', '0000', 'admin', 'active');



-- قاعدة بيانات المدرسين
CREATE TABLE teachers (
    teacher_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(100),
    main_subject_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- قاعدة بيانات المواد الدراسية
CREATE TABLE subjects (
    subject_id INT PRIMARY KEY AUTO_INCREMENT,
    subject_name VARCHAR(100) NOT NULL,
    academic_year VARCHAR(50),
    description TEXT,
    color VARCHAR(7) DEFAULT '#3498db'
);

-- قاعدة بيانات الفصول
CREATE TABLE classes (
    class_id INT PRIMARY KEY AUTO_INCREMENT,
    class_name VARCHAR(100) NOT NULL,
    grade_level VARCHAR(50),
    capacity INT,
    academic_year VARCHAR(50)
);

-- قاعدة بيانات الحصص (المواعيد)
CREATE TABLE class_slots (
    slot_id INT PRIMARY KEY AUTO_INCREMENT,
    slot_number INT NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    duration_minutes INT
);

-- قاعدة بيانات الجدول الدراسي
CREATE TABLE timetable (
    timetable_id INT PRIMARY KEY AUTO_INCREMENT,
    class_id INT NOT NULL,
    subject_id INT NOT NULL,
    teacher_id INT NOT NULL,
    slot_id INT NOT NULL,
    day_of_week ENUM('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'),
    effective_date DATE,
    academic_year VARCHAR(50),
    FOREIGN KEY (class_id) REFERENCES classes(class_id),
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id),
    FOREIGN KEY (teacher_id) REFERENCES teachers(teacher_id),
    FOREIGN KEY (slot_id) REFERENCES class_slots(slot_id)
);

-- إضافة مواعيد الحصص
INSERT INTO class_slots (slot_number, start_time, end_time, duration_minutes) VALUES
(1, '08:00:00', '08:45:00', 45),
(2, '08:45:00', '09:30:00', 45),
(3, '09:30:00', '10:15:00', 45),
(4, '10:15:00', '11:00:00', 45),
(5, '11:00:00', '11:45:00', 45),
(6, '11:45:00', '12:30:00', 45);

-- إضافة المواد الدراسية
INSERT INTO subjects (subject_name, academic_year, color) VALUES
('رياضيات', '2023-2024', '#3498db'),
('علوم', '2023-2024', '#2ecc71'),
('لغة عربية', '2023-2024', '#e74c3c'),
('لغة إنجليزية', '2023-2024', '#9b59b6'),
('تاريخ', '2023-2024', '#f39c12'),
('جغرافيا', '2023-2024', '#1abc9c');

-- إضافة الفصول
INSERT INTO classes (class_name, grade_level, capacity, academic_year) VALUES
('الصف الأول أ', 'الصف الأول', 30, '2023-2024'),
('الصف الأول ب', 'الصف الأول', 30, '2023-2024'),
('الصف الثاني أ', 'الصف الثاني', 30, '2023-2024'),
('الصف الثاني ب', 'الصف الثاني', 30, '2023-2024');

-- إضافة المدرسين
INSERT INTO teachers (name, phone, email, main_subject_id) VALUES
('أحمد محمد', '01012345678', 'ahmed@school.com', 1),
('فاطمة إبراهيم', '01087654321', 'fatma@school.com', 2),
('محمد السيد', '01011112222', 'mohamed@school.com', 3),
('سارة عبدالله', '01033334444', 'sara@school.com', 4);



INSERT INTO teachers (name, phone, email, main_subject_id) VALUES
('أحمد محمد', '01012345678', 'ahmed@school.com', 1),
('فاطمة إبراهيم', '01087654321', 'fatma@school.com', 2),
('محمد السيد', '01011112222', 'mohamed@school.com', 3),
('سارة عبدالله', '01033334444', 'sara@school.com', 4),
('خالد محمود', '01055556666', 'khaled@school.com', 5),
('منى حسن', '01077778888', 'mona@school.com', 6);

INSERT INTO subjects (subject_name, academic_year, color) VALUES
('رياضيات', '2023-2024', '#3498db'),
('علوم', '2023-2024', '#2ecc71'),
('لغة عربية', '2023-2024', '#e74c3c'),
('لغة إنجليزية', '2023-2024', '#9b59b6'),
('تاريخ', '2023-2024', '#f39c12'),
('جغرافيا', '2023-2024', '#1abc9c'),
('تربية دينية', '2023-2024', '#34495e'),
('تربية بدنية', '2023-2024', '#d35400');


INSERT INTO classes (class_name, grade_level, capacity, academic_year) VALUES
('الصف الأول أ', 'الصف الأول', 30, '2023-2024'),
('الصف الأول ب', 'الصف الأول', 30, '2023-2024'),
('الصف الثاني أ', 'الصف الثاني', 30, '2023-2024'),
('الصف الثاني ب', 'الصف الثاني', 30, '2023-2024'),
('الصف الثالث أ', 'الصف الثالث', 30, '2023-2024'),
('الصف الثالث ب', 'الصف الثالث', 30, '2023-2024');


-- الأحد للصف الأول أ
INSERT INTO timetable (class_id, subject_id, teacher_id, slot_id, day_of_week, effective_date, academic_year) VALUES
(1, 1, 1, 1, 'sunday', CURDATE(), '2023-2024'),
(1, 2, 2, 2, 'sunday', CURDATE(), '2023-2024'),
(1, 3, 3, 3, 'sunday', CURDATE(), '2023-2024'),
(1, 4, 4, 4, 'sunday', CURDATE(), '2023-2024'),
(1, 7, 3, 5, 'sunday', CURDATE(), '2023-2024'),
(1, 8, 1, 6, 'sunday', CURDATE(), '2023-2024');

-- الإثنين للصف الأول أ
INSERT INTO timetable (class_id, subject_id, teacher_id, slot_id, day_of_week, effective_date, academic_year) VALUES
(1, 4, 4, 1, 'monday', CURDATE(), '2023-2024'),
(1, 1, 1, 2, 'monday', CURDATE(), '2023-2024'),
(1, 5, 5, 3, 'monday', CURDATE(), '2023-2024'),
(1, 6, 6, 4, 'monday', CURDATE(), '2023-2024'),
(1, 2, 2, 5, 'monday', CURDATE(), '2023-2024'),
(1, 3, 3, 6, 'monday', CURDATE(), '2023-2024');

-- الثلاثاء للصف الأول أ
INSERT INTO timetable (class_id, subject_id, teacher_id, slot_id, day_of_week, effective_date, academic_year) VALUES
(1, 3, 3, 1, 'tuesday', CURDATE(), '2023-2024'),
(1, 4, 4, 2, 'tuesday', CURDATE(), '2023-2024'),
(1, 1, 1, 3, 'tuesday', CURDATE(), '2023-2024'),
(1, 2, 2, 4, 'tuesday', CURDATE(), '2023-2024'),
(1, 6, 6, 5, 'tuesday', CURDATE(), '2023-2024'),
(1, 8, 1, 6, 'tuesday', CURDATE(), '2023-2024');

-- الأربعاء للصف الأول أ
INSERT INTO timetable (class_id, subject_id, teacher_id, slot_id, day_of_week, effective_date, academic_year) VALUES
(1, 2, 2, 1, 'wednesday', CURDATE(), '2023-2024'),
(1, 1, 1, 2, 'wednesday', CURDATE(), '2023-2024'),
(1, 5, 5, 3, 'wednesday', CURDATE(), '2023-2024'),
(1, 4, 4, 4, 'wednesday', CURDATE(), '2023-2024'),
(1, 3, 3, 5, 'wednesday', CURDATE(), '2023-2024'),
(1, 7, 3, 6, 'wednesday', CURDATE(), '2023-2024');

-- الخميس للصف الأول أ
INSERT INTO timetable (class_id, subject_id, teacher_id, slot_id, day_of_week, effective_date, academic_year) VALUES
(1, 1, 1, 1, 'thursday', CURDATE(), '2023-2024'),
(1, 6, 6, 2, 'thursday', CURDATE(), '2023-2024'),
(1, 4, 4, 3, 'thursday', CURDATE(), '2023-2024'),
(1, 2, 2, 4, 'thursday', CURDATE(), '2023-2024'),
(1, 5, 5, 5, 'thursday', CURDATE(), '2023-2024'),
(1, 8, 1, 6, 'thursday', CURDATE(), '2023-2024');

-- الصف الأول ب (جدول مختلف قليلاً)
INSERT INTO timetable (class_id, subject_id, teacher_id, slot_id, day_of_week, effective_date, academic_year) VALUES
(2, 3, 3, 1, 'sunday', CURDATE(), '2023-2024'),
(2, 4, 4, 2, 'sunday', CURDATE(), '2023-2024'),
(2, 1, 1, 3, 'sunday', CURDATE(), '2023-2024'),
(2, 2, 2, 4, 'sunday', CURDATE(), '2023-2024'),
(2, 8, 1, 5, 'sunday', CURDATE(), '2023-2024'),
(2, 7, 3, 6, 'sunday', CURDATE(), '2023-2024'),

(2, 1, 1, 1, 'monday', CURDATE(), '2023-2024'),
(2, 2, 2, 2, 'monday', CURDATE(), '2023-2024'),
(2, 5, 5, 3, 'monday', CURDATE(), '2023-2024'),
(2, 6, 6, 4, 'monday', CURDATE(), '2023-2024'),
(2, 4, 4, 5, 'monday', CURDATE(), '2023-2024'),
(2, 3, 3, 6, 'monday', CURDATE(), '2023-2024');



CREATE table attendance_teacher(
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    teacher_id INT(6) NOT NULL,
    state varchar(100) NOT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    description VARCHAR(255) NOT NULL
);
