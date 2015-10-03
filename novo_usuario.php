<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Página de cadastro de usuários para fora do sistema
 *				Os usuários cadastrados são inativos até que a administração aceite o cadastro.
 *
 *	Observação: A página pega a categoria a ser cadastrada pelo GET['cat']
 *				4 Layouts diferentes de formulario dependendo do ID
 *					- (1) Professor tem a parte do cadastro de tipo de prof e lider
 *					- (2) Alunos precisam definir a categoria do aluno lider e orientador
 *					- (3) Funcionarios devem especificar cargo
 *					- (4) Visitantes devem especificar tempo de visita
 *
 *				CSS para impressão: 	noprint = não sera impresso
 *										toprint = sera impresso
 *										* Tudo deve ser declarado dentro dos divs (wrap e content)
 *										** O cabecalho e rodapé são noprint por default
 *
**/

define("PROFESSORES",1);
define("ALUNOS",2);
define("FUNCIONARIOS",3);
define("VISITANTES",4);

require("classes.php");

$cat = $_GET['cat'];						// categoria do usuario a ser editado

/*
*	CATEGORIAS:
*	0 - Indefinido
*	1 - Professor
*	2 - Estagiário
*	3 - Estudante de Graduação
*	4 - Estudate de Mestrado
*	5 - Estudante de Doutorado
*	6 - Pós-Doutorando
*	7 - Funcionário Terceirizado
*	8 - Funcionário Servidor
*	9 - Funcionário de Empresa Incubada
*	10 - Visitante
*	11 - Outros Vinculos (especificar nas observações)
*
*/
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
	<link href="./css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
	
</head>

<?php
// Verificação dos campos do formulário
$nome 			= $nomeErr = 
$nascimento 	= $nascimentoErr = 
$rg 			= $rgErr = 
$data_rg 		= $data_rgErr = 
$orgao_rg 		= $orgao_rgErr = 
$cpf 			= $cpfErr = 
$nome_pai 		= $nome_paiErr = 
$nome_mae 		= $nome_maeErr = 
$instituicao 	= $instituicaoErr = 
$matricula 		= $matriculaErr = 
$logradouro 	= $logradouroErr = 
$numero 		= $numeroErr = 
$bairro 		= $bairroErr = 
$cep 			= $cepErr = 
$cargo 			= $cargoErr = 
$email 			= $emailErr =
$email_alt		= $email_altErr = 
$telefone 		= $telefoneErr = 
$ramal 			= $ramalErr = 
$usuario 		= $usuarioErr = 
$senha 			= $senhaErr = 
$lider_id		= $lider_idErr = 
$data_expiracao = $data_expiracaoErr = "";

