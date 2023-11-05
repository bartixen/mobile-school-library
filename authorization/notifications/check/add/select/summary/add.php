<?php
    session_start();

    if (!isset($_SESSION['authorization'])) {
        header('Location: ../../../../../login/index.php');
        exit();
    }

    if (isset($_SESSION['reader_id']) && isset($_SESSION['book_id'])) {
        $id_authorization = $_SESSION['id_authorization'];
        $reader_id = $_SESSION['user_id'];
        $book_id = $_SESSION['book_id'];

        require_once "../../../../../../connect.php";

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
                $stmt->execute();

                $reservation_id = $_SESSION['reservation_id'];
                $stmt = $connection->prepare("DELETE FROM `reservation` WHERE id = ?");
                $stmt->bind_param("s", $reservation_id);
                $stmt->execute();

                if ($stmt->execute()) {
                    $stmt = $connection->prepare("INSERT INTO status (id, id_authorization, user, date, ip, info) VALUES (NULL, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssss", $_SESSION['id_authorization'], $_SESSION['name'], $date, $_SESSION['ip'], $info);
                    $info = "Użytkownik dodał wypożyczenie z rezerwacji [ID_READER: $reader_id, ID_BOOK: $book_id]";
                    $stmt->execute();
                    $_SESSION['info'] = 'Pomyślnie dodano';
                    header('Location: ../../../../../rentals');
                } else {
                    throw new Exception($stmt->error);
                }
                $stmt->close();
                $connection->close();
            }
        } catch (Exception $e) {
            $_SESSION['info'] = 'Wystąpił błąd: ' . $e->getMessage();
            header('Location: ../../../../index.php');
        }
    } else {
        $_SESSION['info'] = 'Wystąpił błąd';
        header('Location: ../../../../index.php');
    }

    unset($_SESSION['reservation_id']);
    unset($_SESSION['reservation_name']);
    unset($_SESSION['reservation_last_name']);
    unset($_SESSION['reservation_class']);
    unset($_SESSION['reservation_phone_number']);
    unset($_SESSION['reservation_email']);
    unset($_SESSION['reservation_id_book']);
    unset($_SESSION['title']);
    unset($_SESSION['author']);
    unset($_SESSION['reader_id']);
    unset($_SESSION['reader_name']);
    unset($_SESSION['last_name']);
    unset($_SESSION['class']);
    unset($_SESSION['book_id']);
    unset($_SESSION['record_number']);
    header('Location: ../../../index.php');
?>
