<?php
	require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);
    
    try {
        $connection = new mysqli($host, $db_user, $db_password, $db_name);

        $sql = "SELECT site FROM settings";
		$result = $connection->query($sql);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$site = $row["site"];
			}
		}

		if ($site == 0) {
			header('Location: off/');
			exit();
		}
        
        $connection->close();
    } catch (Exception $e) {
        echo 'Internal error: ' . $e;
    }
?>

<!DOCTYPE HTML>
<html lang="pl_PL">
	<head>
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-NHEH4NR1DR"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());

			gtag('config', 'G-NHEH4NR1DR');
		</script>
		<title>Biblioteka Wiaderna</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
        <link rel="shortcut icon" href="/images/icon.ico">
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
	</head>
	<body class="is-preload">
			<div id="wrapper">
					<header id="header">
						<div class="inner">
								<a href="#" class="logo">
									<span class="symbol"><img src="images/logo.png" alt="" /></span>
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
                            <li><a href="search/"><i class="fas fa-search"></i> Wyszukiwarka</a></li>
							<li><a href="authorization/"><i class="fas fa-sign-in-alt"></i> Panel administratora</a></li>
						</ul>
					</nav>
					<div id="main">
						<div class="inner">
							<header>
								<h1>Twój Kąt Edukacyjny <br>Wypożyczaj z Elektroniczną Biblioteką Szkolną!</h1>
								<p>Wypożyczaj mądrze i wygodnie z naszą elektroniczną szkolną biblioteką. Odkryj bogactwo wiedzy, które możesz mieć na wyciągnięcie ręki. Rozwijaj się w swoim tempie dzięki elastycznym opcjom wypożyczania.</p>
							</header>
                            <br><br><br>
                            <h2>Dostępne przykładowe ksiązki</h2>
                            <?php include 'list.php';?></br>
                            <a href="search/" class="button primary icon solid fa-search">Wyszukaj</a>
						</div>
					</div>
					<footer id="footer">
						<div class="inner">
							<section>
								<h2>Cyfrowa wypożyczalnia książek <br><a href="https://teb.pl/oddzialy/d/piotrkow-trybunalski/">TEB Edukacja w Piotrkowie Trybunalskim</a></h2>
							</section>
							<ul class="copyright">
								<li>&copy; 2023 WIADERNA.EDU.PL</li><li>Powered by <a href="https://bartixen.pl">BARTIXEN.PL - Bartosz Krasoń</a></li><li>Design by <a href="https://www.behance.net/oskarjazdz">Oskar Jażdż</a></li><li>Project supported by <a href="http://html5up.net">HTML5 UP</a></li><li><a href="cookies.html">Polityka Cookies</a></li><li><a href="privacy.html">Polityka Prywatności</a></li>
							</ul>
						</div>
					</footer>
			</div>
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>
	</body>
</html>