// العناصر
const qrBtn = document.getElementById('qr-btn');
const qrInput = document.getElementById('qr-input');
const scannerSection = document.getElementById('scanner-section');
const video = document.getElementById('qr-video');

let scanning = false;
let stream = null;
let rafId = null;
let lastCode = null;

// تشغيل / إيقاف الماسح
qrBtn.addEventListener('click', async () => {
    if (!scanning) {
        await startScanner();
    } else {
        stopScanner();
    }
});

// تشغيل الكاميرا
async function startScanner() {
    try {
        stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'environment' }
        });

        video.srcObject = stream;
        await video.play();

        scannerSection.classList.add('active');
        qrBtn.textContent = '⏹️ إيقاف المسح';
        scanning = true;

        scanLoop();
    } catch (e) {
        alert('❌ لم يتم السماح باستخدام الكاميرا');
        console.error(e);
    }
}

// إيقاف الكاميرا
function stopScanner() {
    scanning = false;

    if (rafId) cancelAnimationFrame(rafId);

    if (stream) {
        stream.getTracks().forEach(t => t.stop());
        stream = null;
    }

    video.srcObject = null;
    scannerSection.classList.remove('active');
    qrBtn.textContent = 'start scan QR 📱📸';
}

// حلقة قراءة QR
function scanLoop() {
    if (!scanning) return;

    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');

    if (video.readyState === video.HAVE_ENOUGH_DATA) {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        ctx.drawImage(video, 0, 0);
        const img = ctx.getImageData(0, 0, canvas.width, canvas.height);

        const code = jsQR(img.data, img.width, img.height);

        if (code && code.data && code.data !== lastCode) {
            lastCode = code.data;

            // إدخال القيمة
            qrInput.value = code.data;

            console.log('✅ QR:', code.data);

            // تشغيل البحث
            if (typeof filterTeachers === 'function') {
                filterTeachers();
            }

            // إيقاف الماسح
            setTimeout(stopScanner, 500);
            return;
        }
    }

    rafId = requestAnimationFrame(scanLoop);
}

// إيقاف الكاميرا عند الخروج
window.addEventListener('beforeunload', stopScanner);
