<?php
	session_start();
	if (!isset($_SESSION['authorization'])) {
		header('Location: ../../login/index.php');
		exit();
	}
?> 
<!DOCTYPE HTML>
<html lang="pl_PL">
	<head>
		<title>Biblioteka Wiaderna - Dodaj książkę</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../../../assets/css/main.css" />
        <link rel="shortcut icon" href="../../../images/icon.ico">
	</head>
	<body class="is-preload">
			<div id="wrapper">
					<header id="header">
						<div class="inner">
                            <a href="../" class="logo">
									<span class="symbol"><img src="../../../images/logo.png" alt="" /></span>
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
                            <li><a href="../../../"><i class="fas fa-home"></i> Strona główna</a></li>
							<li><a href="../../notifications/">Powiadomienia</a></li>
                            <li><a href="../../book/">Książki</a></li>
                            <li><a href="../../reader/">Czytelnicy</a></li>
                            <li><a href="../../rentals/">Wypożyczenia</a></li>
                            <li><a href="../../statistic/">Statystyki</a></li>
                            <li><a href="../../logs/">Logi</a></li>
							<li><a href="../../settings/">Ustawienia</a></li>
							<li><a href="../../login/logout.php"><i class="fas fa-sign-out-alt"></i> Wyloguj</a></li>
						</ul>
					</nav>
					<div id="main">
						<div class="inner">
							<header>
								<h1>Dodaj nową książkę</h1>
                                <p>Możesz tutaj dodać nową książke do naszej biblioteki.</p>
							</header>
							<div id="authorization">
                                <form id='demo-form' method="post" action="work.php">
									<?php
										if (isset($_SESSION['info'])) {
											echo '<div type="button" class="pop-up_login" onclick="submit()" id="del">'.$_SESSION['info'].'</div>';
											unset($_SESSION['info']);
										}
									?>
									<h2>Tytuł:<input type="text" name="title" id="title" value="" /></h2>
									<h2>Autor:<input type="text" name="author" id="author" value="" /></h2>
									<h2>Numer ewidencji:<input type="text" name="record_number" id="record_number" value="" /></h2>
									<button class="button primary icon solid fa-plus" type="submit">Dodaj nową książkę</button><br><br>
                                </form>
                            </div>
                        </div>
                    </div>
					<footer id="footer">
						<div class="inner">
							<section>
								<h2>Cyfrowa wypożyczalnia książek <br><a href="https://teb.pl/oddzialy/d/piotrkow-trybunalski/">TEB Edukacja w Piotrkowie Trybunalskim</a></h2>
							</section>
							<ul class="copyright">
								<li>&copy; 2023 WIADERNA.EDU.PL</li><li>Powered by <a href="https://bartixen.pl">BARTIXEN.PL - Bartosz Krasoń</a></li><li>Design by <a href="https://www.behance.net/oskarjazdz">Oskar Jażdż</a></li><li>Project supported by <a href="http://html5up.net">HTML5 UP</a></li><li><a href="../../../cookies.html">Polityka Cookies</a></li><li><a href="../../../privacy.html">Polityka Prywatności</a></li>
							</ul>
						</div>
					</footer>
			</div>
			<script src="../../../assets/js/jquery.min.js"></script>
			<script src="../../../assets/js/browser.min.js"></script>
			<script src="../../../assets/js/breakpoints.min.js"></script>
			<script src="../../../assets/js/util.js"></script>
			<script src="../../../assets/js/main.js"></script>
	</body>
</html>