<?php
    session_start();
    $_SESSION['search'] = $_POST['search'];
    $_SESSION['data'] = $_POST['data'];
    $_SESSION['first'] = true;
    $_SESSION['g-recaptcha-response'] = $_POST['g-recaptcha-response'];
    header('Location: index.php');
?>
