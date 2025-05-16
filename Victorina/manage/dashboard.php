<?php require 'auth_check.php'; ?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Страница ввода данных викторины">
    <meta name="author" content="Ravil Vildanov">

    <!-- Website Title -->
    <title>Страница ввода данных</title>
    
    <!-- Styles -->
    <link rel="stylesheet" href="style.css">
	<!-- Favicon  -->
    <link rel="icon" href="images/favicon.png">
		
</head>
<body>
<?php if (isset($_GET['success'])): ?>
  <div class="message success">✅ Данные успешно сохранены!<br /><br /></div>
<?php endif; ?>

<?php if (isset($_GET['error']) && $_GET['error'] === 'duplicate'): ?>
  <div class="message error">❌ Эти данные уже были внесены ранее!<br /><br /></div>
<?php endif; ?>

<?php
if (isset($_GET['error_date'])) {
    $errorMessages = [
        'invalid_date' => 'Некорректный формат даты. Используйте ДД.ММ.ГГГГ.',
        'invalid_date_format' => 'Такой даты не существует. Проверьте правильность ввода.',
        'underage' => 'Вам должно быть не менее 14 лет для отправки формы.',
        'missing_fields' => 'Пожалуйста, заполните все обязательные поля.'
    ];

    $error = $_GET['error_date'];

    if (isset($errorMessages[$error])) {
        echo '<div style="color: red; font-weight: bold;">' . $errorMessages[$error] . '</div>';
    }
}
?>

	<form id="dataForm" method="POST" action="save.php" onsubmit="return validateForm()">
		<div class="form-group">
			<div class="error-message" id="numberError"></div>
			<label>Номер билета: <input type="text" name="number" id="number" maxlength="6" pattern="\d{1,6}" required></label><br>
		</div>
		<div class="form-group">
			<div class="error-message" id="lastNameError"></div>
			<label>Фамилия: <input type="text" name="last_name" id="last_name" pattern="^[А-Яа-яЁё\s\-]+$" required></label><br>
		</div>
		<div class="form-group">
			<div class="error-message" id="firstNameError"></div>
			<label>Имя: <input type="text" name="first_name" id="first_name" pattern="^[А-Яа-яЁё\s\-]+$" required></label><br>
		</div>
		<div class="form-group">
			<div class="error-message" id="middleNameError"></div>
			<label>Отчество: <input type="text" name="middle_name" id="middle_name" pattern="^[А-Яа-яЁё\s\-]+$"required></label><br>
		</div>
		<div class="form-group">
			<div class="error-message" id="middleNameError"></div>
			<label>Дата рождения: <input type="text" name="birthdate" placeholder="ДД.ММ.ГГГГ" required pattern="\d{2}\.\d{2}\.\d{4}" title="Введите дату в формате ДД.ММ.ГГГГ" maxlength="10"></label><br>
		</div>	
		<label>Телефон: <input type="tel" name="phone" placeholder="+7 (___) ___-__-__"><br>
		<button type="submit">Отправить</button>
	
	</form>
	<script>
	function validateForm() {
	  let isValid = true;

	  // Сброс сообщений
	  document.getElementById('numberError').innerText = '';
	  document.getElementById('lastNameError').innerText = '';
	  document.getElementById('firstNameError').innerText = '';
	  document.getElementById('middleNameError').innerText = '';

	  const number = document.getElementById('number').value.trim();
	  const lastName = document.getElementById('last_name').value.trim();
	  const firstName = document.getElementById('first_name').value.trim();
	  const middleName = document.getElementById('middle_name').value.trim();
	  const namePattern = /^[А-Яа-яЁё\s\-]+$/;

	  if (!/^\d{1,6}$/.test(number)) {
		document.getElementById('numberError').innerText = 'Введите до 6 цифр без пробелов и букв.';
		isValid = false;
	  }

	  if (!namePattern.test(lastName)) {
		document.getElementById('lastNameError').innerText = 'Фамилия: только кириллица, пробелы и дефисы.';
		isValid = false;
	  }

	  if (!namePattern.test(firstName)) {
		document.getElementById('firstNameError').innerText = 'Имя: только кириллица, пробелы и дефисы.';
		isValid = false;
	  }

	  if (!namePattern.test(middleName)) {
		document.getElementById('middleNameError').innerText = 'Отчество: только кириллица, пробелы и дефисы.';
		isValid = false;
	  }

	  return isValid;
	}
	</script>

	<script>
	document.addEventListener("DOMContentLoaded", function () {
	  const birthInput = document.querySelector('input[name="birthdate"]');

	  birthInput.addEventListener('input', function () {
		// Удалим все кроме цифр
		let digits = this.value.replace(/\D/g, '').slice(0, 8);

		let formatted = '';
		if (digits.length > 0) {
		  formatted += digits.substring(0, 2);
		}
		if (digits.length >= 3) {
		  formatted += '.' + digits.substring(2, 4);
		}
		if (digits.length >= 5) {
		  formatted += '.' + digits.substring(4, 8);
		}

		this.value = formatted;
	  });

	  // Проверка перед отправкой формы
	  const form = birthInput.closest('form');
	  form.addEventListener('submit', function (e) {
		const pattern = /^\d{2}\.\d{2}\.\d{4}$/;
		if (!pattern.test(birthInput.value)) {
		  e.preventDefault();
		  alert("Пожалуйста, введите дату в формате ДД.ММ.ГГГГ");
		}
	  });
	});
	</script>

	<script>
	document.addEventListener("DOMContentLoaded", function () {
	  const form = document.querySelector("form");
	  const button = form.querySelector("button[type='submit']");

	  form.addEventListener("submit", function () {
		button.disabled = true;
		button.textContent = "Отправка...";
	  });
	});
	</script>
	
	<!-- Добавляем кнопку выхода -->
    <a href="logout.php" class="logout-button">Выйти</a>
	
	<!-- скрипт подключается ниже -->
	<script src="https://cdn.jsdelivr.net/npm/inputmask@5.0.8/dist/inputmask.min.js"></script>

	<script>
	  document.addEventListener("DOMContentLoaded", function () {
		const phoneInput = document.querySelector('input[name="phone"]');
		if (phoneInput && typeof Inputmask !== "undefined") {
		  Inputmask("+7 (999) 999-99-99").mask(phoneInput);
		} else {
		  console.log("Поле не найдено или Inputmask не загрузился.");
		}
	  });
	</script>
	
	<!-- Стили для кнопки выхода -->
    <style>
        .logout-button {
			float: right;
            background-color: #222;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 150px;
            display: block;
            width: 150px;
            text-align: center;
            text-decoration: none;
        }

        .logout-button:hover {
            background-color: darkred;
        }
    </style>

</body>
</html>