<form name="fLogin" onsubmit='return false;' id='fLogin'>
<table>
	<tr><td>Логин:</td><td> <input type='text' id='eUser' autofocus></td></tr>
	<tr><td>Пароль:</td><td> <input type='password' id='ePassword' name='ePassword'></td></tr>
	<tr><td colspan=2 align='center'><button id='bLogin'>Войти</button></td></tr>
</table>
</form>

<script>
	var policy = showInterfaceElements(userId, curInterface);
	var bLogin = gDom("bLogin");
	bLogin.onclick = function(){
		var eUser = $("#eUser").val();
		var password = document.fLogin.ePassword.value;
	
		var u = objectlink.gOrm("gAnd",[[classes["Пользователи"]],"id",false,"and n='"+eUser+"'"]);//objectlink.getObjectFromClass("Пользователи", eUser);
		var p = objectlink.gOrm("gAnd",[[classes["Пароли пользователей"]],"id",false,"and n='"+password+"'"]);//objectlink.getObjectFromClass("Пароли пользователей", password);
		if (u && p){
			var key = objectlink.gAND([u, p, classes["Ключи доступа пользователей"]]);
			if (key.length){
				location.href = "?interface=iMainMenu&key="+key[0];
				
			}
		}
	}
</script>