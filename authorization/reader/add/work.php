<?php
    session_start();

    if (!isset($_SESSION['authorization'])) {
        header('Location: ../../login/index.php');
        exit();
    }

    if (!empty($_POST['name']) && !empty($_POST['last_name']) && !empty($_POST['class'])) {
        $id_authorization = $_SESSION['id_authorization'];
        $name = $_POST['name'];
        $last_name = $_POST['last_name'];
        $class = $_POST['class'];
        $phone_number = $_POST['phone_number'];
        $email = $_POST['email'];

        require_once "../../../connect.php";

        try {
            $connection = new mysqli($host, $db_user, $db_password, $db_name);
            mysqli_report(MYSQLI_REPORT_STRICT);
            $connection->set_charset("utf8");

            if ($connection->connect_errno != 0) {
                throw new Exception(mysqli_connect_error());
            } else {
                $stmt = $connection->prepare("INSERT INTO reader (id, name, last_name, class, phone_number, email) VALUES (NULL, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $name, $last_name, $class, $phone_number, $email);

                if ($stmt->execute()) {
                    $_SESSION['info'] = 'Dodano poprawnie!';
                    $date = date("Y-m-d H:i:sa");
                    $stmt = $connection->prepare("INSERT INTO status (id, id_authorization, user, date, ip, info) VALUES (NULL, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssss", $_SESSION['id_authorization'], $_SESSION['name'], $date, $_SESSION['ip'], $info);
                    $info = "Użytkownik dodał nowego czytelnika [$name, $last_name, $class, $phone_number, $email]";
                    $stmt->execute();
                    header('Location: index.php');
                } else {
                    throw new Exception($stmt->error);
                }
                $stmt->close();
                $connection->close();
            }
        } catch (Exception $e) {
            $_SESSION['info'] = 'Wystąpił błąd: ' . $e->getMessage();
            header('Location: index.php');
        }
    } else {
        $_SESSION['info'] = 'Wypełnij wymagane pola (imię, nazwisko, klasa)!';
        header('Location: index.php');
    }
?>
