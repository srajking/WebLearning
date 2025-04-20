<?php
include 'db.php';
session_start(); // للحصول على اسم المستخدم من الجلسة

if (!isset($_SESSION['username'])) {
    echo json_encode(["success" => false, "message" => "يجب تسجيل الدخول"]);
    exit;
}

$id = $_POST['id'];
$sold_quantity = intval($_POST['sold_quantity']);
$username = $_SESSION['username']; // اسم المستخدم الذي قام بالتعديل

// استرجاع العدد المتوفر حاليًا
$result = mysqli_query($conn, "SELECT available_quantity FROM products WHERE id='$id'");
$row = mysqli_fetch_assoc($result);
$available_quantity = intval($row['available_quantity']);

// حساب العدد المتبقي
$remaining_quantity = $available_quantity - $sold_quantity;
if ($remaining_quantity < 0) {
    echo json_encode(["success" => false, "message" => "الكمية غير كافية"]);
    exit;
}

// تحديث جدول المنتجات
$updateProductSql = "UPDATE products SET 
    available_quantity='$remaining_quantity',
    sold_quantity='$sold_quantity'
    WHERE id='$id'";

$updateProductResult = mysqli_query($conn, $updateProductSql);

// تسجيل العملية في جدول `product_updates`
$insertUpdateSql = "INSERT INTO product_updates 
    (product_id, sold_quantity, remaining_quantity, updated_by) 
    VALUES ('$id', '$sold_quantity', '$remaining_quantity', '$username')";

$insertUpdateResult = mysqli_query($conn, $insertUpdateSql);

$response = ["success" => $updateProductResult && $insertUpdateResult];
echo json_encode($response);

mysqli_close($conn);
