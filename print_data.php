<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Página que pega uma sql e transforma em tabela para impressão
 *
 *	Observação:	
 *
**/


$page_access_level = 1;						//Administração
require("valida_session.php");

$sql = urldecode($_GET['sql']);				//sql para impressão
$title = urldecode($_GET['title']);			//Título da página

$sqlresult = $db->query($sql) ; 

if($sqlresult) :

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

<body class="toprint" onload="javascript:window.print()">
	<div class="panel panel-info toprint">
		<div class="panel-heading">
			<h3 class="panel-title " style="text-align: center; font-weight: bold;">
			<?php echo8("UNIVERSIDADE FEDERAL DO RIO GRANDE DO SUL <br> Centro de Biotecnologia<br><br><br> $title");?></h3>
		</div>
</div> <!-- /panel -->
	<?php echo sql_to_html_table( $sqlresult, $delim="\n" ); ?>
</body>
</html>

<?php 
else :
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
			<div class="alert alert-danger" style="text-align: center; font-weight: bold;">
				<?php echo8("Não é possível imprimir a tabela desejada. Requisição inválida."); ?>
			</div>
		</div> <!-- /container -->
	</div> <!-- /wrap -->
	<?php include("footer.php"); ?>	
</body>
</html>

<?php 
endif;
?>
