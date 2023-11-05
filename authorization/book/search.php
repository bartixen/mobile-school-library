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
            $count = ($_SESSION['full'] == true) ? '' : 'LIMIT 5';

            if (!isset($_SESSION['search'])) {
                if (isset($_SESSION['info'])) {
                    echo '<div type="button" class="pop-up_login" onclick="submit()" id="del">' . $_SESSION['info'] . '</div>';
                    unset($_SESSION['info']);
                }
                
                $sql = "SELECT b.title, b.author, b.id, IF(r.id_book IS NULL, 'Dostępna', 'Wypożyczona') AS status, record_number FROM book AS b LEFT JOIN rentals AS r ON b.id = r.id_book ORDER BY b.title ASC $count";
            } else {
                $searchTerm = '%' . $_SESSION['data'] . '%';
                $searchCondition = "(b.id IN (SELECT id FROM book WHERE title LIKE '$searchTerm')) OR (b.author LIKE '$searchTerm') OR (b.record_number = '" . $_SESSION['data'] . "')";
                $sql = "SELECT b.title, b.author, b.id, IF(r.id_book IS NULL, 'Dostępna', 'Wypożyczona') AS status, record_number FROM book AS b LEFT JOIN rentals AS r ON b.id = r.id_book WHERE $searchCondition ORDER BY b.title ASC";
            }

            $result = $connection->query($sql);

            if ($result->num_rows > 0) {
                echo '<div style="overflow-x: auto;"><table><thead><tr><th>ID</th><th>Tytuł</th><th>Autor</th><th>Numer ewidencji</th><th>Status</th><th></th></tr></thead><tbody>';
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><th>" . $row["id"] . "</th><th>" . $row["title"] . "</th><th>" . $row["author"] . "</th><th>" . $row["record_number"] . "</th><th>" . $row["status"] . "</th><th><a href='edit/?id=" . $row["id"] . "'><button type='button' class='button icon fa-edit'>Edytuj</button></a></th></tr>";
                }
                echo "</tbody></table></div>";
            } else {
                echo '<div type="button" class="pop-up" onclick="submit()" id="del">Nie znaleziono pasujących wyników</div>';
                $sql = "SELECT b.title, b.author, b.id, IF(r.id_book IS NULL, 'Dostępna', 'Wypożyczona') AS status, record_number FROM book AS b LEFT JOIN rentals AS r ON b.id = r.id_book ORDER BY b.title ASC LIMIT 6;";
                $result = $connection->query($sql);

                if ($result->num_rows > 0) {
                    echo "<div style='overflow-x: auto;'><table><thead><tr><th>ID</th><th>Tytuł</th><th>Autor</th><th>Numer ewidencji</th><th>Status</th><th></th></tr></thead><tbody>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><th>" . $row["id"] . "</th><th>" . $row["title"] . "</th><th>" . $row["author"] . "</th><th>" . $row["record_number"] . "</th><th>" . $row["status"] . "</th><th><a href='edit/?id=" . $row["id"] . "'><button type='button' class='button icon fa-edit'>Edytuj</button></a></th></tr>";
                    }
                    echo "</tbody></table></div>";
                } else {
                    echo '<div type="button" class="pop-up">Brak danych</div>';
                }
            }

            unset($_SESSION['search']);
            unset($_SESSION['data']);
            $connection->close();
        }
    } catch (Exception $e) {
        echo 'Internal error: ' . $e;
    }
?>
