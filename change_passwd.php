<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Página para troca da senha da administração.
 *
 *	Observação:
 *
**/

$page_access_level = 1;						//Administração
require("valida_session.php");

$id = $_SESSION["user_id"];
$usuario = "admin";

// define variables and set to empty values
$senhaErr = $confirmacaoErr = "";
$senha = $confirmacao = "";

$err_count = 0; 	//default zero
$showform = 1;		//default one
$showsuccess = 0;	//default zero
$showerror = 0;		//default zero
$err_count = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
	//ACESSO
	if (empty($_POST["senha"])) {
		//$usuarioErr = "<br>- Usuário de acesso obrigatório";
		//$err_count ++;
	} else {
		$senha = test_input($_POST["senha"]);
		if(strlen($senha) > MAX_SENHA) {
			$senha = substr($senha,0,MAX_SENHA);
		}
	}
	if (empty($_POST["confirmacao"])) {
		//$usuarioErr = "<br>- Usuário de acesso obrigatório";
		//$err_count ++;
	} else {
		$confirmacao = test_input($_POST["confirmacao"]);
		if(strlen($confirmacao) > MAX_SENHA) {
			$confirmacao = substr($confirmacao,0,MAX_SENHA);
		}
	}
	// Se um dos campos estiver preenchido e o outro não:
	if(empty($_POST["senha"]) XOR empty($_POST["confirmacao"])){
		if(empty($_POST["senha"])){
			$senhaErr = "<br>- Digite uma senha valida";
			$err_count ++;
		} else {
			$confirmacaoErr = "<br>- Informe sua senha atual";
			$err_count ++;
		}
	}
	
	//Verificamos se a senha da confirmação é a mesma que a usada na sessão
	if(!empty($senha) AND !empty($confirmacao)){
		if(strcmp($confirmacao,$_SESSION["user_passwd"])) {
			$confirmacaoErr = "<br>- Senha atual invalida (se você esqueceu informe o grupo de ti!)";
			$err_count ++;
		}				
	}
	
	if($err_count == 0) {
		
		// Criamos objeto do tipo usuario
		$update_user = new User("");
		
		if ($update_user->GetDataFromDB($id) > 0) {
			// TODO ERROR PAGE
			display_error("Erro: Usuário não encontrado!",1);
			exit(1);
		}
								
		//Acesso ao sistema
		$update_user->acesso->usuario = $usuario;
		$update_user->acesso->senha = "";
		if(empty($senha) == FALSE) {
			$update_user->acesso->senha = $senha;
		}
		$err_count += $update_user->acesso->UpdateAccessData();
		
				
		//my_var_dump($new_group);	//for debug purpose
		if ($err_count == 0) {
			$showsuccess = 1;
			$showform = 0;
		} else {
			$showsuccess = 0;
			$showform = 0;
			$showerror = 1;
		}
	} else {
		$showsuccess = 0;
		$showform = 1;
	}
}

if($showform) :
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br">

<head>

	<meta name="keywords" content="" />
	<meta name="description" content="Sistema CBIOT" />
	<meta name="author" content="Arthur Kalsing">

	<?php include("header.php"); ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Centro de Biotecnologia - UFRGS - Intranet</title>

	<!-- Bootstrap core CSS -->
	<link href="./css/bootstrap.css" rel="stylesheet">

</head>

