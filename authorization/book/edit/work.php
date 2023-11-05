<?php
	session_start();
	if (!isset($_SESSION['authorization'])) {
		header('Location: ../../login/index.php');
		exit();
	}

    if (!empty($_POST['title']) && !empty($_POST['author']) && !empty($_POST['record_number'])) {
        $id = $_SESSION['id'];
        $title = $_POST['title'];
        $author = $_POST['author'];
        $record_number = $_POST['record_number'];

        require_once "../../../connect.php";
        mysqli_report(MYSQLI_REPORT_STRICT);

        try {
            $connection = new mysqli($host, $db_user, $db_password, $db_name);
            $connection->set_charset("utf8"); 
            $connection->begin_transaction();

            $stmt = $connection->prepare("UPDATE `book` SET `title` = ?, `author` = ?, `record_number` = ? WHERE `book`.`id` = ?");
            $stmt->bind_param("sssi", $title, $author, $record_number, $id);
            $stmt->execute();

            $connection->commit();

            $_SESSION['info'] = 'Zapisano pomyślnie!';

            $date = date("Y-m-d H:i:sa");
            $stmt = $connection->prepare("INSERT INTO status (id, id_authorization, user, date, ip, info) VALUES (NULL, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $_SESSION['id_authorization'], $_SESSION['name'], $date, $_SESSION['ip'], $info);
            $info = "Użytkownik edytował książkę [ID: $id, $title, $author, $record_number]";
            $stmt->execute();

            header('Location: index.php?id=' . $id);
        } catch (Exception $e) {
            $connection->rollback();
            $_SESSION['info'] = 'Wystąpił błąd: ' . $e->getMessage();
            header('Location: index.php?id=' . $id);
        } finally {
            $connection->close();
        }
	} else {
        $_SESSION['info'] = 'Wypełnij wymagane pola (tytuł, autor, numer ewidencji)!';
        header('Location: index.php?id=' . $id);
    }
?>
