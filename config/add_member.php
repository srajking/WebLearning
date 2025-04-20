<?php
include 'db.php'; // الاتصال بقاعدة البيانات

session_start(); // تأكد من بدء الجلسة

// التحقق من أن الجلسة تحتوي على اسم المستخدم
if (isset($_SESSION['username'])) {
    $admin_user = $_SESSION['username']; // اسم المستخدم من الجلسة
} else {
    // إذا لم يكن هناك اسم مستخدم في الجلسة، يمكنك تعيين قيمة افتراضية أو إظهار خطأ
    $admin_user = "مستخدم غير معروف";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $account_name = trim($_POST['account_name']);
    $account_type = trim($_POST['account_type']);
    $account_number = trim($_POST['account_number']);
    $mobile_number = trim($_POST['mobile_number']);
    $address = trim($_POST['address']);

    // **التحقق من صحة رقم الهاتف (10-16 رقمًا)**
    if (!preg_match("/^\d{10,16}$/", $mobile_number)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "رقم الموبايل يجب أن يكون بين 10 و 16 رقمًا"]);
        exit;
    }

    // **التحقق من وجود العضو مسبقًا**
    $checkQuery = "SELECT id FROM members WHERE account_number = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $account_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "هذا العضو موجود مسبقًا"]);
        $stmt->close();
        exit;
    }
    $stmt->close();

    // **الحصول على آخر ID وتحديد ID الجديد**
    $getLastIdQuery = "SELECT MAX(id) AS last_id FROM members";
    $result = $conn->query($getLastIdQuery);
    $row = $result->fetch_assoc();
    $new_id = ($row['last_id'] !== null) ? $row['last_id'] + 1 : 1;

    // **إدخال العضو الجديد**
    $insertQuery = "INSERT INTO members (id, account_name, account_type, account_number, mobile_number, address, balance_debit, balance_credit, points, discount_percentage, sale_price) 
                    VALUES (?, ?, ?, ?, ?, ?, 0, 0, 0, 0, '0')";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("isssss", $new_id, $account_name, $account_type, $account_number, $mobile_number, $address);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        $stmt->close();

        // **تسجيل العملية في admin_operations**
        $operation_type = "إضافة";
        $operation_details = "تمت إضافة عضو جديد: " . $account_name;
        $logQuery = "INSERT INTO admin_operations (operation_type, operation_details, performed_by) VALUES (?, ?, ?)";
        $logStmt = $conn->prepare($logQuery);
        $logStmt->bind_param("sss", $operation_type, $operation_details, $admin_user);

        if ($logStmt->execute() && $logStmt->affected_rows > 0) {
            $logStmt->close();

            // **إرجاع البيانات كـ JSON**
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "message" => "تمت إضافة العضو وتسجيل العملية بنجاح",
                "member" => [
                    "id" => $new_id,
                    "account_name" => $account_name,
                    "account_type" => $account_type,
                    "account_number" => $account_number,
                    "mobile_number" => $mobile_number,
                    "address" => $address
                ]
            ]);
        } else {
            $logStmt->close();
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "تمت إضافة العضو ولكن لم يتم تسجيل العملية"]);
        }
    } else {
        $stmt->close();
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "لم يتم إدخال العضو"]);
    }
}
