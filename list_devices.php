<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Página para uso da equipe de TI, lista todos os dispositivos por usuário
 *
 *	Observação: Possibilidade de imprimi resultado
 *
 *				CSS para impressão: 	noprint = não sera impresso
 *										toprint = sera impresso
 *										* Tudo deve ser declarado dentro dos divs (wrap e content)
 *										** O cabecalho e rodapé são noprint por default
 *
**/

$page_access_level = 1;						//Administração
require("valida_session.php");

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
	$link = "teachers_management.php";
	
	//coletar parametros da pesquisa,
	$sql_params = " id > 0 AND ativo = 1";
	
	// Pegar número total de registros
	$sql = "SELECT count(id) FROM dispositivos WHERE 1";
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
	$sql = "SELECT id, nome FROM usuarios WHERE ".$sql_params;

	$result = query($sql);
	if(! $result ) {die('Could not get data: ' . mysqli_error()); }
	
	?>
	
	<div class="row noprint" style="padding-bottom: 5px;">
		<div class="col-md-4">
			<strong><?php echo "Registros: ".$rec_count; ?></strong>		
		</div>
		<div class="col-md-4"></div>
		<div class="col-md-4">
			<a href="javascript:window.print()" class="btn btn-primary btn-block" alt="<?php echo8("Imprimir"); ?>">
				<span class="glyphicon glyphicon-print"></span> <?php echo8("Imprimir"); ?>
			</a>
		</div>
	</div>
	<div class="panel panel-default noprint">
	  <!-- Table -->
	  <table class="table">
		<thead>
			<th><?php echo8("Usuário"); ?></th>
			<th><?php echo8("Tipo"); ?></th>
			<th><?php echo8("IP"); ?></th>
			<th><?php echo8("MAC"); ?></th>
			<th><?php echo8("Patrimônio"); ?></th>
			<th><?php echo8("Hostname"); ?></th>
			<th><?php echo8("Local"); ?></th>
		</thead>
		<?php 
		$user = new User("");
		$actual_link = urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
		while($row = $result->fetch_assoc()): 
			$user->GetDataFromDB($row['id']);
			// se tiver dispositivos
			if(count($user->dispositivos)) :
		?>
		<tr>
				<td rowspan="<?php echo count($user->dispositivos); ?>" style="vertical-align: middle;">
				<?php
				$manage_link = VIEW_LINK;
				$manage_link = add_param($manage_link,"id",$row['id']);
				$manage_link = add_param($manage_link,"prev",$actual_link);
				echo "<a href=\"".$manage_link."\">".$user->nome."</a>";
				?>
				</td>
			<?php
				$first_one = 1;
				foreach($user->dispositivos as $dispositivo) :
					if($first_one) :
						$first_one = 0;
			?>
				<td><?php echo $dispositivo->tipo_dispositivo; ?></td>
				<td><?php echo $dispositivo->ip; ?></td>
				<td><?php echo $dispositivo->endereco_mac; ?></td>
				<td><?php echo $dispositivo->patrimonio; ?></td>
				<td><?php echo $dispositivo->hostname; ?></td>
				<td><?php echo $dispositivo->localizacao; ?></td>
		</tr>
					<?php
					else :
					?>
		<tr>
				<td><?php echo $dispositivo->tipo_dispositivo; ?></td>
				<td><?php echo $dispositivo->ip; ?></td>
				<td><?php echo $dispositivo->endereco_mac; ?></td>
				<td><?php echo $dispositivo->patrimonio; ?></td>
				<td><?php echo $dispositivo->hostname; ?></td>
				<td><?php echo $dispositivo->localizacao; ?></td>
		</tr>
					<?php
					endif;
				endforeach;	
			endif;
		endwhile; ?>
	  </table>
	</div>
	
	<!-- A parte abaixo só sera vista na impressão -------------------------------------------------------->
			<?php 
			//pesquisa conforme parametros especificados
			$sql = "SELECT id, nome FROM usuarios WHERE ".$sql_params;

			$result = query($sql);
			if(! $result ) {die('Could not get data: ' . mysqli_error()); }
			
			?>
			<div class="panel panel-info toprint">
				<div class="panel-heading">
					<h3 class="panel-title " style="text-align: center; font-weight: bold;">
					<?php echo8("UNIVERSIDADE FEDERAL DO RIO GRANDE DO SUL <br> Centro de Biotecnologia<br><br><br> RELAÇÃO DE DISPOSITIVOS");?></h3>
				</div>
			</div> <!-- /panel -->
			<div class="panel panel-default toprint">
			  <!-- Table -->
			  <table class="table table-bordered">
				<thead>
					<th><?php echo8("Usuário"); ?></th>
					<th><?php echo8("Tipo"); ?></th>
					<th><?php echo8("IP"); ?></th>
					<th><?php echo8("Endereço MAC"); ?></th>
					<th><?php echo8("Patrimônio"); ?></th>
					<th><?php echo8("Hostname"); ?></th>
					<th><?php echo8("Local"); ?></th>
				</thead>
				<?php 
				$user = new User("");
				while($row = $result->fetch_assoc()): 
					$user->GetDataFromDB($row['id']);
					// se tiver dispositivos
					if(count($user->dispositivos)) :
				?>
				<tr>
						<td rowspan="<?php echo count($user->dispositivos); ?>" style="vertical-align: middle;">
						<?php
						echo $user->nome;
						?>
						</td>
					<?php
						$first_one = 1;
						foreach($user->dispositivos as $dispositivo) :
							if($first_one) :
								$first_one = 0;
					?>
						<td><?php echo $dispositivo->tipo_dispositivo; ?></td>
						<td><?php echo $dispositivo->ip; ?></td>
						<td><?php echo $dispositivo->endereco_mac; ?></td>
						<td><?php echo $dispositivo->patrimonio; ?></td>
						<td><?php echo $dispositivo->hostname; ?></td>
						<td><?php echo $dispositivo->localizacao; ?></td>
				</tr>
							<?php
							else :
							?>
				<tr>
						<td><?php echo $dispositivo->tipo_dispositivo; ?></td>
						<td><?php echo $dispositivo->ip; ?></td>
						<td><?php echo $dispositivo->endereco_mac; ?></td>
						<td><?php echo $dispositivo->patrimonio; ?></td>
						<td><?php echo $dispositivo->hostname; ?></td>
						<td><?php echo $dispositivo->localizacao; ?></td>
				</tr>
							<?php
							endif;
						endforeach;	
					endif;
				endwhile; ?>
			  </table>
			</div>
		<!-- A parte acima só sera vista na impressão -------------------------------------------------------->
	
	
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
