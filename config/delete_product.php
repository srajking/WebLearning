<?php
header("Content-Type: application/json"); // التأكد من أن الاستجابة ستكون JSON
error_reporting(0); // تعطيل عرض الأخطاء

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "admin_panel";

// إنشاء الاتصال
$conn = mysqli_connect($servername, $username, $password, $dbname);

// التحقق من الاتصال
if (!$conn) {
    echo json_encode(["status" => "error", "message" => "فشل الاتصال بقاعدة البيانات: " . mysqli_connect_error()]);
    exit();
}

// التأكد من استقبال ID المنتج للحذف
if (isset($_POST['id'])) {
    $productId = intval($_POST['id']); // تحويل الـ ID إلى رقم صحيح

    // تنفيذ الحذف
    $sql = "DELETE FROM products WHERE id = $productId";
    if (mysqli_query($conn, $sql)) {
        // إعادة ترتيب الأرقام بعد الحذف
        mysqli_query($conn, "SET @count = 0");
        mysqli_query($conn, "UPDATE products SET id = @count:= @count + 1");
        mysqli_query($conn, "ALTER TABLE products AUTO_INCREMENT = 1");

        echo json_encode(["status" => "success", "message" => "تم الحذف بنجاح!", "id" => $productId]);
        mysqli_close($conn); // إغلاق الاتصال
        exit(); // تأكد من إيقاف أي عملية أخرى بعد الاستجابة
    } else {
        echo json_encode(["status" => "error", "message" => "خطأ في تنفيذ الحذف: " . mysqli_error($conn)]);
        mysqli_close($conn); // إغلاق الاتصال
        exit(); // إيقاف أي عملية أخرى بعد الاستجابة
    }
} else {
    echo json_encode(["status" => "error", "message" => "لم يتم توفير ID المنتج للحذف"]);
    mysqli_close($conn); // إغلاق الاتصال
    exit(); // إيقاف أي عملية أخرى بعد الاستجابة
}