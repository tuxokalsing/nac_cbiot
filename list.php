<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Página que lista usuários de acordo com termos pesquisados.
 *
 *	Observação:	Possível realizar edição e exclusão através desta página.
 *				Possível realizar filtragem e pesquisa.
 *
**/

$page_access_level = 1;						//Administração
require("valida_session.php");

define("EDIT_LINK","edit_user.php");		//página de edição de cadastros
define("DELETE_LINK","delete_user.php");	//página de exclusão de cadastros
define("VIEW_LINK","view_user.php");		//página de visualização de cadastros

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
	$link = "list.php";
									
	$pages_limit = 10;			//limite de numero de paginas gerados
	$rec_limit = 25;			//limite de registro que aparecem na tela
	
	//coletar parametros da pesquisa,
	$sql_params = " id > 1";
	
	// pesquisa por nome/rg/cpf/matricula
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$param = $_POST['param'];
		switch($_POST['tipo']) {
			case 0: $sql_params .= " AND nome LIKE '%$param%'";		//nome
					break;
			case 1: $sql_params .= " AND matricula = '$param'";		//matricula
					break;
			case 2: $sql_params .= " AND cpf = '$param'";			//cpf
					break;
			case 3: $sql_params .= " AND rg = '$param'";			//rg
					break;
			default: ;
		}
		$link = add_param($link,"param",$_POST['param']);
		$link = add_param($link,"tipo",$_POST['tipo']);
	} else {
		if(isset($_GET["param"]) AND isset($_GET["tipo"])) {
			$param = $_GET['param'];
			switch($_GET['tipo']) {
				case 0: $sql_params .= " AND nome LIKE '%$param%'";		//nome
						break;
				case 1: $sql_params .= " AND matricula = '$param'";		//matricula
						break;
				case 2: $sql_params .= " AND cpf = '$param'";			//cpf
						break;
				case 3: $sql_params .= " AND rg = '$param'";			//rg
						break;
				default: ;
			}
			$link = add_param($link,"param",$_GET['param']);
			$link = add_param($link,"tipo",$_GET['tipo']);
		}
	}
	
	
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
	//escolaridade
	if(isset($_GET["esc"])) {
		$tmp = $_GET["esc"];
		$sql_params .= " AND escolaridade_id = $tmp";
		$link = add_param($link,"esc",$tmp);
	}
	
	//atividade
	if(isset($_GET["ati"])) {
		$tmp = $_GET["ati"];
		$sql_params .= " AND ativo = $tmp";
		$link = add_param($link,"ati",$tmp);
	}
	
	//lideres de grupo
	if(isset($_GET["lid"])) {
		$tmp = $_GET["lid"];
		$sql_params .= " AND professor_lider = $tmp";
		$link = add_param($link,"lid",$tmp);
	}
	
	//próximos a expirar
	if(isset($_GET["next"])) {
		$tmp = $_GET["next"];
		$sql_params .= " AND data_expiracao BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL $tmp MONTH)";
		$link = add_param($link,"next",$tmp);
	}
		
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
		case 5:		$sql_params .= " ORDER BY rg ASC";
					break;
		case 6:		$sql_params .= " ORDER BY rg DESC";
					break;
		default: 	$sql_params .= " ORDER BY nome ASC";
	}
	
	//pesquisa conforme parametros especificados
	$sql = "SELECT id, nome, rg, cpf FROM usuarios WHERE ".$sql_params.
		   " LIMIT $offset, $rec_limit";
		   
	$result = query($sql);
	if(! $result ) {die('Could not get data: ' . mysqli_error()); }
	
	?>
	
	<form class="form-horizontal" role="form" action="<?php echo htmlspecialchars("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");?>" method="post">
	<div class="row" style="padding-bottom: 5px;">
		<div class="col-md-3">
			<strong><?php echo "Registros: ".$rec_count; ?></strong>		
		</div>
		<div class="col-md-3"> 
			<?php
				$sql = urlencode("SELECT nome, rg, cpf FROM usuarios WHERE ".$sql_params);
				$title = urlencode("LISTA DE USUARIOS DO SISTEMA");
				$page = "print_data.php";
				$print_link = add_param($page,"sql",$sql);
				$print_link = add_param($print_link,"title",$title);
			?>
			<a href="<?php echo $print_link; ?>" class="btn btn-primary btn-block" target="_blank" alt="<?php echo8("Imprimir Toda Lista"); ?>">
				<span class="glyphicon glyphicon-print"></span> <?php echo8("Imprimir Toda Lista"); ?>
			</a>
		</div>
		<div class="col-md-3">
			<input type="text" class="form-control" id="param" name="param" placeholder="Insira o dado a pesquisar"
			  <?php 
			  if(isset($_POST['param'])) {
				echo " value=\"".$_POST['param']."\"";
			  }
			  if(isset($_GET['param'])) {
				echo " value=\"".$_GET['param']."\"";
			  }
			  ?> required>
		</div>
		<div class="col-md-2">
			<select class="form-control" name="tipo" id="tipo">
				<option value="0">Nome</option>
				<option value="1"><?php echo8("Matrícula");?></option>
				<option value="2">CPF</option>
				<option value="3">RG</option>
			</select>
		</div>
		<div class="col-md-1">
			<button class="btn btn-primary btn-block" title="Buscar" type="submit"><span class="glyphicon glyphicon-search"></span></button>
		</div>
	</div>
	</form>
	
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
						echo "<a title='Ordenar por Nome' href=\"".add_param($orderlink,"page",$page)."\">";?>Nome</a></th>
			<th style="text-align: center;"><?php 	$orderlink = "";
						if($orderby == 3) {
							$orderlink = add_param($link,"ord",4);
						} else {
							$orderlink = add_param($link,"ord",3);
						}
						echo "<a title='Ordenar por CPF' href=\"".add_param($orderlink,"page",$page)."\">";?>CPF</a></th>
			<th style="text-align: center;"><?php 	$orderlink = "";
						if($orderby == 5) {
							$orderlink = add_param($link,"ord",6);
						} else {
							$orderlink = add_param($link,"ord",5);
						}
						echo "<a title='Ordenar por RG' href=\"".add_param($orderlink,"page",$page)."\">";?>RG</a></th>
		</thead>
		<?php while($row = $result->fetch_assoc()): ?>
		<tr>
			
			<td><?php
			$actual_link = urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
			$actual_link = urlencode($link);
			$delete_link = DELETE_LINK;
			$delete_link = add_param($delete_link,"id",$row['id']);
			$delete_link = add_param($delete_link,"prev",$actual_link);
			$edit_link = EDIT_LINK;
			$edit_link = add_param($edit_link,"id",$row['id']);
			$edit_link = add_param($edit_link,"prev",$actual_link);
			$view_link = VIEW_LINK;
			$view_link = add_param($view_link,"id",$row['id']);
			$view_link = add_param($view_link,"prev",$actual_link);
			
			echo "<a id='confirm' title='Excluir Registro' href='#' onclick=\"return confirma('".$delete_link."');\" > <span class=\"glyphicon glyphicon-remove-circle\"></span></a> &nbsp;&nbsp;";
			echo "<a href=\"".$edit_link."\" title='Editar Perfil'> <span class=\"glyphicon glyphicon-edit\"></span></a>";
			 
			?></td>
			<td><a title='Ver Perfil' href="<?php echo $view_link; ?>" target="_self" style="color:black;"><?php echo $row['nome']; ?></a></td>
			<td style="text-align: center;"><?php echo $row['cpf']; ?></td>
			<td style="text-align: center;"><?php echo $row['rg']; ?></td>
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
	if($rec_count == 0) : ?>
	<form class="form-horizontal" role="form" action="<?php echo htmlspecialchars("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");?>" method="post">
	<div class="row" style="padding-bottom: 5px;">
		<div class="col-md-3">
			<strong><?php echo "Registros: ".$rec_count; ?></strong>		
		</div>
		<div class="col-md-3"></div>
		<div class="col-md-3">
			<input type="text" class="form-control" id="param" name="param" placeholder="Insira o dado a pesquisar"
			  <?php 
			  if(isset($_POST['param'])) {
				echo " value=\"".$_POST['param']."\"";
			  }
			  ?> required>
		</div>
		<div class="col-md-2">
			<select class="form-control" name="tipo" id="tipo">
				<option value="0">Nome</option>
				<option value="1"><?php echo8("Matrícula");?></option>
				<option value="2">CPF</option>
				<option value="3">RG</option>
			</select>
		</div>
		<div class="col-md-1">
			<button class="btn btn-primary btn-block" alt="Buscar" type="submit"><span class="glyphicon glyphicon-search"></span></button>
		</div>
	</div>
	</form>
	<?php
		display_message("Não foram encontrados resultados para sua pesquisa",2);
		endif;
	?>
	</div> <!-- /container -->
	</div> <!-- /wrap -->
	<?php include("footer.php"); ?>	
	</body>
	<!-- Script para confirmação -->
	<script>
	  function confirma(url){
		var resposta=confirm(<?php echo8("\"Você realmente deseja remover este usuário?\"");?>);
		if (resposta==true) {
			// se o usuário confirmar a pagina será redirecionada
			self.location = url;
		} else {
			// se o usuário não confirmar, não acontecerá nada.
		}
	  }
	  
	  <?php if(isset($_POST['tipo'])) : ?>		document.getElementsByName('tipo')[0].value = '<?php echo $_POST['tipo']; ?>';
	  <?php endif;?>
	  <?php if(isset($_GET['tipo'])) : ?>		document.getElementsByName('tipo')[0].value = '<?php echo $_GET['tipo']; ?>';
	  <?php endif;?>
	</script>
</html>

