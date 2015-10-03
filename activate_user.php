<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Página que ativa / desativa usuários.
 *
 *	Observação: Variavel $_GET['action'] define qual o procedimento de solução
 *
**/

//action=1	ativa usuario
//action=2	desativa usuario

$page_access_level = 1;						//Administração
require("valida_session.php");

$id = $_GET['id'];								// id do usuario a ser ativado
$action = $_GET['action'];						// ação a ser realizada
$previous_page = urldecode($_GET['prev']);		// página de retorno

// Criamos objeto do tipo pendencia
$user = new User("");

if ($user->GetDataFromDB($id) == 0) {
	if($action == 1) {
		$user->ativo = 1;
		$user->UpdateUserData();
		//display_message(utf8_decode("Usuário ativado com sucesso!"),1);
	} elseif($action == 2) {
		$user->ativo = 0;
		$user->UpdateUserData();
		//display_message(utf8_decode("Usuário desativado com sucesso!"),1);
	} 
}

//redireciona para página inicial do admin
header("Location: $previous_page");

?>
