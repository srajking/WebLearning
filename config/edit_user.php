<?php
include '../config/db.php';

// استرجاع البيانات من النموذج
$userId = $_POST['userId'];
$username = $_POST['username'];
$password = $_POST['password'];
$role = $_POST['role'];

// إذا تم إدخال كلمة مرور جديدة، يتم تحديثها
if (!empty($password)) {
    $password = password_hash($password, PASSWORD_DEFAULT); // تشفير كلمة المرور
    $query = "UPDATE users SET username='$username', password='$password', role='$role' WHERE id='$userId'";
} else {
    // إذا لم يتم إدخال كلمة مرور جديدة، يتم تحديث الاسم والصلاحية فقط
    $query = "UPDATE users SET username='$username', role='$role' WHERE id='$userId'";
}

if (mysqli_query($conn, $query)) {
    header("Location: ../pages/login.php?success=تم تعديل المستخدم بنجاح");
} else {
    header("Location: ../pages/login.php?error=حدث خطأ أثناء تعديل المستخدم");
}
?>