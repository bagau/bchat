<?
/* 
  Copyright 2014 Rafis Bagautdinov
  http://www.apache.org/licenses/LICENSE-2.0.txt
*/
include "core.php";

class Admin extends Core
{
	protected $mysqli;

	public function __construct()
	{
		parent::__construct();
		$this->index();
	}

	function index ()
	{
		if (!empty($_POST))
		{
			$page = $_POST['page'];
			if ( !method_exists('Admin', $page) )
				header( 'Location: /admin.php', true, 303 );
			$this->$page($_POST);
		}
		if (!empty($_GET['page']))
		{
			$page = $_GET['page'];
			if ( !method_exists('Admin', $page) )
				header( 'Location: /admin.php', true, 303 );
			$this->$page($_GET);
		}

		if (!empty($this->user->keyid) && $this->user->right == 2)
			include('view/admin/view.php');
		else
			include('view/admin/login.php');
	}

	function login ($post = false)
	{
		unset($_SESSION['error']);
		if (empty($_POST['email'])) 
			$_SESSION['error'][] = "Вы не ввели почту";
		if (empty($_POST['pass'])) 
			$_SESSION['error'][] = "Вы не ввели пароль";
		if (!empty($_SESSION['error']))
			return false;

		$data['email'] = $this->mysqli->real_escape_string($_POST['email']);
		$data['password'] = md5(hash('ripemd160', $this->mysqli->real_escape_string($_POST['pass'])));

		$sql = 'SELECT * FROM manager WHERE email LIKE "'.$data['email'].'"'.' AND password LIKE "'.$data['password'].'"';
		$query = $this->mysqli->query($sql);
		$result = $this->get_query($query);
		if (empty($result[0])) 
			$_SESSION['error'][] = "Неправильный email или пароль. Попробуйте еще раз";
		else
		{
			unset($result[0]['password']);
			$_SESSION['auth'] = $result[0];
		}
	}

	function logout($get = false)
	{
		unset($_SESSION['auth']);
		header( 'Location: /admin.php', true, 303 );
	}

	function menu_array()
	{
		return array(
			'sites' => 'Сайты',
			'managers' => 'Менеджеры',
		);
	}

	function managers()
	{
		$sql = 'SELECT * 
				FROM manager 
				WHERE domain LIKE "'.$data['email'].'" 
				AND password LIKE "'.$data['password'].'"';
		$query = $this->mysqli->query($sql);
		$result = $this->get_query($query);
		$this->data = 'value';
	}

	function sites()
	{
		$this->data = 'key';
	}
}
new Admin();
?>