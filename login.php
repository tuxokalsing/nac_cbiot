<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Página de login.
 *
 *	Observação: 
 *
**/

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br">

<head>

<meta name="keywords" content="" />
<meta name="description" content="Login Intranet CBIOT - UFRGS" />
<meta name="author" content="Arthur Kalsing">

<?php include("header.php"); ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Centro de Biotecnologia - UFRGS - Intranet - Login</title>

<!-- Custom styles for this template -->
<link href="./css/signin.css" rel="stylesheet">

</head>

<body>

	<div id="wrap">
	<div class="container" style="padding-top: 50px;">
		<h1 class="form-signin-heading" align="center">Centro de Biotecnologia - UFRGS</h1>
		  <form class="form-signin" id='form_id' action="login_verifica.php" method="post" name="formlogin" target="_self">
			<h2 class="form-signin-heading">Por favor faça o login:</h2>
			<input type="text" class="form-control" placeholder="Usuário" id='login_id' name="login" required autofocus>
			<input type="password" class="form-control" placeholder="Senha" id='passwd_id' name="passwd" required>
			<button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
			<div>
				<div style="width: 150px; position: absolute; padding-left: 150px;">
					<a style="width: 150px; position: absolute;" href="password_recovery.php" class="btn btn-primary btn-block btn-warning" alt="<?php echo "Recuperar Senha"; ?>">
						<span class="glyphicon glyphicon-question-sign"></span> <?php echo "Recuperar Senha"; ?>
					</a>
				</div>
				<div style="width: 150px;">
					<a href="check_cpf.php" class="btn btn-primary btn-block btn-info" alt="<?php echo "Cadastrar Usuário"; ?>">
						<span class="glyphicon glyphicon-edit"></span> <?php echo "Cadastrar Usuário"; ?>
					</a>
				</div>
			</div>
			<?php
			if(isset($_GET["err"])) {
				$err = $_GET["err"];
				if($err == 1)
					echo "<div class=\"alert alert-danger\" align=\"center\">
							<strong> Usuário não cadastrado no sistema! </strong>
						  </div>";
				elseif($err == 2)
					echo "<div class=\"alert alert-danger\" align=\"center\">
							<strong> Senha incorreta! </strong>
						  </div>";
				elseif($err == 3)
					echo "<div class=\"alert alert-danger\" align=\"center\">
							<strong> Privilégio de administrador necessário! </strong>
						  </div>";
				elseif($err == 4)
					echo "<div class=\"alert alert-danger\" align=\"center\">
							<strong> Você deve realizar login para acessar esta página. </strong>
						  </div>";
				elseif($err == 5)
					echo "<div class=\"alert alert-danger\" align=\"center\">
							<strong>Usuário inativo, por favor entre em contato com a administração. </strong>
						  </div>";
				elseif($err == 6)
					echo "<div class=\"alert alert-danger\" align=\"center\">
							<strong>Sessão expirada, por favor realize login novamente. </strong>
						  </div>";
			}
			?>
		  </form>
	</div> <!-- /container -->
	</div> <!-- /wrap -->
	<?php include("footer.php"); ?>
	</body>
</html>

