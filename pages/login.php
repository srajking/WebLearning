<!-- // login.php -->
<?php
// استدعاء ملف الاتصال بقاعدة البيانات
include '../config/db.php';

session_start();

// بعد التحقق من بيانات المستخدم:
$_SESSION['username'] = $username; // حفظ اسم المستخدم في الجلسة


// استرجاع جميع المستخدمين من قاعدة البيانات
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صفحة تسجيل الدخول للإدارة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/login.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* CSS لعرض الرسالة أسفل زر تسجيل الدخول */
        #error-message,
        #success-message {
            position: relative;
            top: 10px;
            /* ضبط المسافة بين الزر والرسالة */
            transform: translateX(-50%);
            z-index: 9999;
            display: block;
            text-align: center;
            width: 50%;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <!-- إضافة العنوان في الأعلى -->
    <header class="text-center py-3 bg-primary text-white">
        <h1>إيدمارك العراق</h1>
    </header>

    <!-- عرض رسائل الخطأ أو النجاح -->
    <?php
    if (isset($_SESSION['error'])) {
        echo '
        <div id="error-message" class="alert alert-danger" role="alert">
            ' . $_SESSION['error'] . '
        </div>
        <script>
            setTimeout(function() {
                document.getElementById("error-message").style.display = "none";
            }, 3000);
        </script>';
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo '
        <div id="success-message" class="alert alert-success" role="alert">
            ' . $_SESSION['success'] . '
        </div>
        <script>
            setTimeout(function() {
                document.getElementById("success-message").style.display = "none";
            }, 3000);
        </script>';
        unset($_SESSION['success']);
    }
    ?>

    <div class="container">
        <!-- عرض رسائل الخطأ أو النجاح -->
        <div class="form-container">
            <h3>تسجيل الدخول</h3>
            <form action="../config/login_action.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">اسم المستخدم</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">كلمة المرور</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">صلاحية المستخدم</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="admin">مسؤول</option>
                        <option value="manager">إداري</option>
                        <option value="cashier">كاشير</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">تسجيل الدخول</button>

                <!-- عرض الرسالة أسفل الزر مباشرة -->
                <?php
                if (isset($_SESSION['error'])) {
                    echo '
            <div id="error-message" class="alert alert-danger mt-3" role="alert">
                ' . $_SESSION['error'] . '
            </div>';
                    unset($_SESSION['error']);
                }
                if (isset($_SESSION['success'])) {
                    echo '
            <div id="success-message" class="alert alert-success mt-3" role="alert">
                ' . $_SESSION['success'] . '
            </div>';
                    unset($_SESSION['success']);
                }
                ?>
            </form>
        </div>



        <!-- الجزء الأيسر: جدول الإداريين -->
        <div class="table-container">
            <h3>الإداريين</h3>
            <table class="table" id="admin-table">
                <thead>
                    <tr>
                        <th>اسم المستخدم</th>
                        <th>الصلاحية</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr class='user-row' data-id='" . $row['id'] . "'>
                                <td>" . htmlspecialchars($row['username']) . "</td>
                                <td>";

                        // تحويل قيمة الصلاحية إلى العربية
                        if ($row['role'] == 'admin') {
                            echo 'مسؤول';
                        } elseif ($row['role'] == 'manager') {
                            echo 'إداري';
                        } else {
                            echo 'كاشير';
                        }

                        echo "</td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">إضافة مستخدم</button>
            <button class="btn btn-warning" id="edit-btn" disabled>تعديل</button>
            <button class="btn btn-danger" id="delete-btn" disabled>حذف</button>
        </div>
    </div>

    <!-- نافذة منبثقة لإضافة مستخدم -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">إضافة مستخدم جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="../config/add_user.php" method="POST" id="addUserForm">
                        <div class="mb-3">
                            <label for="modalUsername" class="form-label">اسم المستخدم</label>
                            <input type="text" class="form-control" id="modalUsername" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="modalPassword" class="form-label">كلمة المرور</label>
                            <input type="password" class="form-control" id="modalPassword" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="modalRole" class="form-label">صلاحية المستخدم</label>
                            <select class="form-select" id="modalRole" name="role" required>
                                <option value="admin">مسؤول</option>
                                <option value="manager">إداري</option>
                                <option value="cashier">كاشير</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">إضافة مستخدم</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- نافذة منبثقة لتعديل مستخدم -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">تعديل بيانات المستخدم</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="../config/edit_user.php" method="POST" id="editUserForm">
                        <input type="hidden" id="editUserId" name="userId">
                        <div class="mb-3">
                            <label for="editUsername" class="form-label">اسم المستخدم</label>
                            <input type="text" class="form-control" id="editUsername" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPassword" class="form-label">كلمة المرور الجديدة (اختياري)</label>
                            <input type="password" class="form-control" id="editPassword" name="password">
                        </div>
                        <div class="mb-3">
                            <label for="editRole" class="form-label">صلاحية المستخدم</label>
                            <select class="form-select" id="editRole" name="role" required>
                                <option value="admin">مسؤول</option>
                                <option value="manager">إداري</option>
                                <option value="cashier">كاشير</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">تعديل المستخدم</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../public/js/login.js"></script>

    <script>
        // تفعيل الأزرار عند النقر على صف في الجدول
        document.querySelectorAll('.user-row').forEach(function(row) {
            row.addEventListener('click', function() {
                document.getElementById('edit-btn').disabled = false;
                document.getElementById('delete-btn').disabled = false;

                var userId = row.getAttribute('data-id');
                document.getElementById('edit-btn').setAttribute('data-id', userId);
                document.getElementById('delete-btn').setAttribute('data-id', userId);
            });
        });

        // وظيفة التعديل
        document.getElementById('edit-btn').addEventListener('click', function() {
            var userId = this.getAttribute('data-id');
            var row = document.querySelector(`.user-row[data-id='${userId}']`);
            var username = row.cells[0].textContent;
            var role = row.cells[1].textContent;

            document.getElementById('editUserId').value = userId;
            document.getElementById('editUsername').value = username;
            document.getElementById('editRole').value = role;

            var modalElement = document.getElementById('editUserModal');
            var myModal = new bootstrap.Modal(modalElement);
            myModal.show();
        });

        // وظيفة الحذف
        document.getElementById('delete-btn').addEventListener('click', function() {
            var userId = this.getAttribute('data-id');

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../config/delete_user.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var row = document.querySelector(`.user-row[data-id='${userId}']`);
                    row.remove();

                    document.getElementById('edit-btn').disabled = true;
                    document.getElementById('delete-btn').disabled = true;
                } else {
                    alert("حدث خطأ أثناء حذف المستخدم.");
                }
            };
            xhr.send("userId=" + userId);
        });
    </script>
</body>

</html>