<body onload="setup()">
	<div id="wrap">
	<?php include("navbar.php"); ?>
	<div class="container">
	<div class="alert alert-info" style="text-align: center; font-weight: bold;">
		<?php echo8("TROCA DE SENHA DA ADMINISTRAÇÃO"); ?>
	</div>
	<form class="form-horizontal" role="form" action="<?php echo htmlspecialchars("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");?>" method="post">
	<?php if($err_count) : ?>
	  <div class="panel panel-danger">
        <div class="panel-heading">
          <h3 class="panel-title">Por favor, verifique os seguintes erros:</h3>
        </div>
	    <div class="panel-body">
		<?php 
		echo $senhaErr.$confirmacaoErr;
		?>
		</div>
	  </div>
	<?php endif; ?>
	  <div class="panel panel-info">
	    <div class="panel-heading">
          <h3 class="panel-title"><?php echo8("Acesso ao sistema");?></h3>
        </div>
		<div class="panel-body">
		  <div class="form-group">
		    <label for="input_usuario" class="col-sm-2 control-label col-form-cbiot">Usuario:</label>
		   	<div class="col-sm-10 control-label"> <p align="left"><b>admin</b></p>  </div>
		  </div> <!-- /form group -->
		  <div class="form-group">
			<label for="input_confirmacao" class="col-sm-2 control-label col-form-cbiot <?php if(!empty($confirmacaoErr)) { echo "has-error"; } ?>"><?php echo8("Senha Atual");?>:</label>
		   	<div class="col-sm-10 <?php if(!empty($confirmacaoErr)) { echo "has-error"; } ?>">		  
			   <input type="password" class="form-control" id="confirmacao" name="confirmacao" placeholder="Senha Atual" required>
			</div>
		  </div> <!-- /form group -->
		  <div class="form-group">
			<label for="input_senha" class="col-sm-2 control-label col-form-cbiot <?php if(!empty($senhaErr)) { echo "has-error"; } ?>"><?php echo8("Nova Senha");?>:</label>
		   	<div class="col-sm-10 <?php if(!empty($senhaErr)) { echo "has-error"; } ?>">		  
			   <input type="password" class="form-control" id="senha" name="senha" placeholder="Nova Senha de Acesso ao Sistema" required>
			   <span class="help-block" id="disp_help" ><?php echo8("* A senha só sera alterada se os campos acima (senha atual e nova senha) forem preenchidos corretamente."); ?></span>
			</div>
		  </div> <!-- /form group -->
		</div>
	  </div> <!-- /panel -->
    <button class="btn btn-lg btn-primary btn-block" type="submit">Alterar</button>
    </form>
	</div> <!-- /container -->
	</div> <!-- /wrap -->

	<?php include("footer.php"); ?>	
	</body>
</html>

<?php
endif;
if($showsuccess) :
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br">

<head>

	<meta name="keywords" content="" />
	<meta name="description" content="Sistema CBIOT" />
	<meta name="author" content="Arthur Kalsing">

	<?php include("header.php"); ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Centro de Biotecnologia - UFRGS - Intranet</title>

	<!-- Bootstrap core CSS -->
	<link href="./css/bootstrap.css" rel="stylesheet">

</head>

<body>
	<div id="wrap">
		<?php include("navbar.php"); ?>
		<div class="container">
			<div class="alert alert-success" style="text-align: center; font-weight: bold;">
				<?php echo8("Senha alterada com sucesso! <br> Note que você será redirecionado para página de login."); ?>
			</div>
			<meta http-equiv="refresh" content="4;url=logout.php">
		</div> <!-- /container -->
	</div> <!-- /wrap -->
	<?php include("footer.php"); ?>	
</body>
</html>

<?php
endif;
if($showerror) :
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br">

<head>
	<meta name="keywords" content="" />
	<meta name="description" content="Sistema CBIOT" />
	<meta name="author" content="Arthur Kalsing">
	<?php include("header.php"); ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Centro de Biotecnologia - UFRGS - Intranet</title>

	<!-- Bootstrap core CSS -->
	<link href="./css/bootstrap.css" rel="stylesheet">
</head>

<body>
	<div id="wrap">
		<?php include("navbar.php"); ?>
		<div class="container">
			<div class="alert alert-danger" style="text-align: center; font-weight: bold;">
				<?php echo8("Ops, ocorreu um erro inesperado, favor contatar o desenvolvedor!"); ?>
			</div>
		</div> <!-- /container -->
	</div> <!-- /wrap -->
	<?php include("footer.php"); ?>	
</body>
</html>

<?php endif; ?>