<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

$status = "error";
$msg_text = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uniform_name = trim($_POST['product_name'] ?? ''); 
    $category_id  = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
    $size = trim($_POST['size'] ?? ''); 
    $description  = trim($_POST['description'] ?? '');
    $price = $_POST['price'] ?? '';
    $stock = $_POST['stock'] ?? '';

    if (empty($uniform_name)) {
        $msg_text = "Uniform name is required.";
    } 
    elseif ($category_id <= 0) {
        $msg_text = "Please select a valid category.";
    } 
    elseif (empty($size)) {
        $msg_text = "Please select a size.";
    }
    elseif ($price === '' || !is_numeric($price) || floatval($price) < 0) {
        $msg_text = "Please enter a valid price.";
    } 
    elseif ($stock === '' || !is_numeric($stock) || intval($stock) < 0) {
        $msg_text = "Please enter a valid stock quantity.";
    } 
    else {
        $image_name = '../../src/placeholder.jpg'; 
        $upload_success = true;

        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
            $upload_dir = __DIR__ . "/../src/uploads/uniforms/";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file_ext = strtolower(pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION));
            $new_file_name = time() . '_' . preg_replace("/[^a-zA-Z0-9]/", "_", $uniform_name) . '.' . $file_ext;
            $final_destination = $upload_dir . $new_file_name;

            if (move_uploaded_file($_FILES['product_image']['tmp_name'], $final_destination)) {
                $image_name = $new_file_name; 
            } else {
                $upload_success = false;
                $msg_text = "Upload Error.";
            }
        }

        if ($upload_success) {
            try {
                $price_val = floatval($price);
                $stock_val = intval($stock);
                $status_db = ($stock_val > 0) ? 'Available' : 'Out of Stock';

                $query = "INSERT INTO uniforms (category_id, product_name, description, status, price, stock_quantity, size, product_image) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

                if ($stmt = mysqli_prepare($conn, $query)) {
                    mysqli_stmt_bind_param($stmt, "isssdiss", 
                        $category_id, 
                        $uniform_name, 
                        $description,
                        $status_db,
                        $price_val, 
                        $stock_val, 
                        $size,
                        $image_name
                    );
                    
                    if (mysqli_stmt_execute($stmt)) {
                        $status = "success";
                        $msg_text = "Uniform successfully added!";
                    } else {
                        $msg_text = "SQL Error: " . mysqli_stmt_error($stmt);
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $msg_text = "Prepare Error: " . mysqli_error($conn);
                }
            } catch (Exception $e) {
                $msg_text = "System Error: " . $e->getMessage();
            }
        }
    }

    echo json_encode([
        'status' => $status,
        'msg' => $msg_text
    ]);
    exit;
}