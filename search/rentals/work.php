<?php
    session_start();

    $secret_key = "6LeSWuQnAAAAAJZoVRJGhWvtRQoBueQokc1XDdvP";
	$check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $_POST['g-recaptcha-response']);
	$answer = json_decode($check);

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

    if ($_POST['csrf_token'] === $_SESSION['csrf_token']) {
		$_SESSION['info'] = "Wystąpił problem podczas autoryzacji (CSRF)";
		header("Location: index.php?id=" . $id_book . "");
	} else {
        if ($answer->success == false) {
			$status = false;
			$_SESSION['info'] = "Wystąpił problem podczas autoryzacji (recaptcha)";
			header("Location: index.php?id=" . $id_book . "");
		} else {
            if (!empty($_POST['name']) && !empty($_POST['last_name']) && !empty($_POST['class'])) {
                $id_book = $_SESSION['id'];
                $name_reader = $_POST['name'];
                $last_name_reader = $_POST['last_name'];
                $class_reader = $_POST['class'];
                $phone_number_reader = $_POST['phone_number'];
                $email_reader = $_POST['email'];

                require_once "../../connect.php";

                try {
                    $connection = new mysqli($host, $db_user, $db_password, $db_name);
                    mysqli_report(MYSQLI_REPORT_STRICT);
                    $connection->set_charset("utf8");

                    if ($connection->connect_errno != 0) {
                        throw new Exception(mysqli_connect_error());
                    } else {
                        $stmt = $connection->prepare("INSERT INTO reservation (id, name, last_name, class, phone_number, email, id_book) VALUES (NULL, ?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("ssssss", $name_reader, $last_name_reader, $class_reader, $phone_number_reader, $email_reader, $id_book);

                        if ($stmt->execute()) {
                            $date = date("Y-m-d H:i:sa");
                            $stmt = $connection->prepare("INSERT INTO status (id, id_authorization, user, date, ip, info) VALUES (NULL, 'Brak', ?, ?, ?, ?)");
                            $stmt->bind_param("ssss", $name_reader_plus, $date, $ip, $info);
                            $name_reader_plus = $name_reader . ' ' . $last_name_reader;
                            $info = "Użytkownik zarezerwował książkę [$name_reader $last_name_reader, $class_reader, $phone_number_reader, $email_reader, ID: $id_book]";
                            $stmt->execute();
                            header('Location: finish/');
                        } else {
                            throw new Exception($stmt->error);
                        }
                        $stmt->close();
                        $connection->close();
                    }
                } catch (Exception $e) {
                    $_SESSION['info'] = 'Wystąpił błąd: ' . $e->getMessage();
                    header("Location: index.php?id=" . $id_book . "");
                }
            } else {
                $_SESSION['info'] = 'Wypełnij wszystkie dostępne pola!';
                header("Location: index.php?id=" . $id_book . "");
            }
        }
    }
?>
