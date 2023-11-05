<?php
    session_start();

    if (!isset($_SESSION['authorization'])) {
        header('Location: ../../../login/index.php');
        exit();
    }

    require_once "../../../../connect.php";

    try {
        $connection = new mysqli($host, $db_user, $db_password, $db_name);
        mysqli_report(MYSQLI_REPORT_STRICT);
        $connection->set_charset("utf8");

        $name = $_SESSION['reservation_name'];
        $last_name = $_SESSION['reservation_last_name'];
        $class = $_SESSION['reservation_class'];
        $phone_number = $_SESSION['reservation_phone_number'];
        $email = $_SESSION['reservation_email'];

        $query = "SELECT id FROM reader WHERE name = ? AND last_name = ? AND class = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("sss", $name, $last_name, $class);
        $stmt->execute();
        $stmt->bind_result($user_id);
        $stmt->fetch();

        if ($user_id) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['search'] = 'title';
            $_SESSION['data'] = $_SESSION['title'];
            header('Location: select/');
        } else {
            $insert_query = "INSERT INTO reader (name, last_name, class, phone_number, email) VALUES (?, ?, ?, ?, ?)";
            $insert_stmt = $connection->prepare($insert_query);
            $insert_stmt->bind_param("sssss", $name, $last_name, $class, $phone_number, $email);

            if ($insert_stmt->execute()) {
                $user_id = $connection->insert_id;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['search'] = 'title';
                $_SESSION['data'] = $_SESSION['title'];
                header('Location: select/');
            } else {
                header('Location: ../index.php');
                $_SESSION['info'] = 'Wystąpił błąd podczas dodawania użytkownika';
                unset($_SESSION['title']);
                unset($_SESSION['author']);
            }
        }

        unset($_SESSION['reservation_name']);
        unset($_SESSION['reservation_last_name']);
        unset($_SESSION['reservation_class']);
        unset($_SESSION['reservation_phone_number']);
        unset($_SESSION['reservation_email']);
        unset($_SESSION['reservation_id_book']);

        $stmt->close();
        $insert_stmt->close();
        $connection->close();

    } catch (Exception $e) {
        $_SESSION['info'] = 'Wystąpił błąd: ' . $e->getMessage();
        header('Location: ../index.php');
    }
?>
