<?php
include 'db.php'; // تضمين ملف الاتصال بقاعدة البيانات
session_start(); // بدء الجلسة

header('Content-Type: application/json'); // تأكيد أن الاستجابة بصيغة JSON

// التأكد من أن المستخدم مسجل الدخول
if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'لم يتم تسجيل الدخول.']);
    exit();
}

// التحقق من استقبال جميع البيانات المطلوبة
if (
    isset(
        $_POST['id'],
        $_POST['account_name'],
        $_POST['account_type'],
        $_POST['account_number'],
        $_POST['mobile_number'],
        $_POST['address']
    )
) {
    $id = trim($_POST['id']);
    $account_name = trim($_POST['account_name']);
    $account_type = trim($_POST['account_type']);
    $account_number = trim($_POST['account_number']);
    $mobile_number = trim($_POST['mobile_number']);
    $address = trim($_POST['address']);

    // التأكد من عدم وجود قيم فارغة
    if (empty($id) || empty($account_name) || empty($account_type) || empty($account_number) || empty($mobile_number) || empty($address)) {
        echo json_encode(['success' => false, 'message' => 'جميع الحقول مطلوبة!']);
        exit();
    }

    // تحديث بيانات العضو في قاعدة البيانات
    $updateSql = "UPDATE members SET 
        account_name = ?, 
        account_type = ?, 
        account_number = ?, 
        mobile_number = ?, 
        address = ? 
        WHERE id = ?";
    $stmt = $conn->prepare($updateSql);
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'خطأ في تحضير الاستعلام']);
        exit();
    }

    $stmt->bind_param("sssssi", $account_name, $account_type, $account_number, $mobile_number, $address, $id);
    $updateResult = $stmt->execute();

    if ($updateResult) {
        // تسجيل العملية في جدول admin_operations
        $operationDetails = "تم تعديل بيانات العضو: $account_name (رقم الحساب: $account_number)";
        $performedBy = $_SESSION['username']; // اسم المستخدم الحالي
        $operationType = "تعديل";

        $operationSql = "INSERT INTO admin_operations (operation_type, operation_details, performed_by) 
                          VALUES (?, ?, ?)";
        $operationStmt = $conn->prepare($operationSql);
        $operationStmt->bind_param("sss", $operationType, $operationDetails, $performedBy);
        $operationStmt->execute();

        // إرجاع القيم الجديدة لتحديث الجدول في الجافاسكريبت
        echo json_encode([
            'success' => true,
            'message' => 'تم تعديل البيانات بنجاح',
            'account_name' => $account_name,
            'account_type' => $account_type,
            'account_number' => $account_number,
            'mobile_number' => $mobile_number,
            'address' => $address
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'فشل التعديل، يرجى المحاولة لاحقًا.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'بيانات غير صحيحة']);
}
$conn->close(); // إغلاق الاتصال