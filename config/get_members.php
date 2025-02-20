<?php
// ملف get_members.php
require_once 'db.php';
header('Content-Type: application/json');

$query = "SELECT * FROM members ORDER BY id"; // تأكد من أن هذا الاستعلام يرجع الأعضاء الصحيحين
$result = $conn->query($query);

$members = [];
while ($row = $result->fetch_assoc()) {
    $members[] = $row;
}

echo json_encode(["success" => true, "members" => $members]);
