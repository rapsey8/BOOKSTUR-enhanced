<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user_id'])) {
    header("Location: /BOOKSTUR/index.php");
    exit(); 
}
$user_id = $_SESSION['user_id'];

if (isset($conn)) {
    $sync_query = $conn->query("SELECT profile_pic FROM users WHERE id = $user_id");
    if ($sync_query && $sync_row = $sync_query->fetch_assoc()) {
        $_SESSION['profile_pic'] = $sync_row['profile_pic'];
    }
}

$timeout = 3600; 
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    session_unset();
    session_destroy();
    header("Location: /BOOKSTUR/index.php");
    exit();
}
$_SESSION['last_activity'] = time(); 


$user_role = strtolower($_SESSION['course'] ?? 'Student');
?>