<?php
	session_start();
	if (!isset($_SESSION['authorization'])) {
		header('Location: ../../login/index.php');
		exit();
	}

	if (isset($_GET['id'])) {
		require_once "../../../connect.php";
		$id = $_GET['id'];
		$id_authorization = $_SESSION['id_authorization'];

		try {
			$connection = new mysqli($host, $db_user, $db_password, $db_name);
			mysqli_report(MYSQLI_REPORT_STRICT);
			$connection->set_charset("utf8");

			$connection->begin_transaction();

			$stmt = $connection->prepare("DELETE FROM `book` WHERE id = ?");
			$stmt->bind_param("i", $id);
			$stmt->execute();

			$date = date("Y-m-d H:i:sa");
			$stmt = $connection->prepare("INSERT INTO status (id, id_authorization, user, date, ip, info) VALUES (NULL, ?, ?, ?, ?, ?)");
			$stmt->bind_param("sssss", $_SESSION['id_authorization'], $_SESSION['name'], $date, $_SESSION['ip'], $info);
			$info = "Użytkownik usunął książkę [ID: $id]";
			$stmt->execute();

			$connection->commit();
			$connection->close();

			$_SESSION['info'] = 'Usunięto pomyślnie!';
			header('Location: ../index.php');
		} catch (Exception $e) {
			$connection->rollback();
			$_SESSION['info'] = 'Wystąpił błąd: ' . $e->getMessage();
			header('Location: ../index.php');
		}
	} else {
		header('Location: ../index.php');
		exit();
	}
?>
