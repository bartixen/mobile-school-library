<?php
    session_start();

    if (!isset($_SESSION['authorization'])) {
        header('Location: ../../login/index.php');
        exit();
    }

    if (!empty($_POST['title']) && !empty($_POST['author']) && !empty($_POST['record_number'])) {
        $id_authorization = $_SESSION['id_authorization'];
        $title = $_POST['title'];
        $author = $_POST['author'];
        $record_number = $_POST['record_number'];

        require_once "../../../connect.php";

        try {
            $connection = new mysqli($host, $db_user, $db_password, $db_name);
            mysqli_report(MYSQLI_REPORT_STRICT);
            $connection->set_charset("utf8");

            if ($connection->connect_errno != 0) {
                throw new Exception(mysqli_connect_error());
            } else {
                $stmt = $connection->prepare("INSERT INTO book (id, title, author, record_number) VALUES (NULL, ?, ?, ?)");
                $stmt->bind_param("sss", $title, $author, $record_number);

                if ($stmt->execute()) {
                    $_SESSION['info'] = 'Dodano poprawnie!';
                    $date = date("Y-m-d H:i:sa");
                    $stmt = $connection->prepare("INSERT INTO status (id, id_authorization, user, date, ip, info) VALUES (NULL, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssss", $_SESSION['id_authorization'], $_SESSION['name'], $date, $_SESSION['ip'], $info);
                    $info = "Użytkownik dodał nową książkę [$title, $author, $record_number]";
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
        $_SESSION['info'] = 'Wypełnij wszystkie dostępne pola!';
        header('Location: index.php');
    }
?>
