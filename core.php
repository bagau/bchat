<? 

class Core
{
	protected $mysqli;
	protected $user;

	public function __construct()
	{
		session_start();
		$this->mysqli = new mysqli('localhost', 'vseploshadki', '2YjLzaDKT7PNCDCU', 'adminchat');
		$this->user = $this->user_data();
	}

	function isValidEmail($mail)
	{
		$exp = '/^([A-Za-z0-9_\-]+\.)*[A-Za-z0-9_\-]+@([A-Za-z0-9][A-Za-z0-9\-]*[A-Za-z0-9]\.)+[A-Za-z]{2,4}$/u';
		
		return (preg_match($exp, $mail, $pEmail) !== 0);
	}

	function isValidUrl($url)
	{
		$exp = '/^([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,6}$/u';

		return (preg_match($exp, $url, $pUrl) !== 0);
	}

	function get_query($query)
	{
		$result = array();
		if (!empty($query))
		{
			while( $row = $query->fetch_assoc() )
				$result[] = $row;
			$query->close();
		}
		return $result;
	}

	function generateKeyId($length = 20)
	{
		$chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz';
		$numChars = strlen($chars);
		$string = '';
		for ($i = 0; $i < $length; $i++) 
		{
			$string .= substr($chars, rand(1, $numChars) - 1, 1);
		}
		return $string;
	}

	function is_active_page($page = false)
	{
		if (!empty($_GET) 
			&& !empty($_GET['page'])
			&& $_GET['page'] == $page
		)
		{
			return 'active';
		}	
		else
		{
			return '';
		}
	}

	function user_data()
	{
		if ( !empty($_SESSION['auth']) ) return (object)$_SESSION['auth'];
		return false;
	}
}
new Core();
?>