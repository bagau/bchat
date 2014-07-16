<!-- Copyright 2014 Rafis Bagautdinov -->
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
		<link href="css/support.css" rel="stylesheet" type="text/css">
		<script type="text/javascript">var keyid = "<?=$_SESSION['auth']['keyid']?>"</script>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.10.2.custom.min.js"></script>
		<script type="text/javascript" src="js/jquery.cookie.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script type="text/javascript" src="js/underscore-min.js"></script>
		<script type="text/javascript" src="js/websocket.js"></script>
		<script type="text/javascript" src="js/support.js"></script>
	</head>
	<body>
		<div class="dialogWindow">
			<div class="to_dialog">К диалогу</div>
			<div class="messages"></div>
		</div>
		<div class="textFormWindow">
			<form data-from="" class="dialog_form" method="post">
				<input type="text" class="dialogEnter form-control" placeholder="Введите ваше сообщение...">
				<input type="submit" class="btn btn-default dialogSubmit" value="Отправить">
			</form>
		</div>
		<input type="button" value="Остановить сокет" class="stopSocket btn btn-danger">
		<a href="?logout" class="logout btn btn-danger">Выйти</a>
		<? include('template/support_template.php'); ?>
	</body>
</html>