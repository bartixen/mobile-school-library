<?php
	session_start();
	if (!isset($_SESSION['authorization'])) {
		header('Location: ../login/index.php');
		exit();
	}
    $_SESSION['full'] = true;
    header('Location: index.php');
?> 
