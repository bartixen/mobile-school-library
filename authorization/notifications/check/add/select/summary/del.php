<?php
	session_start();
	if (!isset($_SESSION['authorization'])) {
		header('Location: ../../../../../login/index.php');
		exit();
	}

	unset($_SESSION['reservation_id']);
	unset($_SESSION['reservation_name']);
	unset($_SESSION['reservation_last_name']);
	unset($_SESSION['reservation_class']);
	unset($_SESSION['reservation_phone_number']);
	unset($_SESSION['reservation_email']);
	unset($_SESSION['reservation_id_book']);
	unset($_SESSION['title']);
	unset($_SESSION['author']);
	header('Location: ../../../../index.php');
?>
