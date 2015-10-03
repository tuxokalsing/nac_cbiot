<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Página para visualização de dados.
 *
 *	Observação: A página recebe o ID do usuário e mostra página dependendo da categoria
 *				4 Layouts diferentes de visualização dependendo da categoria
 *					- (1) Professor: mostra dotods os seus associados;
 *					- (2) Alunos: mostra orientador e líder;
 *					- (3) Funcionarios: mostra o cargo;
 *					- (4) Visitantes: mostra o cargo;
 *
 *				CSS para impressão: 	noprint = não sera impresso
 *										toprint = sera impresso
 *										* Tudo deve ser declarado dentro dos divs (wrap e content)
 *										** O cabecalho e rodapé são noprint por default
 *
**/

$page_access_level = 2;						//Administração e Professores
require("valida_session.php");

$id = $_GET['id'];								// id do usuario a ser editado
$previous_page = urldecode($_GET['prev']);		// página de retorno

/**
 *	CATEGORIAS:
 *	0 - Indefinido
 *	1 - Professor
 *	2 - Estagiário
 *	3 - Estudante de Graduação
 *	4 - Estudate de Mestrado
 *	5 - Estudante de Doutorado
 *	6 - Pós-Doutorando
 * 	7 - Funcionário Terceirizado
 *	8 - Funcionário Servidor
 *	9 - Funcionário de Empresa Incubada
 *	10 - Visitante
 *	11 - Outros Vinculos (especificar nas observações)
 *
**/

# Definições de grupos para os formulários
define("PROFESSORES",1);
define("ALUNOS",2);
define("FUNCIONARIOS",3);
define("VISITANTES",4);