// variaves de controle para o conteudo da página
$err_count = 0; 	//default zero
$showform = 1;		//default one
$showsuccess = 0;	//default zero
$showerror = 0;		//default zero

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	// DADOS PESSOAIS
	if (empty($_POST["nome"])) {
		$nomeErr = "<br>- Nome obrigatório";
		$err_count ++;
	} else {
		$nome = test_input($_POST["nome"]);
		if(strlen($nome) > MAX_NOME) {
			$nome = substr($nome,0,MAX_NOME);
		}
	}
	// no control for sexo (select)
	$sexo = $_POST['sexo'];
	if (empty($_POST["nascimento"])) {
		$nascimentoErr = "<br>- Nascimento obrigatório";
		$err_count ++;
	} else {
		$nascimento = test_input($_POST["nascimento"]);
	}
	// no control for estado_civil (select)
	$estado_civil = $_POST['estado_civil'];
	if (empty($_POST["rg"])) {
		$rgErr = "<br>- RG obrigatório";
		$err_count ++;
	} else {
		$rg = test_input($_POST["rg"]);
		if(strlen($rg) > MAX_RG) {
			$rg = substr($rg,0,MAX_RG);
		}
	}
	if (empty($_POST["data_rg"])) {
		$data_rgErr = "<br>- Data de emissão do RG obrigatório";
		$err_count ++;
	} else {
		$data_rg = test_input($_POST["data_rg"]);
	}
	if (empty($_POST["orgao_rg"])) {
		$orgao_rgErr = "<br>- Orgão de emissão do RG obrigatório";
		$err_count ++;
	} else {
		$orgao_rg = test_input($_POST["orgao_rg"]);
		if(strlen($orgao_rg) > MAX_EMISSOR_RG) {
			$orgao_rg = substr($orgao_rg,0,MAX_EMISSOR_RG);
		}
	}
	if (empty($_POST["cpf"])) {
		$cpfErr = "<br>- CPF obrigatório";
		$err_count ++;
	} else {
		$cpf = test_input($_POST["cpf"]);
		if(strlen($cpf) > MAX_CPF) {
			$cpf = substr($cpf,0,MAX_CPF);
		}
	}
	if (empty($_POST["nome_pai"])) {
		$nome_paiErr = "<br>- Nome do pai obrigatório";
		$err_count ++;
	} else {
		$nome_pai = test_input($_POST["nome_pai"]);
		if(strlen($nome_pai) > MAX_NOME_PAI) {
			$nome_pai = substr($nome_pai,0,MAX_NOME_PAI);
		}
	}
	if (empty($_POST["nome_mae"])) {
		$nome_maeErr = "<br>- Nome da mãe obrigatório";
		$err_count ++;
	} else {
		$nome_mae = test_input($_POST["nome_mae"]);
		if(strlen($nome_mae) > MAX_NOME_MAE) {
			$nome_mae = substr($nome_mae,0,MAX_NOME_MAE);
		}
	}
	if (empty($_POST["instituicao"])) {
		$instituicaoErr = "<br>- Nome da Instituição obrigatório";
		$err_count ++;
	} else {
		$instituicao = test_input($_POST["instituicao"]);
		if(strlen($instituicao) > MAX_INSTITUICAO) {
			$instituicao = substr($instituicao,0,MAX_INSTITUICAO);
		}
	}
	if (empty($_POST["matricula"])) {
		$matriculaErr = "<br>- Número de matrícula obrigatório";
		$err_count ++;
	} else {
		$matricula = test_input($_POST["matricula"]);
		if(strlen($matricula) > MAX_MATRICULA) {
			$matricula = substr($matricula,0,MAX_MATRICULA);
		}
	}
	
	// no control for escolaridade (select)
	$escolaridade = $_POST['escolaridade'];
	// ENDEREÇO
	if (empty($_POST["logradouro"])) {
		$logradouroErr = "<br>- Logradouro obrigatório";
		$err_count ++;
	} else {
		$logradouro = test_input($_POST["logradouro"]);
		if(strlen($logradouro) > MAX_LOGRADOURO) {
			$logradouro = substr($logradouro,0,MAX_LOGRADOURO);
		}
	}
	if (empty($_POST["numero"])) {
		$numeroErr = "<br>- Número da residência obrigatório";
		$err_count ++;
	} else {
		$numero = test_input($_POST["numero"]);
		if(strlen($numero) > MAX_NUMERO) {
			$numero = substr($numero,0,MAX_NUMERO);
		}
	}
	if(empty($_POST['complemento'])) {
		$complemento = "";
	} else {
		$complemento = test_input($_POST["complemento"]);
		if(strlen($complemento) > MAX_COMPLEMENTO) {
			$complemento = substr($complemento,0,MAX_COMPLEMENTO);
		}
	}
	if (empty($_POST["bairro"])) {
		$bairroErr = "<br>- Bairro obrigatório";
		$err_count ++;
	} else {
		$bairro = test_input($_POST["bairro"]);
		if(strlen($bairro) > MAX_BAIRRO) {
			$bairro = substr($bairo,0,MAX_BAIRRO);
		}
	}
	if (empty($_POST["cep"])) {
		$cepErr = "<br>- CEP obrigatório";
		$err_count ++;
	} else {
		$cep = test_input($_POST["cep"]);
		if(strlen($cep) > MAX_CEP) {
			$cep = substr($cep,0,MAX_CEP);
		}
	}
	// no control for municipio (select)
	$municipio = $_POST['municipio'];
	// no control for pais (select)
	$pais = $_POST['pais'];
	
	// CONTATOS
	if($cat != VISITANTES) {
		if (empty($_POST["email"])) {
			$emailErr = "<br>- E-mail cbiot obrigatório";
			$err_count ++;
		} else {
			$email = test_input($_POST["email"]);
			if(strlen($email) > MAX_CONTATO) {
				$email = substr($email,0,MAX_CONTATO);
			}
		}
	}
	if (empty($_POST["email_alt"])) {
		$email_altErr = "<br>- E-mail alternativo obrigatório";
		$err_count ++;
	} else {
		$email_alt = test_input($_POST["email_alt"]);
		if(strlen($email_alt) > MAX_CONTATO) {
			$email_alt = substr($email_alt,0,MAX_CONTATO);
		}
	}
	if (empty($_POST["telefone"])) {
		$telefoneErr = "<br>- Telefone obrigatório";
		$err_count ++;
	} else {
		$telefone = test_input($_POST["telefone"]);
		if(strlen($telefone) > MAX_CONTATO) {
			$telefone = substr($telefone,0,MAX_CONTATO);
		}
	}
	if (empty($_POST["ramal"])) {
		$ramalErr = "<br>- Ramal obrigatório";
		$err_count ++;
	} else {
		$ramal = test_input($_POST["ramal"]);
		if(strlen($ramal) > MAX_CONTATO) {
			$ramal = substr($ramal,0,MAX_CONTATO);
		}
	}
	
	// DADOS CBIOT
	$categoria = 0;
	if($cat == PROFESSORES) {
		$categoria = 1;
	} elseif($cat == ALUNOS) {
		$categoria = $_POST['categoria'];
	} elseif($cat == FUNCIONARIOS) {
		$categoria = $_POST['categoria'];
	} elseif($cat == VISITANTES) {
		$categoria = 10;
	}
	
	if($cat == FUNCIONARIOS OR $cat == VISITANTES) {
		if (empty($_POST["cargo"])) {
			$cargoErr = "<br>- Cargo obrigatório";
			$err_count ++;
		} else {
			$cargo = test_input($_POST["cargo"]);
			if(strlen($cargo) > MAX_CARGO) {
				$cargo = substr($cargo,0,MAX_CARGO);
			}
		}
	} else {
		$cargo = "";
	}
	
	if($cat == VISITANTES) {
		if (empty($_POST["data_expiracao"])) {
			$data_expiracaoErr = "<br>- Data de Expiração obrigatória";
			$err_count ++;
		} else {
			$data_expiracao = test_input($_POST["data_expiracao"]);
		}
	} else {
		$data_expiracao = "";
	}
	
	// no control for tipo_professor (radio buttons)
	if($cat == PROFESSORES) {
		$tipo_professor = $_POST['tipo_professor'];
	}
		
	// no control for lider_associado (select)
	// no control for orientador (select)
	// no control for lider (select)
	// no control for groups (checkbox)
	
	if($cat == ALUNOS) {
		if (empty($_POST["lider_id"])) {
			$lider_idErr = "<br>- Por favor, selecione orientador e líder";
			$err_count ++;
		} else {
			$lider_id = $_POST["lider_id"];
		}
	}
		
	// ACESSO AO SISTEMA
	if($cat != VISITANTES) {
		if (empty($_POST["usuario"])) {
			$usuarioErr = "<br>- Usuario para acesso obrigatorio";
			$err_count ++;
		} else {
			$usuario = test_input($_POST["usuario"]);
			if(strlen($usuario) > MAX_USUARIO) {
				$usuario = substr($usuario,0,MAX_USUARIO);
			}
			// Checagem adicional para saber se o usuário já existe.
			$sql = "SELECT  acesso.* FROM acesso WHERE acesso.usuario = '%s'";
			$result = query($sql,$usuario);
			if($result->num_rows) {
				//Cadastro já existe, nome duplicado
				$emailErr = "<br>- Email já existe, por favor escolha outro email.";
				$err_count ++;
			} 
		}
		if (empty($_POST["senha"])) {
			$senhaErr = "<br>- Senha para acesso obrigatória";
			$err_count ++;
		} else {
			$senha = test_input($_POST["senha"]);
			if(strlen($senha) > MAX_SENHA) {
				$senha = substr($senha,0,MAX_SENHA);
			}
		}
	}
	
	if (!empty($_POST["info_adicional"])) {
		$info_adicional = test_input($_POST["info_adicional"]);
		if(strlen($info_adicional) > MAX_COMENTARIO) {
			$info_adicional = substr($info_adicional,0,MAX_COMENTARIO);
		}
	} else {
		$info_adicional = "";
	}
	
	if($err_count == 0) {
		//Novo objeto do tipo usuario
		$new_user = new User("");
		
		//Endereço do usuario
		$user_address = new Address($logradouro, 
									$numero, 
									isset($_POST["complemento"]) ? $complemento : "",
									$bairro,
									$cep,
									$municipio,
									$pais);
		
		//Definições de professores líderes e orientadores
		$professor_lider = 0;
		$professor_externo = 0;
		$lider_id = NULL;
		$orientador_id = NULL;
		if($categoria == 1) {
			// se for um professor
			if(strcmp($_POST["tipo_professor"],"lider") == 0) {
				$professor_lider = 1;
				$professor_externo = 0;
				$lider_id = NULL;
			} else if(strcmp($_POST["tipo_professor"],"associado") == 0) {
				$professor_lider = 0;
				$professor_externo = 0;
				$lider_id = $_POST["lider_associado"];
			} else if(strcmp($_POST["tipo_professor"],"externo") == 0) {
				$professor_lider = 0;
				$professor_externo = 1;
				$lider_id = $_POST["lider_associado"];
			} 
		}
		if($cat == ALUNOS) {
			$lider_id = isset($_POST["lider_id"]) ? $_POST["lider_id"] : NULL;
			$orientador_id = isset($_POST["orientador"]) ? $_POST["orientador"] : NULL;
		}
		
		//Acesso ao sistema (todos menos visitantes)
		if($cat == VISITANTES) {
			$access = new Access(NULL, NULL, NULL);
		} else {
			$nivel = 0;
			switch($categoria) {
				case 1: $nivel = 2;
						break;
				case 2:
				case 3:
				case 4:
				case 5:
				case 6: $nivel = 3;
						break;
				case 7:
				case 8:
				case 9: $nivel = 4;
						break;
				
				case 10: 
				case 11: $nivel = 5;
						break;
				default: $nivel = 5;
			}
			$access = new Access($usuario, $senha, $nivel);
		}
		
		//Para grupos contamos quantos grupos existem no bd e verificamos cada id
		$sql = "SELECT * FROM grupo";
		$result = query($sql);
		$groups = array();
		while($row = $result->fetch_assoc()) {
			if(isset($_POST["grupo_".$row["id"]])) {
				$group = new Group(NULL,NULL);
				$group->id = $row["id"];
				array_push($groups, $group);
			}
		}
		
		//Para contatos basta instanciar cada um criado
		$contacts = array();
		//ATENÇÃO aos contatos obrigatórios de cada categoria:
		if($cat != VISITANTES) {
			$contact = new Contact(NULL,4,$email."@cbiot.ufrgs.br");
			array_push($contacts, $contact);
		}
		$contact = new Contact(NULL,5,$email_alt);
		array_push($contacts, $contact);
		$contact = new Contact(NULL,1,$telefone);
		array_push($contacts, $contact);
		$contact = new Contact(NULL,6,$ramal);
		array_push($contacts, $contact);
		//Demais contatos (+ -):
		for ($i = 0 ; $i < 10 ; $i++) {
			if(isset($_POST["contato_".$i])) {
				$contato = test_input($_POST["contato_".$i]);
				if(strlen($contato) > MAX_CONTATO) {
					$contato = substr($contato,0,MAX_CONTATO);
				}
				$contact = new Contact(NULL,$_POST["tipo_contato_".$i],$contato);
				if(strcmp($contato,"") != 0)	//se não for em branco
					array_push($contacts, $contact);
			}
		}
		
		//Para dispositivos instanciamos cada um criado (nada obrigatório)
		$devices = array();
		if($cat != VISITANTES) {
			//Lista de dispositivos (+ -):
			for ($i = 0 ; $i < 10 ; $i++) {
				if(isset($_POST["dispositivo_mac_".$i])) {
					$device_mac = test_input($_POST["dispositivo_mac_".$i]);
					if(strlen($device_mac) > MAX_ENDERECO_MAC) {
						$device_mac = substr($device_mac,0,MAX_ENDERECO_MAC);
					}
					$device_location = test_input($_POST["dispositivo_location_".$i]);
					if(strlen($device_location) > MAX_LOCALIZACAO) {
						$device_location = substr($device_location,0,MAX_LOCALIZACAO);
					}
					$device_registration = test_input($_POST["dispositivo_registration_".$i]);
					if(strlen($device_registration) > MAX_PATRIMONIO) {
						$device_registration = substr($device_registration,0,MAX_PATRIMONIO);
					}
					$device_hostname = test_input($_POST["dispositivo_hostname_".$i]);
					if(strlen($device_hostname) > MAX_HOSTNAME) {
						$device_hostname = substr($device_hostname,0,MAX_HOSTNAME);
					}
					
					$device = new Device(NULL,$_POST["tipo_dispositivo_".$i],$device_mac,$device_registration,$device_hostname,null,$device_location);
					if(strcmp($device_mac,"") != 0)	//se não for em branco
						array_push($devices, $device);
				}
			}
		}
		
		// Todas as informações completas: Include no banco
		$new_user->SignUserCompleteInfo(
				 //informações basicas:
				 $nome, 
				 $rg,
				 $data_rg,
				 $orgao_rg,
				 $cpf, 
				 isset($matricula) ? $matricula : "", 
				 isset($instituicao) ? $instituicao : "",
				 $nascimento, 
				 $sexo,
				 $nome_mae,
				 $nome_pai,
				 //endereco: (objeto do tipo Address)
				 //$logradouro, $numero, $complemento, $bairro,
				 //$cep, $municipio_id, $pais_id,
				 $user_address,
				 $categoria,
				 $escolaridade,
				 $estado_civil,
				 $cargo,
				 //definições de professor
				 $professor_lider,
				 $professor_externo,
				 //pessoas responsaveis:
				 $orientador_id, 
				 $lider_id,
				 //acesso: (objeto do tipo Access)
				 $access,
				 //comentario
				 isset($_POST["info_adicional"]) ? $info_adicional : "",
				 //grupos (array de Group)
				 $groups,
				 //contatos (array de Contact)
				 $contacts,
				 //dispositivos (array de Device)
				 $devices);
		
		// se for visitante ainda precisamos atualizar sua data de expiração
		if($new_user->categoria_id == 10) {
				$new_user->UpdateUserExpirationDate($data_expiracao);
		}
						
		//my_var_dump($new_user);	//for debug purpose
		//my_var_dump($_POST);		//for debug purpose
		if ($new_user->GetDataFromDB($new_user->id) == 0) {
			$showsuccess = 1;
			$showform = 0;
			//my_var_dump($new_user);	//for debug purpose
		} else {
			$showsuccess = 0;
			$showform = 0;
			$showerror = 1;
		}
	} else {
		$showsuccess = 0;
		$showform = 1;
	}
}

if($showform) :
?>

