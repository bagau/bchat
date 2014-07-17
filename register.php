<?
include "core.php";

class Register extends Core
{
	public function __construct()
	{
		parent::__construct();
		$this->index();
	}

	function index ()
	{
		if (!empty($_POST))
		{
			unset($_SESSION['error']);
			foreach ($_POST as $key => $value)
				$_SESSION[$key] = $value;

			if (!$this->register()) 
				header( 'Location: /register.php', true, 303 );
			else
				header( 'Location: /chat.php', true, 303 );
		}
		include('view/register_view.php');
	}

	function register()
	{
		if (strlen($_POST['InputName']) < 3)
			$_SESSION['error'][] = 'Имя должно быть не меньше 3 символов';
		if (!$this->isValidEmail($_POST['InputEmail']))
			$_SESSION['error'][] = 'Неверный формат электронной почты';
		if (strlen($_POST['InputPass']) < 6)
			$_SESSION['error'][] = 'Пароль должен быть не меньше 6 символов';
		if ($_POST['InputPass'] != $_POST['InputPassSecond'])
			$_SESSION['error'][] = 'Пароли должны быть одинаковыми';
		if (!$this->isValidUrl($_POST['InputDomain']))
			$_SESSION['error'][] = 'Домен должен выглядеть как в примере';

		$sql1 = 'SELECT * FROM manager WHERE email LIKE "'.$_POST['InputEmail'].'"';
		$query1 = $this->mysqli->query($sql1);
		$result1 = $this->get_query($query1);
		if (!empty($result1[0]))
			$_SESSION['error'] = "Менеджер с таким email уже есть в базе";

		if (!empty($_SESSION['error'])) 
			return false;

		$data['keyid'] = $this->generateKeyId(20);
		$data['name'] = $this->mysqli->real_escape_string($_POST['InputName']);
		$data['email'] = $this->mysqli->real_escape_string($_POST['InputEmail']);
		$data['password'] = md5(hash('ripemd160', $this->mysqli->real_escape_string($_POST['InputPass'])));
		$data['domain'] = $this->mysqli->real_escape_string($_POST['InputDomain']);
		$data['right'] = 0;

		$sql2 = 'SELECT * FROM manager 
				WHERE domain 
				LIKE "'.$_POST['InputDomain'].'"';
		$query2 = $this->mysqli->query($sql2);
		$result2 = $this->get_query($query2);
		if (empty($result2[0])) $data['right'] = 2;

		$sql3 = 'INSERT INTO manager (`keyid`, `name`, `email`, `password`, `domain`, `right`) 
				VALUES 	("'.$data['keyid'].'", 
						"'.$data['name'].'", 
						"'.$data['email'].'",
						"'.$data['password'].'",
						"'.$data['domain'].'",
						"'.$data['right'].'")';
		$query3 = $this->mysqli->query($sql3);
		return true;
	}
}
new Register();
?>