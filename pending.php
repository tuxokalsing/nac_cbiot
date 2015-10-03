<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Página que lista todas as pendencias.
 *
 *	Observação:	Possível realizar edição e exclusão através desta página.
 *				Não é necessário filtragem e pesquisa.
 *				Filtro padrão pela data de inclusão descendente.
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

<body>
	<div id="wrap">
	
	<?php include("navbar.php"); ?>		
	
	<div class="container">

	<?php
	//essa variavel controla a pagina atual que estamos navegando
	//ela guarda os parametros da pesquisa sql e remove a pagina atual da url (sera adicionada depois).
	$link = "pending.php";
									
	$pages_limit = 10;			//limite de numero de paginas gerados
	$rec_limit = 25;			//limite de registro que aparecem na tela
	//coletar parametros da pesquisa,
	$sql_params = " id >= 0";
	
	// Pegar número total de registros
	$sql = "SELECT count(id) FROM pendencias WHERE ".$sql_params;
	$result = query($sql);

	$row = $result->fetch_array();
	$rec_count = $row[0];
		
	if(isset($_GET{'page'})) {
	   $page = $_GET{'page'};
	   $offset = $rec_limit * $page ;
	} else {
	   $page = 0;
	   $offset = 0;
	}
	$left_rec = $rec_count - ($page * $rec_limit);
	
	if($rec_count != 0):
	
	//ordenamento
	if(isset($_GET["ord"])) {
		$orderby = $_GET["ord"];
	} else {
		$orderby = 8;	//default id ASC
	}
	
	switch($orderby){
		case 1:		$sql_params .= " ORDER BY motivo ASC";
					break;
		case 2:		$sql_params .= " ORDER BY motivo DESC";
					break;
		case 3:		$sql_params .= " ORDER BY tabela ASC";
					break;
		case 4:		$sql_params .= " ORDER BY tabela DESC";
					break;
		case 5:		$sql_params .= " ORDER BY informacao ASC";
					break;
		case 6:		$sql_params .= " ORDER BY informacao DESC";
					break;
		case 7:		$sql_params .= " ORDER BY data ASC";
					break;
		case 8:		$sql_params .= " ORDER BY data DESC";
					break;
		case 9:		$sql_params .= " ORDER BY resolvida ASC";
					break;
		case 10:		$sql_params .= " ORDER BY resolvida DESC";
					break;
		default: 	$sql_params .= " ORDER BY data DESC";
	}
	
	//pesquisa conforme parametros especificados
	$sql = "SELECT * FROM pendencias WHERE ".$sql_params.
		   " LIMIT $offset, $rec_limit";

	$result = query($sql);
	if(! $result ) {die('Could not get data: ' . mysqli_error()); }

	?>
	<div class="row" style="padding-bottom: 5px;">
		<div class="col-md-4">
			<strong><?php echo "Registros: ".$rec_count; ?></strong>		
		</div>
		<div class="col-md-4"></div>
		<div class="col-md-4"></div>
	</div>
	<div class="panel panel-default">
	  <!-- Table -->
	  <table class="table table-striped table-hover">
		<thead>
			<th><?php echo8("Ações"); ?></th>
			<th style="text-align: center;"><?php 	$orderlink = "";
						if($orderby == 1) {
							$orderlink = add_param($link,"ord",2);
						} else {
							$orderlink = add_param($link,"ord",1);
						}
						echo "<a href=\"".add_param($orderlink,"page",$page)."\">"; echo8("Ocorrência");?></a></th>
			<th><?php 	$orderlink = "";
						if($orderby == 3) {
							$orderlink = add_param($link,"ord",4);
						} else {
							$orderlink = add_param($link,"ord",3);
						}
						echo "<a href=\"".add_param($orderlink,"page",$page)."\">";?>Tabela</a></th>
			<th><?php 	$orderlink = "";
						if($orderby == 5) {
							$orderlink = add_param($link,"ord",6);
						} else {
							$orderlink = add_param($link,"ord",5);
						}
						echo "<a href=\"".add_param($orderlink,"page",$page)."\">"; echo8("Informação");?></a></th>
			<th style="text-align: center;"><?php 	$orderlink = "";
						if($orderby == 7) {
							$orderlink = add_param($link,"ord",8);
						} else {
							$orderlink = add_param($link,"ord",7);
						}
						echo "<a href=\"".add_param($orderlink,"page",$page)."\">"; echo8("Data");?></a></th>
			<th style="text-align: center;"><?php 	$orderlink = "";
						if($orderby == 9) {
							$orderlink = add_param($link,"ord",10);
						} else {
							$orderlink = add_param($link,"ord",9);
						}
						echo "<a href=\"".add_param($orderlink,"page",$page)."\">"; echo8("Status");?></a></th>
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
			
			if($row['resolvida'] == 0) {
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
								echo "Nome: <a href='$view_link'>".$row['informacao']."</a>";
							} else {
								echo "Nome: ".$row['informacao']; 
							}
						} else {
							echo $row['informacao']; 
						}
			?></td>
			<td style="text-align: center;"><?php 			
			$datetime = new DateTime($row['data']);
			echo $datetime->format('d-m-Y H:i:s'); ?></td>
			<td style="text-align: center;"><?php echo $row['resolvida']? "<span style='color:green;' title='Resolvida' class=\"glyphicon glyphicon-ok\"></span>" : "<span style='color:gold;' title='Pendente' class=\"glyphicon glyphicon-exclamation-sign\"></span>";?></td>
		</tr>
		<?php endwhile; ?>
	  </table>
	  <div class="panel-footer" align="center">
		<ul class="pagination pagination-sm">
		<?php
			// A geração de indices de paginação depende do numero de registros
			// do numero maximo de registro que sao mostrados $rec_limit
			// e do número maximo de indicies que serao gerados $pages_limit
			// controlamos as 5 primeiras e 5 ultimas paginas, se estiver fora
			// deste range, os indices serao movidos conforme o valor.
			// um botao para ir para a primeira e ultima pagina estarao disponiveis também.
			
			$pages_number = (int)($rec_count/$rec_limit);	//total de paginas geradas
			
			if($pages_number < $pages_limit) {
				$pages_limit = $pages_number;
			}
			
			if(isset($_GET['ord'])) {
				$link = add_param($link,"ord",$orderby);
			}
	
			//verifica se estamos na primeira pagina (<<)
			if($page == 0) {
				echo "<li class=\"disabled\"><a href=\"".add_param($link,"page",0)."\">&laquo;</a></li>";
			}
			else {
				echo "<li><a href=\"".add_param($link,"page",0)."\">&laquo;</a></li>";
			}
			
			//controla até a primeira metade [1 2 3 4 ...]
			if($page < ($pages_limit/2)) {
				for($i=1 ; $i <= $pages_limit ; $i++) {
					if($page == $i) {
						echo "<li class=\"disabled\"><a href=\"".add_param($link,"page",$i)."\">$i</a></li>";
					} else {
						echo "<li><a href=\"".add_param($link,"page",$i)."\">$i</a></li>";
					}
				}
			}
			//controla até a ultima metade [... 25 26 27]
			elseif($page > ($pages_number - ($pages_limit/2))) {
				$i_count = $pages_number - $pages_limit - 1;
				for($i=1 ; $i <= $pages_limit ; $i++) {
					$newi = $i_count + $i;
					if($page == $newi) {
						echo "<li class=\"disabled\"><a href=\"".add_param($link,"page",$newi)."\">$newi</a></li>";
					} else {
						echo "<li><a href=\"".add_param($link,"page",$newi)."\">$newi</a></li>";
					}
				}
			}
			//senão esta nas paginas intermediarias [... 11 12 13 ...]
			else {
				$i_count = $page - ($pages_limit/2);
				for($i=1 ; $i <= $pages_limit ; $i++) {
					$newi = $i_count + $i;
					if($page == $newi) {
						echo "<li class=\"disabled\"><a href=\"".add_param($link,"page",$newi)."\">$newi</a></li>";
					} else {
						echo "<li><a href=\"".add_param($link,"page",$newi)."\">$newi</a></li>";
					}
				}
			}

			//verifica se estamos na ultima pagina (>>)
			if($page == $pages_number) {
				echo "<li class=\"disabled\"><a href=\"".add_param($link,"page",$pages_number)."\">&raquo;</a></li>";
			}
			else {
				echo "<li><a href=\"".add_param($link,"page",$pages_number)."\">&raquo;</a></li>";
			}			
		?>
		</ul>
	  </div>
	</div>
	<?php endif; 
	if($rec_count == 0)
		display_message("Nenhuma pendencia registrada",2);
	?>
	</div> <!-- /container -->
	</div> <!-- /wrap -->
	<?php include("footer.php"); ?>	
	</body>
	<!-- Script para confirmação -->
	<script>
	  function confirma(url){
		var resposta=confirm(<?php echo8("\"Você realmente deseja remover esta pendencia?\"");?>);
		if (resposta==true) {
			// se o usuário confirmar a pagina será redirecionada
			self.location = url;
		} else {
			// se o usuário não confirmar, não acontecerá nada.
		}
	  }
	</script>
</html>