<body onload="setup()">
	<div id="wrap">
	
	<div class="container">
	<div class="alert alert-info" style="text-align: center; font-weight: bold;">
		<?php if($cat == PROFESSORES) {
				echo8("FORMULÁRIO DE CADASTRO PARA PROFESSOR"); 
			  } elseif($cat == ALUNOS) {
				echo8("FORMULÁRIO DE CADASTRO PARA ALUNO"); 
			  } elseif($cat == FUNCIONARIOS) {
				echo8("FORMULÁRIO DE CADASTRO PARA FUNCIONARIO"); 
			  } elseif($cat == VISITANTES) {
				echo8("FORMULÁRIO DE CADASTRO PARA VISITANTE"); 
			  }
		?>
	</div>
	<form class="form-horizontal" role="form" action="<?php echo htmlspecialchars("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");?>" method="post">
	<?php if($err_count) : ?>
	  <div class="panel panel-danger">
        <div class="panel-heading">
          <h3 class="panel-title">Por favor, verifique os seguintes erros:</h3>
        </div>
	    <div class="panel-body">
		<?php 
		echo $nomeErr.$nascimentoErr.$rgErr.$data_rgErr.$orgao_rgErr.$cpfErr.$nome_paiErr.$nome_maeErr.$instituicaoErr.$matriculaErr.$logradouroErr.$numeroErr.$bairroErr.$cepErr.$cargoErr.$emailErr.$email_altErr.$telefoneErr.$ramalErr.$usuarioErr.$senhaErr;
		?>
		</div>
	  </div>
	<?php endif; ?>
	  <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title">Dados Pessoais</h3>
        </div>
	    <div class="panel-body">
		  <div class="form-group <?php if(!empty($nomeErr)) { echo "has-error"; } ?>">
		    <label for="input_nome" class="col-sm-1 control-label col-form-cbiot">Nome:</label>
		   	<div class="col-sm-11">		  
			   <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome Completo"
				  <?php 
				  if(isset($_POST['nome'])) {
				    echo " value=\"".$_POST['nome']."\"";
				  }
				  ?> required autofocus>
			</div>
		  </div>
		  <div class="form-group">
		    <label for="input_sexo" class="col-sm-1 control-label col-form-cbiot">Sexo:</label>
		   	<div class="col-sm-2">		  
			   <select class="form-control" id="sexo" name="sexo">
				  <option value="m">Masculino</option>
				  <option value="f">Feminino</option>
			   </select>
			</div>
			<label for="input_nascimento" class="col-sm-2 control-label col-form-cbiot <?php if(!empty($nascimentoErr)) { echo "has-error"; } ?>">Data Nascimento:</label>
		   	<div class="col-sm-2 <?php if(!empty($nascimentoErr)) { echo "has-error"; } ?>">		  
				<div class="input-group date form_date control-label" style='padding: 0;' data-date="" data-date-format="dd/mm/yyyy" data-link-field="nascimento" data-link-format="dd/mm/yyyy">
                    <input class="form-control" id="nascimento_cal" onchange="CopyField(this.form.nascimento_cal,this.form.nascimento)" placeholder="dd/mm/aaaa" type="text" 
					<?php
					  //escrever novamente se o dado ja estiver ali
					  if(isset($_POST['nascimento'])) {
						echo " value=\"".$_POST['nascimento']."\"";
					  }
					?> required>
					<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
				<input type="hidden" id="nascimento" name="nascimento" 
				<?php
				  //escrever novamente se o dado ja estiver ali
				  if(isset($_POST['nascimento'])) {
					echo " value=\"".$_POST['nascimento']."\"";
				  }
				?>>
			</div>
			<label for="input_estado_civil" class="col-sm-2 control-label col-form-cbiot">Estado Civil:</label>
		   	<div class="col-sm-3">		  
			   <select class="form-control" name="estado_civil" id="estado_civil">
				  <?php
					$sql = "SELECT * FROM estado_civil WHERE id > 0";
					$result = query($sql);
					while($row = $result->fetch_assoc()):
				  ?>
				  <option value="<?php echo $row['id']; ?>"><?php echo $row['estado_civil']; ?></option>
				  <?php endwhile; ?>
			   </select>
			</div>
          </div> <!-- /form group -->
		  <div class="form-group">
		    <label id="input_rg_label" for="input_rg" class="col-sm-1 control-label col-form-cbiot <?php if(!empty($rgErr)) { echo "has-error"; } ?>">RG:</label>
		   	<div id="input_rg_div" class="col-sm-2 <?php if(!empty($rgErr)) { echo "has-error"; } ?>">		  
			   <input type="text" class="form-control" id="rg" name="rg" maxlength="10" placeholder="xxxxxxxxxx" 
				  <?php 
				  if(isset($_POST['rg'])) {
				    echo " value=\"".$_POST['rg']."\"";
				  }
				  ?> required>
			</div>
			<label id="input_emissao_label" for="input_emissao_rg" class="col-sm-2 control-label col-form-cbiot <?php if(!empty($data_rgErr)) { echo "has-error"; } ?>">Data e Emissor:</label>
			<div id="input_emissao_div" class="col-sm-2 <?php if(!empty($data_rgErr)) { echo "has-error"; } ?>">		  
				<div class="input-group date form_date control-label" style='padding: 0;' data-date="" data-date-format="dd/mm/yyyy" data-link-field="data_rg" data-link-format="dd/mm/yyyy">
                    <input class="form-control" id="data_rg_cal" onchange="CopyField(this.form.data_rg_cal,this.form.data_rg)" placeholder="dd/mm/aaaa" type="text" 
					<?php
					  //escrever novamente se o dado ja estiver ali
					  if(isset($_POST['data_rg'])) {
						echo " value=\"".$_POST['data_rg']."\"";
					  }
					?> required>
					<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
				<input type="hidden" id="data_rg" name="data_rg" 
				<?php
				  //escrever novamente se o dado ja estiver ali
				  if(isset($_POST['data_rg'])) {
					echo " value=\"".$_POST['data_rg']."\"";
				  }
				?>>
			</div>
		   	<div id="input_orgao_div"  class="col-sm-1 <?php if(!empty($orgao_rgErr)) { echo "has-error"; } ?>">		  
			   <input type="text" class="form-control" id="orgao_rg" name="orgao_rg" placeholder="XXX"
				  <?php
				  //escrever novamente se o dado ja estiver ali
				  if(isset($_POST['orgao_rg'])) {
				    echo " value=\"".$_POST['orgao_rg']."\"";
				  }
				  ?> required>
			</div>
			<label id="input_cpf_label" for="input_cpf" class="col-sm-1 control-label col-form-cbiot <?php if(!empty($cpfErr)) { echo "has-error"; } ?>">CPF:</label>
		   	<div class="col-sm-3 <?php if(!empty($cpfErr)) { echo "has-error"; } ?>">		  
			   <input type="text" class="form-control" id="cpf" name="cpf" onblur="javascript: if(!document.getElementById('est_check').checked) { validarCPF(this.value); }" onkeypress="javascript: if(!document.getElementById('est_check').checked) { mascara(this, cpf_mask); }"  maxlength="14" placeholder="xxx.xxx.xxx-xx"
				  <?php
				  //escrever novamente se o dado ja estiver ali
				  if(isset($_POST['cpf'])) {
				    echo " value=\"".$_POST['cpf']."\"";
				  }
				  ?> required>
				<span class="help-block" id="estrangeiro_span" >
					<div class="checkbox">
						<label>
						  <input type="checkbox" id="est_check" name="est_check" onclick="ForeignControl();"> Sou estrangeiro
						</label>
					</div>
				</span>
			</div>
          </div> <!-- /form group -->
		  <div class="form-group <?php if(!empty($nome_paiErr)) { echo "has-error"; } ?>">
		    <label for="input_nome_pai" class="col-sm-1 control-label col-form-cbiot">Nome Pai:</label>
		   	<div class="col-sm-11">		  
			   <input type="text" class="form-control" id="nome_pai" name="nome_pai" placeholder="Nome Completo do Pai"
				  <?php 
				  if(isset($_POST['nome_pai'])) {
				    echo " value=\"".$_POST['nome_pai']."\"";
				  }
				  ?> required>
			</div>
		  </div>
		  <div class="form-group <?php if(!empty($nome_maeErr)) { echo "has-error"; } ?>">
		    <label for="input_nome" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Nome Mãe");?>:</label>
		   	<div class="col-sm-11">		  
			   <input type="text" class="form-control" id="nome_mae" name="nome_mae" placeholder="<?php echo8("Nome Completo da Mãe");?>"
				  <?php 
				  if(isset($_POST['nome_mae'])) {
				    echo " value=\"".$_POST['nome_mae']."\"";
				  }
				  ?> required>
			</div>
		  </div> <!-- /form group -->
		  <hr>
		  <div class="form-group">
		    <label for="input_instituicao" class="col-sm-1 control-label col-form-cbiot <?php if(!empty($instituicaoErr)) { echo "has-error"; } ?>"><?php echo8("Instituição");?>:</label>
		   	<div class="col-sm-4 <?php if(!empty($instituicaoErr)) { echo "has-error"; } ?>">		  
			   <input type="text" class="form-control" id="instituicao" name="instituicao" placeholder="<?php echo8("Instituição de Ensino");?>"
				  <?php 
				  if(isset($_POST['instituicao'])) {
				    echo " value=\"".$_POST['instituicao']."\"";
				  }
				  ?> required>
			</div>
			<label for="input_matricula" class="<?php if(!empty($matriculaErr)) { echo "has-error"; } ?> col-sm-1 control-label col-form-cbiot"><?php echo8("Nro. Cartão");?>:</label>
		   	<div class="col-sm-2 <?php if(!empty($matriculaErr)) { echo "has-error"; } ?>">		  
			   <input type="text" class="form-control" id="matricula" name="matricula" placeholder="XXXXXXXX"
				  <?php 
				  if(isset($_POST['matricula'])) {
				    echo " value=\"".$_POST['matricula']."\"";
				  }
				  if($cat == PROFESSORES OR $cat == ALUNOS) {
					echo " required";
				  }
				  ?>>
			</div>
		    <label for="input_escolaridade" class="col-sm-1 control-label col-form-cbiot">Escolaridade:</label>
		   	<div class="col-sm-3">		  
			   <select class="form-control" name="escolaridade" id="escolaridade">
				  <?php
					$sql = "SELECT * FROM escolaridade WHERE id > 0";
					$result = query($sql);
					while($row = $result->fetch_assoc()):
				  ?>
				  <option value="<?php echo $row['id']; ?>"><?php echo $row['escolaridade']; ?></option>
				  <?php endwhile; ?>
			   </select>
			</div>
          </div> <!-- /form group -->
	    </div> <!-- /panel body -->
      </div> <!-- /panel -->
	  <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo8("Endereço");?></h3>
        </div>
	    <div class="panel-body">
		  <div class="form-group">
		    <label for="input_logradouro" class="col-sm-1 control-label col-form-cbiot <?php if(!empty($logradouroErr)) { echo "has-error"; } ?>">Logradouro:</label>
		   	<div class="col-sm-7 <?php if(!empty($logradouroErr)) { echo "has-error"; } ?>">		  
			   <input type="text" class="form-control" id="logradouro" name="logradouro" placeholder="Rua Nome / Avenida Nome"
				  <?php 
				  if(isset($_POST['logradouro'])) {
				    echo " value=\"".$_POST['logradouro']."\"";
				  }
				  ?> required>
			</div>
			<label for="input_numero" class="col-sm-1 control-label col-form-cbiot <?php if(!empty($numeroErr)) { echo "has-error"; } ?>"><?php echo8("Número");?>:</label>
		   	<div class="col-sm-1 col-form-cbiot <?php if(!empty($numeroErr)) { echo "has-error"; } ?>">		  
			   <input type="text" class="form-control" id="numero" name="numero" placeholder="XXX"
				  <?php 
				  if(isset($_POST['numero'])) {
				    echo " value=\"".$_POST['numero']."\"";
				  }
				  ?> required>
			</div>
			<label for="input_complemento" class="col-sm-1 control-label col-form-cbiot">Comp.:</label>
		   	<div class="col-sm-1" style="padding-left: 5px">		  
			   <input type="text" class="form-control" id="complemento" name="complemento" placeholder="Ap. XX"
				  <?php 
				  if(isset($_POST['complemento'])) {
				    echo " value=\"".$_POST['complemento']."\"";
				  }
				  ?>>
			</div>
		  </div> <!-- /form group -->
		  <div class="form-group">
		    <label for="input_bairro" class="col-sm-1 control-label col-form-cbiot <?php if(!empty($bairroErr)) { echo "has-error"; } ?>">Bairro:</label>
		   	<div class="col-sm-7 <?php if(!empty($bairroErr)) { echo "has-error"; } ?>">		  
			   <input type="text" class="form-control" id="bairro" name="bairro" placeholder="Bairro"
				  <?php 
				  if(isset($_POST['bairro'])) {
				    echo " value=\"".$_POST['bairro']."\"";
				  }
				  ?> required>
			</div>
			<label for="input_cep" class="col-sm-1 control-label col-form-cbiot <?php if(!empty($cepErr)) { echo "has-error"; } ?>">CEP:</label>
		   	<div class="col-sm-3 col-form-cbiot <?php if(!empty($cepErr)) { echo "has-error"; } ?>" style="padding-right: 15px">		  
			   <input type="text" class="form-control" id="cep" name="cep" placeholder="XXXXX-XXX"
				  <?php 
				  if(isset($_POST['cep'])) {
				    echo " value=\"".$_POST['cep']."\"";
				  }
				  ?> required>
			</div>
		  </div> <!-- /form group -->
		  <div class="form-group">
		    <label for="input_municipio" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Município");?>:</label>
		   	<div class="col-sm-7">		  
			   <select class="form-control" name="municipio" id="municipio">
				  <?php
					$sql = "SELECT * FROM municipio WHERE id > 0 ORDER BY nome ASC";
					$result = query($sql);
					while($row = $result->fetch_assoc()):
				  ?>
				  <option value="<?php echo $row['id']; ?>" <?php if($row['id'] == 7777) echo "selected='selected'"; ?>><?php echo $row['nome']." - ".$row['uf']; ?></option>
				  <?php endwhile; ?>
			   </select>
			</div>
			<label for="input_pais" class="col-sm-1 control-label col-form-cbiot"><?php echo8("País");?>:</label>
		   	<div class="col-sm-3 col-form-cbiot" style="padding-right: 15px">		  
			   <select class="form-control" name="pais" id="pais">
				  <?php
					$sql = "SELECT * FROM pais WHERE id > 0";
					$result = query($sql);
					while($row = $result->fetch_assoc()):
				  ?>
				  <option value="<?php echo $row['id']; ?>" <?php if($row['id'] == 33) echo "selected='selected'"; ?>><?php echo $row['nome']; ?></option>
				  <?php endwhile; ?>
			   </select>
			</div>
		  </div> <!-- /form group -->
	    </div> <!-- /panel body -->
      </div> <!-- /panel -->
	  <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title">Contatos</h3>
        </div>
	    <div class="panel-body" id="contacts_container">
		  <?php if($cat != VISITANTES) : ?>
		  <div class="form-group">
		    <label for="input_tipo_contato" class="col-sm-1 control-label col-form-cbiot">Tipo:</label>
		   	<div class="col-sm-3">		  
			   <input class="form-control" name="tipo_email" id="tipo_email" readonly="readonly">
			</div>
			<label for="input_tipo_contato" class="col-sm-1 control-label"><center>:</center></label>
			<div class="input-group col-sm-4 <?php if(!empty($emailErr)) { echo "has-error"; } ?>" style="padding-right: 15px; padding-left: 15px;">
				<input type="text" class="form-control" onchange="CopyField(this.form.email,this.form.usuario)" name="email" id="email" placeholder="Seu email CBIOT" ="">
				<span class="input-group-addon">@cbiot.ufrgs.br</span>
			</div>
		  </div> <!-- /form group -->
		  <?php endif; ?>
		  <div class="form-group">
		    <label for="input_tipo_contato" class="col-sm-1 control-label col-form-cbiot">Tipo:</label>
		   	<div class="col-sm-3">		  
			   <input class="form-control" name="tipo_email_alt" id="tipo_email_alt" readonly="readonly">
			</div>
			<label for="input_tipo_contato" class="col-sm-1 control-label"><center>:</center></label>
			<div class="input-group col-sm-4 <?php if(!empty($email_altErr)) { echo "has-error"; } ?>" style="padding-right: 15px; padding-left: 15px;">
				<input type="text" class="form-control" name="email_alt" id="email_alt" placeholder="Seu email alternativo" required>
			</div>
		  </div> <!-- /form group -->
		  <div class="form-group">
		    <label for="input_tipo_contato" class="col-sm-1 control-label col-form-cbiot">Tipo:</label>
		   	<div class="col-sm-3">		  
			   <input class="form-control" name="tipo_telefone" id="tipo_telefone" readonly="readonly">
			</div>
			<label for="input_tipo_contato" class="col-sm-1 control-label"><center>:</center></label>
			<div class="col-sm-4 <?php if(!empty($telefoneErr)) { echo "has-error"; } ?>">
				<input type="text" class="form-control" name="telefone" id="telefone" placeholder="<?php echo8("Telefone Pessoal para contato");?>" required>
			</div>
		  </div> <!-- /form group -->
		  <div class="form-group">
		    <label for="input_tipo_contato" class="col-sm-1 control-label col-form-cbiot">Tipo:</label>
		   	<div class="col-sm-3">		  
			   <input class="form-control" name="tipo_ramal" id="tipo_ramal" readonly="readonly">
			</div>
			<label for="input_tipo_contato" class="col-sm-1 control-label"><center>:</center></label>
			<div class="col-sm-4 <?php if(!empty($ramalErr)) { echo "has-error"; } ?>">
				<input type="text" class="form-control" name="ramal" id="ramal" placeholder="<?php echo8("Ramal UFRGS");?>" required>
			</div>
		  </div> <!-- /form group -->
		  <div class="form-group" id="part_1">
		    <label for="input_tipo_contato" id="part_2" class="col-sm-1 control-label col-form-cbiot">Tipo:</label>
		   	<div class="col-sm-3" id="part_3">		  
			   <select class="form-control" name="tipo_contato_0" id="tipo_contato_0">
				  <?php
					$sql = "SELECT * FROM tipos_contato WHERE id > 0";
					$result = query($sql);
					while($row = $result->fetch_assoc()):
				  ?>
				  <option value="<?php echo $row['id']; ?>"><?php echo $row['tipo_contato']; ?></option>
				  <?php endwhile; ?>
			   </select>
			</div>
			<label for="input_tipo_contato" id="part_4" class="col-sm-1 control-label"><center>:</center></label>
			<div class="col-sm-4" id="part_5">
				<input type="text" class="form-control" id="contato_0" name="contato_0" placeholder="<?php echo8("email@dom.sub ou (xx) xxxx-xxxx");?>" >
			</div>
			<div class="col-form-sign-cbiot col-sm-1" id="plus_sign" >
				<a id="addContact" onclick="addContact()"><span class="glyphicon glyphicon-plus-sign btn-lg" style="padding-left: 0px;"></span></a>
			</div>
			<div class="col-form-sign-cbiot col-sm-1" id="minus_sign" style="visibility: hidden;">
				<a id="removeContact" onclick="removeContact()"><span class="glyphicon glyphicon-minus-sign btn-lg" style="padding-left: 0px;"></span></a>
			</div>
		  </div> <!-- /form group -->	  
	    </div> <!-- /panel body -->
      </div> <!-- /panel -->
	  <?php if($cat != VISITANTES) : ?>
	  <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title">Dispositivos</h3>
        </div>
	    <div class="panel-body" id="devices_container">
		  <div class="form-group" id="parte_1">
		    <label for="input_tipo_dispositivo" id="parte_2" class="col-sm-1 control-label col-form-cbiot">Tipo:</label>
		   	<div class="col-sm-2" id="parte_3">		  
			   <select class="form-control" name="tipo_dispositivo_0" id="tipo_dispositivo_0">
				  <?php
					$sql = "SELECT * FROM tipos_dispositivo WHERE id > 0";
					$result = query($sql);
					while($row = $result->fetch_assoc()):
				  ?>
				  <option value="<?php echo $row['id']; ?>"><?php echo $row['tipo_dispositivo']; ?></option>
				  <?php endwhile; ?>
			   </select>
			</div>
			<div class="col-sm-2" id="parte_4">
				<input type="text" class="form-control" id="dispositivo_registration_0" name="dispositivo_registration_0" placeholder="<?php echo8("Nro. Patrimônio");?>" >
				<span class="help-block" id="disp_help" ><?php echo8("N. Patrimônio (opcional)"); ?></span>
			</div>
			<div class="col-sm-2" id="parte_5">
				<input type="text" class="form-control" id="dispositivo_hostname_0" name="dispositivo_hostname_0" placeholder="<?php echo8("Hostname");?>" >
			</div>
			<div class="col-sm-2" id="parte_6">
				<input type="text" class="form-control" id="dispositivo_mac_0" name="dispositivo_mac_0" placeholder="<?php echo8("XX:XX:XX:XX:XX:XX");?>" >
				<span class="help-block" id="disp_help" ><?php echo8("Endereço MAC <a href='http://pt.wikihow.com/Encontrar-o-Endere%C3%A7o-MAC-do-seu-Computador' target='_blank'>(hein?)</a>"); ?></span>
			</div>
			<div class="col-sm-2" id="parte_7">
				<input type="text" class="form-control" id="dispositivo_location_0" name="dispositivo_location_0" placeholder="<?php echo8("Localização (sala, móvel, etc)");?>" >
			</div>
			<div class="col-form-sign-cbiot col-sm-1" id="plus_sign_dev" >
				<a id="addDevice" onclick="addDevice()"><span class="glyphicon glyphicon-plus-sign btn-lg" style="padding-left: 0px;"></span></a>
			</div>
			<div class="col-form-sign-cbiot col-sm-1" id="minus_sign_dev" style="visibility: hidden;">
				<a id="removeDevice" onclick="removeDevice()"><span class="glyphicon glyphicon-minus-sign btn-lg" style="padding-left: 0px;"></span></a>
			</div>
		  </div> <!-- /form group -->	  
	    </div> <!-- /panel body -->
      </div> <!-- /panel -->
	  <?php endif; ?>
	  <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title">Dados CBIOT</h3>
        </div>
	    <div class="panel-body">
		  <?php if ($cat == PROFESSORES) : ?>
		  <div class="form-group">
			<label for="input_classe_professor" class="col-sm-1 control-label col-form-cbiot">Professor:</label>
			<div class="col-sm-5">
				<div class="radio">
				  <label>
					<input type="radio" name="tipo_professor" id="tipo_lider" onclick="LeaderControl();" value="lider" checked>
					<?php echo8("Sou Professor Líder do CBIOT"); ?>
				  </label>
				</div>
				<div class="radio">
				  <label>
					<input type="radio" name="tipo_professor" id="tipo_associado" onclick="LeaderControl();" value="associado">
					<?php echo8("Sou Professor Associado ao CBIOT"); ?>
				  </label>
				</div>
				<div class="radio">
				  <label>
					<input type="radio" name="tipo_professor" id="tipo_externo" onclick="LeaderControl();" value="externo">
					<?php echo8("Sou Professor Externo ao CBIOT"); ?>
				  </label>
				</div>
			</div>
			<label for="input_lider" id="lider_label" class="col-sm-1 control-label col-form-cbiot" style="visibility: hidden;"><?php echo8("Líder"); ?></label>
		   	<div class="col-sm-5">		  
			   <select class="form-control" name="lider_associado" id="lider_associado" style="visibility: hidden;">
				  <?php
					$sql = "SELECT * FROM usuarios WHERE professor_lider = 1 AND ativo = 1 ORDER BY nome ASC";
					$result = query($sql);
					while($row = $result->fetch_assoc()):
				  ?>
				  <option value="<?php echo $row['id']; ?>"><?php echo $row['nome']; ?></option>
				  <?php endwhile; ?>
			   </select>
			   <span class="help-block"id="lider_help" style="visibility: hidden;"><?php echo8("Selecione o líder associado ao professor externo ou associado."); ?></span>
			</div>
          </div> <!-- /form group -->
		  <?php endif; 
		  if ($cat == ALUNOS OR $cat == FUNCIONARIOS) : ?>
		  <div class="form-group">
		    <label for="input_categoria" class="col-sm-1 control-label col-form-cbiot">Categoria:</label>
		   	<div class="col-sm-11">		  
			   <select class="form-control" name="categoria" id="categoria">
				  <?php
					if($cat == ALUNOS) {
						$sql = "SELECT * FROM categoria WHERE id > 1 and id < 7";	//apenas as categorias para alunos
					} elseif($cat == FUNCIONARIOS) {
						$sql = "SELECT * FROM categoria WHERE id > 6 and id < 10";	//apenas as categorias para alunos
					}
					$result = query($sql);
					while($row = $result->fetch_assoc()):
				  ?>
				  <option value="<?php echo $row['id']; ?>"><?php echo $row['categoria']; ?></option>
				  <?php endwhile; ?>
			   </select>
			</div>
		  </div> <!-- /form group -->
		  <?php endif; 
		  if ($cat == FUNCIONARIOS) : ?>
		  <div class="form-group <?php if(!empty($cargoErr)) { echo "has-error"; } ?>">
		    <label for="input_cargo" class="col-sm-1 control-label col-form-cbiot">Cargo:</label>
		   	<div class="col-sm-11">
			   <input type="text" class="form-control" id="cargo" name="cargo" placeholder="<?php echo8("Descrição do Cargo");?>"
				  <?php 
				  if(isset($_POST['cargo'])) {
				    echo " value=\"".$_POST['cargo']."\"";
				  }
				  ?> required>
			</div>
		  </div> <!-- /form group -->
		  <?php endif; 
		  if ($cat == VISITANTES) : ?>
		  <div class="form-group <?php if(!empty($cargoErr)) { echo "has-error"; } ?>">
		    <label for="input_cargo" class="col-sm-1 control-label col-form-cbiot">Cargo:</label>
		   	<div class="col-sm-11">
			   <input type="text" class="form-control" id="cargo" name="cargo" placeholder="<?php echo8("Descrição do Cargo");?>"
				  <?php 
				  if(isset($_POST['cargo'])) {
				    echo " value=\"".$_POST['cargo']."\"";
				  }
				  ?> required>
			</div>
		  </div> <!-- /form group -->
		  <div class="form-group <?php if(!empty($data_expiracaoErr)) { echo "has-error"; } ?>">
			<label for="input_data_expiracao" class="col-sm-1 control-label col-form-cbiot">Fim da Visita:</label>
			<div class="col-sm-2 <?php if(!empty($data_rgErr)) { echo "has-error"; } ?>">		  
				<div class="input-group date form_date control-label" style='padding: 0; margin-top: 10px;' data-date="" data-date-format="dd/mm/yyyy" data-link-field="data_expiracao" data-link-format="dd/mm/yyyy">
                    <input class="form-control" id="data_expiracao_cal" onchange="CopyField(this.form.data_expiracao_cal,this.form.data_expiracao)" placeholder="dd/mm/aaaa" type="text" 
					<?php
					  //escrever novamente se o dado ja estiver ali
					  if(isset($_POST['data_expiracao'])) {
						echo " value=\"".$_POST['data_expiracao']."\"";
					  }
					?> required>
					<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
				<input type="hidden" id="data_expiracao" name="data_expiracao" 
				<?php
				  //escrever novamente se o dado ja estiver ali
				  if(isset($_POST['data_expiracao'])) {
					echo " value=\"".$_POST['data_expiracao']."\"";
				  }
				?>>
			</div>
		  </div>
		  <?php endif; 
		  if ($cat == ALUNOS) : ?>
		  <div class="form-group">
		    <label for="input_orientador" class="col-sm-1 control-label col-form-cbiot">Orientador:</label>
		   	<div class="col-sm-5">		  
			   <select class="form-control" name="orientador" id="orientador">
				  <?php
					$sql = "SELECT * FROM usuarios WHERE categoria_id = 1 AND ativo = 1 ORDER BY nome ASC";
					$result = query($sql);
					while($row = $result->fetch_assoc()):
				  ?>
				  <option value="<?php echo $row['id']; ?>"><?php echo $row['nome']; ?></option>
				  <?php endwhile; ?>
			   </select>
			</div>
			<label for="input_lider" class="col-sm-1 control-label col-form-cbiot">Lider:</label>
		   	<div class="col-sm-5">		  
			   <input type="text" class="form-control" id="lider" name="lider" readonly="readonly">
			</div>
			<div div class="col-sm-0">
			   <input type="text" class="form-control" id="lider_id" name="lider_id" style="visibility: hidden;" readonly="readonly">
			</div>
          </div> <!-- /form group -->
		  <?php endif; ?>
		  <div class="form-group">
		    <label for="input_grupos" class="col-sm-1 control-label col-form-cbiot">Grupos:</label>
		   	<table class="col-sm-11">
			  <?php
				$sql = "SELECT * FROM grupo";
				$result = query($sql);
				while($row = $result->fetch_assoc()):
			  ?>
			  <tr>
			    <td class="col-sm-4">
				  <div class="checkbox">
				    <label>
					  <input type="checkbox" <?php echo "id=\"grupo_".$row['id']."\" name=\"grupo_".$row['id']."\""; ?>> <?php echo $row['acronimo']; ?>
					  <span class="help-block"><?php echo $row['grupo']; ?></span>
					</label>
				  </div>
				</td>
				<td class="col-sm-5"><?php if($row = $result->fetch_assoc()): ?>  
				  <div class="checkbox">
				    <label>
					  <input type="checkbox" <?php echo "id=\"grupo_".$row['id']."\" name=\"grupo_".$row['id']."\""; ?>> <?php echo $row['acronimo']; ?>
					  <span class="help-block"><?php echo $row['grupo']; ?></span>
					</label>
				  </div>
				<?php endif; ?>
				</td>
			  </tr>
			  <?php endwhile; ?>
			</table>
          </div> <!-- /form group -->
	    </div> <!-- /panel body -->
      </div> <!-- /panel -->
	  <?php if ($cat != VISITANTES) : ?>
	  <div class="panel panel-info">
	    <div class="panel-heading">
          <h3 class="panel-title"><?php echo8("Acesso ao sistema");?></h3>
        </div>
		<div class="panel-body">
		  <div class="form-group">
		    <label for="input_usuario" class="col-sm-1 control-label col-form-cbiot <?php if(!empty($usuarioErr)) { echo "has-error"; } ?>">Usuario:</label>
		   	<div class="col-sm-11 <?php if(!empty($usuarioErr)) { echo "has-error"; } ?>">		  
			   <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Seu usuario de acesso"
				  <?php 
				  if(isset($_POST['usuario'])) {
				    echo " value=\"".$_POST['usuario']."\"";
				  }
				  ?> required readonly>
			</div>
		  </div> <!-- /form group -->
		  <div class="form-group">
			<label for="input_senha" class="col-sm-1 control-label col-form-cbiot <?php if(!empty($senhaErr)) { echo "has-error"; } ?>"><?php echo8("Senha");?>:</label>
		   	<div class="col-sm-11 <?php if(!empty($senhaErr)) { echo "has-error"; } ?>">		  
			   <input type="password" class="form-control" id="senha" name="senha" placeholder="Sua senha de acesso"
				  <?php 
				  if(isset($_POST['senha'])) {
				    echo " value=\"".$_POST['senha']."\"";
				  }
				  ?> required>
			</div>
		  </div> <!-- /form group -->
		</div>
	  </div> <!-- /panel -->
	  <?php endif; ?>
	  <div class="panel panel-info">
	    <div class="panel-heading">
          <h3 class="panel-title"><?php echo8("Informações Adicionais");?></h3>
        </div>
		<div class="panel-body">
		  <div class="form-group">
		    <div class="col-sm-12">		  
			   <textarea class="form-control" id="info_adicional" name="info_adicional" rows="4" 
			   placeholder="<?php if ($cat != VISITANTES) 
										echo8("Digite aqui qualquer informação adicional relevante."); 
									else
										echo8("Por favor especifique o nome do seu responsável.\nIndique seu número de passaporte se for estrangeiro."); 
							?>"><?php 
				 if(isset($_POST['info_adicional'])) {
				    echo $_POST['info_adicional'];
				 } ?></textarea>
			</div>
		  </div> <!-- /form group -->
		</div>
	  </div> <!-- /panel -->
    <button class="btn btn-lg btn-primary btn-block" type="submit">Cadastrar</button>
    </form>
	</div> <!-- /container -->
	</div> <!-- /wrap -->
	
	<!-- Scripts numeric and date field control -->
	<script src="./js/JavaScriptUtil.js"></script>
	<script src="./js/Parsers.js"></script>
	<script src="./js/InputMask.js"></script>
	<script src="./js/CbiotScripts.js"></script>
	<!-- Masks for form fields -->
	<script> 
    function setup() {
		//Set up numeric masks
		var numericMask = new InputMask("##########", "rg");
		var numericMask2 = new InputMask("########", "matricula");
		var numericMask3 = new InputMask("#####-###", "cep");
    }
	</script>
	
	<script type="text/javascript" src="./js/jquery-1.10.2.min.js" charset="UTF-8"></script>
	
	<script type="text/javascript" src="./js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
	<script type="text/javascript" src="./js/bootstrap-datetimepicker.pt-BR.js" charset="UTF-8"></script>
	<script type="text/javascript">
    $('.form_date').datetimepicker({
        language:  'pt-BR',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0
    });
	</script>
	<!-- Script for copy and hidden fields control -->
	<script> 
	function CopyField(from_field, to_field) {
		to_field.value = from_field.value;
	}
	</script>
	<!-- Script for foreign people -->
	<script> 
    function ForeignControl() {
		
		if(document.getElementById('est_check').checked) {
			// set to null, hide and deactivate control
			document.getElementById('rg').value = "0000000000";
			document.getElementById('data_rg').value = "00/00/0000";
			document.getElementById('orgao_rg').value = "XXX";
			document.getElementById('cpf').placeholder = "XXXXXXXX";
			document.getElementById('input_rg_label').style.visibility = "hidden";
			document.getElementById('input_rg_div').style.visibility = "hidden";
			document.getElementById('input_emissao_label').style.visibility = "hidden";
			document.getElementById('input_emissao_div').style.visibility = "hidden";
			document.getElementById('input_orgao_div').style.visibility = "hidden";
			document.getElementById('input_cpf_label').innerHTML = "Passaporte:";
			
		} else {
			// unhide and setup form back
			document.getElementById('rg').value = "";
			document.getElementById('data_rg').value = "";
			document.getElementById('orgao_rg').value = "";
			document.getElementById('cpf').placeholder = "xxx.xxx.xxx-xx";
			document.getElementById('input_rg_label').style.visibility = "visible";
			document.getElementById('input_rg_div').style.visibility = "visible";
			document.getElementById('input_emissao_label').style.visibility = "visible";
			document.getElementById('input_emissao_div').style.visibility = "visible";
			document.getElementById('input_orgao_div').style.visibility = "visible";
			document.getElementById('input_cpf_label').innerHTML = "CPF:";
		}
		
	}
	</script>
	<?php if($cat == PROFESSORES) : ?>
	<!-- Script for professors leader control -->
	<script> 
    function LeaderControl() {
		if(document.getElementById('tipo_lider').checked) {
			document.getElementById('lider_label').style.visibility = "hidden";
			document.getElementById('lider_associado').style.visibility = "hidden";
			document.getElementById('lider_help').style.visibility = "hidden";
		} else if(document.getElementById('tipo_associado').checked || 
				 document.getElementById('tipo_externo').checked) {
			document.getElementById('lider_label').style.visibility = "visible";
			document.getElementById('lider_associado').style.visibility = "visible";
			document.getElementById('lider_help').style.visibility = "visible";
		}
    }
	</script>	
	<?php endif; 
	if($cat == ALUNOS) : ?>
	<!-- Auto-select leader -->
	<script>
		document.getElementById("orientador").onchange = function() {
<?php
	$sql = "SELECT * FROM usuarios WHERE categoria_id = 1 AND ativo = 1";
	$result = query($sql);
	while($row = $result->fetch_assoc()): ?>
			if(getSelectedText('orientador') == '<?php echo $row['nome']; ?>') {
<?php
	$user = new User("");
	$user->GetDataFromDB($row['id']);
	if($user->professor_lider):
?>
				document.getElementById('lider').value = '<?php echo $user->nome; ?>';
				document.getElementById('lider_id').value = '<?php echo $user->id; ?>';
<?php else : ?>
				document.getElementById('lider').value = '<?php echo $user->lider; ?>';
				document.getElementById('lider_id').value = '<?php echo $user->lider_id;; ?>';
<?php endif; ?>
			}
<?php endwhile; ?>
		}
	</script>
	<?php endif; ?>
	<!-- Keep values of select and checkbox after submit -->
	<script>
