<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Página inicial para alunos, funcionarios e visitantes que vão entrar no sistema.
 *
 *	Observação: Mostra os dados do usuario, dispositivos cadastrados
 *
**/

$page_access_level = 5;						//Administração, Professores e Outros
require("valida_session.php");

$id = $_SESSION["user_id"];					// id do usuario acessando o sistema

define("PROFESSORES",1);
define("ALUNOS",2);
define("FUNCIONARIOS",3);
define("VISITANTES",4);

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

			$cat = 1;	// categoria
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
					case 11: $cat = PROFESSORES;
							break;
					default: $cat = PROFESSORES;
				}
			}
			
			//my_var_dump($user);	//for debug purpouse
			
			if($erro == 0) : 
		?>
			<!-- Main jumbotron for a primary marketing message or call to action -->
			<div class="jumbotron">
			  <div class="container">
				<h1><?php echo8("Olá"); echo " $user->nome!"; ?>	</h1>
			  </div>
			</div>
			
			<div class="panel panel-info">
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
						<label for="input_matricula" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Matrícula");?>:</label>
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
			<div class="panel panel-info">
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
							<p><?php echo8($user->endereco->numero);?></p>
						</div>
						<label for="input_complemento" class="col-sm-1 control-label col-form-cbiot">Comp.:</label>
						<div class="col-sm-1" style="padding-left: 5px">		  
							<p><?php echo8($user->endereco->complemento);?></p>
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
			<div class="panel panel-info">
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
			<?php endif;
			if(count($user->dispositivos) > 0) : ?>
			<div class="panel panel-info">
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
			<div class="panel panel-info">
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
					<?php if($user->categoria_id > 6) : ?>
					<div class="row">
						<label for="input_cargo" class="col-sm-1 control-label col-form-cbiot">Cargo:</label>
						<div class="col-sm-4">
							<p><?php echo $user->cargo;?></p>
						</div>
					</div> <!-- /row -->
					<?php endif; ?>
					<?php if($user->categoria_id > 0 AND $user->categoria_id < 7 AND $user->professor_lider == 0) : ?>
					<div class="row">
						<label for="input_lider" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Líder"); ?>:</label>
						<div class="col-sm-11">
							<p><?php echo $user->lider;?></p>
						</div>
					</div> <!-- /row -->
					<?php endif; ?>
					<?php if($user->categoria_id > 1 AND $user->categoria_id < 7) : ?>
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
				</div> <!-- /panel body -->
			</div> <!-- /panel -->
			<?php if($user->comentario) : ?>
			<div class="panel panel-info">
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
			<?php endif; 
		endif; ?>
		</div> <!-- /container -->
	</div> <!-- /wrap -->
	<?php include("footer.php"); ?>	
</body>
</html>