define("EDIT_LINK","edit_user.php");					//página de edição de cadastros
define("VIEW_LINK","view_user.php");					//página de visualização de cadastros
define("ACTIVATE_LINK","activate_user.php?action=1");	//página de ativação/desativação de registros
define("DEACTIVATE_LINK","activate_user.php?action=2");	//página de ativação/desativação de registros


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
			$user = new User("");
			$erro = $user->GetDataFromDB($id);

			$cat = 0;	// categoria
			if($erro == 0) {
				switch($user->categoria_id){
					case 0:
					case 1: $cat = PROFESSORES;
							break;
					case 2:
					case 3:
					case 4:
					case 5:
					case 6: $cat = ALUNOS;
							break;
					case 7:
					case 8:
					case 9: $cat = FUNCIONARIOS;
							break;
					case 10: $cat = VISITANTES;
							break;
					case 11: $cat = VISITANTES;
							break;
					default: $cat = PROFESSORES;
				}
			}
			if($erro == 0) : 
			?>
			<div class="row noprint" style="padding-bottom: 20px;">
				<?php if($access_level == 1) : ?>
				<div class="col-md-3">
					<a href="<?php echo $previous_page ?>" class="btn btn-primary btn-block" alt="<?php echo8("Voltar"); ?>">
						<span class="glyphicon glyphicon-arrow-left"></span> <?php echo8("Voltar"); ?>
					</a>
				</div>
				<div class="col-md-3">
					<?php
					$actual_link = urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
					$edit_link = EDIT_LINK;
					$edit_link = add_param($edit_link,"id",$user->id);
					$edit_link = add_param($edit_link,"prev",$actual_link);
					?>				
					<a href="<?php echo $edit_link ?>" class="btn btn-primary btn-block" alt="<?php echo8("Editar Dados"); ?>">
						<span class="glyphicon glyphicon-edit"></span> <?php echo8("Editar Dados"); ?>
					</a>
				</div>
				<div class="col-md-3">
					<?php if($user->ativo == 0) : 
					$activate_link = ACTIVATE_LINK;
					$activate_link = add_param($activate_link,"id",$user->id);
					$activate_link = add_param($activate_link,"prev",$actual_link); ?>
					<a href="<?php echo $activate_link; ?>" class="btn btn-primary btn-block" alt="<?php echo8("Ativar Usuário"); ?>">
						<span class="glyphicon glyphicon-ok"></span> <?php echo8("Ativar Usuário"); ?>
					</a>
					<?php else : 
					$deactivate_link = DEACTIVATE_LINK;
					$deactivate_link = add_param($deactivate_link,"id",$user->id);
					$deactivate_link = add_param($deactivate_link,"prev",$actual_link); ?>
					<a href="<?php echo $deactivate_link; ?>" class="btn btn-primary btn-block" alt="<?php echo8("Desativar Usuário"); ?>">
						<span class="glyphicon glyphicon-ban-circle"></span> <?php echo8("Desativar Usuário"); ?>
					</a>
					<?php endif; ?>
				</div>
				<div class="col-md-3">
					<a href="javascript:window.print()" class="btn btn-primary btn-block" alt="<?php echo8("Imprimir"); ?>">
						<span class="glyphicon glyphicon-print"></span> <?php echo8("Imprimir"); ?>
					</a>
				</div>
				<?php else : ?>
				<div class="col-md-4">
					<a href="<?php echo $previous_page ?>" class="btn btn-primary btn-block" alt="<?php echo8("Voltar"); ?>">
						<span class="glyphicon glyphicon-arrow-left"></span> <?php echo8("Voltar"); ?>
					</a>
				</div>
				<div class="col-md-4">
					<a href="dashboard_prof.php" class="btn btn-primary btn-block" alt="<?php echo8("Voltar para Página Inicial"); ?>">
						<span class="glyphicon glyphicon-home"></span> <?php echo8("Voltar para Página Inicial"); ?>
					</a>
				</div>
				<div class="col-md-4">
					<a href="javascript:window.print()" class="btn btn-primary btn-block" alt="<?php echo8("Imprimir"); ?>">
						<span class="glyphicon glyphicon-print"></span> <?php echo8("Imprimir"); ?>
					</a>
				</div>				
				<?php endif; ?>
			</div>
			<div class="panel panel-info noprint">
				<div class="panel-heading">
					<h3 class="panel-title">Dados Pessoais</h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<label for="input_nome" class="col-sm-1 control-label col-form-cbiot">Nome:</label>
						<div class="col-sm-11">
							<p><?php echo $user->nome; ?></p>
						</div>
					</div>							
					<div class="row">
						<label for="input_sexo" class="col-sm-1 control-label col-form-cbiot">Sexo:</label>
						<div class="col-sm-2">		  
							<p><?php if($user->sexo == 'f' || $user->sexo == 'F') {
											echo "Feminino";
										} else {
											echo "Masculino";
										}
							?></p>
						</div>
						<label for="input_nascimento" class="col-sm-2 control-label col-form-cbiot">Data Nascimento:</label>
						<div class="col-sm-2">		  
							<p><?php echo $user->nascimento;?></p>
						</div>
						<label for="input_estado_civil" class="col-sm-2 control-label col-form-cbiot">Estado Civil:</label>
						<div class="col-sm-3">		  
							<p><?php echo $user->estado_civil;?></p>
						</div>
					</div> <!-- /row -->
					<div class="row">
						<label for="input_rg" class="col-sm-1 control-label col-form-cbiot">RG:</label>
						<div class="col-sm-2">		  
							<p><?php echo $user->rg;?></p>
						</div>
						<label for="input_emissao_rg" class="col-sm-2 control-label col-form-cbiot">Data e Emissor:</label>
						<div class="col-sm-1">		  
							<p><?php echo $user->data_rg;?></p>
						</div>
						<div class="col-sm-1">		  
							<p><?php echo $user->emissor_rg;?></p>
						</div>
						<label for="input_cpf" class="col-sm-2 control-label col-form-cbiot">CPF:</label>
						<div class="col-sm-3">		  
							<p><?php echo $user->cpf;?></p>
						</div>
					</div> <!-- /row -->
					<div class="row">
						<label for="input_nome_pai" class="col-sm-1 control-label col-form-cbiot">Nome Pai:</label>
						<div class="col-sm-11">		  
							<p><?php echo $user->nome_pai;?></p>
						</div>
					</div>
					<div class="row">
						<label for="input_nome" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Nome Mãe");?>:</label>
						<div class="col-sm-11">		  
							<p><?php echo $user->nome_mae;?></p>
						</div>
					</div> <!-- /row -->
					<hr>
					<div class="row">
						<label for="input_escolaridade" class="col-sm-1 control-label col-form-cbiot">Escolaridade:</label>
						<div class="col-sm-2">		  
							<p><?php echo $user->escolaridade;?></p>
						</div>
						<label for="input_matricula" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Nro. Cartão");?>:</label>
						<div class="col-sm-2">		  
							<p><?php echo $user->matricula;?></p>
						</div>
					</div> <!-- /row -->
					<div class="row">
						<label for="input_instituicao" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Instituição");?>:</label>
						<div class="col-sm-4">		  
							<p><?php echo $user->instituicao;?></p>
						</div>
					</div> <!-- /row -->
				</div> <!-- /panel-body -->
			</div> <!-- /panel -->
			<div class="panel panel-info noprint">
				<div class="panel-heading">
					<h3 class="panel-title"><?php echo8("Endereço");?></h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<label for="input_logradouro" class="col-sm-1 control-label col-form-cbiot">Logradouro:</label>
						<div class="col-sm-7">		  
							<p><?php echo $user->endereco->logradouro;?></p>
						</div>
						<label for="input_numero" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Número");?>:</label>
						<div class="col-sm-1">		  
							<p><?php echo $user->endereco->numero;?></p>
						</div>
						<label for="input_complemento" class="col-sm-1 control-label col-form-cbiot">Comp.:</label>
						<div class="col-sm-1" style="padding-left: 5px">		  
							<p><?php echo $user->endereco->complemento;?></p>
						</div>
					</div> <!-- /row -->
					<div class="row">
						<label for="input_bairro" class="col-sm-1 control-label col-form-cbiot">Bairro:</label>
						<div class="col-sm-7">		  
							<p><?php echo $user->endereco->bairro;?></p>
						</div>
						<label for="input_cep" class="col-sm-1 control-label col-form-cbiot">CEP:</label>
						<div class="col-sm-3 col-form-cbiot" style="padding-right: 15px">		  
							<p><?php echo $user->endereco->cep;?></p>
						</div>
					</div> <!-- /row -->
					<div class="row">
						<label for="input_municipio" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Município");?>:</label>
						<div class="col-sm-7">		  
							<p><?php echo $user->endereco->municipio;?></p>
						</div>
						<label for="input_pais" class="col-sm-1 control-label col-form-cbiot"><?php echo8("País");?>:</label>
						<div class="col-sm-3 col-form-cbiot" style="padding-right: 15px">		  
							<p><?php echo $user->endereco->pais;?></p>
						</div>
					</div> <!-- /row -->
				</div> <!-- /panel body -->
			</div> <!-- /panel -->
			<?php if(count($user->contatos) > 0) : ?>
			<div class="panel panel-info noprint">
				<div class="panel-heading">
					<h3 class="panel-title">Contatos</h3>
				</div>
				<div class="panel-body" id="contacts_container">
					<?php foreach($user->contatos as $contato) : ?>
					<div class="row">
						<label for="input_tipo_contato" class="col-sm-2 control-label col-form-cbiot"><?php echo $contato->tipo_contato; ?>:</label>
						<div class="col-sm-4">		  
							<p><?php echo8($contato->contato);?></p>
						</div>
					</div>
					<?php endforeach; ?>
			   </div> <!-- /panel body -->
			</div> <!-- /panel -->
			<?php endif; ?>
			<?php if(count($user->dispositivos) > 0) : ?>
			<div class="panel panel-info noprint">
				<div class="panel-heading">
					<h3 class="panel-title">Dispositivos</h3>
				</div>
				<div class="panel-body" id="devices_container">
					<?php foreach($user->dispositivos as $dispositivo) : ?>
					<div class="row">
						<label for="input_tipo_dispositivo" class="col-sm-3 control-label col-form-cbiot"><?php echo $dispositivo->tipo_dispositivo; ?>:</label>
						<div class="col-sm-3">		  
							<p><?php echo8("<b>IP:</b> ".$dispositivo->ip);?></p>
						</div>
						<div class="col-sm-3">		  
							<p><?php echo8("<b>MAC:</b> ".$dispositivo->endereco_mac);?></p>
						</div>
						<div class="col-sm-3">		  
							<p><?php echo8("<b>Patrimônio:</b> ".$dispositivo->patrimonio);?></p>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-3"></div>
						<div class="col-sm-3">		  
							<p><?php echo8("<b>Hostname:</b> ".$dispositivo->hostname);?></p>
						</div>
						<div class="col-sm-6">		  
							<p><?php echo8("<b>Local:</b> ".$dispositivo->localizacao);?></p>
						</div>
					</div>
					<?php endforeach; ?>
			   </div> <!-- /panel body -->
			</div> <!-- /panel -->
			<?php endif; ?>
			<div class="panel panel-info noprint">
				<div class="panel-heading">
					<h3 class="panel-title">Dados CBIOT</h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<label for="input_categoria" class="col-sm-1 control-label col-form-cbiot">Categoria:</label>
						<div class="col-sm-4">
							<p><?php echo $user->categoria;?></p>
						</div>
						<?php if($cat == PROFESSORES) : ?>
						<div class="col-sm-5">
							<p><?php 
									if($user->professor_lider) {
										echo8("- Professor Líder");
									} else if($user->professor_externo) { 
										echo8("- Professor Externo ao CBIOT");
									} else {
										echo8("- Professor Associado");
									}
							?></p>
						</div>
						<?php endif; ?>
					</div> <!-- /row -->
					<?php if($cat == FUNCIONARIOS OR $cat == VISITANTES) : ?>
					<div class="row">
						<label for="input_cargo" class="col-sm-1 control-label col-form-cbiot">Cargo:</label>
						<div class="col-sm-4">
							<p><?php echo $user->cargo;?></p>
						</div>
					</div> <!-- /row -->
					<?php endif; ?>
					<?php if($cat == ALUNOS OR $cat == PROFESSORES AND $user->professor_lider == 0) : ?>
					<div class="row">
						<label for="input_lider" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Líder"); ?>:</label>
						<div class="col-sm-11">
							<p><?php echo $user->lider;?></p>
						</div>
					</div> <!-- /row -->
					<?php endif; ?>
					<?php if($cat == ALUNOS) : ?>
					<div class="row">
						<label for="input_orientador" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Orientador"); ?>:</label>
						<div class="col-sm-11">
							<p><?php echo $user->orientador;?></p>
						</div>
					</div> <!-- /row -->
					<?php endif; ?>
					<div class="row">
						<label for="input_tipo_grupo" class="col-sm-1 control-label col-form-cbiot">Grupos:</label>
						<div class="col-sm-11">
							<p>
					<?php foreach($user->grupos as $grupo) : ?>
							<?php echo8($grupo->acronimo.", ");?>
					<?php endforeach; ?>
							</p>
						</div>
					</div>
					<div class="row">
							<label for="input_cargo" class="col-sm-2 control-label col-form-cbiot">Data de Cadastro:</label>
							<div class="col-sm-4">
								<p><?php echo $user->data_cadastro;?></p>
							</div>
						</div> <!-- /row -->
						<div class="row">
							<label for="input_cargo" class="col-sm-2 control-label col-form-cbiot"><?php echo8("Data de Expiração:"); ?></label>
							<div class="col-sm-4">
								<p><?php echo $user->data_expiracao;?></p>
							</div>
						</div> <!-- /row -->
				</div> <!-- /panel body -->
			</div> <!-- /panel -->
			<?php if($user->acesso->id) : ?>
			<div class="panel panel-info noprint">
				<div class="panel-heading">
					<h3 class="panel-title"><?php echo8("Acesso ao sistema");?></h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<label for="input_usuario" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Usuario"); ?>:</label>
						<div class="col-sm-11">
							<p><?php echo $user->acesso->usuario;?></p>
						</div>
					</div> <!-- /row -->
				</div> <!-- /panel-body -->
			</div> <!-- /panel -->
			<?php endif; ?>
			<?php if($user->comentario) : ?>
			<div class="panel panel-info noprint">
				<div class="panel-heading">
					<h3 class="panel-title"><?php echo8("Informações Adicionais");?></h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-12">		  
							<p><?php echo $user->comentario;?></p>
						</div>
					</div> <!-- /row -->
				</div> <!-- /panel-body -->
			</div> <!-- /panel -->
			<?php endif; ?>
			<?php
			if($cat == PROFESSORES) :
				$sql = "SELECT id, nome, categoria_id, professor_externo, ativo FROM usuarios WHERE lider_id = %d OR orientador_id = %d ORDER BY  ativo DESC, nome ASC";
				$result = query($sql,$user->id,$user->id);
			?>
			<div class="panel panel-info noprint">
				<div class="panel-heading">
					<h3 class="panel-title"><?php echo8("Associados a este professor");?></h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-12">	
			<?php
				if($result->num_rows) :
					while($row = $result->fetch_assoc()) :
			?>
							<p><?php
								$actual_link = urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
								$view_link = VIEW_LINK;
								$view_link = add_param($view_link,"id",$row['id']);
								$view_link = add_param($view_link,"prev",$actual_link);
								
								if($row['ativo']) {
									echo "<span style='color:green;' title='Ativo' class='glyphicon glyphicon-ok-sign'></span>";
								} else {
									echo "<span style='color:red;' title='Inativo' class='glyphicon glyphicon-minus-sign'></span>";
								}
								
								echo " <a title='Ver Perfil' href=".$view_link." target='_self' style='color:black;'>".$row['nome']."</a>";
								echo " - ";
								if($row['categoria_id'] == 1) {
									if($row['professor_externo'] == 1) {
										echo "Professor Externo";
									} else {
										echo "Professor Associado";
									}
								} else {
									echo "Aluno";
								}
								?></p>
			<?php
					endwhile;
				else :
			?>
							<p><?php echo8("Nenhum orientando cadastrado.");?></p>
			<?php
				endif;
			?>
						</div>
					</div> <!-- /row -->
				</div> <!-- /panel-body -->
			</div> <!-- /panel -->
			<?php endif; ?>
		
		<!-- A parte abaixo só sera vista na impressão -------------------------------------------------------->
			<div class="panel panel-info toprint">
				<div class="panel-heading">
					<h3 class="panel-title " style="text-align: center; font-weight: bold;">
					<?php echo8("UNIVERSIDADE FEDERAL DO RIO GRANDE DO SUL <br> Centro de Biotecnologia<br><br><br> DADOS DO USUÁRIO");?></h3>
				</div>
			</div> <!-- /panel -->
			<div class="panel panel-info toprint">
				<div class="panel-heading">
					<h3 class="panel-title" style="text-align: center;"><b>Dados Pessoais</b></h3>
				</div>
				<div class="panel-body">
					<table width="100%" border='0'>
						<tr>
							<td><label for="input_nome" class="col-sm-1 control-label col-form-cbiot">Nome:</label></td>
							<td colspan="3"><?php echo $user->nome; ?></td>
						</tr>
						<tr>
							<td><label for="input_sexo" class="col-sm-1 control-label col-form-cbiot">Sexo:</label></td>
							<td><p><?php if($user->sexo == 'f' || $user->sexo == 'F') {
											echo "Feminino";
										} else {
											echo "Masculino";
										}
							?></p></td>
							<td><label for="input_nascimento" class="col-sm-2 control-label col-form-cbiot">Data Nascimento:</label></td>
							<td><p><?php echo $user->nascimento;?></p></td>
						</tr>
						<tr>
							<td><label for="input_rg" class="col-sm-1 control-label col-form-cbiot">RG:</label></td>
							<td><p><?php echo $user->rg;?></p></td>
							<td><label for="input_emissao_rg" class="col-sm-2 control-label col-form-cbiot">Data e Emissor:</label></td>
							<td><p><?php echo $user->data_rg;?> <?php echo $user->emissor_rg;?></p></td>
							
						</tr>
						<tr>
							<td><label for="input_cpf" class="col-sm-2 control-label col-form-cbiot">CPF:</label></td>
							<td><p><?php echo $user->cpf;?></p></td>
							<td><label for="input_estado_civil" class="col-sm-2 control-label col-form-cbiot">Estado Civil:</label></td>
							<td><p><?php echo $user->estado_civil;?></p></td>
						</tr>
						<tr>
							<td><label for="input_nome_pai" class="col-sm-1 control-label col-form-cbiot">Nome Pai:</label></td>
							<td colspan="3"><p><?php echo $user->nome_pai;?></p></td>					
						</tr>
						<tr>
							<td><label for="input_nome" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Nome Mãe");?>:</label></td>
							<td colspan="3"><p><?php echo $user->nome_mae;?></p></td>
						</tr>
						<tr>
							<td><label for="input_escolaridade" class="col-sm-1 control-label col-form-cbiot">Escolaridade:</label></td>
							<td><p><?php echo $user->escolaridade;?></p></td>
							<td><label for="input_matricula" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Nro. Cartão");?>:</label></td>
							<td><p><?php echo $user->matricula;?></p></td>
						</tr>
						<tr>
							<td><label for="input_instituicao" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Instituição");?>:</label></td>
							<td colspan="3"><p><?php echo $user->instituicao;?></p></td>
						</tr>
					</table>
				</div> <!-- /panel-body -->
			</div> <!-- /panel -->
			<div class="panel panel-info toprint">
				<div class="panel-heading">
					<h3 class="panel-title" style="text-align: center; font-weight: bold;"><?php echo8("<b>Endereço</b>");?></h3>
				</div>
				<div class="panel-body">
					<table width="100%" border='0'>
						<tr>
							<td><label for="input_logradouro" class="col-sm-1 control-label col-form-cbiot">Logradouro:</label></td>
							<td><p><?php echo $user->endereco->logradouro;?></p></td>
							<td><label for="input_numero" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Número");?>:</label></td>
							<td><p><?php echo8($user->endereco->numero);?> 
									<b> / </b>
									<?php echo8($user->endereco->complemento);?></p>
							</td>					
						</tr>
						
						<tr>
							<td><label for="input_bairro" class="col-sm-1 control-label col-form-cbiot">Bairro:</label></td>
							<td><p><?php echo $user->endereco->bairro;?></p></td>
							<td><label for="input_cep" class="col-sm-1 control-label col-form-cbiot">CEP:</label></td>
							<td><p><?php echo $user->endereco->cep;?></p></td>
						</tr>
						<tr>
							<td><label for="input_municipio" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Município");?>:</label></td>
							<td><p><?php echo $user->endereco->municipio;?></p></td>
							<td><label for="input_pais" class="col-sm-1 control-label col-form-cbiot"><?php echo8("País");?>:</label></td>
							<td><p><?php echo $user->endereco->pais;?></p></td>
						</tr>
					</table>
				</div> <!-- /panel body -->
			</div> <!-- /panel -->	
			<?php if(count($user->contatos) > 0) : ?>
			<div class="panel panel-info toprint">
				<div class="panel-heading">
					<h3 class="panel-title" style="text-align: center; font-weight: bold;">Contatos</h3>
				</div>
				<div class="panel-body" id="contacts_container">
					<table width="100%" border='0'>
						<?php $tmp =  $user->contatos;
							while($contato = array_shift($tmp)) : 
						?>
						<tr>
							<td><label for="input_tipo_contato" class="col-sm-2 control-label col-form-cbiot"><?php echo $contato->tipo_contato; ?>:</label></td>
							<td><?php echo8($contato->contato);?></td>
							<td><?php if($contato = array_shift($tmp)) : ?>
								<label for="input_tipo_contato" class="col-sm-2 control-label col-form-cbiot"><?php echo $contato->tipo_contato; ?>:</label></td>
							<td><?php echo8($contato->contato);?></td>
								<?php else: ?> </td><td></td> 
								<?php endif;?>
						</tr>
						<?php endwhile; ?>						
					</table>
			   </div> <!-- /panel body -->
			</div> <!-- /panel -->
			<?php endif; ?>
			<?php if(count($user->dispositivos) > 0) : ?>
			<div class="panel panel-info toprint">
				<div class="panel-heading">
					<h3 class="panel-title" style="text-align: center; font-weight: bold;">Dispositivos</h3>
				</div>
				<div class="panel-body" id="devices_container">
					<table width="100%" border='0'>
						<?php foreach($user->dispositivos as $dispositivo) : ?>
						<tr>
							<td><label for="input_tipo_dispositivo" class="col-sm-3 control-label col-form-cbiot"><?php echo $dispositivo->tipo_dispositivo; ?>:</label></td>
							<td><p><?php echo8("<b>MAC:</b> ".$dispositivo->endereco_mac);?></p></td>
							<td><p><?php echo8("<b>Patrimônio:</b> ".$dispositivo->patrimonio);?></p></td>
							<td><p><?php echo8("<b>Local:</b> ".$dispositivo->localizacao);?></p></td>
						</tr>
						<?php endforeach; ?>
					</table>
			   </div> <!-- /panel body -->
			</div> <!-- /panel -->
			<?php endif; ?>
			<div class="panel panel-info toprint">
				<div class="panel-heading">
					<h3 class="panel-title" style="text-align: center; font-weight: bold;">Dados CBIOT</h3>
				</div>
				<div class="panel-body">
					<table width="100%" border='0'>
						<tr>
							<td><label for="input_categoria" class="col-sm-1 control-label col-form-cbiot">Categoria:</label></td>
							<td><p><?php echo $user->categoria;?> 
									<?php if($cat == PROFESSORES) : ?>
										<?php 
												if($user->professor_lider) {
													echo8(" - Professor Líder");
												} else if($user->professor_externo) { 
													echo8(" - Professor Externo ao CBIOT");
												} else {
													echo8(" - Professor Associado");
												}
										?>
									<?php endif; ?></p>
							</td>
						</tr>
						<?php if($cat == FUNCIONARIOS OR $cat == VISITANTES) : ?>
						<tr>
							<td><label for="input_cargo" class="col-sm-1 control-label col-form-cbiot">Cargo:</label></td>
							<td><p><?php echo $user->cargo;?></p></td>
						</tr>
						<?php endif; ?>
						<?php if($cat == ALUNOS OR $cat == PROFESSORES AND $user->professor_lider == 0) : ?>
						<tr>
							<td><label for="input_lider" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Líder"); ?>:</label></td>
							<td><p><?php echo $user->lider;?></p></td>
						</tr>
						<?php endif; ?>
						<?php if($cat == ALUNOS) : ?>
						<tr>
							<td><label for="input_orientador" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Orientador"); ?>:</label></td>
							<td><p><?php echo $user->orientador;?></p></td>
						</tr>
						<?php endif; ?>
						<tr>
							<td><label for="input_tipo_grupo" class="col-sm-1 control-label col-form-cbiot">Grupos:</label></td>
							<td><p>
								<?php foreach($user->grupos as $grupo) : ?>
										<?php echo8($grupo->acronimo.", ");?>
								<?php endforeach; ?></p>
							</td>
						</tr>
						<tr>
							<td><label for="input_cargo" class="col-sm-2 control-label col-form-cbiot">Data de Cadastro:</label></td>
							<td><p><?php echo $user->data_cadastro;?></p></td>
						</tr>
						<tr>
							<td><label for="input_cargo" class="col-sm-2 control-label col-form-cbiot"><?php echo8("Data de Expiração:"); ?></label></td>
							<td><p><?php echo $user->data_expiracao;?></p></td>
						</tr>
					</table>
				</div> <!-- /panel body -->
			</div> <!-- /panel -->
			<?php if($user->comentario) : ?>
			<div class="panel panel-info toprint">
				<div class="panel-heading">
					<h3 class="panel-title " style="text-align: center; font-weight: bold;"><?php echo8("Informações Adicionais");?></h3>
				</div>
				<div class="panel-body">
					<table width="100%" border='0'>
						<tr>
							<td><p><?php echo $user->comentario;?></p></td>
						</tr>
					</table>
				</div> <!-- /panel-body -->
			</div> <!-- /panel -->
			<?php endif; ?>						
			<?php
			if($cat == PROFESSORES) :
				$sql = "SELECT nome, categoria_id, professor_externo, ativo FROM usuarios WHERE lider_id = %d OR orientador_id = %d ORDER BY  ativo DESC, nome ASC";
				$result = query($sql,$user->id,$user->id);
			?>
			<div class="panel panel-info toprint">
				<div class="panel-heading">
					<h3 class="panel-title" style="text-align: center; font-weight: bold;"><?php echo8("Associados a este professor");?></h3>
				</div>
				<div class="panel-body">
					<table width="100%" border='0'>
						<tr>
							<td><?php
									if($result->num_rows) :
										while($row = $result->fetch_assoc()) :
								?>
												<p><?php 
													if($row['ativo']) {
														echo "<span style='color:green;' title='Ativo' class='glyphicon glyphicon-ok-sign'></span>";
													} else {
														echo "<span style='color:red;' title='Inativo' class='glyphicon glyphicon-minus-sign'></span>";
													}
													
													echo " ".$row['nome'];
													echo " - ";
													if($row['categoria_id'] == 1) {
														if($row['professor_externo'] == 1) {
															echo "Professor Externo";
														} else {
															echo "Professor Associado";
														}
													} else {
														echo "Aluno";
													}
													?></p>
								<?php
										endwhile;
									else :
								?>
												<p><?php echo8("Nenhum orientando cadastrado.");?></p>
								<?php
									endif;
								?>
							</td>
						</tr>
					</table>
				</div> <!-- /panel-body -->
			</div> <!-- /panel -->
			<?php endif; ?>
		<!-- A parte acima só sera vista na impressão -------------------------------------------------------->
		<?php endif; ?>
		</div> <!-- /container -->
	</div> <!-- /wrap -->
	<?php include("footer.php"); ?>	
</body>
</html>