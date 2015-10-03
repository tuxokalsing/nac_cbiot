<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Página que direciona para o cadastro de usuário, checa se já existe um cadastro
 *
 *	Observação: 
 *
**/

require("classes.php");

// variaves de controle para o conteudo da página
$ShowFormInicial = 1;		//default one
$ShowFormConfirmacao = 0;	//default zero
$ShowSuccessAtivado = 0;	//default zero
$ShowSuccessReativacao = 0;	//default zero
$ShowNotFound = 0;			//default zero
$ShowError = 0;				//default zero
$err_count = 0;				//default zero	

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

$cpf = $cpf_confirma = NULL;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	// Recupera dados dos formularios
	if (empty($_POST["cpf"])) {
		$err_count ++;
	} else {
		$cpf = test_input($_POST["cpf"]);
		if(strlen($cpf) > MAX_CPF) {
			$cpf = substr($cpf,0,MAX_CPF);
		}
	}
	if (empty($_POST["cpf_confirma"])) {
		$err_count ++;
	} else {
		$cpf_confirma = test_input($_POST["cpf_confirma"]);
		if(strlen($cpf_confirma) > MAX_CPF) {
			$cpf_confirma = substr($cpf_confirma,0,MAX_CPF);
		}
	}
	
	// Caso o usuário tenha informado o CPF
	if($cpf) {
		//checa se o cpf existe no banco e busca o resto das informações
		$sql = "SELECT * FROM usuarios AS usu 
						LEFT JOIN acesso AS ace ON ace.id = usu.acesso_id 
						WHERE usu.cpf = '%s'";
		$result = query($sql,$cpf);	
		// Se encontrou o usuário
		if($row = $result->fetch_assoc()) {
			// Se o usuário encontrado já está ativo
			if($row['ativo'] == 1) {
				$ShowSuccessAtivado = 1;
			} 
			// Se o usuário encontrado não está ativo
			else {
				$ShowFormConfirmacao = 1;
			}
			$nome_user = $row['nome'];
			$cpf_user = $cpf;
			$ShowFormInicial = 0;
		} 
		// Se não encontrou
		else {
			$ShowNotFound = 1;			//mostra mensagem de cpf não encontrado
			$ShowFormInicial = 0;
		}
	} 
	// Caso o usuário tenha confirmado que deseja reativar seu usuário
	elseif($cpf_confirma) {
		//checa se o cpf existe no banco e busca o resto das informações
		$sql = "SELECT usu.id, usu.acesso_id FROM usuarios AS usu 
						LEFT JOIN acesso AS ace ON ace.id = usu.acesso_id 
						WHERE usu.cpf = '%s'";
		$result = query($sql,$cpf_confirma);	
		// Se encontrou o usuário
		if($row = $result->fetch_assoc()) {			
			// Se encontrou o email cadastrado
			$acesso = new Access(NULL,NULL,NULL);
			$acesso->GetDataFromDB($row['acesso_id']);	//recupera objeto do acesso
			$acesso->senha = RandomPassword();			//altera a senha randomica
			$acesso->UpdateAccessData();				//altera no banco
			$user = new User("");
			$user->GetDataFromDB($row['id']);
						
			// Manda email para o usuario informando sua senha de recuperação
$assunto = utf8_decode("Mensagem automatica: Reativacao de usuario do sistema Cbiot");
$mensagem = "Olá,
Seu usuário de acesso é ".$acesso->usuario." e sua nova senha é: ".$acesso->senha."
Aguarde ativação do seu usuário pela administração.
Para mudar sua senha basta acessar o sistema e editar suas informações.
						
Mensagem automática do sistema, não responda.";
$mensagem = utf8_decode($mensagem);

			foreach($user->contatos as $contato){
				// se for um email
				if($contato->tipos_contato_id == 4 OR $contato->tipos_contato_id == 5) {
					SendMail($contato->contato, $assunto, $mensagem);
				}
			}

			// Adiciona pendencia para administração reativar o usuário.
			$pending = new Pending(USUARIOS, $user->id, REATIVACAO, $user->nome);
			$pending->SignThisPending();
			
			$ShowSuccessReativacao = 1;
			$ShowFormInicial = 0;
		} else {
			$ShowError = 1;
			$ShowFormInicial = 0;
		}
	} else {
		$ShowErro = 1;
		$ShowFormInicial = 0;
	}
}

// FAZER O DESIGN DAS JANELAS
// ADICIONAR TRATAMENTO PARA O NOVO PENDING EM pendencias E dashboard_admin


if($ShowFormInicial) : ?>

