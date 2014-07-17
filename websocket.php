<?php

class Websocket
{
	function __construct()
	{
		set_time_limit(0);
		ignore_user_abort(true);
	}

	function init()
	{
		// создает мастер сокет и возвращает дескриптор (ресурс) сокета
		$this->master_socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

		// установка опции на сокете
		socket_set_option($this->master_socket, SOL_SOCKET, SO_REUSEADDR, 1);

		// привязываем этот сокет к определенному хосту и порту
		socket_bind($this->master_socket, $this->host, $this->port);

		// начинаем принимать подключения на порт со стороны клиента
		socket_listen($this->master_socket);

		// добавить сокет в массив сокетов
		$this->socket_array = array($this->master_socket);
	}

	function loop()
	{
		// начать бесконечный цикл
		while (true)
		{
			if ($this->loop == false) break;
			// добавить сокет в массив на чтение
			$read = $this->socket_array;

			/*
				первоначально в массиве read только дескриптор главного сокета master_socket, 
				позже, после поступления новых подключений, 
				дескрипторы новых сокетов тоже добавляются в массив чтения read
				и на новых сокетах тоже проверяется, было ли новое подключение
			*/

			// принимает массивы сокетов и ожидает их для изменения статуса
			socket_select($read, $this->null, $this->null, 0, 10);

			// если главный сокет есть в массиве чтения,
			// значит было новое соединение
			if (in_array($this->master_socket, $read))
			{
				// создание нового сокета и возврат его ресурса (дескриптора),
				// который предназначен для обмена данными с клиентом
				$new_socket = socket_accept($this->master_socket);

				// добавить новый сокет в массив сокетов
				$this->socket_array[] = $new_socket;

				// тут принимается заголовок рукопожатия от сокета
				$header = socket_read($new_socket, 1024);

				// выполнить рукопожатие
				$this->perform_handshaking($header, $new_socket, $this->host, $this->port);

				// возвращает ключ ресурса главного сокета в массиве чтения
				$found_socket = array_search($this->master_socket, $read);

				// удалить главный сокет из этого массива
				unset($read[$found_socket]);
			}

			// получение данных из всех остальных сокетов
			foreach ($read as $read_socket)
			{
				// пока есть полученные данные, 
				// то есть полученная длина не меньше 1 байта
				while (@socket_recv($read_socket, $buf, 1024, 0) >= 1)
				{
					// декодирует данные, которые получили в buf
					$received_text = $this->data_decode($buf);

					// отправить данные
					$this->pre_send($received_text, $read_socket);

					// прервать цикл foreach
					break 2;
				}

				$buf = @socket_read($read_socket, 1024, PHP_NORMAL_READ);
				
				if ($buf === false)
				{
					// проверка отсоединенных пользователей
					// удалить клиента из массива сокетов
					$this->delete_socket($read_socket);
				}
			}
		}
		// закрыть главный сокет
		socket_close($this->master_socket);
	}

	// Раскодировать входящие сообщения
	function data_decode ($text)
	{
		// ord - возвращает ASCII код первого символа строки
		$length = ord($text[1]) & 127;
		if($length == 126) 
		{
			$masks = substr($text, 4, 4);
			$data = substr($text, 8);
		}
		elseif($length == 127) 
		{
			$masks = substr($text, 10, 4);
			$data = substr($text, 14);
		}
		else 
		{
			$masks = substr($text, 2, 4);
			$data = substr($text, 6);
		}
		$text = "";

		for ($i = 0; $i < strlen($data); $i++)
		{
			$text .= $data[$i] ^ $masks[$i%4];
		}
		return $text;
	}

	// Закодировать сообщение для передачи клиенту
	function data_encode ($text)
	{
		$b1 = 0x80 | (0x1 & 0x0f);
		$length = strlen($text);
		
		// pack - упаковывает данные в бинарную строку
		if ($length <= 125)
			$header = pack('CC', $b1, $length);
		elseif ($length > 125 && $length < 65536)
			$header = pack('CCn', $b1, 126, $length);
		elseif ($length >= 65536)
			$header = pack('CCNN', $b1, 127, $length);
		return $header.$text;
	}

	// рукопожатие нового клиента
	function perform_handshaking ($receved_header, $new_socket, $host, $port)
	{
		$headers = array();
		$lines = preg_split("/\r\n/", $receved_header);
		foreach($lines as $line)
		{
			$line = chop($line);
			if (preg_match('/\A(\S+): (.*)\z/', $line, $matches))
			{
				$headers[$matches[1]] = $matches[2];
			}
		}

		$secKey = $headers['Sec-WebSocket-Key'];
		$secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
		
		//заголовок ответа на рукопожатие
		$upgrade  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
					"Upgrade: websocket\r\n" .
					"Connection: Upgrade\r\n" .
					"WebSocket-Origin: ".$host."\r\n" .
					"WebSocket-Location: ws://".$host.":".$port."\r\n".
					"Sec-WebSocket-Accept:".$secAccept."\r\n\r\n";

		// запись в сокет $new_socket данных из буфера $upgrade
		// Возвращает количество байт, успешно записанных в сокет
		socket_write($new_socket, $upgrade, strlen($upgrade));
	}
}

new Websocket();