<?php
	session_start();
	if (!isset($_SESSION['authorization'])) {
		header('Location: ../../login/index.php');
		exit();
	}

	$id_authorization = $_SESSION['id_authorization'];

	require_once "../../../connect.php";
	$id = $_GET['id'];
    if ($id!=0) {
		try {
			$connection = new mysqli($host, $db_user, $db_password, $db_name);
			mysqli_report(MYSQLI_REPORT_STRICT);
			$connection->set_charset("utf8");

			if ($connection->connect_errno != 0) {
				throw new Exception(mysqli_connect_error());
			} else {
				$stmt = $connection->prepare("DELETE FROM `reservation` WHERE id = ?");
				$stmt->bind_param("i", $id);
				$stmt->execute();

				if ($stmt->execute()) {
					$date = date("Y-m-d H:i:sa");
					$stmt = $connection->prepare("INSERT INTO status (id, id_authorization, user, date, ip, info) VALUES (NULL, ?, ?, ?, ?, ?)");
					$stmt->bind_param("sssss", $_SESSION['id_authorization'], $_SESSION['name'], $date, $_SESSION['ip'], $info);
					$info = "Użytkownik usunął rezerwacje [ID: $id]";
					$stmt->execute();
					header('Location: ../index.php');
				} else {
					throw new Exception($stmt->error);
				}
				$stmt->close();
				$connection->close();
			}
		} catch (Exception $e) {
			$_SESSION['info'] = 'Wystąpił błąd: ' . $e->getMessage();
			header('Location: ../index.php');
		}
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
	header('Location: ../index.php');
?>
