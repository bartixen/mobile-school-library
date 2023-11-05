<?php
	session_start();
	if (!isset($_SESSION['authorization'])) {
		header('Location: ../../../../login/index.php');
		exit();
	}

	if (!empty($_SESSION['reader_id']) && !empty($_SESSION['book_id'])) {
        $id_authorization = $_SESSION['id_authorization'];
        $reader_id = $_SESSION['reader_id'];
        $book_id = $_SESSION['book_id'];

        require_once "../../../../../connect.php";

        try {
            $connection = new mysqli($host, $db_user, $db_password, $db_name);
            mysqli_report(MYSQLI_REPORT_STRICT);
            $connection->set_charset("utf8");

            if ($connection->connect_errno != 0) {
                throw new Exception(mysqli_connect_error());
            } else {
				$date = date("Y-m-d H:i:sa");
                $stmt = $connection->prepare("INSERT INTO rentals (id, id_reader, id_book, date) VALUES (NULL, ?, ?, ?)");
                $stmt->bind_param("sss", $reader_id, $book_id, $date);

                if ($stmt->execute()) {
                    $_SESSION['info'] = 'Dodano poprawnie!';
                    $stmt = $connection->prepare("INSERT INTO status (id, id_authorization, user, date, ip, info) VALUES (NULL, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssss", $_SESSION['id_authorization'], $_SESSION['name'], $date, $_SESSION['ip'], $info);
                    $info = "Użytkownik dodał wypożyczenie [ID_READER: $reader_id, ID_BOOK: $book_id]";
                    $stmt->execute();
					$_SESSION['info'] = 'Pomyślnie dodano';
                    header('Location: index.php');
                } else {
                    throw new Exception($stmt->error);
                }
                $stmt->close();
                $connection->close();
            }
        } catch (Exception $e) {
            $_SESSION['info'] = 'Wystąpił błąd: ' . $e->getMessage();
            header('Location: ../../../index.php');
        }
    } else {
        $_SESSION['info'] = 'Wystąpił błąd';
        header('Location: ../../../index.php');
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
