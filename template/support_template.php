<!-- шаблон отправленных сообщений -->
<script type="text/template" id="mess_from_templ">
	<div class="mess_from mess_from<%=from%>" data-from="<%=from%>"></div>
</script>

<script type="text/template" id="mess_item_templ">
	<div class="mess_item <%=route%> <%=status%>" data-from="<%=from%>"><%=text%></div>
</script>