<?php
// الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "admin_panel";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("فشل الاتصال: " . mysqli_connect_error());
}

// التحقق من وجود product_id
if (!isset($_GET['product_id']) || empty($_GET['product_id'])) {
    echo json_encode(['success' => false, 'message' => 'لا يوجد معرف منتج']);
    exit;
}

$product_id = $_GET['product_id']; // الحصول على id المنتج من الرابط

// الاستعلام باستخدام Prepared Statements لتفادي الهجمات
$sql = "SELECT * FROM product_updates WHERE product_id = ? ORDER BY update_time DESC";
$stmt = mysqli_prepare($conn, $sql);

// ربط المعامل
mysqli_stmt_bind_param($stmt, "i", $product_id); // 'i' تشير إلى أن المعامل هو عدد صحيح (integer)

mysqli_stmt_execute($stmt);

// الحصول على النتيجة
$result = mysqli_stmt_get_result($stmt);

$logs = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $logs[] = [
            'sold_quantity' => $row['sold_quantity'],
            'remaining_quantity' => $row['remaining_quantity'],
            'update_time' => $row['update_time'],
            'updated_by' => $row['updated_by']
        ];
    }
    echo json_encode(['success' => true, 'logs' => $logs]);
} else {
    echo json_encode(['success' => false, 'message' => 'لا توجد سجلات لتحديثات هذا المنتج']);
}

// إغلاق الاتصال
mysqli_close($conn);