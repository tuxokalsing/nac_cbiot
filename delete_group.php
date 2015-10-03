<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Página que remove grupo do banco de dados se possível.
 *
 *	Observação:	Todos os grupos que são desnviculados do grupo.
 *
**/

$page_access_level = 1;						//Administração
require("valida_session.php");

$id = $_GET['id'];								// id do grupo a ser editado
$previous_page = urldecode($_GET['prev']);		// página de retorno

// Criamos objeto do tipo grupo
$group = new Group("","");
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
	if ($group->GetDataFromDB($id) == 0) {
		if ($group->ForceRemoveThisGroup() == 0) {
			display_message("Grupo removido com sucesso!",1);
		} // else função de remoção mostra mensagem
	} // else grupo não encontrado
?>
		<meta http-equiv="refresh" content="4;url=<?php echo $previous_page ?>">
		</div> <!-- /container -->
	</div> <!-- /wrap -->
	<?php include("footer.php"); ?>	
</body>
</html>