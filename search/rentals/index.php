<?php
	session_start();
	$csrf_token = bin2hex(random_bytes(32));
	$_SESSION['csrf_token'] = $csrf_token;
?>
<?php
	require_once "../../connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);
    
    try {
        $connection = new mysqli($host, $db_user, $db_password, $db_name);

        $sql = "SELECT site, reservation FROM settings";
		$result = $connection->query($sql);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$site = $row["site"];
				$reservation = $row["reservation"];
			}
		}

		if ($site == 0) {
			header('Location: ../../off/');
			exit();
		}

		if ($reservation == 0) {
			header('Location: off/');
			exit();
		}
        
        $connection->close();
    } catch (Exception $e) {
        echo 'Internal error: ' . $e;
    }
?>
<?php
    require_once "../../connect.php";
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
                $_SESSION['title'] = $row['title'];
				$_SESSION['id'] = $row['id'];
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
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-NHEH4NR1DR"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());

			gtag('config', 'G-NHEH4NR1DR');
		</script>
		<title>Biblioteka Wiaderna - Zarezerwuj książkę</title>
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
							<li><a href="../"><i class="fas fa-search"></i> Wyszukiwarka</a></li>
							<li><a href="../../authorization/"><i class="fas fa-sign-in-alt"></i> Panel administratora</a></li>
						</ul>
					</nav>
					<div id="main">
						<div class="inner">
							<header>
								<h1>Rezerwujesz książkę "<strong style="color: #bfd01d;"><?php echo ''.$_SESSION['title'].'' ?></strong>"</h1>
                                <p>Możesz tutaj zarezerwować swoją książkę. Pamiętaj rezerwacja, nie równa się wypożyczeniem.</p>
							</header>
                            <div id="authorization">
                                <form id='demo-form' method="post" action="work.php">
									<?php
										if (isset($_SESSION['info'])) {
											echo '<div type="button" class="pop-up_login" onclick="submit()" id="del">'.$_SESSION['info'].'</div>';
											unset($_SESSION['info']);
										}
									?>
									<h2>Imię:<input type="text" name="name" id="name" value="" required/></h2>
									<h2>Nazwisko:<input type="text" name="last_name" id="last_name" value="" required/></h2>
									<h2>Klasa:<input type="text" name="class" id="class" value="" required/></h2>
									<h2>Numer telefonu*:<input type="text" name="phone_number" id="phone_number" value="" /></h2>
									<h2>Email*:<input type="text" name="email" id="email" value="" /></h2>
									<p>Klikając przycisk "Zarezerwuj" akceptujesz <a href="../../privacy.html">Polityke Prywatności</a> serwisu.</p>
									<ul class="actions" style="margin-bottom: 1em;">
										<li><button class="g-recaptcha button primary icon solid fa-plus" data-sitekey="6LeSWuQnAAAAAARmqW6u2_0SYuvCx3YOf1KJxQW4" data-callback='onSubmit' data-action='submit' type="submit">Zarezerwuj</button></li>
										<li><button class="button icon solid fa-trash" type="reset">Wyczyść dane</button></li>
									</ul>
									<br>
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