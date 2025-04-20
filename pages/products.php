<?php

require __DIR__ . '/../vendor/autoload.php';


// إذا كنت تستخدم جلسات، تأكد من بدء الجلسة إذا لم تبدأ بعد
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../pages/login.php"); // تحويل إلى صفحة تسجيل الدخول إذا لم يكن المستخدم مسجلاً
    exit();
}
// الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root"; // اسم المستخدم الخاص بقاعدة البيانات
$password = ""; // كلمة المرور الخاصة بقاعدة البيانات
$dbname = "admin_panel"; // اسم قاعدة البيانات
// إنشاء الاتصال
$conn = mysqli_connect($servername, $username, $password, $dbname);
// استعلام لجلب البيانات من جدول المنتجات
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);
?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المنتجات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../public/css/products.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

</head>

<body>
    <!-- العنوان في الأعلى -->
    <header class="text-center py-3 bg-primary text-white">
        <h1>إيدمارك العراق</h1>
    </header>
    <div class="container-fluid mt-5">
        <h3 class="text-center mb-5">إدارة المنتجات</h3>
        <div class="row">
            <!-- الجهة اليسرى - جدول المنتجات -->
            <div class="col-md-8">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>التسلسل</th>
                                <th>اسم المادة</th>
                                <th>العدد المتوفر</th>
                                <th>العدد المباع</th>
                                <th>الوحدة</th>
                                <th>سعر الزبون</th>
                                <th>سعر العضو</th>
                                <th>نقاط المنتج</th>
                                <th>الباركود</th>
                                <th>التصنيف</th>
                                <th>الشركة</th>
                            </tr>
                        </thead>
                        <tbody id="productsTable">
                            <?php
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr data-id='{$row['id']}' onclick='selectRow(this)'>
                                            <td>{$row['id']}</td>
                                            <td>{$row['product_name']}</td>
                                            <td>{$row['available_quantity']}</td>
                                            <td>{$row['sold_quantity']}</td>
                                            <td>{$row['unit']}</td>
                                            <td>{$row['customer_price']}</td>
                                            <td>{$row['member_price']}</td>
                                            <td>{$row['product_points']}</td>
                                            <td>{$row['barcode']}</td>
                                            <td>{$row['category']}</td>
                                            <td>{$row['company']}</td>
                                        </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='11' class='text-center'>لا توجد منتجات في قاعدة البيانات</td></tr>";
                            }
                            ?>
                        </tbody>

                    </table>
                </div>
            </div>
            <!-- الجهة اليمنى - أزرار التحكم -->
            <div class="col-md-4 ">
                <div class="mb-3">
                    <label for="search" class="form-label">بحث عن مادة:</label>
                    <input type="text" id="search" class="form-control" placeholder="ابحث عن المنتج...">
                </div>
                <div class="d-flex flex-column mb-3">
                    <!-- زر إضافة منتج -->
                    <button class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i class="fas fa-plus"></i> إضافة مادة
                    </button>
                    <!-- نافذة منبثقة لإضافة المنتج -->
                    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addProductModalLabel">إضافة منتج جديد</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="addProductForm">
                                        <div class="mb-3">
                                            <label for="product_name" class="form-label">اسم المادة</label>
                                            <input type="text" class="form-control" id="product_name"
                                                name="product_name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="available_quantity" class="form-label">العدد المتوفر</label>
                                            <input type="number" class="form-control" id="available_quantity"
                                                name="available_quantity" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="sold_quantity" class="form-label">العدد المباع</label>
                                            <input type="number" class="form-control" id="sold_quantity"
                                                name="sold_quantity" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="customer_price" class="form-label">سعر الزبون</label>
                                            <input type="number" class="form-control" id="customer_price"
                                                name="customer_price" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="member_price" class="form-label">سعر العضو</label>
                                            <input type="number" class="form-control" id="member_price"
                                                name="member_price" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="product_points" class="form-label">نقاط المنتج</label>
                                            <input type="number" class="form-control" id="product_points"
                                                name="product_points" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="barcode" class="form-label">الباركود</label>
                                            <input type="text" class="form-control" id="barcode" name="barcode"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="category" class="form-label">التصنيف</label>
                                            <input type="text" class="form-control" id="category" name="category"
                                                required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">إضافة المنتج</button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-danger mb-2" id="deleteProductBtn"><i class="fas fa-trash"></i> حذف
                        مادة</button>
                    <button class="btn btn-warning mb-2" id="editProductBtn"><i class="fas fa-edit"></i> تعديل
                        مادة</button>
                    <button class="btn btn-danger mb-2" id="deleteAllBtn"><i class="fas fa-trash-alt"></i> حذف كل
                        البيانات</button>


                    <button class="btn btn-info mb-2" id="viewLogsBtn">
                        <i class="fas fa-history"></i> سجلات التعديل
                    </button>

                    <!-- نافذة منبثقة لعرض سجلات التعديل -->
                    <div class="modal fade" id="viewLogsModal" tabindex="-1" aria-labelledby="viewLogsModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewLogsModalLabel">سجلات التعديل</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>العدد المباع</th>
                                                <th>العدد المتبقى</th>
                                                <th>وقت التعديل</th>
                                                <th>تم التعديل بواسطة</th>
                                            </tr>
                                        </thead>
                                        <tbody id="logsTableBody">
                                            <!-- هنا ستظهر السجلات -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                    document.getElementById("viewLogsBtn").addEventListener("click", function() {
                        const selectedRow = document.querySelector(".table tbody tr.selected");
                        if (!selectedRow) {
                            alert("يرجى تحديد مادة لعرض سجلات التعديل");
                            return;
                        }

                        const productId = selectedRow.getAttribute("data-id");

                        // إرسال طلب للحصول على السجلات من الجدول
                        fetch(`../config/get_product_logs.php?product_id=${productId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    const logsTableBody = document.getElementById("logsTableBody");
                                    logsTableBody.innerHTML = ''; // تنظيف الجدول قبل إضافة السجلات الجديدة

                                    data.logs.forEach(log => {
                                        const row = document.createElement("tr");
                                        row.innerHTML = `
                        <td>${log.sold_quantity}</td>
                        <td>${log.remaining_quantity}</td>
                        <td>${log.update_time}</td>
                        <td>${log.updated_by}</td>
                        `;
                                        logsTableBody.appendChild(row);
                                    });

                                    // عرض النافذة المنبثقة
                                    new bootstrap.Modal(document.getElementById("viewLogsModal")).show();
                                } else {
                                    alert("لا توجد سجلات للتعديل لهذا المنتج.");
                                }
                            })
                            .catch(error => {
                                alert("حدث خطأ أثناء تحميل السجلات: " + error);
                            });
                    });
                    </script>




                    <form action="../config/export_products.php" method="post">
                        <button type="submit" name="export" class="btn btn-info mb-2 w-100">
                            <i class="fas fa-download"></i> تصدير الجدول
                        </button>
                    </form>


                    <button type="button" id="importButton" class="btn btn-success"
                        onclick="document.getElementById('fileInput').click()">
                        <i class="fas fa-upload"></i> استيراد الجدول
                    </button>
                    <input type="file" id="fileInput" style="display: none;" accept=".xlsx, .xls"
                        onchange="handleFileUpload(this)">




                </div>
                <div class="text-center">
                    <a href="dashboard.php" class="btn btn-primary"><i class="fas fa-home"></i> رجوع للصفحة الرئيسية</a>
                </div>
            </div>

        </div>
    </div>

    <!-- نافذة تعديل المنتج -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">تعديل المادة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body">
                    <form id="editProductForm">
                        <input type="hidden" id="edit_product_id">
                        <div class="mb-3">
                            <label for="edit_product_name" class="form-label">اسم المادة</label>
                            <input type="text" class="form-control" id="edit_product_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_available_quantity" class="form-label">العدد المتوفر</label>
                            <input type="number" class="form-control" id="edit_available_quantity" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_sold_quantity" class="form-label">العدد المباع</label>
                            <input type="number" class="form-control" id="edit_sold_quantity" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_customer_price" class="form-label">سعر الزبون</label>
                            <input type="number" class="form-control" id="edit_customer_price" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_member_price" class="form-label">سعر العضو</label>
                            <input type="number" class="form-control" id="edit_member_price" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_product_points" class="form-label">نقاط المنتج</label>
                            <input type="number" class="form-control" id="edit_product_points" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_barcode" class="form-label">الباركود</label>
                            <input type="text" class="form-control" id="edit_barcode" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_category" class="form-label">التصنيف</label>
                            <input type="text" class="form-control" id="edit_category" required>
                        </div>
                        <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                    </form>
                </div>
            </div>
        </div>
    </div>




    <!-- الاستيراد -->
    <script>
    function handleFileUpload(input) {
        const file = input.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, {
                type: 'array'
            });
            const firstSheetName = workbook.SheetNames[0];
            const worksheet = workbook.Sheets[firstSheetName];
            const jsonData = XLSX.utils.sheet_to_json(worksheet, {
                header: 1
            });

            // إرسال البيانات إلى الخادم باستخدام AJAX
            sendDataToServer(jsonData);
        };
        reader.readAsArrayBuffer(file);
    }

    function sendDataToServer(data) {
        $.ajax({
            url: '../config/import_products.php', // ملف PHP الذي سيعالج البيانات
            type: 'POST',
            data: {
                excelData: JSON.stringify(data)
            },
            success: function(response) {
                alert("تم استيراد البيانات بنجاح!");
                location.reload(); // تحديث الصفحة لعرض البيانات الجديدة
            },
            error: function(xhr, status, error) {
                alert("حدث خطأ أثناء استيراد البيانات: " + error);
            }
        });
    }
    </script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../public/js/pdoducts.js"></script>
</body>

</html>

<?php
// إغلاق الاتصال بعد الانتهاء
mysqli_close($conn);
?>