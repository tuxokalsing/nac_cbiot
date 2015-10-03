<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Página para visualização de dados e relatórios por grupo.
 *
 *	Observação: A página recebe o ID do grupo e mostra dados relacionados a aquele grupo;
 *
**/

$page_access_level = 2;							// Administração e Professores
require("valida_session.php");

$id = $_GET['id_grupo'];								// id do usuario a ser editado
$previous_page = urldecode($_GET['prev']);		// página de retorno

define("VIEW_LINK","view_user.php");			//página de visualização de cadastros

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
			$grupo = new Group(NULL,NULL);
			$erro = $grupo->GetDataFromDB($id);

			if($erro == 0) : 
			?>
			<div class="row noprint" style="padding-bottom: 20px;">
				<div class="col-md-3">
					<a href="<?php echo "$previous_page"; ?>" class="btn btn-primary btn-block" alt="<?php echo8("Voltar"); ?>">
						<span class="glyphicon glyphicon-arrow-left"></span> <?php echo8("Voltar"); ?>
					</a>
				</div>
				<div class="col-md-3"></div>
				<div class="col-md-3"></div>
				<div class="col-md-3">
					<a href="get_email_list_cvs.php?id=<?php echo $grupo->id; ?>" class="btn btn-primary btn-block" alt="<?php echo8("Copiar lista de emails"); ?>">
						<span class="glyphicon glyphicon-download-alt"></span> <?php echo8("Copiar lista de emails"); ?>
					</a>
				</div>
			</div>
			
			<h3>Grupo: <?php echo $grupo->grupo." (".$grupo->acronimo.")"; ?> </h3>
			
			<?php
				// select all users from group
				$sql = "SELECT * FROM grupos_usuario WHERE grupo_id = %d";
				$result = query($sql,$grupo->id);
				if(! $result ) {die('Could not get data: ' . mysqli_error()); }
				
			?>
			
			<div class="panel panel-info">
			  <div class="panel-heading">
				<h3 class="panel-title">Participantes do grupo:</h3>	
			  </div>
			  <!-- Table -->
			  <table class="table table-striped table-hover">
				<thead>
					<th>Nome</th>
					<th style="text-align: center;">Categoria</th>
					<th style="text-align: center;">Emails</th>
				</thead>
				<?php 
				$user = new User("");
				while($row = $result->fetch_assoc()): ?>
					<?php
					$user->GetDataFromDB($row['usuarios_id']);
					$actual_link = urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
					$view_link = VIEW_LINK;
					$view_link = add_param($view_link,"id",$user->id);
					$view_link = add_param($view_link,"prev",$actual_link);
					// apenas administração tem direito ao view
					if($access_level != 1) {
						$view_link = "#";
					}
					if($user->ativo) :
					?>
				<tr>
					<td style="vertical-align: middle;"><a title='Ver Perfil' href="<?php echo $view_link; ?>" target="_self" style="color:black;"><?php echo $user->nome; ?></a></td>
					<td style="text-align: center; vertical-align: middle;"><?php echo $user->categoria; ?></td>
					<td style="text-align: center; vertical-align: middle;"><?php 
															foreach($user->contatos as $contato) {
																if($contato->tipos_contato_id == 4 OR $contato->tipos_contato_id == 5) {
																	echo $contato->contato."<br>";
																}
															}
															if(count($user->contatos) == 0) {
																echo "Nenhum contato cadastrado";
															}
													?>
					</td>
				</tr>
				<?php endif;
				endwhile; ?>
			  </table>
			</div>
		<?php endif; ?>
		</div> <!-- /container -->
	</div> <!-- /wrap -->	
	<?php include("footer.php"); ?>	
</body>
</html>