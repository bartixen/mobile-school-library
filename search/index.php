<?php
	session_start();
	$csrf_token = bin2hex(random_bytes(32));
	$_SESSION['csrf_token'] = $csrf_token;
?>
<?php
	require_once "../connect.php";
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
			header('Location: ../off/');
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
		<title>Biblioteka Wiaderna - Wyszukaj</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../assets/css/main.css" />
        <link rel="shortcut icon" href="../images/icon.ico">
		<noscript><link rel="stylesheet" href="../assets/css/noscript.css" /></noscript>
        <script src="https://www.google.com/recaptcha/api.js"></script>
        <script>
            function onSubmit(token) {
                document.getElementById("demo-form").submit();
            }
        </script>
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
							<li><a href="../authorization/"><i class="fas fa-sign-in-alt"></i> Panel administratora</a></li>
						</ul>
					</nav>
					<div id="main">
						<div class="inner">
							<header>
								<h1>Szybko, Łatwo, Skutecznie <br>Wyszukaj Książki w Naszej Bibliotece!</h1>
                                <p>Oferujemy ogromny wybór książek na każdy temat, które możesz wypożyczyć i cieszyć się nową wiedzą.</p>
							</header>
                            <br><br><br>
							<div id="search">
								<form id='demo-form' method="post" action="work.php">
									<h2>Wybierz wyszukiwanie</h2>
									<div class="selection">
										<input type="radio" id="title" name="search" value="title" checked><label for="title">Wyszukaj po tytule</label><input type="radio" id="author" name="search" value="author"><label for="author">Wyszukaj po autorze</label>
									</div>
									<textarea placeholder="Wpisz frazę, którą chcesz wyszukać..." name="data" id="data" cols="20" rows="2"></textarea><br>
									<button class="g-recaptcha button primary icon solid fa-search" data-sitekey="6LeSWuQnAAAAAARmqW6u2_0SYuvCx3YOf1KJxQW4" data-callback='onSubmit' data-action='submit'>Wyszukaj</button>
								</form>
								<?php include 'search.php';?></br>
								<div type="button " class="pop-up">Aby zarezerwować daną książkę, kliknij w nią.</div>
							</div>
						</div>
					</div>
					<footer id="footer">
						<div class="inner">
							<section>
								<h2>Cyfrowa wypożyczalnia książek <br><a href="https://teb.pl/oddzialy/d/piotrkow-trybunalski/">TEB Edukacja w Piotrkowie Trybunalskim</a></h2>
							</section>
							<ul class="copyright">
								<li>&copy; 2023 WIADERNA.EDU.PL</li><li>Powered by <a href="https://bartixen.pl">BARTIXEN.PL - Bartosz Krasoń</a></li><li>Design by <a href="https://www.behance.net/oskarjazdz">Oskar Jażdż</a></li><li>Project supported by <a href="http://html5up.net">HTML5 UP</a></li><li><a href="../cookies.html">Polityka Cookies</a></li><li><a href="../privacy.html">Polityka Prywatności</a></li>
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