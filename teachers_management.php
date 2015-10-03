<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Pagina para gerenciamento de lideres/orientadores e categorias de professores
 *
 *	Observação: Um líder só podera trocar de categoria se nao tiver nenhum subordinado
 *				Associados e Externos precisam definir um lider
 *
**/

$page_access_level = 1;						//Administração
require("valida_session.php");

define("MANAGE_LINK","teacher_manager.php");		//página de gerencia de professores

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
	$link = "teachers_management.php";
	
	//coletar parametros da pesquisa,
	$sql_params = " id > 1 AND professor_lider = 1 AND ativo = 1";
	
	// Pegar número total de registros
	$sql = "SELECT count(id) FROM usuarios WHERE ".$sql_params;
	$result = query($sql);
	if(! $result ) { die('Could not get data: ' . mysqli_error()); }
	$row = $result->fetch_array();
	$rec_count = $row[0];
	
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
	$sql = "SELECT id, nome, rg, cpf FROM usuarios WHERE ".$sql_params;

	$result = query($sql);
	if(! $result ) {die('Could not get data: ' . mysqli_error()); }
	
	?>
	
	<div class="row" style="padding-bottom: 5px;">
		<div class="col-md-4">
			<strong><?php echo "Registros: ".$rec_count; ?></strong>		
		</div>
		<div class="col-md-4"></div>
		<div class="col-md-4">
		</div>
	</div>
	<div class="panel panel-default">
	  <!-- Table -->
	  <table class="table">
		<thead>
			<th><?php echo8("<font class='lider'>Líderes</font>"); ?></th>
			<th><?php echo8("<font class='associado'>Associados</font> e <font class='externo'>Externos</font>"); ?></th>
		</thead>
		<?php 
		$actual_link = urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
		while($row = $result->fetch_assoc()): 
		?>
		<tr>
			<?php 
			$sql = "SELECT id, nome, professor_externo FROM usuarios WHERE lider_id = %d AND categoria_id = 1 AND ativo = 1";	//apenas professores
			$result_associados = query($sql,$row['id']);
			//se tiver associados
			if($result_associados->num_rows) :
			?>
				<td rowspan="<?php echo $result_associados->num_rows; ?>" style="vertical-align: middle;">
				<?php
				$manage_link = MANAGE_LINK;
				$manage_link = add_param($manage_link,"id",$row['id']);
				$manage_link = add_param($manage_link,"prev",$actual_link);
				echo "<a href=\"".$manage_link."\">".$row['nome']."</a>";
				?>
				</td>
			<?php
				$first_one = 1;
				while($row_associado = $result_associados->fetch_assoc()) :
					if($first_one) :
						$first_one = 0;
			?>
				<td><?php
				$manage_link = MANAGE_LINK;
				$manage_link = add_param($manage_link,"id",$row_associado['id']);
				$manage_link = add_param($manage_link,"prev",$actual_link);
				$class = $row_associado['professor_externo'] ? "externo" : "associado";
				echo "<a href=\"".$manage_link."\" class='$class'>".$row_associado['nome']."</a>";
				?></td>
		</tr>
					<?php
					else :
					?>
		<tr>
				<td><?php
				$manage_link = MANAGE_LINK;
				$manage_link = add_param($manage_link,"id",$row_associado['id']);
				$manage_link = add_param($manage_link,"prev",$actual_link);
				$class = $row_associado['professor_externo'] ? "externo" : "associado";
				echo "<a href=\"".$manage_link."\" class='$class'>".$row_associado['nome']."</a>";
				?></td>
		</tr>
					<?php
					endif;
				endwhile;
			//não tem nenhum associado
			else :
			?>
				<td>
				<?php
				$manage_link = MANAGE_LINK;
				$manage_link = add_param($manage_link,"id",$row['id']);
				$manage_link = add_param($manage_link,"prev",$actual_link);
				echo "<a href=\"".$manage_link."\">".$row['nome']."</a>";
				?>
				</td>
				
				<td><?php
				echo8("Nenhum professor associado a este líder");
				?></td>
		</tr>				
			<?php
			endif;
		endwhile; ?>
	  </table>
	</div>
	<?php endif; 
	if($rec_count == 0) : 
		display_message("Não foram encontrados resultados para sua pesquisa",2);
	endif;
	?>
	</div> <!-- /container -->
	</div> <!-- /wrap -->
	<?php include("footer.php"); ?>	
	</body>
</html>
