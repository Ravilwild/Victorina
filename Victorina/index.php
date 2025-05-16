<?php
session_start();
require 'db_config.php'; // Подключение к базе данных

// Обработка авторизации
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $mysqli->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $result = $mysqli->query("SELECT * FROM users WHERE username='$username'");
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin'] = $user['username'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Неверные учетные данные";
    }
}
// Получение списка подарков
$prizes = $mysqli->query("SELECT * FROM prizes");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Страница розыгрыша">
    <meta name="author" content="Ravil Vildanov">

    <!-- OG Meta Tags to improve the way the post looks when you share the page on Google+ -->
	<meta property="og:site_name" content="Victorina" /> <!-- website name -->
	<meta property="og:site" content="https://yoursite.ru" /> <!-- website link -->
	<meta property="og:title" content="Викторина"/> <!-- title shown in the actual shared post -->
	<meta property="og:description" content="Страница розыгрыша" /> <!-- description shown in the actual shared post -->
	<meta property="og:image" content="images/ogimage.jpg" /> <!-- image link, make sure it's jpg -->
	<meta property="og:type" content="article" />

    <!-- Website Title -->
    <title>Викторина</title>
    
    <!-- Styles -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Dela+Gothic+One&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/fontawesome-all.css" rel="stylesheet">
    <link href="css/swiper.css" rel="stylesheet">
	<link href="css/magnific-popup.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet">
	<link href="css/modal.css" rel="stylesheet">
	<link href="css/winners.css" rel="stylesheet">
	<!-- Favicon  -->
    <link rel="icon" href="images/favicon.png">
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body data-spy="scroll" data-target=".fixed-top">
   <?php if (!isset($_SESSION['admin'])): ?>
    <div id="authModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Авторизация</h2>
            <?php if (isset($error)): ?>
                <p style="color: red;"><?= $error ?></p>
            <?php endif; ?>
            <form method="post">
                <label>Логин: <input type="text" name="username" required></label><br>
                <label>Пароль: <input type="password" name="password" required></label><br>
                <button type="submit" name="login">Войти</button>
            </form>
        </div>
    </div>

    <script>
        // Получаем модальное окно
        var modal = document.getElementById('authModal');

        // Показываем модальное окно при загрузке страницы
        window.onload = function() {
            modal.style.display = "block";
            document.body.classList.add("modal-open");
        };

        // Закрытие модального окна по клику на крестик
        var span = document.getElementsByClassName("close")[0];
        span.onclick = function() {
            modal.style.display = "none";
            document.body.classList.remove("modal-open");
        };

        // Закрытие модального окна по клику вне его
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
                document.body.classList.remove("modal-open");
            }
        };
    </script>
