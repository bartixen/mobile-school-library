<?php
	session_start();
	if (!isset($_SESSION['authorization'])) {
		header('Location: ../login/index.php');
		exit();
	}
    if ($_SESSION['name'] != "admin") {
        header('Location: index.php');
		exit();
    }

    require_once "../../connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);
    
    try {
        $connection = new mysqli($host, $db_user, $db_password, $db_name);

        $sql = "SELECT reservation FROM settings";
        $result = $connection->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $reservation = $row["reservation"];
            }
        }

        if ($reservation == 0) {
            $status = 1;
        } else {
            $status = 0;
        }

        $sql = "UPDATE settings SET reservation=$status WHERE 1";
        $connection->query($sql);

        $date = date("Y-m-d H:i:sa");
        $info = "Administrator zmienił status rezerwacji książek do strony ($reservation)";
        $sql = "INSERT INTO status (id, id_authorization, user, date, ip, info) VALUES (NULL, '" . $_SESSION['id_authorization'] . "', '" . $_SESSION['name'] . "', '$date', '" . $_SESSION['ip'] . "', '$info')";
        $connection->query($sql);
        
        $connection->close();
    } catch (Exception $e) {
        echo 'Internal error: ' . $e;
    }
    header('Location: index.php');
?> 