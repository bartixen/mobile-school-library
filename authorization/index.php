<?php
	session_start();
	if (!isset($_SESSION['authorization'])) {
		header('Location: login/');
		exit();
	}
?>
<!DOCTYPE HTML>
<html lang="pl_PL">
	<head>
		<title>Biblioteka Wiaderna - Panel administratora</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../assets/css/main.css" />
        <link rel="shortcut icon" href="../images/icon.ico">
	</head>
	<body class="is-preload">
			<div id="wrapper">
					<header id="header">
						<div class="inner">
                            <a href="../" class="logo">
									<span class="symbol"><img src="../images/logo.png" alt="" /></span>
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
                            <li><a href="../"><i class="fas fa-home"></i> Strona główna</a></li>
                            <li><a href="notifications/">Powiadomienia</a></li>
                            <li><a href="book/">Książki</a></li>
                            <li><a href="reader/">Czytelnicy</a></li>
                            <li><a href="rentals/">Wypożyczenia</a></li>
                            <li><a href="statistic/">Statystyki</a></li>
                            <li><a href="logs/">Logi</a></li>
                            <li><a href="settings/">Ustawienia</a></li>
							<li><a href="login/logout.php"><i class="fas fa-sign-out-alt"></i> Wyloguj</a></li>
						</ul>
					</nav>
					<div id="main">
						<div class="inner">
							<header>
								<h1>Panel administratora</h1>
                                <p>Wital w panelu administratora, od teraz masz pełną kontrolę nad zarządzaniem treściami.</p>
							</header>
                            <section class="tiles">
                                <article class="style1">
									<a href="notifications/">
                                        <span class="image">
                                            <img src="../images/notifications.png" alt="">
                                        </span>
										<h2>Powiadomienia</h2>
										<div class="content">
                                        <p>Znajdziesz tutaj wszystkie najnowsze powiadomienia dotyczące rezerwacji książek.</p>
										</div>
									</a>
								</article>
                                <article class="style1">
                                    <a href="reader/">
                                        <span class="image">
                                            <img src="../images/reader.png" alt="">
                                        </span>
                                        <h2>Czytelnicy</h2>
                                        <div class="content">
                                            <p>Zarządzaj użytkownikami korzystającymi z wypożyczalni.</p>
                                        </div>
                                    </a>
                                </article>
                                <article class="style1">
                                    <a href="rentals/">
                                        <span class="image">
                                            <img src="../images/rentals.png" alt="">
                                        </span>
                                        <h2>Wypożyczenia</h2>
                                        <div class="content">
                                            <p>Zarządzaj wypożyczonymi książkami w łatwy sposób, kiedy tylko chcesz.</p>
                                        </div>
                                    </a>
                                </article>
                                <article class="style1">
                                    <a href="book/">
                                        <span class="image">
                                            <img src="../images/book.png" alt="">
                                        </span>
                                        <h2>Książki</h2>
                                        <div class="content">
                                            <p>Pełna lista i edycja dostępnych książek.</p>
                                        </div>
                                    </a>
                                </article>
                                <article class="style1">
                                    <a href="statistic/">
                                        <span class="image">
                                            <img src="../images/statistic.png" alt="">
                                        </span>
                                        <h2>Statystyki</h2>
                                        <div class="content">
                                            <p>Statystyki zawsze się przydają i tutaj znajdziesz wszystkie przydatne informacje.</p>
                                        </div>
                                    </a>
                                </article>
                                <article class="style1">
                                    <a href="logs/">
                                        <span class="image">
                                            <img src="../images/logs.png" alt="">
                                        </span>
                                        <h2>Logi</h2>
                                        <div class="content">
                                            <p>Znajdziesz tutaj wszystkie logi związane z panelem administratora.</p>
                                        </div>
                                    </a>
                                </article>
                            </section>
                        </div>
                    </div>
					<footer id="footer">
						<div class="inner">
							<section>
								<h2>Cyfrowa wypożyczalnia książek <br><a href="https://teb.pl/oddzialy/d/piotrkow-trybunalski/">TEB Edukacja w Piotrkowie Trybunalskim</a></h2>
							</section>
							<ul class="copyright">
								<li>&copy; 2023 WIADERNA.EDU.PL</li><li>Powered by <a href="https://bartixen.pl">BARTIXEN.PL - Bartosz Krasoń</a></li><li>Design by <a href="https://www.behance.net/oskarjazdz">Oskar Jażdż</a></li><li>Project supported by <a href="http://html5up.net">HTML5 UP</a></li><li>Version: 1.1.2</li>
							</ul>
						</div>
					</footer>
			</div>
			<script src="../assets/js/jquery.min.js"></script>
			<script src="../assets/js/browser.min.js"></script>
			<script src="../assets/js/breakpoints.min.js"></script>
			<script src="../assets/js/util.js"></script>
			<script src="../assets/js/main.js"></script>
	</body>
</html>