<?php if(isset($_POST['sexo'])) : ?>		document.getElementsByName('sexo')[0].value = '<?php echo $_POST['sexo']; ?>';
<?php endif;?>
<?php if(isset($_POST['estado_civil'])) : ?>		document.getElementsByName('estado_civil')[0].value = '<?php echo $_POST['estado_civil']; ?>';
<?php endif;?>
<?php if(isset($_POST['escolaridade'])) : ?>		document.getElementsByName('escolaridade')[0].value = '<?php echo $_POST['escolaridade']; ?>';
<?php endif;?>
<?php if(isset($_POST['municipio'])) : ?>		document.getElementsByName('municipio')[0].value = '<?php echo $_POST['municipio']; ?>';
<?php endif;?>
<?php if(isset($_POST['pais'])) : ?>		document.getElementsByName('pais')[0].value = '<?php echo $_POST['pais']; ?>';
<?php endif;?>

		//System defined contacts (required)
<?php if($cat != VISITANTES): ?>
		document.getElementsByName('tipo_email')[0].value = '<?php echo "Email Principal" ?>';
<?php if(isset($_POST['email'])) : ?>		document.getElementsByName('email')[0].value = '<?php echo $_POST['email']; ?>';
<?php endif;?>
<?php endif; ?>
		document.getElementsByName('tipo_email_alt')[0].value = '<?php echo "Email Alternativo" ?>';
		document.getElementsByName('tipo_telefone')[0].value = '<?php echo "Telefone Pessoal" ?>';
		document.getElementsByName('tipo_ramal')[0].value = '<?php echo "Ramal UFRGS" ?>';
