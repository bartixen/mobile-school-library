<?php
session_start();

require_once "../connect.php";
mysqli_report(MYSQLI_REPORT_STRICT);

try {
    $connection = new mysqli($host, $db_user, $db_password, $db_name);
    $connection->query("SET NAMES 'utf8'");
    
    if ($connection->connect_errno != 0) {
        throw new Exception(mysqli_connect_errno());
    } else {
        if (!isset($_SESSION['search'])) {
            if ($_SESSION['first'] == true) {
                $_SESSION['first'] = false;
            }
            
            $sql = "SELECT b.title, b.author, b.id, COUNT(b.title) AS occurrences 
                    FROM book AS b 
                    LEFT JOIN rentals AS r ON b.id = r.id_book 
                    WHERE r.id_book IS NULL 
                    GROUP BY b.title 
                    ORDER BY occurrences DESC 
                    LIMIT 6;";
            
            $result = $connection->query($sql);
            
            if ($result->num_rows > 0) {
                echo "<table><thead><tr><th>Tytuł</th><th>Autor</th><th>Ilość dostępnych</th></tr></thead><tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr onclick=window.location='https://wiaderna.edu.pl/search/rentals/?id=" . $row["id"] . "'><th>" . $row["title"] . "</th><th>" . $row["author"] . "</th><th>" . $row["occurrences"] . "</th></tr>";
                }
                echo "</tbody></table>";
            } else {
                echo '<div type="button" class="pop-up">Brak danych</div>';
            }
        } else {
            $secret_key = "6LeSWuQnAAAAAJZoVRJGhWvtRQoBueQokc1XDdvP";
            $check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$_SESSION['g-recaptcha-response']);
            $answer = json_decode($check);
            
            if ($answer->success==false) {
                $status=false;
                echo '<div type="button" class="pop-up">Wystąpił problem podczas autoryzacji (recaptcha)</div>';
                header('Location: index.php');
            } else {
                if ($_POST['csrf_token'] === $_SESSION['csrf_token']) {
                    echo '<div type="button" class="pop-up" onclick="submit()" id="del">Wystąpił problem podczas autoryzacji (CSRF)</div>';
                    header('Location: index.php');
                } else {
                    $data = mysqli_real_escape_string($connection, $_SESSION['data']);
                    
                    $sql = "SELECT b.title, b.author, b.id, COUNT(b.title) AS occurrences 
                            FROM book AS b 
                            LEFT JOIN rentals AS r ON b.id = r.id_book 
                            WHERE r.id_book IS NULL AND  " . $_SESSION['search'] . " LIKE ? 
                            GROUP BY b.title 
                            ORDER BY b.title ASC;";
                                        
                    $stmt = mysqli_prepare($connection, $sql);

                    $searchTerm = '%' . $data . '%';
                    mysqli_stmt_bind_param($stmt, "s", $searchTerm);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    
                    if ($result->num_rows > 0) {
                        echo "<table><thead><tr><th>Tytuł</th><th>Autor</th><th>Ilość dostępnych</th></tr></thead><tbody>";
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr onclick=window.location='https://wiaderna.edu.pl/search/rentals/?id=" . $row["id"] . "'><th>" . $row["title"] . "</th><th>" . $row["author"] . "</th><th>" . $row["occurrences"] . "</th></tr>";
                        }
                        echo "</tbody></table>";
                    } else {
                        echo '<div type="button" class="pop-up" onclick="submit()" id="del">Nie znaleziono pasujących wyników</div>';
                        
                        $sql = "SELECT b.title, b.author, b.id, COUNT(b.title) AS occurrences 
                                FROM book AS b 
                                LEFT JOIN rentals AS r ON b.id = r.id_book 
                                WHERE r.id_book IS NULL 
                                GROUP BY b.title 
                                ORDER BY `b`.`title` DESC 
                                LIMIT 6;";
                        
                        $result = $connection->query($sql);
                        
                        if ($result->num_rows > 0) {
                            echo "<table><thead><tr><th>Tytuł</th><th>Autor</th><th>Ilość dostępnych</th></tr></thead><tbody>";
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr onclick=window.location='https://wiaderna.edu.pl/search/rentals/?id=" . $row["id"] . "'><th>" . $row["title"] . "</th><th>" . $row["author"] . "</th><th>" . $row["occurrences"] . "</th></tr>";
                            }
                            echo "</tbody></table>";
                        } else {
                            echo '<div type="button" class="pop-up">Brak danych</div>';
                        }
                    }
                }
            }
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