<?php else: ?>

    <!-- Preloader -->
	<div class="spinner-wrapper">
        <div class="spinner">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
        </div>
    </div>
    <!-- end of preloader -->
   
    <!-- Header -->
    <header id="header" class="header">
        <div class="header-content">
            <div class="container">
                <div class="row">
					<div class="col-lg-6 col-xl-r">
                        <div class="text-container-2">
                            <h2>Список подарков</h2>
								<table id="prizes-table" style="width:99%;">
									<tr>
										<th style="display:none;">ID</th>
										<th style="display:none;width:50%;">Название</th>
										<th style="display:none;width:24%;text-align:center;">Количество</th>
										<th style="width:25%;">&nbsp;</th>
									</tr>
									<?php while ($row = $prizes->fetch_assoc()): ?>
										<tr id="prize-row-<?= $row['id'] ?>" style="font-size:20px;">
											<td style="display:none;"><?= $row['id'] ?></td>
											<td style="width:50%;"><?= $row['prize_name'] ?></td>
											<td style="width:24%;text-align:center;"><?= $row['quantity'] ?></td>
											<td style="width:25%;text-align:center;">
												<button class="raffle-button" data-prize-id="<?= $row['id'] ?>" data-quantity="<?= $row['quantity'] ?>">Разыграть</button>
											</td>
										</tr>
									<?php endwhile; ?>
								</table>
                        </div> <!-- end of text-container -->
                    </div> <!-- end of col -->
                    <div class="col-lg-6 col-xl-5">
                        <div class="text-container">
							<img class="img-fluid img-filter" src="images/GreenPT.png" alt=" ">
							 <!-- Image Logo -->
							<a href="index.php"><img src="images/logo.png" alt="Логотип" style="height:160px;"></a> <br />
                            <span class="Sitename">ВИКТОРИНА</span><br />
							<span class="Sitename" style="font-size:2rem;">Название Викторины</span>
                            <img class="img-fluid img-filter" src="images/fongpt.png" alt="Подарки">
                        </div> <!-- end of text-container -->
                    </div> <!-- end of col -->
                   	<div class="col-lg-6 col-xl-r">
                        <div class="text-container-2">
                            <h2>Победители</h2>
								<div id="winners"></div>

								<script>
									$(document).ready(function() {
										$('.raffle-button').click(function() {
											var prizeId = $(this).data('prize-id');
											var quantity = $(this).data('quantity');
											var rowId = '#prize-row-' + prizeId;

											$.ajax({
												url: 'raffle.php',
												method: 'POST',
												data: {prize_id: prizeId, quantity: quantity},
												success: function(response) {
												var $winnerList = $('<div class="winner-list">' + response + '</div>');

												// Оборачиваем каждую цифру каждого номера в отдельный span для анимации
												$winnerList.find('li').each(function() {
													var number = $(this).text().trim();
													var animatedNumber = number.split('').map(function(digit) {
														return '<span class="digit">' + digit + '</span>';
													}).join('');
													$(this).html(animatedNumber);
													
												});

												$('#winners').html($winnerList);
												// Измерение высоты списка
												var listHeight = $winnerList.outerHeight();
												
												// Установка высоты контейнера
												//$('#winners').height(listHeight);
												// Запуск анимации
												animateDigits();

												// Удаление строки из таблицы
												$(rowId).remove();
											},
											error: function() {
												alert('Ошибка при выполнении запроса.');
											}
										});
									});
								});

								function animateDigits() {
									$('.digit').each(function() {
										var $digit = $(this);
										var finalNumber = $digit.text();

										// Создаем анимацию прокрутки от 0 до финальной цифры
										$digit.animate({
											counter: finalNumber
										}, {
											duration: 1000,
											easing: 'swing',
											step: function(now) {
												$digit.text(Math.floor(now));
											},
											complete: function() {
												$digit.text(finalNumber);
											}
										});
									});
								}
							</script>
                        </div> <!-- end of text-container -->
                    </div> <!-- end of col -->
                </div> <!-- end of row -->
            </div> <!-- end of container -->
        </div> <!-- end of header-content -->
    </header> <!-- end of header -->
    <svg class="header-frame" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" viewBox="0 0 1920 310"><defs><style>.cls-1{fill:#c9e4ef;}</style></defs><title>header-frame</title><path class="cls-1" d="M0,283.054c22.75,12.98,53.1,15.2,70.635,14.808,92.115-2.077,238.3-79.9,354.895-79.938,59.97-.019,106.17,18.059,141.58,34,47.778,21.511,47.778,21.511,90,38.938,28.418,11.731,85.344,26.169,152.992,17.971,68.127-8.255,115.933-34.963,166.492-67.393,37.467-24.032,148.6-112.008,171.753-127.963,27.951-19.26,87.771-81.155,180.71-89.341,72.016-6.343,105.479,12.388,157.434,35.467,69.73,30.976,168.93,92.28,256.514,89.405,100.992-3.315,140.276-41.7,177-64.9V0.24H0V283.054Z"/></svg>
    <!-- end of header -->

    <!-- Customers -->
    <div class="slider-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    
                    <!-- Image Slider -->
                    <div class="slider-container">
                        <div class="swiper-container image-slider">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                        <img class="img-fluid" src="images/partner1.png" alt="alternative">
                                </div>
                                <div class="swiper-slide">
                                        <img class="img-fluid" src="images/partner2.png" alt="alternative">
                                </div>
                                <div class="swiper-slide">
                                        <img class="img-fluid" src="images/partner3.png" alt="alternative">
                                </div>
                                <div class="swiper-slide">
                                        <img class="img-fluid" src="images/partner4.png" alt="alternative">
                                </div>
                                <div class="swiper-slide">
                                        <img class="img-fluid" src="images/partner5.png" alt="alternative">
                                </div>
                                <div class="swiper-slide">
                                        <img class="img-fluid" src="images/partner6.png" alt="alternative">
                                </div>
                            </div> <!-- end of swiper-wrapper -->
                        </div> <!-- end of swiper container -->
                    </div> <!-- end of slider-container -->
                    <!-- end of image slider -->

                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of slider-1 -->
    <!-- end of customers -->

    <!-- Footer -->
    <svg class="footer-frame" data-name="Layer 2" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" viewBox="0 0 1920 79"><defs><style>.cls-2{fill:#222;}</style></defs><title>footer-frame</title><path class="cls-2" d="M0,72.427C143,12.138,255.5,4.577,328.644,7.943c147.721,6.8,183.881,60.242,320.83,53.737,143-6.793,167.826-68.128,293-60.9,109.095,6.3,115.68,54.364,225.251,57.319,113.58,3.064,138.8-47.711,251.189-41.8,104.012,5.474,109.713,50.4,197.369,46.572,89.549-3.91,124.375-52.563,227.622-50.155A338.646,338.646,0,0,1,1920,23.467V79.75H0V72.427Z" transform="translate(0 -0.188)"/></svg>
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="footer-col first">
                        <h4>О викторине</h4>
                        <p class="p-small">Предлагайте проекты, которые решат насущные проблемы, распределяйте часть городских и районных бюджетов, контролируйте исполнение</p>
                    </div>
                </div> <!-- end of col -->
                <div class="col-md-4">
                    <div class="footer-col middle">
                        <h4>Ссылки для ознакомления</h4>
                        <ul class="list-unstyled li-space-lg p-small">
                            <li class="media">
                                <i class="fas fa-square"></i>
                                <div class="media-body">Главный партнер <a class="white" href="https://yourpartner.com" target="_blank">портал Живём на Севере</a></div>
                            </li>
                            <li class="media">
                                <i class="fas fa-square"></i>
                                <div class="media-body">Подробнее о викторине <a class="white" href="https://redme.com" target="_blank" >можно узнать здесь</a></div>
                            </li>
                        </ul>
                    </div>
                </div> <!-- end of col -->
                <div class="col-md-4">
                    <div class="footer-col last">
                        <h4>Контакты</h4>
                        <ul class="list-unstyled li-space-lg p-small">
                            <li class="media">
                                <i class="fas fa-map-marker-alt"></i>
                                <div class="media-body">Здесь напишите свой адрес</div>
                            </li>
                            <li class="media">
                                <i class="fas fa-envelope"></i>
                                <div class="media-body"><a class="white" href="mailto:your@mail.ru">your@mail.ru</a> <i class="fas fa-globe"></i><a class="white" href="https://yoursite.com" target="_blank">tvmig.ru</a></div>
                            </li>
                        </ul>
                    </div> 
                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of footer -->  
    <!-- end of footer -->

	<!-- Навигация внизу -->    
	<div style="background-color:#222;height:70px;">
		<div style="float:left;margin-left:30px;">
			<form method="post" action="logout.php" style="margin-top: 10px;">
				<button type="submit">Выйти</button>
			</form>
		</div>
		<div style="float:right;margin-right:30px;">
			<form method="post" action="admin.php" style="margin-top: 10px;float:left;">
				<button type="submit">Загрузить номера билетов</button>
			</form>
			<div style="margin: 10px 0 0 30px;float:right;">
				<form method="post" action="manage_prizes.php" style="float:left;">
					<button type="submit">Загрузить подарки</button>
				</form>
				&nbsp; &nbsp; &nbsp;
				<form method="post" action="winners.php" style="float:right;">
					<button type="submit">Результат викторины</button>
				</form>
			</div>
		</div>
	</div>

    <!-- Copyright -->
    <div class="copyright">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <p class="p-small">Copyright © 2024 <a href="https://01company.ru">Сайт разработан Ravil Vildanov</a></p>
                </div> <!-- end of col -->
            </div> <!-- enf of row -->
        </div> <!-- end of container -->
    </div> <!-- end of copyright --> 
    <!-- end of copyright -->

    <!-- Scripts 
    <script src="js/jquery.min.js"></script> <!-- jQuery for Bootstrap's JavaScript plugins -->
    <script src="js/popper.min.js"></script> <!-- Popper tooltip library for Bootstrap -->
    <script src="js/bootstrap.min.js"></script> <!-- Bootstrap framework -->
    <script src="js/jquery.easing.min.js"></script> <!-- jQuery Easing for smooth scrolling between anchors -->
    <script src="js/swiper.min.js"></script> <!-- Swiper for image and text sliders -->
    <script src="js/jquery.magnific-popup.js"></script> <!-- Magnific Popup for lightboxes -->
    <script src="js/validator.min.js"></script> <!-- Validator.js - Bootstrap plugin that validates forms -->
    <script src="js/scripts.js"></script> <!-- Custom scripts -->

<?php endif; ?>

</body>
</html>