<?php
include 'websocket.php';

class Start_socket extends Websocket
{
	protected $mysqli;

	function __construct()
	{
		parent::__construct();
		$this->host = 'localhost'; //хост
		$this->port = 4041; //порт
		$this->null = NULL;
		$this->mysqli = new mysqli('localhost', 'vseploshadki', '2YjLzaDKT7PNCDCU', 'adminchat');
		$this->loop = true;
		$this->user_keys = array();
		$this->init();
		$this->loop();
	}

	function pre_send ($str, $read_socket)
	{
		$decode = json_decode($str);
		if (empty($decode->command)) return true;
		$func = $decode->command;
		if ( method_exists('Start_socket', $func) ) 
			$this->$func($str, $decode, $read_socket);
	}

	function send_message ($str, $to = false)
	{
		$msg = $this->data_encode($str);

		if (!empty($to))
		{
			$to_socket_key = array_search($to, $this->user_keys);
			$to_socket = $this->socket_array[$to_socket_key];
			@socket_write($to_socket, $msg, strlen($msg));
			return true;
		}

		foreach($this->socket_array as $item)
		{
			@socket_write($item, $msg, strlen($msg));
		}
	}

	function open ($str, $decode, $read_socket = false)
	{
		$arr_key = array_search($read_socket, $this->socket_array);
		$this->user_keys[$arr_key] = $decode->keyid;
		$this->send_message($str, $decode->to_user);
	}

	function send_text ($str, $decode, $read_socket = false)
	{
		$this->send_message($str, $decode->to_user);
	}

	function delete_socket ($read_socket)
	{
		$found_socket = array_search($read_socket, $this->socket_array);
		unset($this->socket_array[$found_socket]);
		unset($this->user_keys[$found_socket]);
	}

	function stop ($str = false, $decode, $read_socket = false)
	{
		$this->loop = false;
	}

	function get_manager ($str, $decode, $read_socket = false)
	{
		$result['command'] = $decode->command;
		$result['query'] = '';

		$sql1 = 'SELECT m.* FROM manager m LEFT JOIN manager_url m_u ON m_u.manager_id = m.id WHERE m_u.url LIKE "%'.$decode->url.'%"';
		$query1 = $this->mysqli->query($sql1);

		$temp1 = $this->get_query($query1);
		if (!empty($temp1[0]))
			$result['query'] = $temp1[0];

		if (empty($result['query']) && !empty($this->user_keys))
		{
			$sql2 = 'SELECT * FROM manager WHERE keyid IN ("'.implode('","', $this->user_keys).'")';
			$query2 = $this->mysqli->query($sql2);

			$temp2 = $this->get_query($query2);
			if (!empty($temp2))
				$result['query'] = $temp2[array_rand($temp2)];
		}

		if (empty($result['query'])) $result['query'] = 0;

		$this->send_message(json_encode($result), $decode->to_user);
	}

	function get_query($query)
	{
		$temp = array();
		if (!empty($query))
		{
			while( $row = $query->fetch_assoc() )
				$temp[] = $row;
			$query->close();
		}
		return $temp;
	}
}

new Start_socket();