<?php if(isset($_POST['email_alt'])) : ?>		document.getElementsByName('email_alt')[0].value = '<?php echo $_POST['email_alt']; ?>';
<?php endif;?>
<?php if(isset($_POST['telefone'])) : ?>		document.getElementsByName('telefone')[0].value = '<?php echo $_POST['telefone']; ?>';
<?php endif;?>
<?php if(isset($_POST['ramal'])) : ?>		document.getElementsByName('ramal')[0].value = '<?php echo $_POST['ramal']; ?>';
<?php endif;?>

		//Dynamic part of contacts
		<?php
			$i=0;
			while(isset($_POST["contato_".$i])) :
				if($i > 0) :
		?>window.onload = addContact();
		<?php	endif; ?>document.getElementById(<?php echo "'contato_".$i."'"; ?>).value = '<?php echo $_POST["contato_".$i]; ?>';
		document.getElementById(<?php echo "'tipo_contato_".$i."'"; ?>).value = '<?php echo $_POST["tipo_contato_".$i]; ?>';
		<?php 
				$i++;
			endwhile; 
		?>
		
		//Dynamic part of contacts
		<?php
			if($cat != VISITANTES) :
				$i=0;
				while(isset($_POST["dispositivo_mac_".$i])) :
					if($i > 0) :
		?>window.onload = addDevice();
		<?php	endif; ?>document.getElementById(<?php echo "'dispositivo_mac_".$i."'"; ?>).value = '<?php echo $_POST["dispositivo_mac_".$i]; ?>';
		document.getElementById(<?php echo "'dispositivo_registration_".$i."'"; ?>).value = '<?php echo $_POST["dispositivo_registration_".$i]; ?>';
		document.getElementById(<?php echo "'dispositivo_location_".$i."'"; ?>).value = '<?php echo $_POST["dispositivo_location_".$i]; ?>';
		document.getElementById(<?php echo "'tipo_dispositivo_".$i."'"; ?>).value = '<?php echo $_POST["tipo_dispositivo_".$i]; ?>';
		document.getElementById(<?php echo "'tipo_hostname_".$i."'"; ?>).value = '<?php echo $_POST["tipo_hostname_".$i]; ?>';
		<?php 
					$i++;
				endwhile; 
			endif;
		?>
		
