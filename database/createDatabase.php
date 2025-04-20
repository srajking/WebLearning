<?php
$host = "localhost";
$username = "root"; // اسم المستخدم لقاعدة البيانات
$password = ""; // كلمة مرور قاعدة البيانات

// إنشاء الاتصال
$conn = new mysqli($host, $username, $password);
$conn->set_charset("utf8mb4");

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}

// إنشاء قاعدة البيانات إذا لم تكن موجودة
$sql = "CREATE DATABASE IF NOT EXISTS admin_panel";
if ($conn->query($sql) === TRUE) {
    echo "تم إنشاء قاعدة البيانات أو أنها موجودة مسبقًا.";
} else {
    echo "خطأ في إنشاء قاعدة البيانات: " . $conn->error;
}

// اختيار قاعدة البيانات
$conn->select_db('admin_panel');

// إنشاء جدول المستخدمين إذا لم يكن موجودًا
$tableSql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL
)";

if ($conn->query($tableSql) === TRUE) {
    echo "تم إنشاء الجدول بنجاح.";
} else {
    echo "خطأ في إنشاء الجدول: " . $conn->error;
}

// إنشاء جدول المنتجات
$tableSql = "CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,          -- معرف المنتج (رقم تسلسلي)
    product_name VARCHAR(255) NOT NULL,         -- اسم المادة
    available_quantity INT NOT NULL,            -- العدد المتوفر
    sold_quantity INT NOT NULL,                -- العدد المباع
    unit VARCHAR(50) NOT NULL,                  -- الوحدة
    customer_price DECIMAL(10, 2) NOT NULL,    -- سعر الزبون
    member_price DECIMAL(10, 2) NOT NULL,      -- سعر العضو
    product_points INT NOT NULL,               -- نقاط المنتج
    barcode VARCHAR(100) NOT NULL,             -- الباركود
    category VARCHAR(100) NOT NULL,            -- التصنيف
    company VARCHAR(100) NOT NULL              -- الشركة
)";

// التحقق من إنشاء جدول المنتجات
if ($conn->query($tableSql) === TRUE) {
    echo "تم إنشاء جدول المنتجات بنجاح.";
} else {
    echo "خطأ في إنشاء جدول المنتجات: " . $conn->error;
}

// إنشاء جدول سجلات التعديلات
$tableSql = "CREATE TABLE IF NOT EXISTS product_updates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    sold_quantity INT NOT NULL,
    remaining_quantity INT NOT NULL,
    update_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_by VARCHAR(255) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id)
)";

// التحقق من إنشاء جدول سجلات التعديلات
if ($conn->query($tableSql) === TRUE) {
    echo "تم إنشاء جدول سجلات التعديلات بنجاح.";
} else {
    echo "خطأ في إنشاء جدول سجلات التعديلات: " . $conn->error;
}

// إنشاء جدول الأعضاء
$tableSql = "CREATE TABLE IF NOT EXISTS members (
    id INT AUTO_INCREMENT PRIMARY KEY,          -- معرف العضو
    account_name VARCHAR(255) NOT NULL,         -- اسم الحساب
    balance_debit DECIMAL(10, 2) NOT NULL,      -- الرصيد المدين
    balance_credit DECIMAL(10, 2) NOT NULL,     -- الرصيد الدائن
    points INT NOT NULL,                       -- النقاط
    account_type VARCHAR(50) NOT NULL,          -- طبيعة الحساب
    discount_percentage DECIMAL(5, 2) NOT NULL,-- نسبة الخصم
    sale_price VARCHAR(100) NOT NULL,        -- سعر البيع
    account_number VARCHAR(50) NOT NULL,       -- رقم الحساب
    mobile_number VARCHAR(50) NOT NULL,        -- رقم الموبايل
    address TEXT NOT NULL,                     -- العنوان
    last_sale_date DATE,                       -- تاريخ آخر بيع
    last_sale_total DECIMAL(10, 2),            -- إجمالي آخر بيع
    last_payment_date DATE,                    -- تاريخ آخر قبض
    last_payment_value DECIMAL(10, 2)          -- قيمة آخر قبض
)";

// التحقق من إنشاء جدول الأعضاء
if ($conn->query($tableSql) === TRUE) {
    echo "تم إنشاء جدول الأعضاء بنجاح.";
} else {
    echo "خطأ في إنشاء جدول الأعضاء: " . $conn->error;
}

// إنشاء جدول سجل العمليات
$tableSql = "CREATE TABLE IF NOT EXISTS admin_operations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    operation_type VARCHAR(50) NOT NULL,    -- نوع العملية (إضافة/تعديل/حذف)
    operation_details TEXT,                 -- تفاصيل العملية (مثل المنتج أو العضو الذي تم تعديله)
    operation_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- وقت العملية
    performed_by VARCHAR(255) NOT NULL      -- الإداري الذي قام بالعملية
)";

// التحقق من إنشاء جدول سجل العمليات
if ($conn->query($tableSql) === TRUE) {
    echo "تم إنشاء جدول سجل العمليات بنجاح.";
} else {
    echo "خطأ في إنشاء جدول سجل العمليات: " . $conn->error;
}

// إغلاق الاتصال
$conn->close();
