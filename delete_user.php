<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Página que remove usuario do banco de dados se possível.
 *
 *	Observação:	Página sera usada raramente, atenção a todas as dependencias
 *				Líderes e orientadores com alunos nao podem ser deletados se 
 *				estiverem com alunos linkados a eles.
 *
**/

$page_access_level = 1;						//Administração
require("valida_session.php");

$id = $_GET['id'];								// id do usuario a ser editado
$previous_page = urldecode($_GET['prev']);		// página de retorno

// Criamos objeto do tipo usuario
$user = new User("");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br">

<head>
	<meta name="keywords" content="" />
	<meta name="description" content="Sistema CBIOT - Usuário" />
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
	if ($user->GetDataFromDB($id) == 0) {
		if ($user->RemoveThisUser() == 0) {
			display_message("Usuário removido com sucesso!",1);
		} // else função de remoção mostra mensagem
	} // else usuario não encontrado
?>
		<meta http-equiv="refresh" content="4;url=<?php echo $previous_page ?>">
		</div> <!-- /container -->
	</div> <!-- /wrap -->
	<?php include("footer.php"); ?>	
</body>
</html>