<body onload="setup()">
	<div id="wrap">
	
	<div class="container">
	<div class="alert alert-info" style="text-align: center; font-weight: bold;">
		<?php echo8("CADASTRO DE USUÁRIO"); ?>
	</div>
	<form class="form-horizontal" role="form" action="<?php echo htmlspecialchars("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");?>" method="post">
	  <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title">Por favor informe seu CPF:</h3>
        </div>
	    <div class="panel-body">
		  <div class="form-group">
		    <label for="input_nome" class="col-sm-1 control-label col-form-cbiot">CPF:</label>
		   	<div class="col-sm-8">
			   <input type="text" class="form-control" id="cpf" name="cpf" onblur="javascript: validarCPF(this.value);" onkeypress="javascript: mascara(this, cpf_mask);"  maxlength="14" placeholder="000.000.000-00" required autofocus>
			</div>
			<div class="col-sm-3">
				<button class="btn btn-primary btn-block" type="submit">Enviar</button>
			</div>
		  </div> <!-- /form group -->
		 </div> <!-- /panel body -->
		</div> <!-- /panel -->
    </form>
	</div> <!-- /container -->
	</div> <!-- /wrap -->
	<script src="./js/CbiotScripts.js"></script>
<?php
endif;
if($ShowFormConfirmacao) :
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
				<?php echo8("Encontramos um usuário cadastrado com este CPF!"); ?>
			</div>
			<br>
			<form class="form-horizontal" role="form" action="<?php echo htmlspecialchars("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");?>" method="post">
				<div class="alert alert-info col-sm-12">
					<?php echo8("Nome do usuário: <b>".$nome_user."</b> <br> Status: <b> INATIVO </b>"); ?>
					<?php echo8("<br><br>Deseja reativar este usuário ?<br>"); ?> <br>
				   <button class="btn btn-primary btn-block" style="width: 120px;" type="submit">Reativar</button>
				   <input class="form-control" id="cpf_confirma" name="cpf_confirma" placeholder="000.000.000-00" value="<?php echo $cpf_user ?>" type="hidden" readonly>
				</div>
			</form>			
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
			<div class="alert alert-info noprint" style="text-align: center; font-weight: bold;">
				<?php echo8("O CPF informado não está cadastrado no sistema, por favor, escolha uma das opções abaixo para continuar seu cadastro:"); ?>
			</div>
			<div class="row" style="padding-bottom: 60px;">
				<div class="col-md-3">
					<a href="novo_usuario.php?cat=1" class="btn btn-primary btn-block" alt="<?php echo "Cadastrar Professor"; ?>">
						<span class="glyphicon glyphicon-user"></span> <?php echo "Cadastrar Professor"; ?>
					</a>
				</div>
				<div class="col-md-3">
					<a href="novo_usuario.php?cat=2" class="btn btn-primary btn-block" alt="<?php echo "Cadastrar Aluno"; ?>">
						<span class="glyphicon glyphicon-user"></span> <?php echo "Cadastrar Aluno"; ?>
					</a>
				</div>
				<div class="col-md-3">
					<a href="novo_usuario.php?cat=3" class="btn btn-primary btn-block" alt="<?php echo "Cadastrar Funcionário"; ?>">
						<span class="glyphicon glyphicon-user"></span> <?php echo "Cadastrar Funcionário"; ?>
					</a>
				</div>
				<div class="col-md-3">
					<a href="novo_usuario.php?cat=4" class="btn btn-primary btn-block" alt="<?php echo "Cadastrar Visitante"; ?>">
						<span class="glyphicon glyphicon-user"></span> <?php echo "Cadastrar Visitante"; ?>
					</a>
				</div>
			</div>
		</div> <!-- /container -->
	</div> <!-- /wrap -->

<?php
endif;
if($ShowSuccessAtivado) :
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
					<a href="password_recovery.php" class="btn btn-primary btn-block btn-warning" alt="<?php echo "Recuperar Senha"; ?>">
						<span class="glyphicon glyphicon-question-sign"></span> <?php echo "Recuperar Senha"; ?>
					</a>
				</div>
			</div>
			<div class="alert alert-success noprint" style="text-align: center; font-weight: bold;">
				<?php echo8("O CPF informado está cadastrado no sistema e o usuário está ativado! <br> Retorne para tela de login para entrar no sistema."); ?>
			</div>
		</div> <!-- /container -->
	</div> <!-- /wrap -->

<?php
endif;
if($ShowSuccessReativacao) :
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
				<?php echo8("Sua requisição de reativação foi efetuada com sucesso! <br> Por favor, verifique os seguintes endereços de email e aguarde
								a reativação por parte da administração.<br><br> Mensagens enviadas para: ");
				foreach($user->contatos as $contato){
					// se for um email
					if($contato->tipos_contato_id == 4 OR $contato->tipos_contato_id == 5) {
						echo $contato->contato."; ";
					}
				}

				?>
			</div>
		</div> <!-- /container -->
	</div> <!-- /wrap -->	

	<?php
endif;
if($ShowError) :
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
				<?php echo8("Ocorreu um erro interno no sistema, por favor contate a administração do Cbiot.");?>
			</div>
		</div> <!-- /container -->
	</div> <!-- /wrap -->	
	
<?php endif; ?>
	<?php include("footer.php"); ?>	
	</body>
</html>

