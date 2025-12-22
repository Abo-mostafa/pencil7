<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مسح QR للبحث في قاعدة البيانات</title>
    <style>
        /* تنسيقات عامة */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            padding: 20px;
            direction: rtl;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
            text-align: center;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        
        /* تنسيقات حقل البحث */
        .search-container {
            margin-bottom: 30px;
            position: relative;
        }
        
        .search-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .search-input-group {
            display: flex;
            gap: 10px;
        }
        
        #search-input {
            flex: 1;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        #search-input:focus {
            outline: none;
            border-color: #3498db;
        }
        
        #search-btn {
            background-color: #2ecc71;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
        }
        
        #search-btn:hover {
            background-color: #27ae60;
        }
        
        #scan-btn {
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
        }
        
        #scan-btn:hover {
            background-color: #2980b9;
        }
        
        #scan-btn.scanning {
            background-color: #e74c3c;
        }
        
        #scan-btn.scanning:hover {
            background-color: #c0392b;
        }
        
        /* تنسيقات ماسح QR */
        .scanner-section {
            display: none;
            margin-top: 20px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border: 2px dashed #3498db;
        }
        
        .scanner-section.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .camera-container {
            position: relative;
            width: 100%;
            height: 300px;
            overflow: hidden;
            border-radius: 6px;
            background-color: #000;
            margin-bottom: 15px;
        }
        
        #qr-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .scanner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .scanner-frame {
            position: relative;
            width: 200px;
            height: 200px;
            border: 2px solid rgba(255, 255, 255, 0.5);
        }
        
        .frame-corner {
            position: absolute;
            width: 25px;
            height: 25px;
            border-color: #3498db;
            border-style: solid;
        }
        
        .top-left {
            top: -2px;
            right: -2px;
            border-width: 4px 4px 0 0;
            border-top-right-radius: 6px;
        }
        
        .top-right {
            top: -2px;
            left: -2px;
            border-width: 4px 0 0 4px;
            border-top-left-radius: 6px;
        }
        
        .bottom-left {
            bottom: -2px;
            right: -2px;
            border-width: 0 4px 4px 0;
            border-bottom-right-radius: 6px;
        }
        
        .bottom-right {
            bottom: -2px;
            left: -2px;
            border-width: 0 0 4px 4px;
            border-bottom-left-radius: 6px;
        }
        
        .scan-line {
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(to left, transparent, #3498db, transparent);
            animation: scan 2s linear infinite;
        }
        
        @keyframes scan {
            0% { top: 0; }
            50% { top: calc(100% - 3px); }
            100% { top: 0; }
        }
        
        .scanner-info {
            text-align: center;
            color: #7f8c8d;
            font-size: 14px;
            margin-top: 10px;
        }
        
        /* تنسيقات النتائج */
        .results-section {
            margin-top: 30px;
            display: none;
        }
        
        .results-section.has-results {
            display: block;
        }
        
        .result-card {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            border-right: 4px solid #2ecc71;
        }
        
        .result-title {
            color: #2c3e50;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .result-details {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        
        .detail-item {
            background-color: white;
            padding: 12px;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .detail-label {
            font-weight: 600;
            color: #3498db;
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .detail-value {
            color: #2c3e50;
            font-size: 16px;
        }
        
        /* رسالة حالة */
        .status-message {
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            display: none;
        }
        
        .status-message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            display: block;
        }
        
        .status-message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            display: block;
        }
        
        .status-message.info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
            display: block;
        }
        
        /* تنسيقات الأزرار */
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            justify-content: center;
        }
        
        .action-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .action-btn.primary {
            background-color: #3498db;
            color: white;
        }
        
        .action-btn.primary:hover {
            background-color: #2980b9;
        }
        
        .action-btn.secondary {
            background-color: #95a5a6;
            color: white;
        }
        
        .action-btn.secondary:hover {
            background-color: #7f8c8d;
        }
        
        /* تصميم متجاوب */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            
            .search-input-group {
                flex-direction: column;
            }
            
            .camera-container {
                height: 250px;
            }
            
            .scanner-frame {
                width: 180px;
                height: 180px;
            }
            
            .result-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 البحث في قاعدة البيانات باستخدام QR</h1>
        
        <!-- رسالة حالة -->
        <div id="status-message" class="status-message"></div>
        
        <!-- حقل البحث مع زر المسح -->
        <div class="search-container">
            <label for="search-input" class="search-label">أدخل الرقم أو الكود للبحث:</label>
            <div class="search-input-group">
                <input type="text" id="search-input" placeholder="أدخل الرقم أو الكود أو استخدم ماسح QR">
                <button id="scan-btn" class="scan-btn">
                    <span class="btn-icon">📷</span>
                    <span class="btn-text">مسح QR</span>
                </button>
                <button id="search-btn" class="search-btn">
                    <span class="btn-icon">🔍</span>
                    <span class="btn-text">بحث</span>
                </button>
            </div>
        </div>
        
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
        
        <!-- قسم النتائج -->
        <div id="results-section" class="results-section">
            <div class="result-card">
                <h3 class="result-title">📋 نتيجة البحث</h3>
                <div id="result-content">
                    <!-- سيتم ملء هذا القسم ديناميكياً -->
                </div>
                <div class="action-buttons">
                    <button id="clear-results" class="action-btn secondary">
                        <span>🗑️</span> مسح النتائج
                    </button>
                    <button id="new-search" class="action-btn primary">
                        <span>🔍</span> بحث جديد
                    </button>
                </div>
            </div>
        </div>
        
        <!-- معلومات التطبيق -->
        <div style="margin-top: 30px; padding: 15px; background-color: #f1f8ff; border-radius: 6px; font-size: 14px; color: #555;">
            <p><strong>كيفية الاستخدام:</strong></p>
            <ol style="padding-right: 20px; margin-top: 10px;">
                <li>أدخل الرقم أو الكود يدوياً في حقل البحث أو استخدم زر "مسح QR"</li>
                <li>عند الضغط على "مسح QR"، سيظهر الماسح الضوئي</li>
                <li>وجه الكاميرا نحو رمز QR وسيتم قراءته تلقائياً</li>
                <li>لإيقاف الماسح، اضغط مرة أخرى على زر "مسح QR"</li>
                <li>اضغط على زر "بحث" لتنفيذ عملية البحث في قاعدة البيانات</li>
            </ol>
        </div>
    </div>

    <!-- مكتبة jsQR لقراءة رموز QR -->
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
    
    <script>
        // العناصر الأساسية
        const searchInput = document.getElementById('search-input');
        const scanBtn = document.getElementById('scan-btn');
        const searchBtn = document.getElementById('search-btn');
        const scannerSection = document.getElementById('scanner-section');
        const video = document.getElementById('qr-video');
        const resultsSection = document.getElementById('results-section');
        const resultContent = document.getElementById('result-content');
        const statusMessage = document.getElementById('status-message');
        const clearResultsBtn = document.getElementById('clear-results');
        const newSearchBtn = document.getElementById('new-search');
        
        // المتغيرات العامة
        let isScanning = false;
        let stream = null;
        let animationFrameId = null;
        let lastScannedCode = null;
        
        // عرض رسالة حالة
        function showStatusMessage(message, type = 'info') {
            statusMessage.textContent = message;
            statusMessage.className = `status-message ${type}`;
            
            // إخفاء الرسالة بعد 5 ثوانٍ للنوع info
            if (type === 'info') {
                setTimeout(() => {
                    statusMessage.className = 'status-message';
                }, 5000);
            }
        }
        
        // تبديل حالة الماسح الضوئي
        async function toggleScanner() {
            if (!isScanning) {
                // تشغيل الماسح
                try {
                    await startScanner();
                    isScanning = true;
                    scanBtn.innerHTML = '<span class="btn-icon">⏹️</span><span class="btn-text">إيقاف المسح</span>';
                    scanBtn.classList.add('scanning');
                    scannerSection.classList.add('active');
                    showStatusMessage('تم تشغيل الماسح الضوئي. وجه الكاميرا نحو رمز QR.', 'info');
                    
                    // بدء المسح
                    startQRScanning();
                    
                } catch (error) {
                    console.error('خطأ في تشغيل الماسح:', error);
                    showStatusMessage('فشل في تشغيل الكاميرا. تأكد من منح الإذن.', 'error');
                }
            } else {
                // إيقاف الماسح
                stopScanner();
                isScanning = false;
                scanBtn.innerHTML = '<span class="btn-icon">📷</span><span class="btn-text">مسح QR</span>';
                scanBtn.classList.remove('scanning');
                scannerSection.classList.remove('active');
                showStatusMessage('تم إيقاف الماسح الضوئي.', 'info');
            }
        }
        
        // تشغيل الماسح الضوئي
        async function startScanner() {
            // إيقاف الماسح إذا كان يعمل
            if (stream) {
                stopScanner();
            }
            
            // طلب إذن الكاميرا
            stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'environment', // استخدام الكاميرا الخلفية
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                }
            });
            
            video.srcObject = stream;
            
            // انتظار تحميل الفيديو
            return new Promise((resolve) => {
                video.onloadedmetadata = () => {
                    video.play();
                    resolve();
                };
            });
        }
        
        // إيقاف الماسح الضوئي
        function stopScanner() {
            if (animationFrameId) {
                cancelAnimationFrame(animationFrameId);
                animationFrameId = null;
            }
            
            if (stream) {
                const tracks = stream.getTracks();
                tracks.forEach(track => track.stop());
                stream = null;
            }
            
            if (video.srcObject) {
                video.srcObject = null;
            }
        }
        
        // بدء عملية مسح QR
        function startQRScanning() {
            if (!stream) return;
            
            // إنشاء عنصر canvas لمعالجة الصور
            const canvas = document.createElement('canvas');
            const canvasContext = canvas.getContext('2d');
            
            // دورة المسح
            function scanFrame() {
                if (!isScanning || !stream) return;
                
                // التأكد من أن الفيديو جاهز
                if (video.readyState === video.HAVE_ENOUGH_DATA) {
                    // تعيين أبعاد canvas
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    
                    // رسم الفيديو على canvas
                    canvasContext.drawImage(video, 0, 0, canvas.width, canvas.height);
                    
                    // الحصول على بيانات الصورة
                    const imageData = canvasContext.getImageData(0, 0, canvas.width, canvas.height);
                    
                    // استخدام jsQR لقراءة رمز QR
                    const code = jsQR(imageData.data, imageData.width, imageData.height);
                    
                    // إذا تم العثور على رمز QR
                    if (code && code.data) {
                        // منع تكرار نفس الرمز
                        if (code.data !== lastScannedCode) {
                            lastScannedCode = code.data;
                            
                            // عرض النتيجة في حقل البحث
                            searchInput.value = code.data;
                            
                            // عرض رسالة في الكونسول
                            console.log(`✅ تم مسح QR بنجاح: ${code.data}`);
                            console.log(`⏱️ الوقت: ${new Date().toLocaleTimeString()}`);
                            
                            // عرض إشعار للمستخدم
                            showStatusMessage(`تم قراءة الرمز: ${code.data}`, 'success');
                            
                            // إيقاف الماسح تلقائياً بعد القراءة
                            setTimeout(() => {
                                if (isScanning) {
                                    toggleScanner();
                                    
                                    // تلقائياً نفذ البحث بعد 1 ثانية
                                    setTimeout(() => {
                                        executeSearch();
                                    }, 1000);
                                }
                            }, 1000);
                        }
                    }
                }
                
                // الاستمرار في المسح إذا كان الماسح لا يزال نشطاً
                if (isScanning) {
                    animationFrameId = requestAnimationFrame(scanFrame);
                }
            }
            
            // بدء دورة المسح
            scanFrame();
        }
        
        // تنفيذ البحث (محاكاة للبحث في قاعدة البيانات)
        function executeSearch() {
            const searchValue = searchInput.value.trim();
            
            if (!searchValue) {
                showStatusMessage('الرجاء إدخال قيمة للبحث.', 'error');
                return;
            }
            
            // عرض رسالة بحث
            showStatusMessage(`جارٍ البحث عن: "${searchValue}"...`, 'info');
            
            // محاكاة تأخير البحث في قاعدة البيانات
            setTimeout(() => {
                // نتائج محاكاة (في التطبيق الحقيقي، ستأتي هذه البيانات من قاعدة البيانات)
                const mockResults = generateMockResults(searchValue);
                
                // عرض النتائج
                displayResults(searchValue, mockResults);
                
                // عرض رسالة نجاح
                showStatusMessage(`تم العثور على ${mockResults.items.length} نتيجة للبحث عن: "${searchValue}"`, 'success');
                
                // تسجيل في الكونسول
                console.log(`🔍 تم البحث عن: ${searchValue}`);
                console.log(`📊 عدد النتائج: ${mockResults.items.length}`);
                console.log(`📅 وقت البحث: ${new Date().toLocaleTimeString()}`);
                
            }, 1500);
        }
        
        // توليد نتائج محاكاة للبحث
        function generateMockResults(searchTerm) {
            const mockData = {
                searchTerm: searchTerm,
                timestamp: new Date().toLocaleString('ar-SA'),
                items: []
            };
            
            // توليد بيانات محاكاة بناءً على مصطلح البحث
            if (searchTerm.startsWith('PROD')) {
                // محاكاة منتجات
                mockData.type = 'منتج';
                mockData.items = [
                    { label: 'رقم المنتج', value: searchTerm },
                    { label: 'اسم المنتج', value: `منتج ${searchTerm.substring(4)}` },
                    { label: 'الفئة', value: 'إلكترونيات' },
                    { label: 'السعر', value: `${Math.floor(Math.random() * 1000) + 100} ريال` },
                    { label: 'المخزون', value: `${Math.floor(Math.random() * 100)} وحدة` },
                    { label: 'الحالة', value: 'متوفر' }
                ];
            } else if (searchTerm.startsWith('EMP')) {
                // محاكاة موظفين
                mockData.type = 'موظف';
                mockData.items = [
                    { label: 'رقم الموظف', value: searchTerm },
                    { label: 'الاسم', value: `موظف ${searchTerm.substring(3)}` },
                    { label: 'القسم', value: 'المبيعات' },
                    { label: 'الراتب', value: `${Math.floor(Math.random() * 5000) + 3000} ريال` },
                    { label: 'تاريخ التعيين', value: '2023-01-15' },
                    { label: 'الحالة', value: 'نشط' }
                ];
            } else if (searchTerm.startsWith('ORD')) {
                // محاكاة طلبات
                mockData.type = 'طلب';
                mockData.items = [
                    { label: 'رقم الطلب', value: searchTerm },
                    { label: 'تاريخ الطلب', value: '2023-10-01' },
                    { label: 'الحالة', value: 'مكتمل' },
                    { label: 'المجموع', value: `${Math.floor(Math.random() * 1000) + 50} ريال` },
                    { label: 'طريقة الدفع', value: 'بطاقة ائتمان' },
                    { label: 'العنوان', value: 'الرياض، السعودية' }
                ];
            } else if (/^\d+$/.test(searchTerm)) {
                // محاكاة أرقام
                mockData.type = 'رقم تسلسلي';
                mockData.items = [
                    { label: 'الرقم', value: searchTerm },
                    { label: 'النوع', value: 'رقم تسلسلي' },
                    { label: 'تاريخ الإنشاء', value: new Date().toLocaleDateString('ar-SA') },
                    { label: 'الحالة', value: 'نشط' },
                    { label: 'الملاحظات', value: 'تم إنشاؤه تلقائياً' }
                ];
            } else {
                // محاكاة بيانات عامة
                mockData.type = 'عام';
                mockData.items = [
                    { label: 'القيمة المدخلة', value: searchTerm },
                    { label: 'نوع البحث', value: 'نص حر' },
                    { label: 'وقت البحث', value: new Date().toLocaleTimeString('ar-SA') },
                    { label: 'الحالة', value: 'تم العثور على تطابقات' },
                    { label: 'عدد النتائج', value: `${Math.floor(Math.random() * 5) + 1}` }
                ];
            }
            
            return mockData;
        }
        
        // عرض النتائج
        function displayResults(searchTerm, results) {
            // تفعيل قسم النتائج
            resultsSection.classList.add('has-results');
            
            // إنشاء محتوى النتائج
            let html = `
                <div class="result-details">
                    <div class="detail-item">
                        <div class="detail-label">كلمة البحث</div>
                        <div class="detail-value">${searchTerm}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">نوع النتيجة</div>
                        <div class="detail-value">${results.type}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">وقت البحث</div>
                        <div class="detail-value">${results.timestamp}</div>
                    </div>
            `;
            
            // إضافة العناصر الأخرى
            results.items.forEach(item => {
                html += `
                    <div class="detail-item">
                        <div class="detail-label">${item.label}</div>
                        <div class="detail-value">${item.value}</div>
                    </div>
                `;
            });
            
            html += `</div>`;
            
            // تعيين المحتوى
            resultContent.innerHTML = html;
            
            // تمرير النتائج إلى أعلى الصفحة
            resultsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
        
        // مسح النتائج
        function clearResults() {
            resultsSection.classList.remove('has-results');
            resultContent.innerHTML = '';
            searchInput.value = '';
            searchInput.focus();
            showStatusMessage('تم مسح النتائج.', 'info');
        }
        
        // بحث جديد
        function newSearch() {
            clearResults();
            showStatusMessage('أدخل قيمة جديدة للبحث أو استخدم ماسح QR.', 'info');
        }
        
        // تعريف الأحداث
        scanBtn.addEventListener('click', toggleScanner);
        searchBtn.addEventListener('click', executeSearch);
        clearResultsBtn.addEventListener('click', clearResults);
        newSearchBtn.addEventListener('click', newSearch);
        
        // البحث عند الضغط على Enter في حقل الإدخال
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                executeSearch();
            }
        });
        
        // إيقاف الماسح عند مغادرة الصفحة
        window.addEventListener('beforeunload', () => {
            if (isScanning) {
                stopScanner();
            }
        });
        
        // عرض رسالة ترحيب عند التحميل
        window.addEventListener('DOMContentLoaded', () => {
            showStatusMessage('مرحباً! أدخل الرقم أو الكود للبحث أو استخدم ماسح QR.', 'info');
            console.log('✅ نظام مسح QR للبحث في قاعدة البيانات جاهز للاستخدام');
            console.log('⏱️ وقت التحميل:', new Date().toLocaleTimeString());
        });
    </script>
</body>
</html>