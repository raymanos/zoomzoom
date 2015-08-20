<?php header('Content-Type: text/html;charset=utf-8;'); ?>
<html>
<head>
	<link href="favicon.png" rel="shortcut icon" type="image/x-icon" />
	<script type="text/javascript" src="js/jquery.js"></script>
	<!--// <script type="text/javascript" src="js/jquery.validate.min.js"></script>
	// <script type="text/javascript" src="js/myscript.js"></script>
	// <script src="js/bootstrap.min.js"></script> -->
	<script src="js/registr.js"></script> 
	
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" media="all" href="css/custom.css">
	<link rel="stylesheet" type="text/css" href="css/registr.css">
</head>
<body>
		<div class='forma'>
			<form id='regform' class='form-horizontal' action = 'reg.php' method='post' enctype="multipart/form-data">
				<div class='control-group'>
					<input class='input-large' type = 'text' name = 'login' placeholder="Логин" required>
				</div>

				<div class='control-group'>
					<input type = 'password' name = 'password' id='password' placeholder="Пароль" required>
				</div>

				<div class='control-group'>
					<button id='send_reg' class='btn btn-primary' type = 'submit' name = 'registr'>Зарегистрироваться</button>
				</div>
			</form>
		</div>
</body>
</html>