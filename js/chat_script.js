var chat_base_url = 'http://adminchat/';
var keyid = cookie();
var to_user = 0;
var url = location.href;
var typing = false;
include();

function include()
{
	$('head').append('<link href="'+chat_base_url+'css/client.css" rel="stylesheet" type="text/css">');
	$('head').append('<script type="text/javascript" src="'+chat_base_url+'js/client.js"></script>');
	$('head').append('<script type="text/javascript" src="'+chat_base_url+'js/websocket.js"></script>');
	$.getJSON(chat_base_url + 'template/client_template.php' + '?callback=?', 0)
		.done(function(data)
		{
			$('body').append(data);
			addTemplates();
			event_func();
		}
	);
}

function ajax (phpFunc, sendData, afterAjax)
{
	$.getJSON(base_url + '/' + phpFunc+'?callback=?', sendData)
	.done(function(data)
	{
		eval(afterAjax)(data);
	});
}

function cookie()
{
	var cookieName = 'keyid';
	if (getCookie(cookieName) != undefined)
		return getCookie(cookieName);
	var cookieOptions = {expires: 62208000};
	var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
	var string_length = 20;
	var randomstring = '';
	for (var i = 0; i < string_length; i++)
	{
		var rnum = Math.floor(Math.random() * chars.length);
		randomstring += chars.substring(rnum,rnum+1);
	}
	setCookie(cookieName, randomstring, cookieOptions);
	console.log(getCookie(cookieName));
	return getCookie(cookieName);
}

// возвращает cookie с именем name, если есть, если нет, то undefined
function getCookie(name) 
{
	var matches = document.cookie.match(new RegExp(
	"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
	));
	return matches ? decodeURIComponent(matches[1]) : undefined;
}

function setCookie(name, value, options) 
{
	options = options || {};
	var expires = options.expires;
	if (typeof expires == "number" && expires) 
	{
		var d = new Date();
		d.setTime(d.getTime() + expires*1000);
		expires = options.expires = d;
	}
	if (expires && expires.toUTCString) 
	{ 
		options.expires = expires.toUTCString();
	}
	value = encodeURIComponent(value);
	var updatedCookie = name + "=" + value;
	for(var propName in options) 
	{
		updatedCookie += "; " + propName;
		var propValue = options[propName];    
		if (propValue !== true) 
		{ 
			updatedCookie += "=" + propValue;
		}
	}
	document.cookie = updatedCookie;
}