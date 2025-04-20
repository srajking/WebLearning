<?php
// الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "admin_panel";

// إنشاء الاتصال
$conn = mysqli_connect($servername, $username, $password, $dbname);

// التحقق من الاتصال
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// الحصول على البيانات من النموذج
$product_name = $_POST['product_name'];
$available_quantity = $_POST['available_quantity'];
$sold_quantity = $_POST['sold_quantity'];
$customer_price = $_POST['customer_price'];
$member_price = $_POST['member_price'];
$product_points = $_POST['product_points'];
$barcode = $_POST['barcode'];
$category = $_POST['category'];
$company = "إيدمارك"; // قيمة ثابتة
$unit = "قطعة"; // الوحدة الخاصة بالمنتج

// استعلام لإضافة المنتج
$sql = "INSERT INTO products (product_name, available_quantity, sold_quantity, customer_price, member_price, product_points, barcode, category, company, unit) 
VALUES ('$product_name', '$available_quantity', '$sold_quantity', '$customer_price', '$member_price', '$product_points', '$barcode', '$category', '$company', '$unit')";

// تنفيذ الاستعلام
if (mysqli_query($conn, $sql)) {
    echo "تم إضافة المنتج بنجاح!";
} else {
    echo "خطأ: " . $sql . "<br>" . mysqli_error($conn);
}

// إغلاق الاتصال
mysqli_close($conn);
?>