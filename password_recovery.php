<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Página para recuperação de senha do sistema.
 *
 *	Observação: 
 *
**/

require("classes.php");

// variaves de controle para o conteudo da página
$ShowForm = 1;		//default one
$ShowFound = 0;		//default zero
$ShowNotFound = 0;	//default zero	
$err_count = 0;		//default zero	

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
<?php 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty($_POST["email"])) {
		$err_count ++;
	} else {
		$email = test_input($_POST["email"]);
		if(strlen($email) > MAX_CONTATO) {
			$email = substr($email,0,MAX_CONTATO);
		}
	}

	//checa se o email existe no banco e busca o resto das informações
	$sql = "SELECT usu.id, usu.acesso_id FROM contato AS cont 
					LEFT JOIN usuarios AS usu ON usu.id = cont.usuarios_id 
					LEFT JOIN acesso AS ace ON ace.id = usu.acesso_id 
					WHERE cont.contato = '%s'";
	$result = query($sql,$email);	
	
	if($row = $result->fetch_assoc()) {
		// Se encontrou o email cadastrado
		$acesso = new Access(NULL,NULL,NULL);
		$acesso->GetDataFromDB($row['acesso_id']);	//recupera objeto do acesso
		$acesso->senha = RandomPassword();			//altera a senha randomica
		$acesso->UpdateAccessData();				//altera no banco
		$user = new User("");
		$user->GetDataFromDB($row['id']);
		
		// Manda email para o usuario informando sua senha de recuperação
$assunto = "Mensagem automatica: Recuperacao de senha do sistema Cbiot";
$mensagem = "Olá,
Seu usuário de acesso é ".$acesso->usuario." e sua nova senha é: ".$acesso->senha."
Para mudar sua senha basta acessar o sistema e editar suas informações.

Mensagem automatica do sistema, não responda.";

$assunto = utf8_decode($assunto);
$mensagem = utf8_decode($mensagem);
		
		foreach($user->contatos as $contato){
			// se for um email
			if($contato->tipos_contato_id == 4 OR $contato->tipos_contato_id == 5) {
				SendMail($contato->contato, $assunto, $mensagem);
			}
		}
		
		$ShowFound = 1;			//mostra mensagem de email enviado
		$ShowForm = 0;
	} else {
		$ShowNotFound = 1;			//mostra mensagem de email não encontrado
		$ShowForm = 0;
	}
	
	
}

if($ShowForm) : ?>

<body onload="setup()">
	<div id="wrap">
	
	<div class="container">
	<div class="alert alert-info" style="text-align: center; font-weight: bold;">
		<?php echo8("RECUPERAÇÃO DE SENHA"); ?>
	</div>
	<form class="form-horizontal" role="form" action="<?php echo htmlspecialchars("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");?>" method="post">
	  <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title">Por favor informe seu email do instituto:</h3>
        </div>
	    <div class="panel-body">
		  <div class="form-group">
		    <label for="input_nome" class="col-sm-1 control-label col-form-cbiot">Email Cbiot:</label>
		   	<div class="col-sm-8">
			   <input type="text" class="form-control" id="email" name="email" placeholder="email@cbiot.ufrgs.br" required autofocus>
			</div>
			<div class="col-sm-3">
				<button class="btn btn-primary btn-block" type="submit">Buscar</button>
			</div>
		  </div> <!-- /form group -->
		 </div> <!-- /panel body -->
		</div> <!-- /panel -->
		
    </form>
	</div> <!-- /container -->
	</div> <!-- /wrap -->
<?php
endif;
if($ShowFound) :
?>	 
		 
<body>
	<div id="wrap">
		<div class="container">
			<div class="row noprint" style="padding-bottom: 20px;">
				<div class="col-md-4">
					<a href="login.php" class="btn btn-primary btn-block" alt="<?php echo8("Voltar para Login"); ?>">
						<span class="glyphicon glyphicon-arrow-left"></span> <?php echo8("Voltar para Login"); ?>
					</a>
				</div>
				<div class="col-md-4">
				</div>
				<div class="col-md-4">
				</div>
			</div>
			
			<div class="alert alert-success noprint" style="text-align: center; font-weight: bold;">
				
				<?php	echo8("Sua nova senha foi enviada para: <b>"); 
					
				foreach($user->contatos as $contato){
					// se for um email
					if($contato->tipos_contato_id == 4 OR $contato->tipos_contato_id == 5) {
						echo $contato->contato."; ";
					}
				}
					echo8("</b> por favor também verifique sua caixa de spam.");

				?>
			</div>
		</div> <!-- /container -->
	</div> <!-- /wrap -->

<?php
endif;
if($ShowNotFound) :
?>

<body>
	<div id="wrap">
		<div class="container">
			<div class="row noprint" style="padding-bottom: 20px;">
				<div class="col-md-4">
					<a href="login.php" class="btn btn-primary btn-block" alt="<?php echo8("Voltar para Login"); ?>">
						<span class="glyphicon glyphicon-arrow-left"></span> <?php echo8("Voltar para Login"); ?>
					</a>
				</div>
				<div class="col-md-4">
				</div>
				<div class="col-md-4">
				</div>
			</div>
			
			<div class="alert alert-danger noprint" style="text-align: center; font-weight: bold;">
				<?php echo8("O email <b>".$email."</b> não esta cadastrado no nosso sistema. Favor contate a administração."); ?>
			</div>
			
		</div> <!-- /container -->
	</div> <!-- /wrap -->

<?php endif; ?>
	<?php include("footer.php"); ?>	
	</body>
</html>

