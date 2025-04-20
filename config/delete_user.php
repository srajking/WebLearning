<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = mysqli_real_escape_string($conn, $_POST['userId']);

    // حذف المستخدم من قاعدة البيانات
    $query = "DELETE FROM users WHERE id = '$userId'";
    if (mysqli_query($conn, $query)) {
        echo "تم حذف المستخدم بنجاح";
    } else {
        echo "حدث خطأ أثناء حذف المستخدم";
    }
}