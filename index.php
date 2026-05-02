<?php include './include/loginResponseHandler.php'?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="./pages/signup/signup.css">
    <title>SSCR BOOKSTORE || Login</title>
</head>
<body>

  <div class = "login-container"> 
    <div class="login-wrapper">
         <div class="brand-side hide-on-mobile">
            <div style="flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                <img src="./src/SSCRLogo1.png" alt="SSCR LOGO" style="width: 80%; height: auto;">
                <p style="font-size: 1.95rem; font-weight: 900; text-align: center; color:rgb(44, 17, 17);">SSCR BOOKSTORE</p>
            </div>
            
            <footer style="width: 100%; text-align: center;color:rgb(44, 17, 17); font-size: 0.75rem; font-weight: 500;">
                <p style="margin: 0; font-size: 14px;">&copy; <?= date("Y"); ?> SSCR BOOKSTORE</p>
                <p style="margin-top: 2px; font-size: 14px; font-weight: 500;">Developed by Alyssa Shane S. Catindig, Stanley Gabriel M. Crisostomo, Franchesca H. Encarnacion, Julien Carl J. Jose</p>
            </footer>
        </div>
        <div class="form-side">
            <div class="form-header">
                <img src="./src/SSCRLogo1.png" alt="SSCR LOGO" style="width: 70px; height: auto;" class="mobile-logo">
                <h2>Login</h2>
            </div>

            <form action="./include/loginResponseHandler.php" method="POST" id="loginForm">
                <div class="input-box">
                    <span class="material-symbols-outlined">person</span>
                    <input type="text" name="student_number" placeholder="Student Number" >
                </div>

                <div class="input-box">
                    <span class="material-symbols-outlined">lock</span>
                    <input type="password" name="password" placeholder="Password">
                </div>

                <button type="submit" class="login-btn">
                    LOGIN
                    <span class="material-symbols-outlined">double_arrow</span>
                </button>
            </form> 
            
            <p style="text-align:center; margin-top:20px; font-size:0.9rem; color:#666;">
                Don't have an account? <a href="./pages/signup/signup.php" style="color:#d32f2f; text-decoration:none; font-weight:bold;">Sign Up</a>
            </p>
        </div>
    </div>
  </div>
  <script src="./icons/sweetalert2.all.min.js"></script>
<script src="./script.js"></script>

</body>
</html>