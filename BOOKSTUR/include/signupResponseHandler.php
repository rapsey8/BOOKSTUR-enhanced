<?php
require_once './config.php';

$status = "";
$msg_text ="";

if ($_SERVER['REQUEST_METHOD']=='POST'){
    $full_name = trim($_POST['full_name'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $student_number = trim($_POST['student_number'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    // Input validation
    if(empty($full_name)){
        $status = "error";
        $msg_text = "Fullname is required.";
    }
    elseif (empty($course)) {
        $status = "error";
        $msg_text = "Course is required.";
    }
    elseif (strlen($student_number) != 9) {
        $status = "error";
        $msg_text = "Please complete your student number (9 digits required).";
    }
    elseif (empty($password)) {
        $status = "error";
        $msg_text = "Password is required.";
    }
    elseif (empty($confirm_password)) {
        $status = "error";
        $msg_text = "Please confirm your password.";
    }
    elseif ($password !== $confirm_password) {
        $status = "error";
        $msg_text = "Passwords do not match.";
    }
    // Password Validation
    else{
        if (strlen($password) < 8){
            $status = "error";
            $msg_text = "Password should atleast be 8 characters long.";
        }
        elseif(!preg_match('/[A-Z]/', $password)){
            $status = 'error';
            $msg_text = 'Password must include atleast one upper case letter.';
        }
        elseif(!preg_match('/[a-z]/', $password)){
            $status = 'error';
            $msg_text = 'Password must include atleast one lower case letter.';
        }
        elseif(!preg_match('/\d/', $password)){
            $status = 'error';
            $msg_text = 'Password must include atleast one number.';
        }
         elseif (!preg_match('/[$#@!?]/', $password)) {
            $status = 'error';
            $msg_text = 'Password must include atleast one special character';
        } 
        else{
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            try{
                $check_stmt = $conn->prepare("SELECT * FROM users WHERE student_number = ? ");
                $check_stmt->bind_param('i', $student_number);
                $check_stmt->execute();
                $result = $check_stmt->get_result();
                if ($row = $result->fetch_assoc()){
                    $status = "error";
                    $msg_text = "Student Already has an account.";
                }
                else{
                    $stmt = $conn->prepare("INSERT INTO users(full_name, course, student_number, password) VALUES(?,?,?,?)");
                    $stmt->bind_param('ssis',$full_name, $course, $student_number, $hashed_password);

                    if($stmt->execute()){
                        $status = "success";
                        $msg_text = "You have signed up successfully.";
                    }
                    else{
                         $status = "error";
                        $msg_text = "signup failed" . $conn->error;
                    }
                }
            } catch (Exception $e) {
                $status = "error";
                $msg_text = "Database Error: " . $e->getMessage();
            }
        }
    }header('Content-Type: application/json');
        echo json_encode([
            'status' => $status,
            'msg' => $msg_text
        ]);
        exit; 
}
?>