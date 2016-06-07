<table>
	<tr><td>Логин:</td><td> <input type="text" id="userName" autofocus></td></tr>
	<tr><td>Пароль:</td><td> <input type="password" name="userPass"></td></tr>
	<tr><td colspan=2 align='center'><button id="bSubmit">Войти</button></td></tr>
</table>

<script>
	var bSubmit = gDom("bSubmit");
	bSubmit.onclick = function(){
		var userName = $("#userName").val();
		userKey = objectlink.getObjectFromClass('Пользователи',userName);
		location.href = "?interface=iMainMenu&key="+userKey;
		return;
	}
</script>