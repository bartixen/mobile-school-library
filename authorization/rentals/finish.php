<?php 
	session_start();
	if (!isset($_SESSION['authorization'])) {
		header('Location: ../login/index.php');
		exit();
	}

	if (isset($_GET['id'])) {
		require_once "../../connect.php";
		$id = $_GET['id'];
		$id_authorization = $_SESSION['id_authorization'];

		try {
			$connection = new mysqli($host, $db_user, $db_password, $db_name);
			mysqli_report(MYSQLI_REPORT_STRICT);
			$connection->set_charset("utf8");

			$connection->begin_transaction();

            try {
            
                $sql = "SELECT `id_reader`, `id_book`, `date` FROM rentals WHERE id = $id";
                $result = $connection->query($sql);
            
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $reader_id = $row["id_reader"];
                    $book_id = $row["id_book"];
                    $rentals_date = $row["date"];
            
                    $sql = "SELECT `name`, `last_name`, `class` FROM reader WHERE id = $reader_id";
                    $result = $connection->query($sql);
            
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $reader = $row["name"] . ' ' . $row["last_name"] . ' [' . $row["class"] . ']';
            
                        $sql = "SELECT `title`, `author`, `record_number` FROM book WHERE id = $book_id";
                        $result = $connection->query($sql);
            
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $book = $row["title"] . ' (' . $row["author"] . ') [' . $row["record_number"] . ']';
                        } else {
                            throw new Exception("Nie znaleziono danych książki.");
                        }
                    } else {
                        throw new Exception("Nie znaleziono danych czytelnika.");
                    }
                } else {
                    throw new Exception("Nie znaleziono danych wypożyczenia.");
                }
            
                $connection->commit();
            } catch (Exception $e) {
                $connection->rollback();
                $_SESSION['info'] = 'Wystąpił błąd: ' . $e->getMessage();
                header('Location: index.php');
            }
            
            $date = date("Y-m-d H:i:sa");
			$stmt = $connection->prepare("INSERT INTO rentals_history (id, reader, book, rental_date, delivery_date) VALUES (NULL, ?, ?, ?, ?)");
			$stmt->bind_param("ssss", $reader, $book, $rentals_date, $date);
			$stmt->execute();

			$stmt = $connection->prepare("DELETE FROM `rentals` WHERE id = ?");
			$stmt->bind_param("i", $id);
			$stmt->execute();

			$date = date("Y-m-d H:i:sa");
			$stmt = $connection->prepare("INSERT INTO status (id, id_authorization, user, date, ip, info) VALUES (NULL, ?, ?, ?, ?, ?)");
			$stmt->bind_param("sssss", $_SESSION['id_authorization'], $_SESSION['name'], $date, $_SESSION['ip'], $info);
			$info = "Użytkownik zakończył wypożyczenie [ID: $id]";
			$stmt->execute();

			$connection->commit();
			$connection->close();

			$_SESSION['info'] = 'Zakończono pomyślnie!';
			header('Location: index.php');
		} catch (Exception $e) {
			$connection->rollback();
			$_SESSION['info'] = 'Wystąpił błąd: ' . $e->getMessage();
			header('Location: index.php');
		}
	} else {
		header('Location: index.php');
		exit();
	}
?>