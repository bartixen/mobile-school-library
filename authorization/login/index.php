<?php
	session_start();
	if ((isset($_SESSION['authorization']))) {
		header('Location: ../index.php');
		exit();
	}
	$csrf_token = bin2hex(random_bytes(32));
	$_SESSION['csrf_token'] = $csrf_token;
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
		<title>Biblioteka Wiaderna - Logowanie</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../../assets/css/main.css" />
        <link rel="shortcut icon" href="../../images/icon.ico">
		<noscript><link rel="stylesheet" href="../../assets/css/noscript.css" /></noscript>
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
                            <a href="../../" class="logo">
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
							<li><a href="../../search/"><i class="fas fa-search"></i> Wyszukiwarka</a></li>
						</ul>
					</nav>
					<div id="main">
						<div class="inner">
							<header>
								<h1>Zaloguj się do Panelu Administratora</h1>
                                <p>Po zalogowaniu do panelu naszej strony masz pełną kontrolę nad zarządzaniem treściami. </p>
							</header>
                            <div id="authorization">
                                <form id='demo-form' method="post" action="authorization.php">
                                <?php
                                    if (isset($_SESSION['info'])) {
                                        echo '<div type="button" class="pop-up_login" onclick="submit()" id="del">'.$_SESSION['info'].'</div>';
                                        unset($_SESSION['info']);
                                    }
                                ?>
                                    <h2>Login:<input type="text" name="login" id="login" value="" /></h2>
                                    <h2>Hasło:<input type="password" name="password" id="password" value="" /></h2>
                                    <button class="g-recaptcha button primary icon solid fa-sign-in-alt" data-sitekey="6LeSWuQnAAAAAARmqW6u2_0SYuvCx3YOf1KJxQW4" data-callback='onSubmit' data-action='submit'>Zaloguj</button><br><br>
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
								<li>&copy; 2023 WIADERNA.EDU.PL</li><li>Powered by <a href="https://bartixen.pl">BARTIXEN.PL - Bartosz Krasoń</a></li><li>Design by <a href="https://www.behance.net/oskarjazdz">Oskar Jażdż</a></li><li>Project supported by <a href="http://html5up.net">HTML5 UP</a></li><li><a href="../../cookies.html">Polityka Cookies</a></li><li><a href="privacy.html">Polityka Prywatności</a></li>
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