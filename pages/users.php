<?php include '../includes/header.php'; ?>
<div class="row">
    <div class="col-12">
        <div>
            <!-- add teacher -->
            <button type="button" class="btn btn-outline-primary">اضافة مستخدم</button>
        </div>
    </div>
</div>
<div class="row">
    <div id="buildings-container" class="buildings-grid">
        <div class="loading">جاري تحميل بيانات المستخدمين...</div>
    </div>
    <div id="floor-details" class="floor-details">
        <h3 id="floor-title">تفاصيل المستخدم</h3>
        <div id="rooms-container" class="rooms-grid"></div>
    </div>
</div>

<script src="../public/js/pages/users.js"></script>

<?php include '../includes/footer.php'; ?>