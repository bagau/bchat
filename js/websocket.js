function webSocket()
{
	if (!window.conn)
	{
		try
		{
			window.conn = new WebSocket('ws://localhost:4041');
			conn.onopen = function(e)
			{
				on_open();
				console.log ("Открыто соединение с вебсокетом");
			};
			conn.onmessage = function(e)
			{
				var data = $.parseJSON(e.data);
				on_message(data);
				console.log(data);
			};
			conn.onerror = function(e)
			{
				console.log('Ошибка вебсокета');
			};
			conn.onclose = function(evt)
			{
				console.log('Закрыто соединение с вебсокетом');
			};
		}
		catch(exception)
		{
			console.log(exception);
		}
	}
}

$(document).ready(function()
{
	webSocket();
});