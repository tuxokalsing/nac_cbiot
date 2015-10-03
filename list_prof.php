<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Página que lista alunos e associados do professor.
 *
 *	Observação:	Apenas para visualização.
 *
 *
**/

$page_access_level = 2;						//Professores
require("valida_session.php");

$id = $_SESSION["user_id"];					//id do professor acessando o sistema

define("EDIT_LINK","edit_user.php");		//página de edição de cadastros
define("DELETE_LINK","delete_user.php");	//página de exclusão de cadastros
define("VIEW_LINK","view_user.php");		//página de exclusão de cadastros

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
	$link = "list_prof.php";
									
	$pages_limit = 10;			//limite de numero de paginas gerados
	$rec_limit = 25;			//limite de registro que aparecem na tela
	
	//coletar parametros da pesquisa,
	$sql_params = " usuarios.id > 1";
	
	//todos os parametros que podem ser adicionados:
	if(isset($_GET["cat"])) {
		//se for um array necessita tratamento especial
		if(is_array($_GET["cat"])) {
			//precisamos arrumar o link (colocar ? e parametros)
			if(!stripos($link,"?")) {
				$link = $link."?";
			}
			$andflag = false;
			foreach($_GET["cat"] as $cat) {
				$link = $link."&"."cat[]=".$cat;
				if(!$andflag) {
					$sql_params .= " AND (categoria_id = $cat";
					$andflag = true;
				} else {
					$sql_params .= " OR categoria_id = $cat";
				}
			}
			$sql_params .= ")";
		} 
		// se nao for array é simples
		else {
			$tmp = $_GET["cat"];
			$sql_params .= " AND categoria_id = $tmp";
			$link = add_param($link,"cat",$tmp);
		}
	}
	
	//atividade
	if(isset($_GET["ati"])) {
		$tmp = $_GET["ati"];
		$sql_params .= " AND ativo = $tmp";
		$link = add_param($link,"ati",$tmp);
	} else {
		$sql_params .= " AND ativo = 1";
	}
	
	//apenas relacionados a este professor (usuario que esta acessando)
	$sql_params .= " AND (lider_id = $id OR orientador_id = $id)" ;
	
	//...
		
	// Pegar número total de registros
	$sql = "SELECT count(id) FROM usuarios WHERE ".$sql_params;
	$result = query($sql);
	if(! $result ) { die('Could not get data: ' . mysqli_error()); }
	$row = $result->fetch_array();
	$rec_count = $row[0];
	if(isset($_GET{'page'})) {
	   $page = $_GET{'page'};
	   $offset = $rec_limit * $page ;
	}
	else {
	   $page = 0;
	   $offset = 0;
	}
	$left_rec = $rec_count - ($page * $rec_limit);
	
	if($rec_count != 0):
	
	//ordenamento
	if(isset($_GET["ord"])) {
		$orderby = $_GET["ord"];
	} else {
		$orderby = 1;	//default name ASC
	}
	
	switch($orderby){
		case 1:		$sql_params .= " ORDER BY nome ASC";
					break;
		case 2:		$sql_params .= " ORDER BY nome DESC";
					break;
		case 3:		$sql_params .= " ORDER BY cpf ASC";
					break;
		case 4:		$sql_params .= " ORDER BY cpf DESC";
					break;
		case 7:		$sql_params .= " ORDER BY matricula DESC";
					break;
		case 8:		$sql_params .= " ORDER BY matricula	ASC";
					break;
		default: 	$sql_params .= " ORDER BY nome ASC";
	}
	
	//pesquisa conforme parametros especificados
	$sql = "SELECT usuarios.id, nome, rg, cpf, matricula, contato FROM usuarios
			LEFT JOIN contato ON usuarios.id = contato.usuarios_id AND contato.tipos_contato_id = 6 
			WHERE ".$sql_params.
		   " LIMIT $offset, $rec_limit";
		   
	$result = query($sql);
	if(! $result ) {die('Could not get data: ' . mysqli_error()); }
	
	?>
	
	<div class="row" style="padding-bottom: 5px;">
		<div class="col-md-3">
			<strong><?php echo "Registros: ".$rec_count; ?></strong>		
		</div>
		<div class="col-md-9"></div>
	</div>	
	<div class="panel panel-default">
	  <!-- Table -->
	  <table class="table table-striped table-hover">
		<thead>
			<th><?php 	$orderlink = "";
						if($orderby == 1) {
							$orderlink = add_param($link,"ord",2);
						} else {
							$orderlink = add_param($link,"ord",1);
						}
						echo "<a href=\"".add_param($orderlink,"page",$page)."\">";?>Nome</a></th>
			<th style="text-align: center;"><?php 	$orderlink = "";
						if($orderby == 3) {
							$orderlink = add_param($link,"ord",4);
						} else {
							$orderlink = add_param($link,"ord",3);
						}
						echo "<a href=\"".add_param($orderlink,"page",$page)."\">";?>CPF</a></th>
			<th style="text-align: center;"><?php 	$orderlink = "";
						if($orderby == 7) {
							$orderlink = add_param($link,"ord",8);
						} else {
							$orderlink = add_param($link,"ord",7);
						}
						echo "<a href=\"".add_param($orderlink,"page",$page)."\">"; echo8("Matrícula");?></a></th>
			<th style="text-align: center;">Ramal</th>
		</thead>
		<?php while($row = $result->fetch_assoc()): ?>
		<tr>
			
			<?php
			$actual_link = urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
			// $delete_link = DELETE_LINK;
			// $delete_link = add_param($delete_link,"id",$row['id']);
			// $delete_link = add_param($delete_link,"prev",$actual_link);
			// $edit_link = EDIT_LINK;
			// $edit_link = add_param($edit_link,"id",$row['id']);
			// $edit_link = add_param($edit_link,"prev",$actual_link);
			$view_link = VIEW_LINK;
			$view_link = add_param($view_link,"id",$row['id']);
			$view_link = add_param($view_link,"prev",$actual_link);
			
			// echo "<a id='confirm' href='#' onclick=\"return confirma('".$delete_link."');\" > <span class=\"glyphicon glyphicon-remove-circle\"></span></a> &nbsp;&nbsp;";
			// echo "<a href=\"".$edit_link."\" alt='teste'> <span class=\"glyphicon glyphicon-edit\"></span></a>";
			 
			?>
			<td><a href="<?php echo $view_link; ?>" target="_self" style="color:black;"><?php echo $row['nome']; ?></a></td>
			<td style="text-align: center;"><?php echo $row['cpf']; ?></td>
			<td style="text-align: center;"><?php echo $row['matricula']; ?></td>
			<td style="text-align: center;"><?php echo $row['contato']; ?></td>
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
	<?php
		display_message("Para alterar os integrantes ou registros de suas listas por favor informe suas requisições a Administração",2);
	?>
	<?php endif; 
	if($rec_count == 0) : ?>
	
	<?php
		display_message("Não foram encontrados resultados",2);
	endif;
	?>
	</div> <!-- /container -->
	</div> <!-- /wrap -->
	<?php include("footer.php"); ?>	
	</body>
</html>

