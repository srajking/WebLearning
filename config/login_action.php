<?php
// استدعاء ملف الاتصال بقاعدة البيانات
include 'db.php';

// بدء الجلسة في بداية الملف
session_start();

// التحقق من أنه تم إرسال البيانات عبر POST
if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['role'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // التحقق من أن البيانات ليست فارغة
    if (empty($username) || empty($password) || empty($role)) {
        $_SESSION['error'] = "يرجى ملء جميع الحقول.";
        header("Location: ../pages/login.php");
        exit();
    }

    // استعلام للتحقق من البيانات في قاعدة البيانات
    $query = "SELECT * FROM users WHERE username = '$username' AND role = '$role'";
    $result = mysqli_query($conn, $query);

    // التحقق إذا كان يوجد مستخدم مطابق
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        // التحقق من كلمة المرور
        if (password_verify($password, $user['password'])) {
            // إذا كانت البيانات صحيحة، تحويل المستخدم إلى صفحة التحكم الرئيسية
            $_SESSION['user_id'] = $user['id']; // حفظ ID المستخدم في الجلسة
            $_SESSION['username'] = $user['username']; // حفظ اسم المستخدم في الجلسة
            $_SESSION['role'] = $user['role']; // حفظ الصلاحية في الجلسة

            header("Location: ../pages/dashboard.php"); // توجيه إلى صفحة التحكم الرئيسية
            exit();
        } else {
            // إذا كانت كلمة المرور غير صحيحة
            $_SESSION['error'] = "كلمة المرور غير صحيحة.";
            header("Location: ../pages/login.php"); // إعادة التوجيه إلى صفحة تسجيل الدخول
            exit();
        }
    } else {
        // إذا كان اسم المستخدم أو الصلاحية غير صحيحة
        $_SESSION['error'] = "اسم المستخدم أو الصلاحية غير صحيحة.";
        header("Location: ../pages/login.php"); // إعادة التوجيه إلى صفحة تسجيل الدخول
        exit();
    }
} else {
    // إذا لم يتم إرسال البيانات بشكل صحيح
    $_SESSION['error'] = "يرجى ملء جميع الحقول.";
    header("Location: ../pages/login.php"); // إعادة التوجيه إلى صفحة تسجيل الدخول
    exit();
}
