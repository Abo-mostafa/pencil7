<?php include '../includes/header.php'; ?>
<script>window.currentUser = <?php echo json_encode($username); ?>;</script>
<link rel="stylesheet" href="../public/css/chat.css">
<!-- $username = $_SESSION['user']['username'] -->
<?php
if (!isset($_SESSION['user']['username'])) {
    header('Location: /login.php');
    exit;
}
$me = $_SESSION['user']['username'];
$other = $_GET['user'] ?? null;
if (!$other) {
    echo "اختر شخصًا للتحدث";
    include __DIR__ . '/../includes/footer.php';
    exit;
}
?>


<div class="chat-page">
    <aside>

    </aside>
    <main>
        <h3>الدردشة مع: <?php echo htmlentities($other); ?></h3>
        <div id="chat-box" data-with="<?php echo htmlentities($other); ?>"></div>
        <div id="composer">
            <input id="msg-input" placeholder="اكتب رسالتك...">
            <button id="send">إرسال</button>
        </div>
    </main>
</div>


<script>
/* ======== 1) تعريف المتغيرات الأساسية ======== */
const me    = "<?php echo $_SESSION['user']['username']; ?>";
const other = "<?php echo $_GET['user']; ?>";

let lastLen = 0;


/* ======== 2) دالة حماية HTML ======== */
function escapeHtml(t){
    return t.replace(/[&<>"']/g, m => ({
        '&':'&amp;',
        '<':'&lt;',
        '>':'&gt;',
        '"':'&quot;',
        "'":'&#39;'
    }[m]));
}


/* ======== 3) تحميل الرسائل ======== */
async function load(){
    let res = await fetch("../api/onlineChat.php?action=get&user=" + encodeURIComponent(other), {
        credentials: "include"
    });

    let chat = await res.json();
    const box = document.getElementById("chat-box");

    box.innerHTML = "";

    chat.forEach(item => {
        const el = document.createElement("div");
        el.className = item.from === me ? "msg msg-me" : "msg msg-other";
        el.innerHTML = `<b>${item.from}</b>: ${escapeHtml(item.msg)}`;
        box.appendChild(el);
    });

    // لو فيه رسائل جديدة
    if (chat.length > lastLen && chat.length > 0){
        const last = chat[chat.length - 1];
        if (last.from !== me){
            notifyIncoming();
        }
    }

    lastLen = chat.length;
    box.scrollTop = box.scrollHeight;
}

// نعيد التحميل كل ثانية
setInterval(load, 1000);
load();


/* ======== 4) إرسال الرسالة ======== */
async function sendMessage(){
    const input = document.getElementById("msg-input");
    let text = input.value.trim();
    if (!text) return;

    await fetch("../api/onlineChat.php?action=send", {
        method: "POST",
        credentials: "include",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({
            to: other,
            msg: text
        })
    });

    input.value = "";
    load(); // عرض الرسالة مباشرة
}

document.getElementById("send").onclick = sendMessage;

// Enter لإرسال الرسالة
document.getElementById("msg-input").addEventListener("keydown", e => {
    if (e.key === "Enter"){
        e.preventDefault();
        sendMessage();
    }
});


/* ======== 5) تنبيه عند وصول رسالة جديدة ======== */
function notifyIncoming(){
    try {
        new Audio("../assets/sound/notify.mp3").play();
    } catch (e){}
}

/* ======== 6) Heartbeat (يضمن استمرار تسجيل المتصلين) ======== */
setInterval(()=>{
    fetch("../api/onlineUser.php", {
        method: "POST",
        credentials: "include"
    });
}, 5000);

</script>

    <?php include '../includes/footer.php'; ?>