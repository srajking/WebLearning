<?php
require  '../vendor/autoload.php'; // تحميل مكتبات Composer
require  'db.php'; // ملف الاتصال بقاعدة البيانات

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// التأكد من أن الزر تم الضغط عليه
if (isset($_POST['export'])) {

    // إنشاء كائن ملف Excel جديد
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // إضافة عناوين الأعمدة
    $headers = ['التسلسل', 'اسم المادة', 'العدد المتوفر', 'العدد المباع', 'الوحدة', 'سعر الزبون', 'سعر العضو', 'نقاط المنتج', 'الباركود', 'التصنيف', 'الشركة'];
    $sheet->fromArray([$headers], null, 'A1');

    // جلب البيانات من قاعدة البيانات
    $query = "SELECT id, product_name, available_quantity, sold_quantity, unit, customer_price, member_price, product_points, barcode, category, company FROM products";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $rowNumber = 2; // ابدأ من الصف الثاني بعد العناوين
        while ($row = mysqli_fetch_assoc($result)) {
            $sheet->fromArray([$row], null, "A$rowNumber");
            $rowNumber++;
        }
    }

    // اسم الملف
    $fileName = "products_export.xlsx";

    // تحديد الهيدر لتحميل الملف مباشرة
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}
