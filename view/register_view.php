<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.10.2.custom.min.js"></script>
		<script type="text/javascript" src="js/jquery.cookie.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script type="text/javascript" src="js/underscore-min.js"></script>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<form role="form" method="POST">
					<div class="col-lg-6">
						<div class="well well-sm"><strong><span class="glyphicon glyphicon-asterisk"></span>Обязательные поля</strong></div>
						<div class="form-group">
							<label for="InputName">Введите имя</label>
							<div class="input-group">
								<input type="text" class="form-control" value="<?=(!empty($_SESSION['InputName'])?$_SESSION['InputName']:'')?>" name="InputName" id="InputName" placeholder="Введите имя" required>
								<span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
							</div>
						</div>
						<div class="form-group">
							<label for="InputEmail">Введите Email</label>
							<div class="input-group">
								<input type="email" class="form-control" value="<?=(!empty($_SESSION['InputEmail'])?$_SESSION['InputEmail']:'')?>" id="InputEmail" name="InputEmail" placeholder="Введите Email" required>
								<span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
							</div>
						</div>
						<div class="form-group">
							<label for="InputPass">Введите Пароль</label>
							<div class="input-group">
								<input type="password" class="form-control" id="InputPass" name="InputPass" placeholder="Введите пароль" required>
								<span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
							</div>
						</div>
						<div class="form-group">
							<label for="InputPassSecond">Подтвердите Пароль</label>
							<div class="input-group">
								<input type="password" class="form-control" id="InputPassSecond" name="InputPassSecond" placeholder="Подтвердите Пароль" required>
								<span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
							</div>
						</div>
						<div class="form-group">
							<label for="InputDomain">Введите ваш домен (например, domain.ru)</label>
							<div class="input-group">
								<input type="text" class="form-control" id="InputDomain" value="<?=(!empty($_SESSION['InputDomain'])?$_SESSION['InputDomain']:'')?>" name="InputDomain" placeholder="Введите ваш домен" required>
								<span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
							</div>
						</div>
						<input type="submit" name="submit" id="submit" value="Отправить" class="btn btn-info pull-right">
					</div>
				</form>
				<div class="col-lg-5 col-md-push-1">
					<div class="col-md-12">
						<? 	if (is_array($_SESSION['error']) && !empty($_SESSION['error'])) : ?>
								<div class="alert alert-danger">
									<?
										foreach ($_SESSION['error'] as $error)
										echo $error.'<br>'; ?>
								</div>
						<? 	endif ?>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>