<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Página que lista contatos publicos dos usuários de acordo com termos pesquisados.
 *
 *	Observação:	Apenas para visualização
 *
**/

$page_access_level = 5;						//Administração, Professores e Outros
require("valida_session.php");

$id = $_SESSION["user_id"];						// id do usuario acessando o sistema
$access_level = $access_level;					// nivel de acesso do usuario acessando o sistema (vem de valida_session)

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
	$link = "contacts.php";
									
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
				default: ;
			}
			$link = add_param($link,"param",$_GET['param']);
			$link = add_param($link,"tipo",$_GET['tipo']);
		}
	}

	//atividade
	if(isset($_GET["ati"])) {
		$tmp = $_GET["ati"];
		$sql_params .= " AND ativo = $tmp";
		$link = add_param($link,"ati",$tmp);
	} else {
		$sql_params .= " AND ativo = 1";			// por padrão mostra apenas ativos
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
	$sql = "SELECT id, nome FROM usuarios WHERE ".$sql_params.
		   " LIMIT $offset, $rec_limit";
	$result = query($sql);
	if(! $result ) {die('Could not get data: ' . mysqli_error()); }
	
	?>
	
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
			  if(isset($_GET['param'])) {
				echo " value=\"".$_GET['param']."\"";
			  }
			  ?> required>
		</div>
		<div class="col-md-2">
			<select class="form-control" name="tipo" id="tipo">
				<option value="0">Nome</option>
				<option value="1"><?php echo8("Matrícula");?></option>
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
			<th><?php 	$orderlink = "";
						if($orderby == 1) {
							$orderlink = add_param($link,"ord",2);
						} else {
							$orderlink = add_param($link,"ord",1);
						}
						echo "<a title='Ordenar por Nome' href=\"".add_param($orderlink,"page",$page)."\">";?>Nome</a></th>
			<th style="text-align: center;">Orientador</th>
			<th style="text-align: center;">Grupos</th>
			<th style="text-align: center;">Contatos</th>
		</thead>
		<?php 
		$user = new User("");
		while($row = $result->fetch_assoc()): ?>
		<tr>
			<?php
			$user->GetDataFromDB($row['id']);
			$actual_link = urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
			$actual_link = urlencode($link);
			$view_link = VIEW_LINK;
			$view_link = add_param($view_link,"id",$row['id']);
			$view_link = add_param($view_link,"prev",$actual_link);
			// apenas administração tem direito ao view
			if($access_level != 1){
				$view_link = "#";
			}
			?>
			<td style="vertical-align: middle;"><a title='Ver Perfil' href="<?php echo $view_link; ?>" target="_self" style="color:black;"><?php echo $user->nome; ?></a></td>
			<td style="text-align: center; vertical-align: middle;"><?php echo $user->orientador; ?></td>
			<td style="text-align: center; vertical-align: middle;"><?php 
													$i = 0;
													foreach($user->grupos as $grupo) {
														echo $grupo->acronimo." ";
														if($i%2) {
															echo "<br>";
														}
														$i++;
													}
													if(count($user->grupos) == 0) {
														echo "Nenhum grupo cadastrado";
													}
											?>
			</td>
			<td style="text-align: center; vertical-align: middle;"><?php foreach($user->contatos as $contato) {
														if($contato->tipos_contato_id != 3) {
															echo $contato->tipo_contato.": ".$contato->contato."<br>";
														}
													}
													if(count($user->contatos) == 0) {
														echo "Nenhum contato cadastrado";
													}
											?>
			</td>
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
</html>
