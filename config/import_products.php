<?php
require __DIR__ . '/../vendor/autoload.php';

// الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "admin_panel";
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("فشل الاتصال بقاعدة البيانات: " . mysqli_connect_error());
}

// استقبال البيانات من JavaScript
$excelData = json_decode($_POST['excelData'], true);

// تجاهل الصف الأول (العناوين)
array_shift($excelData);

// إدخال البيانات في قاعدة البيانات
foreach ($excelData as $row) {
    $product_name = $row[0];
    $available_quantity = $row[1];
    $sold_quantity = $row[2];
    $unit = $row[3];
    $customer_price = $row[4];
    $member_price = $row[5];
    $product_points = $row[6];
    $barcode = $row[7];
    $category = $row[8];
    $company = $row[9];

    $sql = "INSERT INTO products (product_name, available_quantity, sold_quantity,unit, customer_price, member_price, product_points, barcode, category, company)
            VALUES ('$product_name', '$available_quantity', '$sold_quantity', '$unit', '$customer_price', '$member_price', '$product_points', '$barcode', '$category', '$company')";

    if (!mysqli_query($conn, $sql)) {
        echo "خطأ في إدخال البيانات: " . mysqli_error($conn);
    }
}

echo "تم استيراد البيانات بنجاح!";
mysqli_close($conn);
