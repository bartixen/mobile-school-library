<?php
	session_start();

	if (empty($_POST['login']) && empty($_POST['password']) && empty($_POST['csrf_token'])) {
		$_SESSION['info'] = 'Wystąpił błąd podczas przesyłania kluczy';
		header('Location: index.php');
		exit();
	}



	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*';
	$charactersLength = strlen($characters);
	$_SESSION['id_authorization'] = '';
	for ($i = 0; $i < 6; $i++) {
		$_SESSION['id_authorization'] .= $characters[rand(0, $charactersLength - 1)];
	}

	if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
		$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
		$_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
	}
	$client  = @$_SERVER['HTTP_CLIENT_IP'];
	$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	$remote  = $_SERVER['REMOTE_ADDR'];
	if (filter_var($client, FILTER_VALIDATE_IP)) {
		$ip = $client;
	} elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
		$ip = $forward;
	} else {
		$ip = $remote;
	}
	$_SESSION['ip'] = $ip;

	$secret_key = "6LeSWuQnAAAAAJZoVRJGhWvtRQoBueQokc1XDdvP";
	$check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $_POST['g-recaptcha-response']);
	$answer = json_decode($check);

	if ($_POST['csrf_token'] === $_SESSION['csrf_token']) {
		$_SESSION['info'] = "Wystąpił problem podczas autoryzacji (CSRF)";
		header('Location: index.php');
	} else {
		if ($answer->success == false) {
			$status = false;
			$_SESSION['info'] = "Wystąpił problem podczas autoryzacji (recaptcha)";
			header('Location: index.php');
		} else {
			require_once "../../connect.php";
			$connection = @new mysqli($host, $db_user, $db_password, $db_name);
			$connection->query("SET NAMES 'utf8'");

			if ($connection->connect_errno != 0) {
				echo "Internal error: " . $connection->connect_errno;
			} else {
				$login = mysqli_real_escape_string($connection, $_POST['login']);
				$password = $_POST['password'];

				$sql = "SELECT authorization FROM settings";
				$result = $connection->query($sql);
				if ($result->num_rows > 0) {
					while ($row = $result->fetch_assoc()) {
						$authorization = $row["authorization"];
					}
				}
		
				if ($authorization == 0) {
					$_SESSION['info'] = 'Autoryzacja jest obecnie wyłączona';
					header('Location: index.php');
					exit();
				}

				$sql = "SELECT * FROM authentication WHERE user=?";
				$stmt = $connection->prepare($sql);
				$stmt->bind_param("s", $login);
				$stmt->execute();
				$result = $stmt->get_result();

				if ($result->num_rows > 0) {
					$row = $result->fetch_assoc();
					$hash = $row["password"];
					$name = $row["user"];
					$date = date("Y-m-d H:i:sa");
					$_SESSION['name'] = $name;

					if (password_verify($password, $hash)) {
						$_SESSION['authorization'] = true;
						$session_cookie_params = session_get_cookie_params();
						$cookie_name = session_name();
						setcookie(
							$cookie_name,
							session_id(),
							0,
							$session_cookie_params['path'],
							$session_cookie_params['domain'],
							true,
							true
						);
						unset($_SESSION['info']);

						$stmt->close();

						$sql = "INSERT INTO status (id, id_authorization, user, date, ip, info) VALUES (NULL, ?, ?, ?, ?, 'Pomyślna autoryzacja użytkownika');";
						$stmt = $connection->prepare($sql);
						$stmt->bind_param("ssss", $_SESSION['id_authorization'], $_SESSION['name'], $date, $_SESSION['ip']);
						$stmt->execute();

						$stmt->close();
						$connection->close();

						header('Location: ../');
					} else {
						$stmt->close();

						$sql = "INSERT INTO status (id, id_authorization, user, date, ip, info) VALUES (NULL, ?, ?, ?, ?, 'Niepoprawne dane przy autoryzacji');";
						$stmt = $connection->prepare($sql);
						$stmt->bind_param("ssss", $_SESSION['id_authorization'], $_SESSION['name'], $date, $_SESSION['ip']);
						$stmt->execute();

						$stmt->close();
						$connection->close();

						session_unset();
						session_destroy();
						$_SESSION['info'] = 'Podane dane do autoryzacji są nieprawidłowe';
						header('Location: index.php');
					}
				}
			}
		}
	}
?>
