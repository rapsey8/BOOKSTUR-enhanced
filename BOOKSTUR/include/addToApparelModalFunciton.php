<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

$status = "error";
$msg_text = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = trim($_POST['product_name'] ?? '');
    $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
    $description = trim($_POST['description'] ?? '');
    $size = trim($_POST['size'] ?? 'N/A');
    $price = $_POST['price'] ?? '';
    $stock = $_POST['stock'] ?? '';

    if (empty($product_name)) {
        $msg_text = "Product name is required.";
    } 
    elseif ($category_id <= 0) {
        $msg_text = "Please select a valid category.";
    } 
    elseif ($price === '' || !is_numeric($price) || floatval($price) < 0) {
        $msg_text = "Please enter a valid price.";
    } 
    elseif ($stock === '' || !is_numeric($stock) || intval($stock) < 0) {
        $msg_text = "Please enter a valid stock quantity.";
    } 
    else {
        $image_file_name = 'placeholder.jpg'; 
        $upload_success = true;

        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
            $upload_dir = __DIR__ . "/../src/uploads/products/";
            
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file_ext = strtolower(pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION));
            $new_file_name = time() . '_' . preg_replace("/[^a-zA-Z0-9]/", "_", $product_name) . '.' . $file_ext;
            $final_destination = $upload_dir . $new_file_name;

            if (move_uploaded_file($_FILES['product_image']['tmp_name'], $final_destination)) {
                $image_file_name = $new_file_name; 
            } else {
                $upload_success = false;
                $msg_text = "Upload Error";
            }
        }

        if ($upload_success) {
            try {
                $price_val = floatval($price);
                $stock_val = intval($stock);
                $status_db = ($stock_val > 0) ? 'Available' : 'Out of Stock';

                $query = "INSERT INTO apparel (category_id, product_name, description, size, price, stock_quantity, product_image, status) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                
                if ($stmt = mysqli_prepare($conn, $query)) {
                    mysqli_stmt_bind_param($stmt, "isssdiss", 
                        $category_id, 
                        $product_name, 
                        $description, 
                        $size,
                        $price_val, 
                        $stock_val, 
                        $image_file_name, 
                        $status_db
                    );
                    
                    if (mysqli_stmt_execute($stmt)) {
                        $status = "success";
                        $msg_text = "Product successfully added to the catalog!";
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