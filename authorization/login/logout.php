<?php
	session_start();

	if (!isset($_SESSION['authorization'])) {
		header('Location: ../login/index.php');
		exit();
	}

	$id_authorization = $_SESSION['authorization'];
	require_once "../../connect.php";

	$connection = @new mysqli($host, $db_user, $db_password, $db_name);
	$connection->query("SET NAMES 'utf8'");

	if ($connection->connect_errno != 0) {
		echo "Internal error: " . $connection->connect_errno;
	} else {
		$date = date("Y-m-d H:i:sa");

		$sql = "INSERT INTO status (id, id_authorization, user, date, ip, info) VALUES (NULL, ?, ?, ?, ?, 'Cofniecie autoryzacji dla użytkownika');";
		$stmt = $connection->prepare($sql);
		$stmt->bind_param("ssss", $_SESSION['id_authorization'], $_SESSION['name'], $date, $_SESSION['ip']);
		$stmt->execute();
		$stmt->close();

		$connection->close();
	}

	session_unset();
	session_destroy();
	header('Location: index.php');
?>
