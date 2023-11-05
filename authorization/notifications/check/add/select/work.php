<?php 
	session_start();
    $_SESSION['search'] = $_POST['search'];
    if ($_POST['search1']=='') {
        $_SESSION['search1']='=';
    } else {
        $_SESSION['search1'] = $_POST['search1'];
    }
    $_SESSION['data'] = $_POST['data'];
    $_SESSION['first'] = true;
    $_SESSION['g-recaptcha-response'] = $_POST['g-recaptcha-response'];
    header('Location: index.php?id=' . $_SESSION['reader_id']);
?>