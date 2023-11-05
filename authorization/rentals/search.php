<?php
    session_start();

    require_once "../../connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $connection = new mysqli($host, $db_user, $db_password, $db_name);
        $connection->query("SET NAMES 'utf8'");
        
        if ($connection->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        } else {
            $count = ($_SESSION['full'] == true) ? '' : 'LIMIT 4';

            if (!isset($_SESSION['search'])) {
                if (isset($_SESSION['info'])) {
                    echo '<div type="button" class="pop-up_login" onclick="submit()" id="del">' . $_SESSION['info'] . '</div>';
                    unset($_SESSION['info']);
                }
                $sql = "SELECT rentals.id, CONCAT(reader.name, ' ', reader.last_name, ' [', reader.class, ']') AS reader_name, CONCAT(book.title, ' (', book.author, ') [', book.record_number, ']') AS book_details, rentals.date FROM rentals JOIN reader ON rentals.id_reader = reader.id JOIN book ON rentals.id_book = book.id ORDER BY rentals.id $count";
            } else {
                $searchTerm = '%' . $_SESSION['data'] . '%';
                $searchCondition = "(book.id IN (SELECT id FROM book WHERE title LIKE '$searchTerm')) OR (reader.id IN (SELECT id FROM reader WHERE name = SUBSTRING_INDEX('" . $_SESSION['data'] . "', ' ', 1) AND last_name = SUBSTRING_INDEX('" . $_SESSION['data'] . "', ' ', -1)))";
                $sql = "SELECT rentals.id, CONCAT(reader.name, ' ', reader.last_name, ' [', reader.class, ']') AS reader_name, CONCAT(book.title, ' (', book.author, ') [', book.record_number, ']') AS book_details, rentals.date FROM rentals JOIN reader ON rentals.id_reader = reader.id JOIN book ON rentals.id_book = book.id WHERE $searchCondition ORDER BY rentals.id";
            }

            $result = $connection->query($sql);

            if ($result->num_rows > 0) {
                echo '<div style="overflow-x: auto;"><table><thead><tr><th>ID</th><th>Imię i Nazwisko</th><th>Książka</th><th>Data</th><th></th></tr></thead><tbody>';
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><th>" . $row["id"] . "</th><th>" . $row["reader_name"] . "</th><th>" . $row["book_details"] . "</th><th>" . $row["date"] . "</th><th><a href='finish.php?id=" . $row["id"] . "'><button type='button' class='button primary icon solid fa-times'>Zakończ</button></a></th></tr>";
                }
                echo "</tbody></table></div>";
                $_SESSION['search_ok'] = true;
            } else {
                echo '<div type="button" class="pop-up" onclick="submit()" id="del">Nie znaleziono pasujących wyników</div>';
                $sql = "SELECT rentals.id, CONCAT(reader.name, ' ', reader.last_name) AS reader_name, CONCAT(book.title, ' (', book.author, ') [', book.record_number, ']') AS book_details, rentals.date FROM rentals JOIN reader ON rentals.id_reader = reader.id JOIN book ON rentals.id_book = book.id ORDER BY rentals.id $count";
                $result = $connection->query($sql);

                if ($result->num_rows > 0) {
                    echo "<div style='overflow-x: auto;'><table><thead><tr><th>ID</th><th>Imię i Nazwisko</th><th>Książka</th><th>Data</th><th></th></tr></thead><tbody>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><th>" . $row["id"] . "</th><th>" . $row["reader_name"] . "</th><th>" . $row["book_details"] . "</th><th>" . $row["date"] . "</th><th><a href='finish.php?id=" . $row["id"] . "'><button type='button' class='button primary icon solid fa-times'>Zakończ</button></a></th></tr>";
                    }
                    echo "</tbody></table></div>";
                } else {
                    echo '<div type="button" class="pop-up">Brak danych</div>';
                }
                $_SESSION['search_ok'] = false;
            }

            unset($_SESSION['search']);
            unset($_SESSION['data']);
            $connection->close();
        }
    } catch (Exception $e) {
        echo 'Internal error: ' . $e;
    }
?>
