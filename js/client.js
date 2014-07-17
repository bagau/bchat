function addTemplates()
{
	var dialogTemplate = _.template($('#dialogTemplate').text());
	$('body').append(dialogTemplate);
}

function getMessages()
{
	//ajax ('getMessages', { keyid : keyid }, 'print_messages');
}

function get_manager()
{
	var data = {
		command: 'get_manager',
		url: url,
		keyid: keyid,
		to_user: keyid
	};
	conn.send(JSON.stringify(data));
}

function print_messages (data)
{
	var mess = $('.dialogMessages');
	$.each(data, function(key, value)
	{
		$('.typing').html('');
		if (value.type == 'typing')
			$('.typing').html('Менеджер набирает сообщение...');

		if (!value.text) return false;
		var obj = 
		{
			align : (value.to_user == keyid)?'inbox':'outbox',
			keyid : keyid,
			text : (value.text)?value.text:value
		};

		var template = _.template($('#dialogMessagesTemplate').text(), obj);
		mess.append(template);
	});
	mess.animate({scrollTop: 9999999}, 1);
}

// добавление текста

function sendText(type)
{
	var value = $('.dialogEnter').val();
	if (!to_user)
	{
		alert("Для вас пока нет собеседника");
		return false;
	}
	var data = {
		command: 'send_text',
		text: value,
		keyid: keyid,
		to_user: to_user
	};
	if (type == 'submit')
	{
		print_messages( {0: data} );
		data.type = type;
		$('.dialogEnter').val('');
	}
	conn.send( JSON.stringify(data) );
}

function on_open()
{
	var data = {command : 'open', keyid : keyid, to_user : keyid};
	conn.send( JSON.stringify(data) );
}

function on_message (data)
{
	if (data && data.command && data.command == 'open')
		to_user = get_manager();

	if (data && data.command && data.command == 'send_text') 
		print_messages({0: data});

	if (data.query && data.query != 0 && data.query.keyid)
		to_user = data.query.keyid;
}

// выполнение ajax запроса

function event_func()
{
	$('.dialogButton').click(function()
	{
		$('.dialogWindow').toggleClass('open');
	});

	$('.dialogEnter').keyup(function()
	{
		sendText('typing');
	});

	$('#dialogForm').submit(function()
	{
		sendText('submit');
		return false;
	});
}

$(document).ready(function()
{
	getMessages();
});