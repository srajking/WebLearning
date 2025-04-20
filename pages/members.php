<?php
include '../config/db.php'; // تأكد من مسار ملف الاتصال بقاعدة البيانات
session_start(); // بدء الجلسة
if (isset($_SESSION['username'])) {
    // echo "مرحبًا، " . $_SESSION['username'];
} else {
    echo "لم يتم تسجيل الدخول.";
}

?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الأعضاء</title>
    <!-- إضافة رابط Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../public/css/members.css">

</head>

<body>

    <div class="container-fluid mt-4">
        <h2 class="text-center mb-4">إدارة الأعضاء</h2>

        <div class="row">
            <!-- الأزرار في العمود الأيمن -->
            <div class="col-md-3">
                <!-- البحث -->
                <div class="mb-3">
                    <input type="text" id="searchMember" class="form-control"
                        placeholder="🔍 ابحث عن اسم أو رقم الحساب..." dir="rtl" style="text-align: right;">
                </div>


                <!-- ترتيب الأزرار في صفوف -->
                <div class="row-buttons">


                    <div class="col-button">
                        <button class="btn btn-danger">حذف</button>
                    </div>



                    <!-- التعديل -->
                    <div class="col-button">
                        <button class="btn btn-warning" id="editBtn" disabled>تعديل</button>
                    </div>

                    <!-- التعديل -->
                    <div class="modal" tabindex="-1" id="editMemberModal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">تعديل العضو</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="editMemberForm">
                                        <div class="mb-3">
                                            <label for="account_name" class="form-label">اسم الحساب</label>
                                            <input type="text" class="form-control" name="account_name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="account_type" class="form-label">طبيعة الحساب</label>
                                            <select class="form-control" name="account_type" required>
                                                <option value="عضو">عضو</option>
                                                <option value="زبون">زبون</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="account_number" class="form-label">رقم الحساب</label>
                                            <input type="text" class="form-control" name="account_number" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="mobile_number" class="form-label">رقم الموبايل</label>
                                            <input type="tel" class="form-control" name="mobile_number" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="address" class="form-label">العنوان</label>
                                            <textarea class="form-control" name="address" required></textarea>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">إغلاق</button>
                                    <button type="button" class="btn btn-primary" id="saveEditMember">حفظ
                                        التعديلات</button>
                                </div>
                            </div>
                        </div>
                    </div>





                    <div class="col-button">
                        <!-- زر فتح النافذة المنبثقة -->
                        <button class="btn btn-primary" id="openAddMemberModal">جديد</button>
                    </div>

                    <!-- نافذة منبثقة لإضافة عضو جديد -->
                    <div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addMemberModalLabel">إضافة عضو جديد</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="إغلاق"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="addMemberForm">
                                        <div class="mb-3">
                                            <label for="account_name" class="form-label">اسم الحساب</label>
                                            <input type="text" class="form-control" id="account_name"
                                                name="account_name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="account_type" class="form-label">طبيعة الحساب</label>
                                            <select class="form-control" id="account_type" name="account_type" required>
                                                <option value="عضو">عضو</option>
                                                <option value="زبون">زبون</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="account_number" class="form-label">رقم الحساب</label>
                                            <input type="text" class="form-control" id="account_number"
                                                name="account_number" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="mobile_number" class="form-label">رقم الموبايل</label>
                                            <input type="number" class="form-control" id="mobile_number"
                                                name="mobile_number" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="address" class="form-label">العنوان</label>
                                            <input type="text" class="form-control" id="address" name="address"
                                                required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">إلغاء</button>
                                            <button type="button" class="btn btn-primary" id="saveMember">حفظ</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>


                </div>

                <div class="row-buttons">
                    <div class="col-button">
                        <button class="btn btn-success">صرف</button>
                    </div>
                    <div class="col-button">
                        <button class="btn btn-info">قبض</button>
                    </div>
                </div>

                <div class="button-container">
                    <button class="btn btn-secondary"> استيراد</button>
                </div>
                <div class="button-container">
                    <button class="btn btn-secondary"> تصدير</button>
                </div>
                <div class="button-container">
                    <button class="btn btn-dark">كشف حساب</button>
                </div>

                <!-- زر العودة للصفحة الرئيسية -->
                <div class="button-container">
                    <button class="btn btn-primary back-btn">العودة للصفحة الرئيسية</button>
                </div>
            </div>

            <!-- الجدول في العمود الأيسر -->
            <div class="col-md-9">
                <div class="table-container">

                    <table class="table table-bordered table-striped" dir="rtl">
                        <thead>
                            <tr>
                                <th rowspan="2">الترقيم</th>
                                <th rowspan="2">اسم الحساب</th>
                                <th colspan="2">الرصيد</th>
                                <th rowspan="2">النقاط</th>
                                <th rowspan="2">طبيعة الحساب</th>
                                <th rowspan="2">نسبة الخصم</th>
                                <th rowspan="2">سعر البيع</th>
                                <th colspan="3">معلومات الاتصال</th>
                                <th colspan="2">آخر بيع</th>
                                <th colspan="2">آخر قبض</th>
                            </tr>
                            <tr>
                                <th>عليه مدين</th>
                                <th>له دائن</th>
                                <th>رقم الحساب</th>
                                <th>رقم الموبايل</th>
                                <th>العنوان</th>
                                <th>تاريخ</th>
                                <th>اجمالي</th>
                                <th>تاريخ</th>
                                <th>القيمة</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // استعلام لجلب بيانات الأعضاء من قاعدة البيانات
                            $sql = "SELECT * FROM members";
                            $result = mysqli_query($conn, $sql);

                            // التحقق من وجود بيانات في الجدول
                            if (mysqli_num_rows($result) > 0) {
                                // طباعة الصفوف في الجدول
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr data-id='" . $row['id'] . "'>";
                                    echo "<td>" . $row['id'] . "</td>";
                                    echo "<td>" . $row['account_name'] . "</td>";
                                    echo "<td>" . $row['balance_debit'] . "</td>";
                                    echo "<td>" . $row['balance_credit'] . "</td>";
                                    echo "<td>" . $row['points'] . "</td>";
                                    echo "<td>" . $row['account_type'] . "</td>";
                                    echo "<td>" . $row['discount_percentage'] . "</td>";
                                    echo "<td>" . $row['sale_price'] . "</td>";
                                    echo "<td>" . $row['account_number'] . "</td>";
                                    echo "<td>" . $row['mobile_number'] . "</td>";
                                    echo "<td>" . $row['address'] . "</td>";
                                    echo "<td>" . $row['last_sale_date'] . "</td>";
                                    echo "<td>" . $row['last_sale_total'] . "</td>";
                                    echo "<td>" . $row['last_payment_date'] . "</td>";
                                    echo "<td>" . $row['last_payment_value'] . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='14' class='text-center'>لا توجد بيانات لعرضها</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>


                </div>
            </div>

        </div>
    </div>



    <!-- الملف الخارجي  -->
    <script src="../public/js/members.js"></script>



    <!-- إضافة رابط Bootstrap JS (اختياري) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>