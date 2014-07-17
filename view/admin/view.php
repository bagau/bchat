<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link href="css/admin.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.10.2.custom.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script type="text/javascript" src="js/underscore-min.js"></script>
	</head>
	<body>
		<? include('view/admin/tab_menu.php') ?>
		<? 	if (
				!empty($_GET['page']) 
				&& file_exists('view/admin/'.$_GET['page'].'.php')
			)
			{
				include('view/admin/'.$_GET['page'].'.php');
			} ?>
		<input type="button" value="Остановить сокет" class="stopSocket btn btn-danger">
	</body>
</html>