<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
		<link href="css/support.css" rel="stylesheet" type="text/css">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.10.2.custom.min.js"></script>
		<script type="text/javascript" src="js/jquery.cookie.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script type="text/javascript" src="js/underscore-min.js"></script>
	</head>
	<body>
		<form method='POST' class="login_form col-md-4 ">
			<? 	if (is_array($_SESSION['error']) && !empty($_SESSION['error']))
				{ ?>
					<div class="alert alert-danger">
						<? foreach ($_SESSION['error'] as $error)
							echo $error.'<br>'; ?>
					</div>
			<? 	} ?>
			<input type="hidden" name="func" value="login">
			<div class="form-group">
				<input type='text' class="form-control input-lg" name='email' placeholder="Email">
			</div>
			<div class="form-group">
				<input type='password' class="form-control input-lg" name='pass' placeholder="Пароль">
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-primary btn-lg btn-block">Войти</button>
			</div>
		</form>
	</body>
</html>