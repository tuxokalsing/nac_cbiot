<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Página que lista grupos do centro.
 *
 *	Observação:	Possível realizar edição e exclusão através desta página.
 *				Não é necessário filtragem e pesquisa.
 *
**/

$page_access_level = 1;						//Administração
require("valida_session.php");

define("EDIT_LINK","edit_group.php");		//página de edição de grupos
define("DELETE_LINK","delete_group.php");	//página de exclusão de grupos
define("VIEW_LINK","view_group.php");		//página de visualização de grupos

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

	<?php
	//essa variavel controla a pagina atual que estamos navegando
	//ela guarda os parametros da pesquisa sql e remove a pagina atual da url (sera adicionada depois).
	$link = "groups.php";
									
	$pages_limit = 10;			//limite de numero de paginas gerados
	$rec_limit = 25;			//limite de registro que aparecem na tela
	//coletar parametros da pesquisa,
	$sql_params = " id >= 0";
	
	// Pegar número total de registros
	$sql = "SELECT count(id) FROM grupo WHERE ".$sql_params;
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
		$orderby = 1;	//default id ASC
	}
	
	switch($orderby){
		case 1:		$sql_params .= " ORDER BY id ASC";
					break;
		case 2:		$sql_params .= " ORDER BY id DESC";
					break;
		case 3:		$sql_params .= " ORDER BY grupo ASC";
					break;
		case 4:		$sql_params .= " ORDER BY grupo DESC";
					break;
		case 5:		$sql_params .= " ORDER BY acronimo ASC";
					break;
		case 6:		$sql_params .= " ORDER BY acronimo DESC";
					break;
		default: 	$sql_params .= " ORDER BY id ASC";
	}
	
	//pesquisa conforme parametros especificados
	$sql = "SELECT * FROM grupo WHERE ".$sql_params.
		   " LIMIT $offset, $rec_limit";

	$result = query($sql);
	if(! $result ) {die('Could not get data: ' . mysqli_error()); }

	?>
	<div class="row" style="padding-bottom: 5px;">
		<div class="col-md-4">
			<strong><?php echo "Registros: ".$rec_count; ?></strong>		
		</div>
		<div class="col-md-4">
			<?php
				$sql = urlencode("SELECT * FROM grupo");
				$title = urlencode("GRUPOS CADASTRADOS NO SISTEMA");
				$page = "print_data.php";
				$link = add_param($page,"sql",$sql);
				$link = add_param($link,"title",$title);
			?>
			<a href="<?php echo $link; ?>" class="btn btn-primary btn-block" target="_blank" alt="<?php echo8("Imprimir Toda Lista"); ?>">
				<span class="glyphicon glyphicon-print"></span> <?php echo8("Imprimir Toda Lista"); ?>
			</a>
		</div>
		<div class="col-md-4">
			<a href="new_group.php" class="btn btn-primary btn-block" alt="<?php echo8("Novo Grupo"); ?>">
				<span class="glyphicon glyphicon-tag"></span> <?php echo8("Novo Grupo"); ?>
			</a>
		</div>
	</div>
	<div class="panel panel-default">
	  <!-- Table -->
	  <table class="table table-striped table-hover">
		<thead>
			<th><?php echo8("Ações"); ?></th>
			<th><?php 	$orderlink = "";
						if($orderby == 1) {
							$orderlink = add_param($link,"ord",2);
						} else {
							$orderlink = add_param($link,"ord",1);
						}
						echo "<a href=\"".add_param($orderlink,"page",$page)."\">";?>#</a></th>
			<th><?php 	$orderlink = "";
						if($orderby == 3) {
							$orderlink = add_param($link,"ord",4);
						} else {
							$orderlink = add_param($link,"ord",3);
						}
						echo "<a href=\"".add_param($orderlink,"page",$page)."\">";?>Grupo</a></th>
			<th><?php 	$orderlink = "";
						if($orderby == 5) {
							$orderlink = add_param($link,"ord",6);
						} else {
							$orderlink = add_param($link,"ord",5);
						}
						echo "<a href=\"".add_param($orderlink,"page",$page)."\">";?>Acronimo</a></th>
		</thead>
		<?php while($row = $result->fetch_assoc()): ?>
		<tr>
			<td><?php
			$actual_link = urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
			$delete_link = DELETE_LINK;
			$delete_link = add_param($delete_link,"id",$row['id']);
			$delete_link = add_param($delete_link,"prev",$actual_link);
			$edit_link = EDIT_LINK;
			$edit_link = add_param($edit_link,"id",$row['id']);
			$edit_link = add_param($edit_link,"prev",$actual_link);
			$view_link = VIEW_LINK;
			$view_link = add_param($view_link,"id_grupo",$row['id']);
			$view_link = add_param($view_link,"prev",$actual_link);
			
			echo "<a id='confirm' title='Remover Grupo' href='#' onclick=\"return confirma('".$delete_link."');\" > <span class=\"glyphicon glyphicon-remove-circle\"></span></a> &nbsp;&nbsp;";
			echo "<a href=\"".$edit_link."\" title='Alterar Grupo'> <span class=\"glyphicon glyphicon-edit\"></span></a>";
			 
			?></td>
			<td><?php echo $row['id']; ?></td>
			<td><?php echo "<a title='Ver Grupo' href=".$view_link." target='_self' style='color:black;'>".$row['grupo']."</a>"; ?></td>
			<td><?php echo $row['acronimo']; ?></td>
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
		display_message("Não foram encontrados resultados para sua pesquisa",2);
	?>
	</div> <!-- /container -->
	</div> <!-- /wrap -->
	<?php include("footer.php"); ?>	
	</body>
	<!-- Script para confirmação -->
	<script>
	  function confirma(url){
		var resposta=confirm(<?php echo8("\"Ao remover o grupo todos os usuários serão desnvinculados do mesmo. Você realmente deseja remover este grupo?\"");?>);
		if (resposta==true) {
			// se o usuário confirmar a pagina será redirecionada
			self.location = url;
		} else {
			// se o usuário não confirmar, não acontecerá nada.
		}
	  }
	</script>
</html>

