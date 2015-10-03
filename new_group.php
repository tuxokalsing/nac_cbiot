<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Página de cadastro para grupos.
 *
 *	Observação:
 *
**/

$page_access_level = 1;						//Administração
require("valida_session.php");

// define variables and set to empty values
$nomeErr = $acronimoErr = "";
$nome = $acronimo = "";

$err_count = 0; 	//default zero
$showform = 1;		//default one
$showsuccess = 0;	//default zero
$showerror = 0;		//default zero

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	// NOME DO GRUPO
	if (empty($_POST["nome"])) {
		$nomeErr = "<br>- Nome do grupo obrigatório";
		$err_count ++;
	} else {
		$nome = test_input($_POST["nome"]);
		if(strlen($nome) > MAX_GRUPO) {
			$nome = substr($nome,0,MAX_GRUPO);
		}
	}
	// ACRONIMO
	if (empty($_POST["acronimo"])) {
		$acronimoErr = "<br>- Acrônimo obrigatório";
		$err_count ++;
	} else {
		$acronimo = test_input($_POST["acronimo"]);
		if(strlen($acronimo) > MAX_ACRONIMO) {
			$acronimo = substr($acronimo,0,MAX_ACRONIMO);
		}
	}
	
	if($err_count == 0) {
		
		//Novo objeto do tipo usuario
		$new_group = new Group($nome, $acronimo);
		
		// Todas as informações completas: Include no banco
		$new_group->SignThisGroup();
		
		//my_var_dump($new_group);	//for debug purpose
		if ($new_group->GetDataFromDB($new_group->id) == 0) {
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
		<?php echo8("FORMULÁRIO DE CADASTRO DE GRUPO"); ?>
	</div>
	<form class="form-horizontal" role="form" action="<?php echo htmlspecialchars("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");?>" method="post">
	<?php if($err_count) : ?>
	  <div class="panel panel-danger">
        <div class="panel-heading">
          <h3 class="panel-title">Por favor, verifique os seguintes erros:</h3>
        </div>
	    <div class="panel-body">
		<?php 
		echo $nomeErr.$acronimoErr;
		?>
		</div>
	  </div>
	<?php endif; ?>
	  <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title">Dados do Grupo</h3>
        </div>
	    <div class="panel-body">
		  <div class="form-group <?php if(!empty($nomeErr)) { echo "has-error"; } ?>">
		    <label for="input_nome" class="col-sm-1 control-label col-form-cbiot">Nome:</label>
		   	<div class="col-sm-11">		  
			   <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome do Grupo"
				  <?php 
				  if(isset($_POST['nome'])) {
				    echo " value=\"".$_POST['nome']."\"";
				  }
				  ?> required autofocus>
			</div>
		  </div>
		  <div class="form-group">
		    <label for="input_acronimo" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Acrônimo:"); ?></label>
		   	<div class="col-sm-11">		  
			   <input type="text" class="form-control" id="acronimo" name="acronimo" placeholder="Sigla/Acronimo do grupo em caixa baixa"
				  <?php 
				  if(isset($_POST['acronimo'])) {
				    echo " value=\"".$_POST['acronimo']."\"";
				  }
				  ?> required>
			</div>
          </div> <!-- /form group -->
		</div> <!-- /panel body -->
	  </div> <!-- /panel info -->
    <button class="btn btn-lg btn-primary btn-block" type="submit">Cadastrar</button>
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
				<?php echo8("Novo grupo cadastrado com sucesso!"); ?>
			</div>
			<div class="row" style="padding-bottom: 20px;">
				<div class="col-md-4">
					<a href="new_group.php" class="btn btn-primary btn-block" alt="<?php echo8("Novo Grupo"); ?>">
						<span class="glyphicon glyphicon-tag"></span> <?php echo8("Novo Grupo"); ?>
					</a>
				</div>
				<div class="col-md-4">
					<a href="edit_group.php<?php echo "?id=".$new_group->id; ?>" class="btn btn-primary btn-block" alt="<?php echo8("Alterar Dados do Grupo"); ?>">
						<span class="glyphicon glyphicon-edit"></span> <?php echo8("Alterar Dados do Grupo"); ?>
					</a>
				</div>
				<div class="col-md-4">
					<a href="dashboard_admin.php" class="btn btn-primary btn-block" alt="<?php echo8("Voltar para Página Inicial"); ?>">
						<span class="glyphicon glyphicon-home"></span> <?php echo8("Voltar para Página Inicial"); ?>
					</a>
				</div>
			</div>
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title">Dados do Grupo</h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<label for="input_nome" class="col-sm-1 control-label col-form-cbiot">Nome:</label>
						<div class="col-sm-11">
							<p><?php echo $new_group->grupo; ?></p>
						</div>
					</div>							
					<div class="row">
						<label for="input_acronimo" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Acrônimo"); ?></label>
						<div class="col-sm-11">
							<p><?php echo $new_group->acronimo; ?></p>
						</div>
					</div> <!-- /row -->
				</div> <!-- /panel-body -->
			</div> <!-- /panel -->
		</div> <!-- /container -->
	</div> <!-- /wrap -->
	<meta http-equiv="refresh" content="2;url=groups.php">
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
			<?php my_var_dump($_POST); ?>
		</div> <!-- /container -->
	</div> <!-- /wrap -->
	<?php include("footer.php"); ?>	
</body>
</html>

<?php endif; ?>