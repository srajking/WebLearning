<?php
include 'db.php';

// تعطيل قيود المفتاح الخارجي مؤقتًا
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0;");

// حذف السجلات المرتبطة أولاً
mysqli_query($conn, "DELETE FROM product_updates WHERE product_id IN (SELECT id FROM products);");

// الآن حذف السجلات من جدول المنتجات
$sql = "DELETE FROM products";
if (mysqli_query($conn, $sql)) {
    // إعادة تعيين الترقيم التلقائي
    mysqli_query($conn, "ALTER TABLE products AUTO_INCREMENT = 1;");
    echo "تم حذف جميع البيانات بنجاح! تم إعادة الترقيم!";
} else {
    echo "حدث خطأ أثناء الحذف: " . mysqli_error($conn);
}

// إعادة تفعيل قيود المفتاح الخارجي
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1;");

mysqli_close($conn);
