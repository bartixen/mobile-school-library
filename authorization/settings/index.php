<?php
	session_start();
	if (!isset($_SESSION['authorization'])) {
		header('Location: ../login/index.php');
		exit();
	}
?> 
<!DOCTYPE HTML>
<html lang="pl_PL">
	<head>
		<title>Biblioteka Wiaderna - Ustawienia</title>
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
							<li><a href="../login/logout.php"><i class="fas fa-sign-out-alt"></i> Wyloguj</a></li>
						</ul>
					</nav>
					<div id="main">
						<div class="inner">
							<header>
								<h1>Ustawienia strony</h1>
                                <p>Możesz tutaj zarządzać stroną oraz panelem zarządzania.</p>
							</header>
                            <section id="search">
                                <h2>Dostępne ustawienia</h2>
                                <?php
                                $button = "";
                                $a_href = "";
                                
                                if ($_SESSION['name'] != "admin") {
                                    $button = "disabled";
                                    $a_href= 'onclick="return false;"';
                                    echo '<div type="button" class="pop-up_login" onclick="submit()" id="del">Ustawienia są dostępne tylko dla administratora</div>';
                                }
                                
                                require_once "../../connect.php";
                                mysqli_report(MYSQLI_REPORT_STRICT);
                                
                                try {
                                    $connection = new mysqli($host, $db_user, $db_password, $db_name);
                                    $connection->query("SET NAMES 'utf8'");
                                    
                                    if ($connection->connect_errno != 0) {
                                        throw new Exception(mysqli_connect_errno());
                                    } else {
                                        $sql = "SELECT * FROM settings";
                                        $result = $connection->query($sql);
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $reservation = $row["reservation"];
                                                $site = $row["site"];
                                                $authorization = $row["authorization"];
                                            }
                                        }
                                        $connection->close();
                                    }
                                } catch (Exception $e) {
                                    echo 'Internal error: ' . $e;
                                }
                                
                                if ($reservation == 0) {
                                    $reservation_text = "Włącz";
                                    $reservation_text_button = "";
                                } else {
                                    $reservation_text = "Wyłącz";
                                    $reservation_text_button = "primary";
                                }
                                
                                if ($site == 0) {
                                    $site_text = "Włącz";
                                    $site_text_button = "";
                                } else {
                                    $site_text = "Wyłącz";
                                    $site_text_button = "primary";
                                }
                                
                                if ($authorization == 0) {
                                    $authorization_text = "Włącz";
                                    $authorization_text_button = "";
                                } else {
                                    $authorization_text = "Wyłącz";
                                    $authorization_text_button = "primary";
                                }
                                ?>
                                <div style="overflow-x: auto;">
                                    <table>
                                        <thead>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>Rezerwacje na stronie</th>
                                                <th>
                                                    <a href="reservation_on_off.php" <?php echo $a_href ?>>
                                                        <button type="button" class="button <?php echo $reservation_text_button . ' ' . $button ?> icon fa-edit"><?php echo $reservation_text ?></button>
                                                    </a>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>Dostępność strony</th>
                                                <th>
                                                    <a href="site_on_off.php" <?php echo $a_href ?>>
                                                        <button type="button" class="button <?php echo $site_text_button . ' ' . $button ?> icon fa-edit"><?php echo $site_text ?></button>
                                                    </a>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>Wyloguj wszystkich użytkowników</th>
                                                <th>
                                                    <a href="logout_all.php" <?php echo $a_href ?>>
                                                        <button type="button" class="button primary <?php echo $button ?> icon fa-edit">Wykonaj</button>
                                                    </a>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>Autoryzacja do panelu</th>
                                                <th>
                                                    <a href="authorization_on_off.php" <?php echo $a_href ?>>
                                                        <button type="button" class="button <?php echo $authorization_text_button . ' ' . $button ?> icon fa-edit"><?php echo $authorization_text ?></button>
                                                    </a>
                                                </th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <br>
                            </section>
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