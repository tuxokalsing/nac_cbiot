<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Script que verifica se o usuário efetuou login, este script é acessado em todo
 *				intranet do sistema.
 *
 *	Observação: $access_level deve estar declarado antes da chamada desta função.
 *				Os níveis de acesso vão de 1-5 sendo que os níveis superiores podem
 *				acessar tudo que os inferiores acessam (na verdade os niveis 3-5 se igualam no nivel de acesso).
 *				Ex: 
 *						$access_level = 1 -> apenas administração tem acesso
 *						$access_level = 2 -> Administração e Professores tem acesso
 *						$access_level = 3 -> Alunos
 *						$access_level = 4 -> Funcionarios
 *						$access_level = 5 -> Visitantes
 *
**/

require("classes.php");
session_start();

//verificação da existencia de sessões
if (isset($_SESSION["user_login"]) AND isset($_SESSION["user_passwd"])) {

	# Verifica o timeout da sessao
	if (isset($_SESSION['timeout'])) {	
		# Check Session Time for expiry
		#
		# Tempo em segundos. 120 * 60 = 7200s = 120 minutos
		if ($_SESSION['timeout'] + 120 * 60 < time()){
			//Sessão expirada
			unset($_SESSION["user_login"]);
			unset($_SESSION["user_passwd"]);
			unset($_SESSION["user_id"]);
			unset($_SESSION['timeout']);
			session_destroy();
			header("Location: login.php?err=6");
			exit(1);
		}
	}
	else {
		# Initialize time
		$_SESSION['timeout'] = time();
	}

    $login = $_SESSION["user_login"];
    $passwd = $_SESSION["user_passwd"];
	
	//verifica se estas informações correspondem ao banco de dados
	//consulta ao bd usuarios para retirar verificar a existencia do mesmo
	$sql = "SELECT * FROM acesso WHERE usuario = '%s'";
	$result = query($sql,$login);
	
	if($result->num_rows > 0) {
		//usuario existente
		$data = $result->fetch_assoc();
		$access_level = $data['nivel'];
		$hash = $data['senha'];
		if (Bcrypt::check($passwd, $hash)) {
			//senha correta
			unset($login);
			unset($passwd);
			unset($hash);		
			if($access_level > $page_access_level) {
				//Usuario sem permissao para acesso
				unset($_SESSION["user_login"]);
				unset($_SESSION["user_passwd"]);
				unset($_SESSION["user_id"]);
				unset($_SESSION['timeout']);
				session_destroy();
				header("Location: login.php?err=3");
				exit(1);
			}
		}
		else {
			//senha incorreta
			unset($login);
			unset($passwd);
			unset($hash);
			unset($_SESSION["user_login"]);
			unset($_SESSION["user_passwd"]);
			unset($_SESSION["user_id"]);
			unset($_SESSION['timeout']);
			session_destroy();
			header("Location: login.php?err=2");
			exit(1);
		}
	}
	else {	
		//usuario não cadastrador no banco
		unset($_SESSION["user_login"]);
		unset($_SESSION["user_passwd"]);
		unset($_SESSION["user_id"]);
		unset($_SESSION['timeout']);
		unset($login);
		unset($passwd);
		unset($hash);
		session_destroy();
		header("Location: login.php?err=1");
		exit(1);
	}
}
else {
	//caso não houve sessão iniciada, significa que não houve login
	header("Location: login.php?err=4");
	exit(1);
}

?>