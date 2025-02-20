<?php
// الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "admin_panel";

// إنشاء الاتصال
$conn = mysqli_connect($servername, $username, $password, $dbname);

// استعلام لجلب البيانات من جدول المنتجات
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);

// التحقق إذا كانت هناك بيانات في قاعدة البيانات
if (mysqli_num_rows($result) > 0) {
    
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['product_name']}</td>
                <td>{$row['available_quantity']}</td>
                <td>{$row['sold_quantity']}</td>
                <td>{$row['unit']}</td>
                <td>{$row['customer_price']}</td>
                <td>{$row['member_price']}</td>
                <td>{$row['product_points']}</td>
                <td>{$row['barcode']}</td>
                <td>{$row['category']}</td>
                <td>{$row['company']}</td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p class='text-center'>لا توجد منتجات في قاعدة البيانات</p>";
}

// إغلاق الاتصال بعد الانتهاء
mysqli_close($conn);
?>