<?php
	session_start();
	if (!isset($_SESSION['authorization'])) {
		header('Location: ../../../../login/index.php');
		exit();
	}

	unset($_SESSION['reader_id']);
	unset($_SESSION['reader_name']);
	unset($_SESSION['last_name']);
	unset($_SESSION['class']);
	unset($_SESSION['book_id']);
	unset($_SESSION['title']);
	unset($_SESSION['author']);
	unset($_SESSION['record_number']);
	header('Location: ../../../index.php');
?>
