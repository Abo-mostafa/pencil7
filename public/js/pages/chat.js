// heartbeat لتحديث حالة الاونلاين
setInterval(()=>{
if (!window.currentUser) return;
fetch('../api/onlineChat.php', {method:'POST'}).catch(()=>{});
}, 10000);


// اطلاق صوت الاشعار جاهز (موجود في chat.php عند استقبال رسالة)
function ring() { document.getElementById('ring').play(); }



