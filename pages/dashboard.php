<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../pages/login.php"); // تحويل إلى صفحة تسجيل الدخول إذا لم يكن المستخدم مسجلاً
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* تخصيص الأزرار والأيقونات لتناسب التصميم */
        .dashboard-btn {
            width: 100%;
            height: 150px;
            font-size: 18px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 10px;
            color: white;
            margin-bottom: 15px;
            transition: all 0.3s ease-in-out;
        }

        .dashboard-btn:hover {
            opacity: 0.8;
        }

        .dashboard-btn i {
            font-size: 40px;
            margin-bottom: 10px;
        }

        /* تخصيص الألوان حسب التصميم */
        .btn-primary {
            background-color: #3a8e5e;
        }

        .btn-success {
            background-color: #28a745;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-warning {
            background-color: #ffc107;
        }

        .btn-info {
            background-color: #17a2b8;
        }

        i {
            padding: 10px;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>

<body>

    <!-- إضافة العنوان في الأعلى -->
    <header class="text-center py-3 bg-primary text-white">
        <h1>إيدمارك العراق</h1>
    </header>

    <div class="container mt-5">
        <h3 class="text-center mb-5">مرحبًا بك في لوحة التحكم، <?php echo $_SESSION['username']; ?>!</h3>

        <div class="row">
            <div class="col-md-4">
                <a href="members.php" class="dashboard-btn btn-info">
                    <i class="fas fa-users"></i>
                    الاعضاء
                </a>
            </div>
            <div class="col-md-4">
                <a href="products.php" class="dashboard-btn btn-warning">
                    <i class="fa fa-cart-plus"></i>
                    المنتجات
                </a>
            </div>
            <div class="col-md-4">
                <a href="sales.php" class="dashboard-btn btn-success">
                    <i class="fa fa-money-bill"></i>
                    بيع
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <a href="shipments.php" class="dashboard-btn btn-danger">
                    <i class="fas fa-truck"></i>
                    الشحنات
                </a>
            </div>
            <div class="col-md-4">
                <a href="warehouses.php" class="dashboard-btn btn-primary">
                    <i class="fas fa-box"></i>
                    المستودعات
                </a>
            </div>
            <div class="col-md-4">
                <a href="invoices.php" class="dashboard-btn btn-danger">
                    <i class="fas fa-folder"></i>
                    الفواتير
                </a>
            </div>
        </div>

    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>