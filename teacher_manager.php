<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Página de alteração de status e associações entre os professores.
 *
 *	Observação:	Mostra o status do professor atual e permite mudança (exceto para lider).
 *
**/

$page_access_level = 1;						//Administração
require("valida_session.php");

$id = $_GET['id'];								// id do usuario a ser editado
$previous_page = urldecode($_GET['prev']);		// página de retorno

$err_count = 0; 	//default zero
$showsuccess = 0;	//default zero
$showerror = 0;		//default zero
$showform = 1;		//default one (if user not found then show only error)


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
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
			
	$tipo_professor = $_POST['tipo_professor'];
	$lider_associado = $_POST['lider_associado'];

	// Criamos objeto do tipo usuario
	$update_user = new User("");
	
	if ($update_user->GetDataFromDB($id) > 0) {
		$showerror = 1;
	} else {
		
		// Se o prof ja é lider:
		if ($update_user->professor_lider == 1) {
			// do nothing
		} else {
			//se for associado ou externo:
			// (1) muda o status dependendo da opção escolhida
			if(strcmp($tipo_professor,"lider") == 0) {
				$update_user->professor_lider = 1;
				$update_user->professor_externo = 0;
				$update_user->lider_id = NULL;
			} elseif(strcmp($tipo_professor,"associado") == 0) {
				$update_user->professor_lider = 0;
				$update_user->professor_externo = 0;
			} elseif(strcmp($tipo_professor,"externo") == 0) {
				$update_user->professor_lider = 0;
				$update_user->professor_externo = 1;
			}

			// (2) se mudou o status para líder então todos os alunos 
			//     que o tinham como orientador agora passam a telo como lider tambem
			if($update_user->professor_lider == 1) {
				$sql = "UPDATE usuarios SET	orientador_id = %d,
											lider_id = %d
										WHERE orientador_id = %d";
				$result = query($sql,$update_user->id,$update_user->id,$update_user->id);
			} else {
				// se nao mudou para líder ainda temos que verificar se mudou o líder do ass/ex
				// se sim então temos que mudar o lider do professor e de todos os alunos
				if($lider_associado != $update_user->lider_id) {
					
					$update_user->lider_id = $lider_associado;
										
					$sql = "UPDATE usuarios SET	lider_id = %d WHERE orientador_id = %d";
					$result = query($sql,$update_user->lider_id,$update_user->id);
				}
			}
			// atualiza os dados do professor
			$err_count += $update_user->UpdateUserData();
		}
	}
	
	if ($err_count == 0) {
		$showsuccess = 1;
		$user = $update_user;
	} else {
		$showsuccess = 0;
		$showerror = 1;
	}
} else { // if it is not a post then we will search for user data
	$user = new User("");
	if ($user->GetDataFromDB($id) > 0) {
		$showform = 0;
	} else {
		if($user->categoria_id != 1) {
			$showform = 0;
			display_error("Este usuário não é um professor!",1);
		}
	}
}
if($showform) :
?>
	<form class="form-horizontal" role="form" action="<?php echo htmlspecialchars("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");?>" method="post">
	<?php if($showerror) : ?>
	  <div class="alert alert-danger" style="text-align: center; font-weight: bold;">
		<?php echo8("Ops, ocorreu um erro inesperado, favor contatar o desenvolvedor! (se possivel enviar os dados abaixo)"); ?>
	  </div>
	<?php endif; ?>
	<?php if($showsuccess) : ?>
	  <div class="alert alert-success" style="text-align: center; font-weight: bold;">
		<?php echo8("Status alterado com sucesso!"); ?>
	  </div>
	<?php endif; ?>
	<?php if($user->professor_lider) : ?>
	  <div class="alert alert-info" style="text-align: center; font-weight: bold;">
		<?php echo8("Atenção: Professores Líderes não podem mudar para associados/externos"); ?>
	  </div>
	<?php endif; ?>
	
	  <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo8("Mudança de Status de Professor"); ?></h3>
        </div>
	    <div class="panel-body">
		  <div class="form-group">
			  <label for="input_nome" class="col-sm-1">Nome:</label>
			  <div class="col-sm-11">
				  <?php echo $user->nome; ?>
			  </div>
		  </div>
		  <div class="form-group">
			<label for="input_classe_professor" class="col-sm-1">Professor:</label>
			<div class="col-sm-5">
				<div class="radio">
				  <label>
					<input type="radio" name="tipo_professor" id="tipo_lider" onclick="LeaderControl();" value="lider" checked>
					<?php echo8("Professor Líder do CBIOT"); ?>
				  </label>
				</div>
				<div class="radio">
				  <label>
					<input type="radio" name="tipo_professor" id="tipo_associado" onclick="LeaderControl();" value="associado">
					<?php echo8("Professor Associado ao CBIOT"); ?>
				  </label>
				</div>
				<div class="radio">
				  <label>
					<input type="radio" name="tipo_professor" id="tipo_externo" onclick="LeaderControl();" value="externo">
					<?php echo8("Professor Externo ao CBIOT"); ?>
				  </label>
				</div>
			</div>
			<label for="input_lider" id="lider_label" class="col-sm-1 control-label col-form-cbiot" style="visibility: hidden;"><?php echo8("Líder"); ?></label>
		   	<div class="col-sm-5">		  
			   <select class="form-control" name="lider_associado" id="lider_associado" style="visibility: hidden;">
				  <?php
					$sql = "SELECT * FROM usuarios WHERE professor_lider = 1 AND ativo = 1 ORDER BY nome ASC";
					$result = query($sql);
					while($row = $result->fetch_assoc()):
				  ?>
				  <option value="<?php echo $row['id']; ?>"><?php echo $row['nome']; ?></option>
				  <?php endwhile; ?>
			   </select>
			   <span class="help-block"id="lider_help" style="visibility: hidden;"><?php echo8("Selecione o líder associado ao professor externo ou associado."); ?></span>
			</div>
          </div> <!-- /form group -->
		</div> <!-- /panel body-->
	  </div> <!-- /panel info-->
    <button class="btn btn-lg btn-primary btn-block" type="submit">Atualizar Status</button>
	<br>
    </form>
	</div> <!-- /container -->
	</div> <!-- /wrap -->
	
	<!-- Script for professors leader control -->
	<script> 
    function LeaderControl() {
		if(document.getElementById('tipo_lider').checked) {
			document.getElementById('lider_label').style.visibility = "hidden";
			document.getElementById('lider_associado').style.visibility = "hidden";
			document.getElementById('lider_help').style.visibility = "hidden";
		} else if(document.getElementById('tipo_associado').checked || 
				 document.getElementById('tipo_externo').checked) {
			document.getElementById('lider_label').style.visibility = "visible";
			document.getElementById('lider_associado').style.visibility = "visible";
			document.getElementById('lider_help').style.visibility = "visible";
		}
    }
	
	<?php 
	//Defined lider	
	if($user->professor_lider): ?>		document.getElementById('tipo_lider').checked = 1;
<?php endif; if($user->professor_lider == 0 AND 
				$user->professor_externo == 0): ?>		document.getElementById('tipo_associado').checked = 1; LeaderControl();
<?php endif; if($user->professor_externo): ?>		document.getElementById('tipo_externo').checked = 1; LeaderControl();
<?php endif;
	  if($user->professor_lider == 0):?>
		document.getElementsByName('lider_associado')[0].value = '<?php echo $user->lider_id; ?>';
<?php endif; ?>
	</script>
	<?php endif; //if showform?>	

	<?php include("footer.php"); ?>	
	</body>
</html>
