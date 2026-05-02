<?php
require_once 'config.php';
$query = "SELECT p.*, c.category_name 
          FROM apparel p 
          LEFT JOIN categories c ON p.category_id = c.category_id 
          ORDER BY p.created_at DESC";
$result = mysqli_query($conn, $query);
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>
