<?php
    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $connection = new mysqli($host, $db_user, $db_password, $db_name);
        $connection->query("SET NAMES 'utf8'");

        if ($connection->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        } else { 
            $sql = "SELECT DISTINCT `title`, `author` FROM book ORDER BY RAND() LIMIT 5;";
            $result = $connection->query($sql);

            if ($result->num_rows > 0) {
                echo "<table><thead><tr><th>Tytuł</th><th>Autor</th></tr></thead><tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><th>" . $row["title"] . "</th><th>" . $row["author"] . "</th></tr>";
                }
                echo "</tbody></table>";
            } else {
                echo '<div type="button" class="pop-up">Brak danych</div>';
            }

            $connection->close();
        }
    } catch (Exception $e) {
        echo 'Internal error: ' . $e;
    }
?>
