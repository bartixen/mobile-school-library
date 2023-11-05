<?php
    session_start();

    if (!isset($_SESSION['authorization'])) {
        header('Location: ../login/index.php');
        exit();
    }

    require_once "../../connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $connection = new mysqli($host, $db_user, $db_password, $db_name);
        $connection->query("SET NAMES 'utf8'");

        if ($connection->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        } else {
            $count = ($_SESSION['full'] == true) ? 'DESC' : 'DESC LIMIT 10';
            $sql = "SELECT * FROM reservation ORDER BY `reservation`.`id` $count";
            $result = $connection->query($sql);

            if ($result->num_rows > 0) {
                echo '<div style="overflow-x: auto;"><table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Imię</th>
                                <th>Nazwisko</th>
                                <th>Klasa</th>
                                <th>Książka</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>';

                while ($row = $result->fetch_assoc()) {
                    $id = $row["id"];
                    $name = $row["name"];
                    $last_name = $row["last_name"];
                    $class = $row["class"];
                    $book = $row["id_book"];

                    $sql = "SELECT `title`, `author` FROM book WHERE id = $book";
                    $bookResult = $connection->query($sql);

                    if ($bookResult->num_rows > 0) {
                        $bookRow = $bookResult->fetch_assoc();
                        $book = $bookRow["title"] . ' (' . $bookRow["author"] . ')';
                    } else {
                        throw new Exception("Nie znaleziono danych książki.");
                    }
                    echo "<tr>
                            <th>$id</th>
                            <th>$name</th>
                            <th>$last_name</th>
                            <th>$class</th>
                            <th>$book</th>
                            <th><a href='check/?id=$id'><button type='button' class='button icon fa-edit'>Sprawdź</button></a></th>
                        </tr>";
                }
                echo "</tbody></table></div>";
            } else {
                echo '<div type="button" class="pop-up">Brak danych</div>';
            }

            $connection->close();
        }
    } catch (Exception $e) {
        echo 'Internal error: ' . $e;
    }
?>
