<? 
/* 
  Copyright 2014 Rafis Bagautdinov
  http://www.apache.org/licenses/LICENSE-2.0.txt
*/
include "core.php";

class Support extends Core
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
			unset($_SESSION['error']);
			$func = $_POST['func'];
			if (!$this->$func($_POST))
				header( 'Location: /support.php', true, 303 );
		}
		if (isset($_GET['logout']))
		{
			unset($_SESSION['auth']);
			header( 'Location: /support.php', true, 303 );
		}

		if (!empty($_SESSION['auth']['keyid']))
			include('view/support_view.php');
		else
			include('view/support_login_view.php');
	}

	function login ($post = false)
	{
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
}
new Support();
?>