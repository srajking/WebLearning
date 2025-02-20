<?php
include 'db.php';

$id = $_POST['id'];
$product_name = $_POST['product_name'];
$available_quantity = $_POST['available_quantity'];
$sold_quantity = $_POST['sold_quantity'];
$customer_price = $_POST['customer_price'];
$member_price = $_POST['member_price'];
$product_points = $_POST['product_points'];
$barcode = $_POST['barcode'];
$category = $_POST['category'];

$sql = "UPDATE products SET 
    product_name='$product_name',
    available_quantity='$available_quantity',
    sold_quantity='$sold_quantity',
    customer_price='$customer_price',
    member_price='$member_price',
    product_points='$product_points',
    barcode='$barcode',
    category='$category'
    WHERE id='$id'";

$response = ["success" => mysqli_query($conn, $sql)];
echo json_encode($response);

mysqli_close($conn);