<?php require_once '../../include/config.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSCR BOOKSTORE || Sign Up</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="../../style.css">
    <link rel="stylesheet" href="signup.css">
    
</head>
<body>

<div class="login-container"> 
    <div class="login-wrapper">
        <div class="brand-side hide-on-mobile">
            <div style="flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                <img src="../../src/SSCRLogo1.png" alt="HEPC Logo" style="width: 80%; height: auto;">
                <p style="font-size: 1.95rem; font-weight: 900; text-align: center; color:rgb(44, 17, 17);">SSCR BOOKSTORE</p>
            </div>
            
            <footer style="width: 100%; text-align: center;color:rgb(44, 17, 17); font-size: 0.75rem; font-weight: 500;">
                <p style="margin: 0; font-size: 14px;">&copy; <?= date("Y"); ?> SSCR BOOKSTORE</p>
                <p style="margin-top: 2px; font-size: 14px; font-weight: 500;">Developed by Alyssa Shane S. Catindig, Stanley Gabriel M. Crisostomo, Franchesca H. Encarnacion, Julien Carl J. Jose</p>
            </footer>
        </div>

        <div class="form-side">
            <div class="form-header">
                 <div class="logo-box">
                    <img src="../../src/SSCRLogo1.png" alt="logo" class="mobile-logo">
                </div>

                <div class="header-text">
                    <h2>Sign Up</h2>
                </div>
            </div>

            <form action="../../include/signupResponseHandler.php" method="POST" id="signupForm">

                <div class="input-box">
                    <span class="material-symbols-outlined">badge</span>
                    <input type="text" name="full_name" placeholder="Full Name" >
                </div>

                <div class="input-box">
                    <span class="material-symbols-outlined">school</span>
                    <input type="text" name="course" placeholder="Course" >
                </div>

                <div class="input-box">
                    <span class="material-symbols-outlined">person</span>
                    <input type="text" name="student_number" placeholder="Student Number" >
                </div>

                <div class="input-box">
                    <span class="material-symbols-outlined">lock</span>
                    <input type="password" name="password" placeholder="Password" >
                </div>

                <div class="input-box">
                    <span class="material-symbols-outlined">lock_reset</span>
                    <input type="password" name="confirm_password" placeholder="Confirm Password" >
                </div>

                <button type="submit" class="login-btn">
                    SIGN UP
                    <span class="material-symbols-outlined">double_arrow</span>
                </button>
            </form> 
            
            <p style="text-align:center; margin-top:15px; font-size:0.85rem; color:#666;">
                Already have an account? <a href="../../index.php" style="color:#d32f2f; text-decoration:none; font-weight:bold;">Login</a>
            </p>
        </div>
    </div>
</div>
<script src="signupResponseHandler.js"></script>
<script src="../../icons/sweetalert2.all.min.js"></script>
</body>
</html>