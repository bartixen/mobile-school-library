<?php
	session_start();
	if (!isset($_SESSION['authorization'])) {
		header('Location: ../../../../../login/index.php');
		exit();
	}
?> 
<?php
    require_once "../../../../../../connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);
    $id = $_GET['id'];
    if ($id!=0) {
        try {
            $connection = new mysqli($host, $db_user, $db_password, $db_name);
            $connection->query("SET NAMES 'utf8'");
            if ($connection->connect_errno!=0) {
                throw new Exception(mysqli_connect_errno());
            } else { 
                $sql = "SELECT * FROM book WHERE id='$id'";
                $result = $connection->query($sql);
                $row = $result->fetch_assoc();
				$_SESSION['book_id'] = $row['id'];
				$_SESSION['title'] = $row['title'];
                $_SESSION['author'] = $row['author'];
                $_SESSION['record_number'] = $row['record_number'];

				$id = $_SESSION['user_id'];
				$sql = "SELECT * FROM reader WHERE id='$id'";
                $result = $connection->query($sql);
                $row = $result->fetch_assoc();
                $_SESSION['reader_name'] = $row['name'];
                $_SESSION['last_name'] = $row['last_name'];
				$_SESSION['class'] = $row['class'];
                $connection->close();
            }
        } catch(Exception $e) {
            echo 'Internal error: '.$e;
        }
    } else {
        header('Location: ../index.php');
        exit();
    }
?> 
<!DOCTYPE HTML>
<html lang="pl_PL">
	<head>
		<title>Biblioteka Wiaderna - Wypożyczenia</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../../../../../../assets/css/main.css" />
        <link rel="shortcut icon" href="../../../../../../images/icon.ico">
	</head>
	<body class="is-preload">
			<div id="wrapper">
					<header id="header">
						<div class="inner">
                            <a href="../" class="logo">
									<span class="symbol"><img src="../../../../../../images/logo.png" alt="" /></span>
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
                            <li><a href="../../../../../../"><i class="fas fa-home"></i> Strona główna</a></li>
							<li><a href="../../../../../notifications/">Powiadomienia</a></li>
                            <li><a href="../../../../../book/">Książki</a></li>
                            <li><a href="../../../../../reader/">Czytelnicy</a></li>
                            <li><a href="../../../../../rentals/">Wypożyczenia</a></li>
                            <li><a href="../../../../../statistic/">Statystyki</a></li>
                            <li><a href="../../../../../logs/">Logi</a></li>
							<li><a href="../../../../../settings/">Ustawienia</a></li>
							<li><a href="../../../../../login/logout.php"><i class="fas fa-sign-out-alt"></i> Wyloguj</a></li>
						</ul>
					</nav>
					<div id="main">
						<div class="inner">
							<header>
								<h1>Podsumowanie wypożyczalnia</h1>
								<section id="search">
									<h2>Dane czytelnika:</h2>
									<p>ID: <strong><?php echo ''.$_SESSION['reader_id'].'' ?></strong></p>
									<p>Imię: <strong><?php echo ''.$_SESSION['reader_name'].'' ?></strong></p>
									<p>Nazwisko: <strong><?php echo ''.$_SESSION['last_name'].'' ?></strong></p>
									<p>Klasa: <strong><?php echo ''.$_SESSION['class'].'' ?></strong></p>

									<h2>Dane książki:</h2>
									<p>ID: <strong><?php echo ''.$_SESSION['book_id'].'' ?></strong></p>
									<p>Tutuł: <strong><?php echo ''.$_SESSION['title'].'' ?></strong></p>
									<p>Autor: <strong><?php echo ''.$_SESSION['author'].'' ?></strong></p>
									<p>Numer ewidencji: <strong><?php echo ''.$_SESSION['record_number'].'' ?></strong></p>

									<ul class="actions">
										<li><a href="add.php" class="button primary icon solid fa-save">Dodaj wypożyczalnie</a></li>
										<li><a href="del.php" class="button icon solid fa-trash">Anuluj</a></li>
									</ul>
								</section>
							</header>
                        </div>
                    </div>
					<footer id="footer">
						<div class="inner">
							<section>
								<h2>Cyfrowa wypożyczalnia książek <br><a href="https://teb.pl/oddzialy/d/piotrkow-trybunalski/">TEB Edukacja w Piotrkowie Trybunalskim</a></h2>
							</section>
							<ul class="copyright">
								<li>&copy; 2023 WIADERNA.EDU.PL</li><li>Powered by <a href="https://bartixen.pl">BARTIXEN.PL - Bartosz Krasoń</a></li><li>Design by <a href="https://www.behance.net/oskarjazdz">Oskar Jażdż</a></li><li>Project supported by <a href="http://html5up.net">HTML5 UP</a></li><li><a href="../../../../../../cookies.html">Polityka Cookies</a></li><li><a href="../../../../../../privacy.html">Polityka Prywatności</a></li>
							</ul>
						</div>
					</footer>
			</div>
			<script src="../../../../../../assets/js/jquery.min.js"></script>
			<script src="../../../../../../assets/js/browser.min.js"></script>
			<script src="../../../../../../assets/js/breakpoints.min.js"></script>
			<script src="../../../../../../assets/js/util.js"></script>
			<script src="../../../../../../assets/js/main.js"></script>
	</body>
</html>