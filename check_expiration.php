<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Esta página é rodada toda a vez que alguem da administração faz login no sistema
 *				ela realiza uma busca no banco de dados por usuários que estão com data de expiração
 *				próxima a atual e gera notificações (pendencias).
 *
 *	Observação: 1 - Gera pendencias para expirações próximas.
 *				2 - Inativa usuários com data de pendencia vencida.
 *
**/
$page_access_level = 1;						//Administração
require("valida_session.php");

//consulta ao bd para verificar se existem usuarios que vão expirar no proximo mês:
$sql = "SELECT id, nome, data_expiracao FROM usuarios WHERE data_expiracao BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 1 MONTH)";
$result = query($sql);

$user = new User("");

if($result->num_rows > 0) { 	//encontrou resultados

	// para cada resultado devemos pesquisar se já não existe uma notificação com mesmo id, nome, e data da pendencia no ultimo mes;
	while($row = $result->fetch_assoc()) {

		$sql = "SELECT * FROM pendencias WHERE tabela = %d AND tabela_id = %d AND motivo = %d AND informacao = '%s' AND data BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 1 MONTH)";
		$check = query($sql,USUARIOS,$row['id'],EXPIRACAO,"Nome: ".$row['nome']." - Expira em: ".date_conv($row['data_expiracao']));
		if($check->num_rows == 0) { 	// não encontrou nada então criamos a notificação, manda um email para usuário avisando
			$pending = new Pending(USUARIOS, $row['id'], EXPIRACAO, "Nome: ".$row['nome']." - Expira em: ".date_conv($row['data_expiracao']));
			$pending->SignThisPending();
			
			$user->GetDataFromDB($row['id']);
			$nome = $user->nome;
			
			// Manda email para o usuario informando sua senha de recuperação
$assunto = "Mensagem automatica: Seu cadastro no Cbiot esta prestes a expirar";
$mensagem = "Olá $nome,
Seu cadastro no sistema do Cbiot está prestes a expirar, isso significa que você
perderá seus acessos as portas, emails e permissões de rede automaticamente.
Caso você continue no instituto, por favor informe a administração.
Caso contrário, você pode ignorar este email.
												
Mensagem automática do sistema, não responda.";
$assunto = utf8_decode($assunto);
$mensagem = utf8_decode($mensagem);
			foreach($user->contatos as $contato) {
				// se for um email
				if($contato->tipos_contato_id == 4 OR $contato->tipos_contato_id == 5) {
					SendMail($contato->contato, $assunto, $mensagem);
				}
			}
		}
	}
}

// selecionar todos os usuarios que serão desativados
$sql = "SELECT * FROM usuarios WHERE data_expiracao < NOW() AND data_expiracao != '00-00-0000' AND ativo = 1";
$result = query($sql);

// para cada usuário encontrado
while($row = $result->fetch_assoc()) {
	// remover todos os seus dispositivos
	$user->GetDataFromDB($row['id']);
	foreach($user->dispositivos as $dispositivo) {
		$user->RemoveDevice($dispositivo);
	}
}

//inativação de todos os usuários com data de validade vencida
$sql = "UPDATE usuarios SET ativo = 0 WHERE data_expiracao < NOW() AND data_expiracao != '00-00-0000' AND ativo = 1";
$result = query($sql);

//redireciona para página inicial do admin
header("Location: dashboard_admin.php");

?>