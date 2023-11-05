<?php 
	session_start();
    $_SESSION['search'] = $_POST['search'];
    $_SESSION['data'] = $_POST['data'];
    $_SESSION['first'] = true;
    header('Location: index.php');
?>