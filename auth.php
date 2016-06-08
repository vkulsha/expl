<form name="fLogin" onsubmit='return false;'>
<table>
	<tr><td>Логин:</td><td> <input type='text' id='userName' autofocus></td></tr>
	<tr><td>Пароль:</td><td> <input type='password' id='password' name='userPass'></td></tr>
	<tr><td colspan=2 align='center'><button id='bSubmit'>Войти</button></td></tr>
</table>
</form>

<script>

	var bSubmit = gDom("bSubmit");
	bSubmit.onclick = function(){
		var userName = $("#userName").val();
		var password = document.fLogin.userPass.value;

		var u = objectlink.getObjectFromClass("Пользователи", userName);
		var p = objectlink.getObjectFromClass("Пароли пользователей", password);
		if (u && p){
			var key = objectlink.gAND([u, p, objectlink.gO("Ключи доступа пользователей")]);
			if (key.length){
				location.href = "?interface=iMainMenu&key="+key[0];
				
			}
		}
	}
</script>