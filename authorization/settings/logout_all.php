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

        $date = date("Y-m-d H:i:sa");
        $info = "Administrator wylogował wszystkich";
        $sql = "INSERT INTO status (id, id_authorization, user, date, ip, info) VALUES (NULL, '" . $_SESSION['id_authorization'] . "', '" . $_SESSION['name'] . "', '$date', '" . $_SESSION['ip'] . "', '$info')";
        $connection->query($sql);
        
        $connection->close();
    } catch (Exception $e) {
        echo 'Internal error: ' . $e;
    }

    $sessions = scandir(session_save_path());

    if ($sessions) {
        foreach ($sessions as $session) {
            if (strpos($session, "sess_") === 0) {
                session_id(substr($session, 5));
                session_start();
                session_destroy();
            }
        }
    }

    header('Location: index.php');
?> 