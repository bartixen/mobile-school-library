<?php
    session_start();

    require_once "../../../connect.php";
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

                $sql = "SELECT `id`, `name`, `last_name`, `class`, `phone_number`, `email` FROM reader ORDER BY `name` ASC $count";
            } else {
                $searchTerm = '%' . $_SESSION['data'] . '%';
                $sql = "SELECT `id`, `name`, `last_name`, `class`, `phone_number`, `email` FROM reader WHERE {$_SESSION['search']} LIKE '$searchTerm' ORDER BY `name` ASC";
            }

            $result = $connection->query($sql);

            if ($result->num_rows > 0) {
                echo '<div style="overflow-x: auto;"><table><thead><tr><th>ID</th><th>Imię</th><th>Nazwisko</th><th>Klasa</th><th>Numer telefonu</th><th>Adres email</th><th></th></tr></thead><tbody>';
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><th>" . $row["id"] . "</th><th>" . $row["name"] . "</th><th>" . $row["last_name"] . "</th><th>" . $row["class"] . "</th><th>" . $row["phone_number"] . "</th><th>" . $row["email"] . "</th><th><a href='select/?id=" . $row["id"] . "'><button type='button' class='button icon solid fa-sign-in-alt'>Wybierz</button></a></th></tr>";
                }
                echo "</tbody></table></div>";
                $_SESSION['search_ok'] = true;
            } else {
                echo '<div type="button" class="pop-up" onclick="submit()" id="del">Nie znaleziono pasujących wyników</div>';
                $sql = "SELECT `id`, `name`, `last_name`, `class`, `phone_number`, `email` FROM reader ORDER BY `name` ASC $count";
                $result = $connection->query($sql);

                if ($result->num_rows > 0) {
                    echo "<div style='overflow-x: auto;'><table><thead><tr><th>ID</th><th>Imię</th><th>Nazwisko</th><th>Klasa</th><th>Numer telefonu</th><th>Adres email</th><th></th></tr></thead><tbody>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><th>" . $row["id"] . "</th><th>" . $row["name"] . "</th><th>" . $row["last_name"] . "</th><th>" . $row["class"] . "</th><th>" . $row["phone_number"] . "</th><th>" . $row["email"] . "</th><th><a href='select/?id=" . $row["id"] . "'><button type='button' class='button icon solid fa-sign-in-alt'>Wybierz</button></a></th></tr>";
                    }
                    echo "</tbody></table></div>";
                } else {
                    echo '<div type="button" class="pop-up">Brak danych</div>';
                }
                $_SESSION['search_ok'] = false;
            }

            unset($_SESSION['search']);
            unset($_SESSION['search1']);
            unset($_SESSION['data']);
            $connection->close();
        }
    } catch (Exception $e) {
        echo 'Internal error: ' . $e;
    }
?>
