<?php
	session_start();
	if (!isset($_SESSION['authorization'])) {
		header('Location: ../../../../login/index.php');
		exit();
	}
?> 
<?php
    require_once "../../../../../connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);
    $id = $_SESSION['user_id'];
    if ($id!=0) {
        try {
            $connection = new mysqli($host, $db_user, $db_password, $db_name);
            $connection->query("SET NAMES 'utf8'");
            if ($connection->connect_errno!=0) {
                throw new Exception(mysqli_connect_errno());
            } else { 
                $sql = "SELECT * FROM reader WHERE id='$id'";
                $result = $connection->query($sql);
                $row = $result->fetch_assoc();
				$_SESSION['reader_id'] = $row['id'];
                $_SESSION['reader_name'] = $row['name'];
                $_SESSION['last_name'] = $row['last_name'];
                $connection->close();
            }
        } catch(Exception $e) {
            echo 'Internal error: '.$e;
        }
    } else {
        header('Location: ../../index.php');
        exit();
    }
?> 
<!DOCTYPE HTML>
<html lang="pl_PL">
	<head>
		<title>Biblioteka Wiaderna - Wypożyczenia</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../../../../../assets/css/main.css" />
        <link rel="shortcut icon" href="../../../../../images/icon.ico">
	</head>
	<body class="is-preload">
			<div id="wrapper">
					<header id="header">
						<div class="inner">
                            <a href="../" class="logo">
									<span class="symbol"><img src="../../../../../images/logo.png" alt="" /></span>
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
                            <li><a href="../../../../../"><i class="fas fa-home"></i> Strona główna</a></li>
							<li><a href="../../../../notifications/">Powiadomienia</a></li>
                            <li><a href="../../../../book/">Książki</a></li>
                            <li><a href="../../../../reader/">Czytelnicy</a></li>
                            <li><a href="../../../../rentals/">Wypożyczenia</a></li>
                            <li><a href="../../../../statistic/">Statystyki</a></li>
                            <li><a href="../../../../logs/">Logi</a></li>
							<li><a href="../../../../settings/">Ustawienia</a></li>
							<li><a href="../../../../login/logout.php"><i class="fas fa-sign-out-alt"></i> Wyloguj</a></li>
						</ul>
					</nav>
					<div id="main">
						<div class="inner">
							<header>
								<h1>Znajdź książkę</h1>
                                <p>Wyszukaj teraz dokładnie książkę, którą wypożyczasz.</p>
							</header>
							<h2>Wybrany czytelnik: <strong style="color: #bfd01d;"><?php echo ''.$_SESSION['reader_name'].'' ?> <?php echo ''.$_SESSION['last_name'].'' ?> (ID: <?php echo ''.$_SESSION['reader_id'].'' ?>)</strong></h2>
							<h2>Książka, którą wybrał: <strong style="color: #bfd01d;"><?php echo ''.$_SESSION['title'].'' ?> <?php echo ''.$_SESSION['Autor'].'' ?></strong></h2>
                            <section id="search">
								<form id='demo-form' method="post" action="work.php">
                                    <h2>Wybierz wyszukiwanie</h2>
                                    <div class="selection">
                                        <input type="radio" id="title" name="search" value="title" checked><label for="title">Tytuł</label><input type="radio" id="author" name="search" value="author"><label for="author">Autor</label><input type="radio" id="record_number" name="search" value="record_number"><label for="record_number">Numer ewidencji</label>
                                    </div>
                                    <textarea placeholder="Wpisz frazę..." name="data" id="data" cols="20" rows="2"><?php echo ''.$_SESSION['title'].'' ?></textarea><br>
                                    <button class="button primary icon solid fa-search" data-action='submit'>Wyszukaj</button>
                                </form>
                                <?php include 'search.php';?></br>
                            </section><br>
                        </div>
                    </div>
					<footer id="footer">
						<div class="inner">
							<section>
								<h2>Cyfrowa wypożyczalnia książek <br><a href="https://teb.pl/oddzialy/d/piotrkow-trybunalski/">TEB Edukacja w Piotrkowie Trybunalskim</a></h2>
							</section>
							<ul class="copyright">
								<li>&copy; 2023 WIADERNA.EDU.PL</li><li>Powered by <a href="https://bartixen.pl">BARTIXEN.PL - Bartosz Krasoń</a></li><li>Design by <a href="https://www.behance.net/oskarjazdz">Oskar Jażdż</a></li><li>Project supported by <a href="http://html5up.net">HTML5 UP</a></li><li><a href="../../../../../cookies.html">Polityka Cookies</a></li><li><a href="../../../../../privacy.html">Polityka Prywatności</a></li>
							</ul>
						</div>
					</footer>
			</div>
			<script src="../../../../../assets/js/jquery.min.js"></script>
			<script src="../../../../../assets/js/browser.min.js"></script>
			<script src="../../../../../assets/js/breakpoints.min.js"></script>
			<script src="../../../../../assets/js/util.js"></script>
			<script src="../../../../../assets/js/main.js"></script>
	</body>
</html>