<?php 
	//Defined lider
if(isset($_POST['tipo_professor']) AND $cat == PROFESSORES) :
	if(strcmp($_POST["tipo_professor"],"lider") == 0): ?>		document.getElementById('tipo_lider').checked;
<?php endif; if(strcmp($_POST["tipo_professor"],"associado") == 0): ?>		document.getElementById('tipo_associado').checked = 1; LeaderControl();
<?php endif; if(strcmp($_POST["tipo_professor"],"externo") == 0): ?>		document.getElementById('tipo_externo').checked = 1; LeaderControl();
<?php endif;
endif;?>
<?php if(isset($_POST['lider_associado']) AND $cat == PROFESSORES) : ?>		document.getElementsByName('lider_associado')[0].value = '<?php echo $_POST['lider_associado']; ?>';
<?php endif;?>
	
		//Defined orientador and lider
		<?php if($cat == ALUNOS) : ?>
<?php if(isset($_POST['orientador'])) : ?>		document.getElementsByName('orientador')[0].value = '<?php echo $_POST['orientador']; ?>';
<?php endif;?>
<?php if(isset($_POST['lider'])) : ?>		document.getElementsByName('lider')[0].value = '<?php echo $_POST['lider']; ?>';
<?php endif;?>
		<?php endif; ?>

		//Selected Groups
		<?php
			$sql = "SELECT * FROM grupo";
			$result = query($sql);
			while($row = $result->fetch_assoc()):
		?>document.getElementById(<?php echo "'grupo_".$row['id']."'"; ?>).checked = <?php if(isset($_POST["grupo_".$row['id']])) echo "true"; else echo "false"; ?>;
		<?php endwhile; ?>
	</script>

