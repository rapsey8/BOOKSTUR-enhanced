<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once  'config.php';

$status = "";
$msg_text = "";
$full_name ="";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_number = trim($_POST['student_number'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($student_number)) {
        $status = "error";
        $msg_text = "Please enter your student number.";
    } elseif (empty($password)) {
        $status = "error";
        $msg_text = "Please enter your password.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT * FROM users WHERE student_number = ?");
            $stmt->bind_param('i', $student_number);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                if (password_verify($password, $row['password'])) {
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['student_number'] = $row['student_number']; 
                    $_SESSION['full_name'] = $row['full_name'] ?? 'User';
                    $_SESSION['course'] = $row['course'];

                    $status = "success";
                    $msg_text = "Login successful!";
                } else {
                    $status = "error";
                    $msg_text = "Invalid student number or password.";
                }
            } else {
                $status = "error";
                $msg_text = "Invalid student number or password.";
            }
        } catch (Exception $e) {
            $status = "error";
            $msg_text = "System Error: " . $e->getMessage();
        }
    }
    header('Content-Type: application/json');
    echo json_encode([
        'status' => $status,
        'msg' => $msg_text,
        'full_name' => $full_name
    ]);
    exit; 
}
?>