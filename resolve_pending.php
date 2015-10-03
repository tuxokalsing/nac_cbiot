<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Página que resolve pendencias do banco de dados.
 *
 *	Observação: Variavel $_GET['action'] define qual o procedimento de solução
 *
**/

//action=0	apenas resolve a pendencia
//action=1	exclui a pendencia
//action=2	aceita o usuário
//action=3	rejeita o usuário
//action=4	renova período de expiração
//action=5	reativa usuário
//action=6	renova período de expiração


$page_access_level = 1;						//Administração
require("valida_session.php");

$id = $_GET['id'];								// id da pendencia a ser editado
$action = $_GET['action'];						// ação a ser realizada na pendencia
$previous_page = urldecode($_GET['prev']);		// página de retorno
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

// Criamos objeto do tipo pendencia
$pending = new Pending("","","","");

if ($pending->GetDataFromDB($id) == 0) {
	if($action == 0) {
		$pending->MarkAsResolved();
		display_message("Pendência resolvida com sucesso!",1);
	} elseif($action == 1) {
		$pending->RemoveThisPending();
		display_message("Pendência removida com sucesso!",1);
	} elseif($action == 2) {
		// ativa usuário e resolve pendencia
		$user = new User("");
		$user->GetDataFromDB($pending->tabela_id);
		$user->ativo = 1;
		$user->UpdateUserData();
		$pending->MarkAsResolved();
		display_message("Usuário ativado com sucesso!",1);
	} elseif($action == 3) {
		// exclui cadastro do usuário e resolve pendencia
		$user = new User("");
		$user->GetDataFromDB($pending->tabela_id);
		$user->RemoveThisUser();
		$pending->MarkAsResolved();
		display_message("Usuário removido com sucesso!",1);
	} elseif($action == 4) {
		// atualiza data de expiração e resolve pendencia
		$user = new User("");
		$user->GetDataFromDB($pending->tabela_id);
		// Calcula data de expiracao
		$user->categoria = new Category(NULL,NULL,NULL);
		$user->categoria->GetDataFromDB($user->categoria_id);
		if($user->categoria->periodo_expiracao) {
			$tmp = $user->categoria->periodo_expiracao;
			$user->data_expiracao = date('d/m/Y', 
								strtotime("+ $tmp months", 
								strtotime(date("d-m-Y"))));
		}
		$user->UpdateUserExpirationDate($user->data_expiracao);
		$user->ativo = 1;
		$user->UpdateUserData();
		$pending->MarkAsResolved();
		display_message("Data de expiração atualizada com sucesso!",1);
	} elseif($action == 5) {
		// atualiza data de expiração, ativa usuário e resolve pendencia
		$user = new User("");
		$user->GetDataFromDB($pending->tabela_id);
		// Calcula data de expiracao
		$user->categoria = new Category(NULL,NULL,NULL);
		$user->categoria->GetDataFromDB($user->categoria_id);
		if($user->categoria->periodo_expiracao) {
			$tmp = $user->categoria->periodo_expiracao;
			$user->data_expiracao = date('d/m/Y', 
								strtotime("+ $tmp months", 
								strtotime(date("d-m-Y"))));
		}
		$user->UpdateUserExpirationDate($user->data_expiracao);
		$user->ativo = 1;
		$user->UpdateUserData();
		$pending->MarkAsResolved();
		display_message("Usuário reativado com sucesso!",1);
	} elseif($action == 6) {
		// Não reativa o usuário
		$pending->MarkAsResolved();
		display_message("Pendência resolvida, o usuário não foi ativado.",1);
	}
}
	
?>
		<meta http-equiv="refresh" content="1;url=<?php echo $previous_page ?>">
		</div> <!-- /container -->
	</div> <!-- /wrap -->
	<?php include("footer.php"); ?>	
</body>
</html>