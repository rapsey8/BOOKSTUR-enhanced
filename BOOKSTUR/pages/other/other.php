<?php
require_once '../../include/config.php';
require_once '../../include/auth_checker.php';
require_once '../../include/otherFunction.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../component/navbar/navbar.css">
    <link rel="stylesheet" href="../../component/searchbar/searchbar.css">
    <link rel="stylesheet" href="../../component/libraryHeader/libraryHeader.css">
    <link rel="stylesheet" href="../../component/homeFilter/homeFilter.css">
    <link rel="stylesheet" href="../../component/adminUtils/adminUtils.css">
    <link rel="stylesheet" href="../../component/addToBooksModal/addToBooksModal.css">
    <link rel="stylesheet" href="../../component/libraryContent/libraryContent.css">
    <link rel="stylesheet" href="../../component/footer/footer.css">
    <link rel="stylesheet" href="../../style.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
    <title>Book Catalog | SSCR-C Bookstore</title>
</head>
<body>
   <?php include '../../component/navbar/navbar.php'?>
   <?php include '../../component/otherHeader/otherHeader.php'?>
   <?php include '../../component/homeFilter/homeFilter.php'?>
    <?php include '../../component/libraryContent/libraryContent.php'?>
    <?php include '../../component/adminUtils/adminUtils.php'?>
    <?php include '../../component/addToUniformModal/addToUniformModal.php'?>
    <?php include '../../component/addToApparelModal/addToApparelModal.php'?>
    <?php include '../../component/addToOtherModal/addToOtherModal.php'?>
    <?php include '../../component/addToBooksModal/addToBooksModal.php'?>
    <?php include '../../component/footer/footer.php'?>
    
<script src="../../icons/sweetalert2.all.min.js"></script>
<script src="../../component/addToBooksModal/addToBooksModal.js"></script>
<script src="../../component/addToOtherModal/addToOtherModal.js"></script>
<script src="../../component/addToUniformModal/addToUniformModal.js"></script>
<script src="../../component/addToApparelModal/addToApparelModal.js"></script>
<script src="../../component/adminUtils/adminUtils.js"></script>
<script src="../../component/navbar/nav.js"></script>
</body>
</html>