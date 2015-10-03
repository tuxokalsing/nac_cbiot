<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Página de edição de dados de grupos.
 *
 *	Observação:
 *
**/

$page_access_level = 1;						//Administração
require("valida_session.php");

$id = $_GET['id'];								// id do grupo a ser editado
$previous_page = urldecode($_GET['prev']);		// página de retorno


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
		
		// Criamos objeto do tipo grupo
		$update_group = new Group("","");
		
		if ($update_group->GetDataFromDB($id) > 0) {
			// TODO ERROR PAGE
			display_error("Grupo não encontrado!",1);
			exit(1);
		}
			
		//Definições do grupo
		$update_group->grupo = $nome;
		$update_group->acronimo = $acronimo;
		
		$err_count += $update_group->UpdateGroupData();
		
		if ($err_count == 0) {
			$showsuccess = 1;
			$showform = 0;
			$updated_group = $update_group;
		} else {
			$showsuccess = 0;
			$showform = 0;
			$showerror = 1;
		}
	} else {
		$showsuccess = 0;
		$showform = 1;
	}
} else { // if it is not a post then we will search for group data
	$group = new Group("","");
	if ($group->GetDataFromDB($id) > 0) {
		// TODO ERROR PAGE
		display_error("Grupo não encontrado!",1);
		exit(1);
	}
}

if($showform) :
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br">

<head>

	<meta name="keywords" content="" />
	<meta name="description" content="Sistema CBIOT - Grupo" />
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
		<div class="row" style="padding-bottom: 20px;">
			<div class="col-md-4">
				<a href="<?php echo $previous_page ?>" class="btn btn-primary btn-block" alt="<?php echo8("Voltar"); ?>">
					<span class="glyphicon glyphicon-arrow-left"></span> <?php echo8("Voltar"); ?>
				</a>
			</div>
			<div class="col-md-4">
			</div>
			<div class="col-md-4">
				<a href="dashboard_admin.php" class="btn btn-primary btn-block" alt="<?php echo8("Voltar para Página Inicial"); ?>">
					<span class="glyphicon glyphicon-home"></span> <?php echo8("Voltar para Página Inicial"); ?>
				</a>
			</div>
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
				  } else {
					echo " value=\"".$group->grupo."\"";
				  }
				  
				  ?> required autofocus>
			</div>
		  </div>
		  <div class="form-group <?php if(!empty($acronimoErr)) { echo "has-error"; } ?>">
		    <label for="input_acronimo" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Acrônimo:"); ?></label>
		   	<div class="col-sm-11">		  
			   <input type="text" class="form-control" id="acronimo" name="acronimo" placeholder="Sigla/Acronimo do grupo em caixa baixa"
				  <?php 
				  if(isset($_POST['acronimo'])) {
				    echo " value=\"".$_POST['acronimo']."\"";
				  } else {
					echo " value=\"".$group->acronimo."\"";
				  }
				  
				  ?> required>
			</div>
		  </div> <!-- /form group -->
		</div>
	  </div> <!-- /panel -->
    <button class="btn btn-lg btn-primary btn-block" type="submit">Atualizar Dados</button>
    </form>
	</div> <!-- /container -->
	</div> <!-- /wrap -->
	<?php include("footer.php"); ?>	
	</body>
</html>

<?php
endif;
if($showsuccess) :
// botão de reeditar - voltar para página anterior - voltár para inicial
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br">

<head>

	<meta name="keywords" content="" />
	<meta name="description" content="Sistema CBIOT - Grupo" />
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
				<?php echo8("Dados do grupo alterados com sucesso!"); ?>
			</div>
			<div class="row" style="padding-bottom: 20px;">
				<div class="col-md-4">
					<a href="<?php echo $previous_page ?>" class="btn btn-primary btn-block" alt="<?php echo8("Voltar"); ?>">
						<span class="glyphicon glyphicon-arrow-left"></span> <?php echo8("Voltar"); ?>
					</a>
				</div>
				<div class="col-md-4">
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
							<p><?php echo $updated_group->grupo; ?></p>
						</div>
					</div>							
					<div class="row">
						<label for="input_acronimo" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Acrônimo"); ?></label>
						<div class="col-sm-11">
							<p><?php echo $updated_group->acronimo; ?></p>
						</div>
					</div> <!-- /row -->
				</div> <!-- /panel-body -->
			</div> <!-- /panel -->
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
	<meta name="description" content="Sistema CBIOT - Grupo" />
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
				<?php echo8("Ops, ocorreu um erro inesperado, favor contatar o desenvolvedor! (se possivel enviar os dados abaixo)"); ?>
			</div>
			<?php my_var_dump($_POST); ?>
		</div> <!-- /container -->
	</div> <!-- /wrap -->
	<?php include("footer.php"); ?>	
</body>
</html>

<?php endif; ?>