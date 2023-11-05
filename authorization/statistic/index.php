<?php
	session_start();
	if (!isset($_SESSION['authorization'])) {
		header('Location: ../login/index.php');
		exit();
	}
?> 
<?php
    require_once "../../connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $connection = new mysqli($host, $db_user, $db_password, $db_name);
        $connection->query("SET NAMES 'utf8'");
        
        if ($connection->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        } else {
            $result1 = $connection->query("SELECT COUNT(*) AS occurrences FROM `authentication`")->fetch_assoc()["occurrences"];
            $result2 = $connection->query("SELECT COUNT(*) AS occurrences FROM `reader`")->fetch_assoc()["occurrences"];
            $result3 = $connection->query("SELECT COUNT(*) AS occurrences FROM `rentals`")->fetch_assoc()["occurrences"];
            $result4 = $connection->query("SELECT COUNT(*) AS occurrences FROM book WHERE id NOT IN (SELECT id_book FROM rentals)")->fetch_assoc()["occurrences"];
            $result5 = $connection->query("SELECT COUNT(*) AS occurrences FROM `book`")->fetch_assoc()["occurrences"];
            
            $connection->close();
        }
    } catch (Exception $e) {
        echo 'Internal error: ' . $e;
    }
?>

<!DOCTYPE HTML>
<html lang="pl_PL">
	<head>
		<title>Biblioteka Wiaderna - Statystyki</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../../assets/css/main.css" />
        <link rel="shortcut icon" href="../../images/icon.ico">
	</head>
	<body class="is-preload">
			<div id="wrapper">
					<header id="header">
						<div class="inner">
                            <a href="../" class="logo">
									<span class="symbol"><img src="../../images/logo.png" alt="" /></span>
                                    <span class="title">Teb Edukacja<br> w Piotrkowie Tryb.</span>
								</a>
								<nav>
									<ul>
										<li><a href="#menu">Menu</a></li>
									</ul>
								</nav>
						</div>
					</header>
					<nav id="menu">
						<h2>Menu</h2>
						<ul>
                            <li><a href="../../"><i class="fas fa-home"></i> Strona główna</a></li>
                            <li><a href="../notifications/">Powiadomienia</a></li>
                            <li><a href="../book/">Książki</a></li>
                            <li><a href="../reader/">Czytelnicy</a></li>
                            <li><a href="../rentals/">Wypożyczenia</a></li>
                            <li><a href="../statistic/">Statystyki</a></li>
                            <li><a href="../logs/">Logi</a></li>
                            <li><a href="../settings/">Ustawienia</a></li>
							<li><a href="../login/logout.php"><i class="fas fa-sign-out-alt"></i> Wyloguj</a></li>
						</ul>
					</nav>
					<div id="main">
						<div class="inner">
							<header>
								<h1>Statystyki biblioteki</h1>
                                <p>Tutaj znajdziesz wszystkie najważniejsze statystyki naszej biblioteki.</p>
							</header>
                            <div style="overflow-x: auto;">
                                <table><thead>
                                <tr>
                                    <th>Typ</th>
                                    <th>Dane</th>
                                    <th></th>
                                </tr></thead><tbody>
                                <tr>
                                    <th>Ilość użytkowników z uprawnieniami do autoryzacji</th>
                                    <th><strong><?php echo $result1; ?></strong></th>
                                    <th><button type="button" style="visibility: hidden;"></button></th>
                                </tr>
                                <tr>
                                    <th>Ilość czytelników</th>
                                    <th><strong><?php echo $result2; ?></strong></th>
                                    <th><a href="../reader/"><button type="button" class="button" style="float: center">Zobacz więcej</button></a></th>
                                </tr>
                                <tr>
                                    <th>Ilość wypożyczonych książek</th>
                                    <th><strong><?php echo $result3; ?></strong></th>
                                    <th><a href="../rentals/"><button type="button" class="button" style="float: center">Zobacz więcej</button></a></th>
                                </tr>
                                <tr>
                                    <th>Ilość dostępnych książek</th>
                                    <th><strong><?php echo $result4; ?></strong></th>
                                    <th><a href="../book/"><button type="button" class="button" style="float: center">Zobacz więcej</button></a></th>
                                </tr>
                                <tr>
                                    <th>Ilość zarejestrowanych książek</th>
                                    <th><strong><?php echo $result5; ?></strong></th>
                                    <th><a href="../book/"><button type="button" class="button" style="float: center">Zobacz więcej</button></a></th>
                                </tr>
                            </tbody></table></div><br>
                        <ul class="actions">
                        <li><a href="download.php" class="button primary icon solid fa-download">Pobierz pełną listę</a></li>
                        <ul>
                        </div>
                    </div>
					<footer id="footer">
						<div class="inner">
							<section>
								<h2>Cyfrowa wypożyczalnia książek <br><a href="https://teb.pl/oddzialy/d/piotrkow-trybunalski/">TEB Edukacja w Piotrkowie Trybunalskim</a></h2>
							</section>
							<ul class="copyright">
								<li>&copy; 2023 WIADERNA.EDU.PL</li><li>Powered by <a href="https://bartixen.pl">BARTIXEN.PL - Bartosz Krasoń</a></li><li>Design by <a href="https://www.behance.net/oskarjazdz">Oskar Jażdż</a></li><li>Project supported by <a href="http://html5up.net">HTML5 UP</a></li><li><a href="../../cookies.html">Polityka Cookies</a></li><li><a href="../../privacy.html">Polityka Prywatności</a></li>
							</ul>
						</div>
					</footer>
			</div>
			<script src="../../assets/js/jquery.min.js"></script>
			<script src="../../assets/js/browser.min.js"></script>
			<script src="../../assets/js/breakpoints.min.js"></script>
			<script src="../../assets/js/util.js"></script>
			<script src="../../assets/js/main.js"></script>
	</body>
</html>