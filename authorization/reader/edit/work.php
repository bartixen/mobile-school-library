<?php
	session_start();
	if (!isset($_SESSION['authorization'])) {
		header('Location: ../../login/index.php');
		exit();
	}

    if (!empty($_POST['name']) && !empty($_POST['last_name']) && !empty($_POST['class'])) {
        $id_authorization = $_SESSION['id_authorization'];
        $id = $_SESSION['id'];
        $name = $_POST['name'];
        $last_name = $_POST['last_name'];
        $class = $_POST['class'];
        $phone_number = $_POST['phone_number'];
        $email = $_POST['email'];

        require_once "../../../connect.php";
        mysqli_report(MYSQLI_REPORT_STRICT);

        try {
            $connection = new mysqli($host, $db_user, $db_password, $db_name);
            $connection->set_charset("utf8"); 
            $connection->begin_transaction();

            $stmt = $connection->prepare("UPDATE `reader` SET `name` = ?, `last_name` = ?, `class` = ?, `phone_number` = ?, `email` = ? WHERE `reader`.`id` = ?");
            $stmt->bind_param("sssssi", $name, $last_name, $class, $phone_number, $email, $id);
            $stmt->execute();

            $connection->commit();

            $_SESSION['info'] = 'Zapisano pomyślnie!';

            $date = date("Y-m-d H:i:sa");
            $stmt = $connection->prepare("INSERT INTO status (id, id_authorization, user, date, ip, info) VALUES (NULL, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $_SESSION['id_authorization'], $_SESSION['name'], $date, $_SESSION['ip'], $info);
            $info = "Użytkownik edytował czytelnika [ID: $id, $name, $last_name, $class, $phone_number, $email]";
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
        $_SESSION['info'] = 'Wypełnij wymagane pola (imię, nazwisko, klasa)!';
        header('Location: index.php?id=' . $id);
    }
?>
