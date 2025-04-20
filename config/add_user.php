<?php
// استدعاء ملف الاتصال بقاعدة البيانات
include 'db.php';

// التحقق من وجود بيانات POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // استرجاع البيانات من النموذج
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // التحقق من أن جميع الحقول قد تم تعبئتها
    if (empty($username) || empty($password) || empty($role)) {
        echo "جميع الحقول مطلوبة.";
        exit();
    }

    // التحقق من وجود مستخدم بنفس اسم المستخدم
    $checkQuery = "SELECT * FROM users WHERE username = '$username'";
    $checkResult = mysqli_query($conn, $checkQuery);

    // إذا كان اسم المستخدم موجودًا بالفعل، توقف عن الإضافة
    if (mysqli_num_rows($checkResult) > 0) {
        echo "اسم المستخدم موجود بالفعل. لا يمكن إضافة المستخدم.";
        exit();  // إيقاف العملية إذا كان اسم المستخدم موجودًا
    }

    // التحقق من وجود مسؤول واحد فقط إذا كانت الصلاحية هي "admin"
    if ($role == 'admin') {
        $adminCheckQuery = "SELECT * FROM users WHERE role = 'admin'";
        $adminCheckResult = mysqli_query($conn, $adminCheckQuery);
        
        if (mysqli_num_rows($adminCheckResult) > 0) {
            echo "لا يمكن إضافة أكثر من مسؤول واحد في النظام.";
            exit();  // إيقاف العملية إذا كان يوجد مسؤول واحد بالفعل
        }
    }

    // تشفير كلمة المرور
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // استعلام لإضافة المستخدم إلى قاعدة البيانات
    $insertQuery = "INSERT INTO users (username, password, role) VALUES ('$username', '$hashedPassword', '$role')";

    // تنفيذ الاستعلام
    if (mysqli_query($conn, $insertQuery)) {
        echo "تم إضافة المستخدم بنجاح.";
    } else {
        echo "حدث خطأ أثناء إضافة المستخدم: " . mysqli_error($conn);
    }
} else {
    echo "طلب غير صالح.";
}
?>