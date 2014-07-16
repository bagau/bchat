<?php

/* 
  Copyright 2014 Rafis Bagautdinov
  http://www.apache.org/licenses/LICENSE-2.0.txt
*/

echo $_GET['callback'] . '(' .json_encode(
'<script type="text/template" id="dialogTemplate">
	<div class="dialogBlock">
		<div class="dialogWindow">
			<div class="dialogMessages"></div>
			<div class="typing"></div>
			<form id="dialogForm" method="post">
				<input type="text" class="dialogEnter" data-id="" placeholder="Введите ваше сообщение...">
				<input type="submit" class="dialogSubmit" value="Отправить">
			</form>
		</div>
		<div class="dialogButton"></div>
	</div>
</script>

<script type="text/template" id="dialogMessagesTemplate">
	<div class="dialogItemMessage <%=align%>" data-id="<%=keyid%>"><%=text%></div>
</script>'
). ')'; ?>
