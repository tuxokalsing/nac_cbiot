<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Página inicial do sistema para o administrador. Resolver pendencias.
 *
 *	Observação: Pendencias são adicionadas automaticamente pelas outras páginas, resolvidas apenas aqui.
 *
**/

$page_access_level = 1;						//Administração
require("valida_session.php");

define("RESOLVE_LINK","resolve_pending.php?action=0");				//página de solução
define("DELETE_LINK","resolve_pending.php?action=1");				//página de exclusão de pendencia
define("ACEITA_USER_LINK","resolve_pending.php?action=2");			//página aceita usuario
define("REJEITA_USER_LINK","resolve_pending.php?action=3");			//página rejeita usuario
define("RENOVAR_PERIODO_LINK","resolve_pending.php?action=4");		//página renovar período
define("REATIVAR_USER_LINK","resolve_pending.php?action=5");		//página reativar usuário
define("NAO_REATIVAR_PERIODO_LINK","resolve_pending.php?action=6");	//página não reativar usuário
define("VIEW_LINK","view_user.php");								//página de visualização de cadastros

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
// recupera dados do usuário
$user = new User("");
$user->GetDataFromDB($_SESSION["user_id"]);
 ?>		

<body>
	<div id="wrap">
	
	<?php include("navbar.php"); ?>		
	
	<div class="container">
	<!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h1><?php echo8("Olá $user->nome!"); ?>	</h1>
      </div>
    </div>

    
      
	
	<?php
	//essa variavel controla a pagina atual que estamos navegando
	//ela guarda os parametros da pesquisa sql e remove a pagina atual da url (sera adicionada depois).
	$link = "dashboard_admin.php";
									
	//coletar parametros da pesquisa,
	$sql_params = " resolvida = 0";
	
	// Pegar número total de registros
	$sql = "SELECT count(id) FROM pendencias WHERE ".$sql_params;
	$result = query($sql);

	$row = $result->fetch_array();
	$rec_count = $row[0];
	
	if($rec_count != 0):
	
	$sql_params .= " ORDER BY data DESC";
	//pesquisa conforme parametros especificados
	$sql = "SELECT * FROM pendencias WHERE ".$sql_params;

	$result = query($sql);
	if(! $result ) {die('Could not get data: ' . mysqli_error()); }

	?>
	<!-- Example row of columns -->
    <h2><?php echo8("Pendências:"); ?>	</h2>
	<div class="panel panel-default">
	  <!-- Table -->
	  <table class="table table-striped table-hover">
		<thead>
			<th><?php echo8("Ações"); ?></th>
			<th style="text-align: center;"><?php echo8("Ocorrência");?></th>
			<th>Tabela</th>
			<th><?php echo8("Informação");?></th>
			<th style="text-align: center;"><?php echo8("Data");?></th>
			<th style="text-align: center;"><?php echo8("Status");?></th>
		</thead>
		<?php while($row = $result->fetch_assoc()): ?>
		<tr>
			<td><?php
			// lista de ações dependendo do motivo
			$actual_link = urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
			$delete_link = DELETE_LINK;
			$delete_link = add_param($delete_link,"id",$row['id']);
			$delete_link = add_param($delete_link,"prev",$actual_link);
			$resolve_link = RESOLVE_LINK;
			$resolve_link = add_param($resolve_link,"id",$row['id']);
			$resolve_link = add_param($resolve_link,"prev",$actual_link);
			$aceita_user_link = ACEITA_USER_LINK;
			$aceita_user_link = add_param($aceita_user_link,"id",$row['id']);
			$aceita_user_link = add_param($aceita_user_link,"prev",$actual_link);
			$rejeita_user_link = REJEITA_USER_LINK;
			$rejeita_user_link = add_param($rejeita_user_link,"id",$row['id']);
			$rejeita_user_link = add_param($rejeita_user_link,"prev",$actual_link);
			$renovar_periodo_link = RENOVAR_PERIODO_LINK;
			$renovar_periodo_link = add_param($renovar_periodo_link,"id",$row['id']);
			$renovar_periodo_link = add_param($renovar_periodo_link,"prev",$actual_link);
			$reativar_user_link = REATIVAR_USER_LINK;
			$reativar_user_link = add_param($reativar_user_link,"id",$row['id']);
			$reativar_user_link = add_param($reativar_user_link,"prev",$actual_link);
			$nao_reativar_user_link = NAO_REATIVAR_PERIODO_LINK;
			$nao_reativar_user_link = add_param($nao_reativar_user_link,"id",$row['id']);
			$nao_reativar_user_link = add_param($nao_reativar_user_link,"prev",$actual_link);
			
			echo "<a id='confirm' href='#' onclick=\"return confirma('".$delete_link."');\" > <span title='"."Remover Pendência"."' class=\"glyphicon glyphicon-remove-circle\"></span></a> &nbsp;&nbsp;";
			
			if($row['tabela'] == USUARIOS) {
				if($row['motivo'] == NOVO) {
					// Novos usuários podem ser aceitos ou rejeitados
					echo "<a href=\"".$rejeita_user_link."\"> <span title='"."Rejeitar Usuário"."' class=\"glyphicon glyphicon-thumbs-down\"></span></a> &nbsp;&nbsp;";
					echo "<a href=\"".$aceita_user_link."\"> <span title='"."Aceitar Usuário"."' class=\"glyphicon glyphicon-thumbs-up\"></span></a>";
				} elseif($row['motivo'] == ALTERADO) {
					// Se for alterado basta dar um ok
					echo "<a href=\"".$resolve_link."\"> <span title='Resolver' class=\"glyphicon glyphicon-check\"></span></a>";
				} elseif($row['motivo'] == REMOVIDO) {
					// Se for removido basta dar um ok
					echo "<a href=\"".$resolve_link."\"> <span title='Resolver' class=\"glyphicon glyphicon-check\"></span></a>";
				} elseif($row['motivo'] == EXPIRACAO) {
					// Se for expiração pode ignorar ou renovar tempo
					echo "<a href=\"".$resolve_link."\"> <span title='"."Não renovar tempo de ativação (usuário saiu do Cbiot)"."' class=\"glyphicon glyphicon-new-window\"></span></a> &nbsp;&nbsp;";
					echo "<a href=\"".$renovar_periodo_link."\"> <span title='"."Renovar tempo de ativação (usuário continua no Cbiot)"."' class=\"glyphicon glyphicon-time\"></span></a>";
				} elseif($row['motivo'] == REATIVACAO) {
					// Se for reativação pode ignorar ou reativar o usuário
					echo "<a href=\"".$nao_reativar_user_link."\"> <span title='"."Não reativar usuário (registro continua salvo)"."' class=\"glyphicon glyphicon-fire\"></span></a> &nbsp;&nbsp;";
					echo "<a href=\"".$reativar_user_link."\"> <span title='"."Reativar cadastro de usuário"."' class=\"glyphicon glyphicon-saved\"></span></a>";
				}
			} else {
				// caso não for usuarios só resta resolver a pendencia
				echo "<a href=\"".$resolve_link."\"> <span title='Resolver' class=\"glyphicon glyphicon-check\"></span></a>";
			}
						
			?></td>
			<td style="text-align: center;"><?php echo_motivo($row['motivo']); ?></td>
			<td><?php echo_tabela($row['tabela']); ?></td>
			<td><?php 
						$view_link = VIEW_LINK;
						$view_link = add_param($view_link,"id",$row['tabela_id']);
						$view_link = add_param($view_link,"prev",$actual_link);
						
						if($row['tabela'] == USUARIOS AND $row['motivo'] != EXPIRACAO) {
							if($row['motivo'] == NOVO OR $row['motivo'] == REATIVACAO) {
								echo "Nome: <a title='Ver Perfil' href='$view_link'>".$row['informacao']."</a>";
							} else {
								echo "Nome: ".$row['informacao']; 
							}
						} else {
							echo $row['informacao']; 
						}
			?></td>
			<td style="text-align: center;"><?php
			$datetime = new DateTime($row['data']);
			echo $datetime->format('d-m-Y H:i:s');  ?>
			</td>
			<td style="text-align: center;"><?php echo8($row['resolvida']? "<span title='Pendência resolvida' style='color:green;' class=\"glyphicon glyphicon-ok\"></span>" : "<span title='Pendência não resolvida' style='color:gold;' class=\"glyphicon glyphicon-exclamation-sign\"></span>");?></td>
		</tr>
		<?php endwhile; ?>
	  </table>
	  <div class="panel-footer" align="center"></div>
	</div>
	<?php
	endif;
	if($rec_count == 0) :
	?>	
		<div class="col-sm-6" style="padding-top: 20px;">
			<h2 align="right" style="padding-top: 10px;"><?php echo8("Está tudo em dia! "); ?> </h2>
		</div>
		<div class="col-sm-1" style="padding-top: 20px;">
			<span class="glyphicon glyphicon-thumbs-up" style="font-size: 77px;"></span>
		</div>
	<?php
	endif;
	?>		  
		  
	</div> <!-- /container -->
	</div> <!-- /container -->
	</div> <!-- /wrap -->
	<?php include("footer.php"); ?>	
	</body>
</html>

