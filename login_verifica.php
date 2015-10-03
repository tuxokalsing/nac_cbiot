<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Verificação de informações de login, utilizada após login.
 *
 *	Observação: 
 *
**/

require("classes.php");

//captura dos valores digitados
$login = $_POST["login"];
$passwd = $_POST["passwd"];

//tratamento para evitar MySQL Injection
$login = stripslashes($login);
$passwd = stripslashes($passwd);
//$login = mysqli_real_escape_string($login);
//$passwd = mysqli_real_escape_string($passwd);

//consulta ao bd usuarios para verificar a existencia do usuario e validade
$sql = "SELECT usuarios.id, usuarios.ativo, acesso.senha, acesso.usuario, acesso.nivel FROM acesso 
		 LEFT JOIN usuarios ON usuarios.acesso_id = acesso.id
		 WHERE acesso.usuario = '%s'";
$result = query($sql,$login);

if($result->num_rows > 0) { 	//usuario existente
		
	$data = $result->fetch_assoc();
	$hash = $data['senha'];
	if (Bcrypt::check($passwd, $hash)) {
		$access_level = $data['nivel'];
		$user_id = $data['id'];
		
		if($data['ativo'] == 0) {
			//usuario inativo
			$log->lwrite('Tentativa de login usuário: '.$login.' - Usuário Inativo');
			header("Location: login.php?err=5");
			exit(1);
		}
		
		$log->lwrite('Tentativa de login usuário: '.$login.' - Permitido');
		
		//iniciando session
		session_start();
		$_SESSION["user_login"] = $login;		//salva login na session
		$_SESSION["user_passwd"] = $passwd;		//senha
		$_SESSION["user_id"] = $user_id;		//e numero de cadastro do usuario do bd
		
		//redirecionando conforme nivel de acesso
		if($access_level == 1) {
			header("Location: check_expiration.php"); // check_expiration manda para dashboard_admin
			exit(1);
		}
		elseif($access_level == 2) {
			header("Location: dashboard_prof.php");
			exit(1);
		}
		elseif($access_level > 2) {
			header("Location: dashboard_user.php");
			exit(1);
		}
	}
	else {
		//senha invalida
		$log->lwrite('Tentativa de login usuário: '.$login.' - Senha Inválida');
		header("Location: login.php?err=2");
		exit(1);
	}
}
else {	
	//usuario inválido
	$log->lwrite('Tentativa de login usuário: '.$login.' - Usuário Inexistente');
	header("Location: login.php?err=1");
	exit(1);
}
?>