<?php
endif;
if($showsuccess) :
?>

	<body>
	<div id="wrap">
		<div class="container">
			<div class="alert alert-success noprint" style="text-align: center; font-weight: bold;">
				<?php echo8("Novo usuário cadastrado com sucesso! <br> 
				<font color='red'>Por favor, clique no botão imprimir e entregue a ficha de cadastro devidamente assinada na secretaria para proseguir com os procedimentos de cadastro.<br>
				Atenção: Assinando esta ficha você automaticamente concorda com os termos dos seguites documentos: <br></font>
				- <a href='".LINK_DOCUMENTO_1."' target='_blank'>Políticas da Rede Cbiot</a> <br>
				- <a href='".LINK_DOCUMENTO_2."' target='_blank'>Políticas Gerais do Cbiot</a> <br>"); ?>
			</div>
			<div class="row noprint" style="padding-bottom: 20px;">
				<div class="col-md-4">
					<a href="login.php" class="btn btn-primary btn-block" alt="<?php echo8("Voltar para Login"); ?>">
						<span class="glyphicon glyphicon-arrow-left"></span> <?php echo8("Voltar para Login"); ?>
					</a>
				</div>
				<div class="col-md-4">
				</div>
				<div class="col-md-4">
					<a href="javascript:window.print()" class="btn btn-primary btn-block" alt="<?php echo8("Imprimir"); ?>">
						<span class="glyphicon glyphicon-print"></span> <?php echo8("Imprimir"); ?>
					</a>
				</div>
			</div>
			<div class="panel panel-info noprint">
				<div class="panel-heading">
					<h3 class="panel-title">Dados Pessoais</h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<label for="input_nome" class="col-sm-1 control-label col-form-cbiot">Nome:</label>
						<div class="col-sm-11">
							<p><?php echo $new_user->nome; ?></p>
						</div>
					</div>							
					<div class="row">
						<label for="input_sexo" class="col-sm-1 control-label col-form-cbiot">Sexo:</label>
						<div class="col-sm-2">		  
							<p><?php if($new_user->sexo == 'f' || $new_user->sexo == 'F') {
											echo "Feminino";
										} else {
											echo "Masculino";
										}
							?></p>
						</div>
						<label for="input_nascimento" class="col-sm-2 control-label col-form-cbiot">Data Nascimento:</label>
						<div class="col-sm-2">		  
							<p><?php echo $new_user->nascimento;?></p>
						</div>
						<label for="input_estado_civil" class="col-sm-2 control-label col-form-cbiot">Estado Civil:</label>
						<div class="col-sm-3">		  
							<p><?php echo $new_user->estado_civil;?></p>
						</div>
					</div> <!-- /row -->
					<div class="row">
						<label for="input_rg" class="col-sm-1 control-label col-form-cbiot">RG:</label>
						<div class="col-sm-2">		  
							<p><?php echo $new_user->rg;?></p>
						</div>
						<label for="input_emissao_rg" class="col-sm-2 control-label col-form-cbiot">Data e Emissor:</label>
						<div class="col-sm-1">		  
							<p><?php echo $new_user->data_rg;?></p>
						</div>
						<div class="col-sm-1">		  
							<p><?php echo $new_user->emissor_rg;?></p>
						</div>
						<label for="input_cpf" class="col-sm-2 control-label col-form-cbiot"><?php if(substr($new_user->cpf, 3) == '.') echo "CPF"; else echo "Passaporte"; ?>:</label>
						<div class="col-sm-3">		  
							<p><?php echo $new_user->cpf;?></p>
						</div>
					</div> <!-- /row -->
					<div class="row">
						<label for="input_nome_pai" class="col-sm-1 control-label col-form-cbiot">Nome Pai:</label>
						<div class="col-sm-11">		  
							<p><?php echo $new_user->nome_pai;?></p>
						</div>
					</div>
					<div class="row">
						<label for="input_nome" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Nome Mãe");?>:</label>
						<div class="col-sm-11">		  
							<p><?php echo $new_user->nome_mae;?></p>
						</div>
					</div> <!-- /row -->
					<hr>
					<div class="row">
						<label for="input_escolaridade" class="col-sm-1 control-label col-form-cbiot">Escolaridade:</label>
						<div class="col-sm-2">		  
							<p><?php echo $new_user->escolaridade;?></p>
						</div>
						<label for="input_matricula" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Nro. Cartão");?>:</label>
						<div class="col-sm-2">		  
							<p><?php echo $new_user->matricula;?></p>
						</div>
					</div> <!-- /row -->
					<div class="row">
						<label for="input_instituicao" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Instituição");?>:</label>
						<div class="col-sm-4">		  
							<p><?php echo $new_user->instituicao;?></p>
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
							<p><?php echo $new_user->endereco->logradouro;?></p>
						</div>
						<label for="input_numero" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Número");?>:</label>
						<div class="col-sm-1">		  
							<p><?php echo8($new_user->endereco->numero);?></p>
						</div>
						<label for="input_complemento" class="col-sm-1 control-label col-form-cbiot">Comp.:</label>
						<div class="col-sm-1" style="padding-left: 5px">		  
							<p><?php echo8($new_user->endereco->complemento);?></p>
						</div>
					</div> <!-- /row -->
					<div class="row">
						<label for="input_bairro" class="col-sm-1 control-label col-form-cbiot">Bairro:</label>
						<div class="col-sm-7">		  
							<p><?php echo $new_user->endereco->bairro;?></p>
						</div>
						<label for="input_cep" class="col-sm-1 control-label col-form-cbiot">CEP:</label>
						<div class="col-sm-3 col-form-cbiot" style="padding-right: 15px">		  
							<p><?php echo8($new_user->endereco->cep);?></p>
						</div>
					</div> <!-- /row -->
					<div class="row">
						<label for="input_municipio" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Município");?>:</label>
						<div class="col-sm-7">		  
							<p><?php echo $new_user->endereco->municipio;?></p>
						</div>
						<label for="input_pais" class="col-sm-1 control-label col-form-cbiot"><?php echo8("País");?>:</label>
						<div class="col-sm-3 col-form-cbiot" style="padding-right: 15px">		  
							<p><?php echo $new_user->endereco->pais;?></p>
						</div>
					</div> <!-- /row -->
				</div> <!-- /panel body -->
			</div> <!-- /panel -->
			<div class="panel panel-info noprint">
				<div class="panel-heading">
					<h3 class="panel-title">Contatos</h3>
				</div>
				<div class="panel-body" id="contacts_container">
					<?php foreach($new_user->contatos as $contato) : ?>
					<div class="row">
						<label for="input_tipo_contato" class="col-sm-2 control-label col-form-cbiot"><?php echo $contato->tipo_contato; ?>:</label>
						<div class="col-sm-4">		  
							<p><?php echo8($contato->contato);?></p>
						</div>
					</div>
					<?php endforeach; ?>
			   </div> <!-- /panel body -->
			</div> <!-- /panel -->
			<?php if(count($new_user->dispositivos) > 0) : ?>
			<div class="panel panel-info noprint">
				<div class="panel-heading">
					<h3 class="panel-title">Dispositivos</h3>
				</div>
				<div class="panel-body" id="devices_container">
					<?php foreach($new_user->dispositivos as $dispositivo) : ?>
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
							<p><?php echo $new_user->categoria;?></p>
						</div>
						<?php if($new_user->categoria_id == 1) : ?>
						<div class="col-sm-5">
							<p><?php 
									if($new_user->professor_lider) {
										echo8("- Professor Líder");
									} else if($new_user->professor_externo) { 
										echo8("- Professor Externo ao CBIOT");
									} else {
										echo8("- Professor Associado");
									}
							?></p>
						</div>
						<?php endif; ?>
					</div> <!-- /row -->
					<?php if(!$new_user->professor_lider AND $new_user->categoria_id == 1) : ?>
					<div class="row">
						<label for="input_lider" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Líder"); ?>:</label>
						<div class="col-sm-11">
							<p><?php echo $new_user->lider;?></p>
						</div>
					</div> <!-- /row -->
					<?php endif; ?>
					<?php if($cat == ALUNOS) : ?>
					<div class="row">
						<label for="input_lider" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Líder"); ?>:</label>
						<div class="col-sm-11">
							<p><?php echo $new_user->lider;?></p>
						</div>
					</div> <!-- /row -->
					<div class="row">
						<label for="input_orientador" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Orientador"); ?>:</label>
						<div class="col-sm-11">
							<p><?php echo $new_user->orientador;?></p>
						</div>
					</div> <!-- /row -->
					<?php endif; ?>
					<?php if($cat == FUNCIONARIOS OR $cat == VISITANTES) : ?>
					<div class="row">
						<label for="input_cargo" class="col-sm-1 control-label col-form-cbiot">Cargo:</label>
						<div class="col-sm-4">
							<p><?php echo $new_user->cargo;?></p>
						</div>
					</div> <!-- /row -->
					<?php endif; ?>
					<div class="row">
						<label for="input_tipo_grupo" class="col-sm-1 control-label col-form-cbiot">Grupos:</label>
						<div class="col-sm-11">
							<p>
					<?php foreach($new_user->grupos as $grupo) : ?>
							<?php echo8($grupo->acronimo.", ");?>
					<?php endforeach; ?>
							</p>
						</div>
					</div>
					<div class="row">
						<label for="input_cargo" class="col-sm-2 control-label col-form-cbiot">Data de Cadastro:</label>
						<div class="col-sm-4">
							<p><?php echo $new_user->data_cadastro;?></p>
						</div>
					</div> <!-- /row -->
				</div> <!-- /panel body -->
			</div> <!-- /panel -->
			<?php if($new_user->acesso->id) : ?>
			<div class="panel panel-info noprint">
				<div class="panel-heading">
					<h3 class="panel-title"><?php echo8("Acesso ao sistema");?></h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<label for="input_usuario" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Usuario"); ?>:</label>
						<div class="col-sm-11">
							<p><?php echo $new_user->acesso->usuario;?></p>
						</div>
					</div> <!-- /row -->
				</div> <!-- /panel-body -->
			</div> <!-- /panel -->
			<?php endif; ?>
			<?php if($new_user->comentario) :?>
			<div class="panel panel-info noprint">
				<div class="panel-heading">
					<h3 class="panel-title"><?php echo8("Informações Adicionais");?></h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-12">		  
							<p><?php echo $new_user->comentario;?></p>
						</div>
					</div> <!-- /row -->
				</div> <!-- /panel-body -->
			</div> <!-- /panel -->
			<?php endif; ?>
			<!-- A parte abaixo só sera vista na impressão -------------------------------------------------------->
			<div class="panel panel-info toprint">
				<div class="panel-heading">
					<h3 class="panel-title " style="text-align: center; font-weight: bold;">
					<?php echo8("UNIVERSIDADE FEDERAL DO RIO GRANDE DO SUL <br> Centro de Biotecnologia<br><br><br> FICHA DE CADASTRO");?></h3>
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
							<td colspan="3"><?php echo $new_user->nome; ?></td>
						</tr>
						<tr>
							<td><label for="input_nascimento" class="col-sm-2 control-label col-form-cbiot">Data Nascimento:</label></td>
							<td><p><?php echo $new_user->nascimento;?></p></td>
							<td><label for="input_cpf" class="col-sm-2 control-label col-form-cbiot"><?php if(substr($new_user->cpf, 3) == '.') echo "CPF"; else echo "Passaporte"; ?>:</label></td>
							<td><p><?php echo $new_user->cpf;?></p></td>
						</tr>
						<tr>
							<td><label for="input_escolaridade" class="col-sm-1 control-label col-form-cbiot">Escolaridade:</label></td>
							<td><p><?php echo $new_user->escolaridade;?></p></td>
							<td><label for="input_matricula" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Nro. Cartão");?>:</label></td>
							<td><p><?php echo $new_user->matricula;?></p></td>
						</tr>
					</table>
				</div> <!-- /panel-body -->
			</div> <!-- /panel -->
			<?php if(count($new_user->contatos) > 0) : ?>
			<div class="panel panel-info toprint">
				<div class="panel-heading">
					<h3 class="panel-title" style="text-align: center; font-weight: bold;">Contatos</h3>
				</div>
				<div class="panel-body" id="contacts_container">
					<table width="100%" border='0'>
						<?php $tmp =  $new_user->contatos;
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
			<div class="panel panel-info toprint">
				<div class="panel-heading">
					<h3 class="panel-title" style="text-align: center; font-weight: bold;">Dados CBIOT</h3>
				</div>
				<div class="panel-body">
					<table width="100%" border='0'>
						<tr>
							<td><label for="input_categoria" class="col-sm-1 control-label col-form-cbiot">Categoria:</label></td>
							<td><p><?php echo $new_user->categoria;?> 
									<?php if($cat == PROFESSORES) : ?>
										<?php 
												if($new_user->professor_lider) {
													echo8(" - Professor Líder");
												} else if($new_user->professor_externo) { 
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
							<td><p><?php echo $new_user->cargo;?></p></td>
						</tr>
						<?php endif; ?>
						<?php if($cat == PROFESSORES AND $new_user->professor_lider == 0) : ?>
						<tr>
							<td><label for="input_lider" class="col-sm-1 control-label col-form-cbiot"><?php echo8("Líder"); ?>:</label></td>
							<td><p><?php echo $new_user->lider;?></p></td>
						</tr>
						<?php endif; ?>
						<tr>
							<td><label for="input_tipo_grupo" class="col-sm-1 control-label col-form-cbiot">Grupos:</label></td>
							<td><p>
								<?php foreach($new_user->grupos as $grupo) : ?>
										<?php echo8($grupo->acronimo.", ");?>
								<?php endforeach; ?></p>
							</td>
						</tr>
						<?php if($cat == VISITANTES) : ?>
						<tr>
							<td><label for="input_cargo" class="col-sm-1 control-label col-form-cbiot">Fim da visita:</label></td>
							<td><p><?php echo $new_user->data_expiracao;?></p></td>
						</tr>
						<?php endif; ?>
					</table>
				</div> <!-- /panel body -->
			</div> <!-- /panel -->			
			<div class="panel panel-info toprint">
				<div class="panel-heading">
					<h3 class="panel-title" style="text-align: center; font-weight: bold;"><?php echo8("Assinaturas dos Responsáveis");?></h3>
				</div>
				<div class="panel-body">
					<table width="100%" border='0'>
						<?php echo8("Eu, ");?><?php echo $new_user->nome;?><?php echo8(", concordo com os termos dos documentos apresentados durante o cadastro.");?>
						<tr style="text-align: center;">
							<?php if($cat == ALUNOS) : ?>
							<td><br><br><?php echo $new_user->nome;?><br><b><?php echo8("Ass. Aluno");?></b></td>
							<td><br><br><?php echo $new_user->orientador;?><br><b><?php echo8("Ass. Orientador");?></b></td>
							<td><br><br><?php echo $new_user->lider;?><br><b><?php echo8("Ass. Líder");?></b></td>
							<?php elseif($cat == PROFESSORES) :	?>
							<td><br><br><?php echo $new_user->nome;?><br><b><?php echo8("Assinatura do Professor");?></b></td>
							<?php elseif($cat == FUNCIONARIOS) :
									if($new_user->categoria_id == 9) :
							?>
							<td><br><br><?php echo $new_user->nome;?><br><b><?php echo8("Ass. Funcionário");?></b></td>
							<td><br><br><br><b><?php echo8("Ass. Gerencia Icubadora");?></b></td>
							<td><br><br><br><b><?php echo8("Ass. Empresa Incubada");?></b></td>
							<?php	else : ?>
							<td><br><br><?php echo $new_user->nome;?><br><b><?php echo8("Ass. do Funcionário");?></b></td>
							<td><br><br><br><b><?php echo8("Ass. do Diretor do Instituto");?></b></td>
							<?php 	endif; 
								  elseif($cat == VISITANTES) :	?>
							<td><br><br><?php echo $new_user->nome;?><br><b><?php echo8("Ass. do Visitante");?></b></td>
							<td><br><br><br><b><?php echo8("Ass. do Responsável");?></b></td>
							<?php endif; ?>
						</tr>
					</table>
				</div> <!-- /panel-body -->
			</div> <!-- /panel -->
		<!-- A parte acima só sera vista na impressão -------------------------------------------------------->
		</div> <!-- /container -->
	</div> <!-- /wrap -->

<?php
endif;
if($showerror) :
?>

<body>
	<div id="wrap">
		<div class="container">
			<div class="alert alert-danger" style="text-align: center; font-weight: bold;">
				<?php echo8("Ops, ocorreu um erro inesperado, favor contatar a administração!"); ?>
			</div>
			<?php 
				$log->lwrite("ERRO: ".var_export($_POST,true));
			?>
		</div> <!-- /container -->
	</div> <!-- /wrap -->

<?php endif; ?>
	<?php include("footer.php"); ?>	
	</body>
</html>