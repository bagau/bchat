function print_inbox (data)
{
	if (!data.text) return false;
	
	var mess = $('.messages');
	var from = data.keyid;

	if ($('.mess_from'+from).length == 0)
	{
		var mess_from_data = {from: from};
		var mess_from_templ = _.template($('#mess_from_templ').text(), mess_from_data);
		mess.append(mess_from_templ);
	}

	var mess_from = $('.mess_from'+from);
	var last_item = $('.mess_from'+from+' .mess_item:last');
	
	var mess_item_data = {
		route 	: 'inbox',
		from 	: from,
		text 	: data.text,
		status 	: (data.type == 'submit') ? 'sended' : '',
	};

	var mess_item_templ = _.template($('#mess_item_templ').text(), mess_item_data);

	if ( last_item.length && !last_item.hasClass("sended") )
		last_item.replaceWith(mess_item_templ);
	else if (data.type != 'submit')
		mess_from.append(mess_item_templ);
		
	mess.animate({scrollTop: 9999999}, 1);
}

function print_outbox (data)
{
	if (!data.text) return false;
	var mess = $('.messages');
	var mess_to = $('.mess_from'+data.to_user);
	var mess_item_data = {
		route 	: 'outbox',
		from 	: keyid,
		text 	: data.text,
		status 	: 'sended',
	};
	var mess_item_templ = _.template($('#mess_item_templ').text(), mess_item_data);
	mess_to.append(mess_item_templ);
	mess.animate({scrollTop: 9999999}, 1);
}

function sendText (type)
{
	var from = $('.dialog_form').data('from');
	var value = $('.dialogEnter').val();
	if (!from)
	{
		alert("Выберите сначала, с кем будете общаться");
		return false;
	}

	var data = {
		command	: 'send_text',
		text 	: value,
		keyid 	: keyid,
		to_user : from,
		type 	: type,
	};
	if (type == 'typing')
		data.text = '';
	if (type == 'submit')
	{
		$('.dialogEnter').val('');
		print_outbox(data);
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
	if (data && data.command && data.command == 'send_text')
	{
		print_inbox(data);
	}
}

function to_dialog()
{
	var mess = $('.messages');
	mess.addClass('in_dialog');
	$('.mess_from').removeClass('hide');
	
	var dialog_form = $('.dialog_form');
	dialog_form.hide();
	dialog_form.data('from', '');
	mess.animate({scrollTop: 9999999}, 1);
}

function show_dialog(elem)
{
	var mess = $('.messages');
	mess.removeClass('in_dialog');
	$('.mess_from').addClass('hide');
	elem.removeClass('hide');
	
	var dialog_form = $('.dialog_form');
	dialog_form.data('from', elem.data('from'));
	dialog_form.show();
	mess.animate({scrollTop: 9999999}, 1);
}

function get_mess()
{
	to_dialog();
}

$(document).ready(function()
{
	get_mess();
	$('.dialogEnter').keyup(function()
	{
		sendText('typing');
	});

	$('.dialog_form').submit(function()
	{
		sendText('submit');
		return false;
	});

	$('.stopSocket').click(function()
	{
		var data = {
			command: 'stop',
			keyid: keyid,
			to_user: keyid,
		};
		conn.send( JSON.stringify(data) );
	});

	$('.to_dialog').click(function()
	{
		to_dialog();
	});

	$('.messages').on('click', ".mess_from", function()
	{
		show_dialog($(this));
	});
});