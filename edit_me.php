<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Página de edição de dados pessoais.
 *
 *	Observação:	Alguns dados devem ser fixos.
 *
**/

$page_access_level = 5;						//Administração, Professores e Outros
require("valida_session.php");

$id = $_SESSION["user_id"];						// id do usuario acessando o sistema
$cat = $access_level;							// categoria do usuario acessando o sistema (vem de valida_session)


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
$confirmacao	= $confirmacaoErr = 
$senha 			= $senhaErr = "";

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
	// if (empty($_POST["cpf"])) {
		// $cpfErr = "<br>- CPF obrigatório";
		// $err_count ++;
	// } else {
		// $cpf = test_input($_POST["cpf"]);
		// if(strlen($cpf) > MAX_CPF) {
			// $cpf = substr($cpf,0,MAX_CPF);
		// }
	// }
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
	if (empty($_POST["matricula"])) {	// Não obrigatório
		//$matriculaErr = "<br>- Número de matrícula obrigatório";
		//$err_count ++;
		$matricula = "";
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
	
	// DADOS CBIOT
	if($cat == 3 OR $cat == 4) {
		$categoria = $_POST['categoria'];
	}
	
	if($cat > 3) {
		if (empty($_POST["cargo"])) {
			$cargoErr = "<br>- Cargo obrigatório";
			$err_count ++;
		} else {
			$cargo = test_input($_POST["cargo"]);
			if(strlen($cargo) > MAX_CARGO) {
				$cargo = substr($cargo,0,MAX_CARGO);
			}
		}
	}
	
	//ACESSO
	if (empty($_POST["usuario"])) {
		$usuarioErr = "<br>- Usuário de acesso obrigatório";
		$err_count ++;
	} else {
		$usuario = test_input($_POST["usuario"]);
		if(strlen($usuario) > MAX_USUARIO) {
			$usuario = substr($usuario,0,MAX_USUARIO);
		}
	}
	if (empty($_POST["senha"])) {
		//$usuarioErr = "<br>- Usuário de acesso obrigatório";
		//$err_count ++;
	} else {
		$senha = test_input($_POST["senha"]);
		if(strlen($senha) > MAX_SENHA) {
			$senha = substr($senha,0,MAX_SENHA);
		}
	}
	if (empty($_POST["confirmacao"])) {
		//$usuarioErr = "<br>- Usuário de acesso obrigatório";
		//$err_count ++;
	} else {
		$confirmacao = test_input($_POST["confirmacao"]);
		if(strlen($confirmacao) > MAX_SENHA) {
			$confirmacao = substr($confirmacao,0,MAX_SENHA);
		}
	}
	// Se um dos campos estiver preenchido e o outro não:
	if(empty($_POST["senha"]) XOR empty($_POST["confirmacao"])){
		if(empty($_POST["senha"])){
			$senhaErr = "<br>- Digite uma senha valida";
			$err_count ++;
		} else {
			$confirmacaoErr = "<br>- Informe sua senha anterior";
			$err_count ++;
		}
	}
	
	//Verificamos se a senha da confirmação é a mesma que a usada na sessão
	if(!empty($senha) AND !empty($confirmacao)){
		if(strcmp($confirmacao,$_SESSION["user_passwd"])) {
			$confirmacaoErr = "<br>- Senha anterior invalida (se você esqueceu informe a administração)";
			$err_count ++;
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
		
		// Criamos objeto do tipo usuario
		$update_user = new User("");
		
		if ($update_user->GetDataFromDB($id) > 0) {
			// TODO ERROR PAGE
			display_error("Erro: Usuário não encontrado!",1);
			exit(1);
		}
	
		//Alterações no Endereço
		$update_user->endereco->logradouro = $logradouro;
		$update_user->endereco->numero = $numero;
		$update_user->endereco->complemento = isset($_POST["complemento"]) ? $complemento : "";
		$update_user->endereco->bairro = $bairro;
		$update_user->endereco->cep = $cep;
		$update_user->endereco->municipio_id = $municipio;
		$update_user->endereco->pais_id = $pais;
		
		$err_count += $update_user->endereco->UpdateAddressData();
								
		//Acesso ao sistema
		$update_user->acesso->usuario = $usuario;
		$update_user->acesso->senha = "";
		if(empty($senha) == FALSE) {
			$update_user->acesso->senha = $senha;
		}
		$err_count += $update_user->acesso->UpdateAccessData();
		
		//Grupos: remove todos os grupos existentes, adiciona tudo novamente.
		$update_user->RemoveAllGroups();
		$sql = "SELECT * FROM grupo";
		$result = query($sql);
		while($row = $result->fetch_assoc()) {
			if(isset($_POST["grupo_".$row["id"]])) {
				$group = new Group(NULL,NULL);
				$group->id = $row["id"];
				$group->grupo = $row["grupo"];
				$err_count += $update_user->AddGroup($group);
			}
		}
		
		//Contatos: remove todos existentes, adiciona tudo novamente
		$err_count += $update_user->RemoveAllContacts();
		//Adicionar lista novamente
		for ($i = 0 ; $i < 10 ; $i++) {
			if(isset($_POST["contato_".$i])) {
				$contato = test_input($_POST["contato_".$i]);
				if(strlen($contato) > MAX_CONTATO) {
					$contato = substr($contato,0,MAX_CONTATO);
				}
				$contact = new Contact(NULL,$_POST["tipo_contato_".$i],$contato);
				if(strcmp($contato,"") != 0)	//se não for em branco
					$err_count += $update_user->AddContact($contact);
			}
		}
				
		//Dispositivos: verifica a existencia de cada um no banco, remove salientes, cadastra novos
		$ondb = array();
		$onform = array();
		// Populando array da memoria do banco de dados
		$sql = "SELECT * FROM dispositivos WHERE usuarios_id = %d ";
		$result = query($sql,$update_user->id);
		//se existirem dispositivos cadastrados vamos recuperar todos e colocar no array
		while ($row = $result->fetch_assoc()) {
			$tmp = new Device($row['usuarios_id'],$row['tipos_dispositivo_id'],$row['endereco_mac'],$row['patrimonio'],$row['hostname'],$row['ip'],$row['localizacao']);
			$tmp->id = $row['id'];
			array_push($ondb, $tmp);
		}
		// Populando array dos dados do formulário
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
					array_push($onform, $device);
			}
		}
		if(!empty($onform) AND !empty($ondb)) {	//se não tiver nenhum dispositivo no formulario então nem precisa verificar'
			foreach($ondb as $key_db => $device_ondb) {	// para cada dispositivo no bd verifica se tem o mesmo no form
				foreach($onform as $key_form => $device_onform) {
					if($device_ondb->tipos_dispositivo_id == $device_onform->tipos_dispositivo_id AND
						$device_ondb->endereco_mac == $device_onform->endereco_mac AND
						$device_ondb->patrimonio == $device_onform->patrimonio AND
						$device_ondb->hostname == $device_onform->hostname AND
						$device_ondb->localizacao == $device_onform->localizacao) {
						//se os dados forem iguais no form e no db, remove dos arrays (nao serao adicionados nem excluidos)
						unset($ondb[$key_db],$onform[$key_form]);
					}
				}
			}
		}
		// Remove os que sobraram
		foreach($ondb as $key_db => $device_ondb) {
			$err_count += $update_user->RemoveDevice($device_ondb);
		}
		// Cadastra os que são novos
		foreach($onform as $key_db => $device_onform) {
			$err_count += $update_user->AddDevice($device_onform);
		}
				
		//Definições do usuário
		$update_user->nome = $nome;
		$update_user->nascimento = $nascimento;
		$update_user->rg = $rg;
		$update_user->data_rg = $data_rg;
		$update_user->emissor_rg = $orgao_rg;
		//$update_user->cpf = $cpf; // apenas administração pode mudar
		$update_user->matricula = isset($matricula) ? $matricula : "";
		$update_user->instituicao = isset($instituicao) ? $instituicao : "";
		$update_user->sexo = $sexo;
		$update_user->nome_mae = $nome_mae;
		$update_user->nome_pai = $nome_pai;
		$update_user->escolaridade_id = $escolaridade;
		$update_user->estado_civil_id = $estado_civil;
		$update_user->cargo = $cargo;		// profs sem cargo
		$update_user->comentario = isset($_POST["info_adicional"]) ? $info_adicional : "";
		
		// Alunos e Funcionários podem mudar sua categoria
		if($cat == 3 OR $cat == 4) {
			$update_user->categoria_id = $categoria;
		}
		
		$err_count += $update_user->UpdateUserData();
		
		if ($err_count == 0) {
			$showsuccess = 1;
			$showform = 0;
			$updated_user = $update_user;
		} else {
			$showsuccess = 0;
			$showform = 0;
			$showerror = 1;
		}
	} else {
		$showsuccess = 0;
		$showform = 1;
		$user = new User("");
		if ($user->GetDataFromDB($id) > 0) {
			// TODO ERROR PAGE
			display_error("Usuário não encontrado!",1);
			exit(1);
		}
	}
} else { // if it is not a post then we will search for user data
	$user = new User("");
	if ($user->GetDataFromDB($id) > 0) {
		// TODO ERROR PAGE
		display_error("Usuário não encontrado!",1);
		exit(1);
	}
	//my_var_dump($user); //for debug purpose
}

if($showform) :
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

<body onload="setup()">
	<div id="wrap">
	<?php include("navbar.php"); ?>
	<div class="container">
	<form class="form-horizontal" role="form" action="<?php echo htmlspecialchars("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");?>" method="post">
	<?php if($err_count) : ?>
	  <div class="panel panel-danger">
        <div class="panel-heading">
          <h3 class="panel-title">Por favor, verifique os seguintes erros:</h3>
        </div>
	    <div class="panel-body">
		<?php 
		echo $nomeErr.$nascimentoErr.$rgErr.$data_rgErr.$orgao_rgErr.$cpfErr.$nome_paiErr.$nome_maeErr.$instituicaoErr.$matriculaErr.$logradouroErr.$numeroErr.$bairroErr.$cepErr.$cargoErr.$senhaErr.$usuarioErr.$confirmacaoErr;
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
				  } else {
					echo " value=\"".$user->nome."\"";
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
					  } else {
						echo " value=\"".$user->nascimento."\"";
					  }
					?> required>
					<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
				<input type="hidden" id="nascimento" name="nascimento" 
				<?php
				  //escrever novamente se o dado ja estiver ali
				  if(isset($_POST['nascimento'])) {
					echo " value=\"".$_POST['nascimento']."\"";
				  } else {
					echo " value=\"".$user->nascimento."\"";
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
		    <label for="input_rg" class="col-sm-1 control-label col-form-cbiot <?php if(!empty($rgErr)) { echo "has-error"; } ?>">RG:</label>
		   	<div class="col-sm-2 <?php if(!empty($rgErr)) { echo "has-error"; } ?>">		  
			   <input type="text" class="form-control" id="rg" name="rg" maxlength="10" placeholder="xxxxxxxxxx" 
				  <?php 
				  if(isset($_POST['rg'])) {
				    echo " value=\"".$_POST['rg']."\"";
				  }else {
					echo " value=\"".$user->rg."\"";
				  }
				  ?> required>
			</div>
			<label for="input_emissao_rg" class="col-sm-2 control-label col-form-cbiot <?php if(!empty($data_rgErr)) { echo "has-error"; } ?>">Data e Emissor:</label>
		   	<div class="col-sm-2 <?php if(!empty($data_rgErr)) { echo "has-error"; } ?>">		  
				<div class="input-group date form_date control-label" style='padding: 0;' data-date="" data-date-format="dd/mm/yyyy" data-link-field="data_rg" data-link-format="dd/mm/yyyy">
                    <input class="form-control" id="data_rg_cal" onchange="CopyField(this.form.data_rg_cal,this.form.data_rg)" placeholder="dd/mm/aaaa" type="text"
					<?php
					  //escrever novamente se o dado ja estiver ali
					  if(isset($_POST['data_rg'])) {
						echo " value=\"".$_POST['data_rg']."\"";
					  } else {
						echo " value=\"".$user->data_rg."\"";
					  }
					?> required>
					<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
				<input type="hidden" id="data_rg" name="data_rg" 
				<?php
				  //escrever novamente se o dado ja estiver ali
				  if(isset($_POST['data_rg'])) {
					echo " value=\"".$_POST['data_rg']."\"";
				  } else {
					echo " value=\"".$user->data_rg."\"";
				  }
				?>>
			</div>
		   	<div class="col-sm-1 <?php if(!empty($orgao_rgErr)) { echo "has-error"; } ?>">		  
			   <input type="text" class="form-control" id="orgao_rg" name="orgao_rg" placeholder="XXX"
				  <?php
				  //escrever novamente se o dado ja estiver ali
				  if(isset($_POST['orgao_rg'])) {
				    echo " value=\"".$_POST['orgao_rg']."\"";
				  } else {
					echo " value=\"".$user->emissor_rg."\"";
				  }
				  ?> required>
			</div>
			<label for="input_cpf" class="col-sm-1 control-label col-form-cbiot <?php if(!empty($cpfErr)) { echo "has-error"; } ?>"><?php if(substr($user->cpf, 3) == '.') echo "CPF"; else echo "Passaporte"; ?>:</label>
		   	<div class="col-sm-3 <?php if(!empty($cpfErr)) { echo "has-error"; } ?>">		  
			   <input type="text" class="form-control" id="cpf" name="cpf" maxlength="14" placeholder="xxx.xxx.xxx-xx"
				  <?php
				  //escrever novamente se o dado ja estiver ali
				  if(isset($_POST['cpf'])) {
				    echo " value=\"".$_POST['cpf']."\"";
				  } else {
					echo " value=\"".$user->cpf."\"";
				  }
				  ?> required readonly>
			</div>
          </div> <!-- /form group -->
		  <div class="form-group <?php if(!empty($nome_paiErr)) { echo "has-error"; } ?>">
		    <label for="input_nome_pai" class="col-sm-1 control-label col-form-cbiot">Nome Pai:</label>
		   	<div class="col-sm-11">		  
			   <input type="text" class="form-control" id="nome_pai" name="nome_pai" placeholder="Nome Completo do Pai"
				  <?php 
				  if(isset($_POST['nome_pai'])) {
				    echo " value=\"".$_POST['nome_pai']."\"";
				  } else {
					echo " value=\"".$user->nome_pai."\"";
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
				  } else {
					echo " value=\"".$user->nome_mae."\"";
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
				  } else {
					echo " value=\"".$user->instituicao."\"";
				  }
				  ?> required>
			</div>
			<label for="input_matricula" class="<?php if(!empty($matriculaErr)) { echo "has-error"; } ?> col-sm-1 control-label col-form-cbiot"><?php echo8("Nro. Cartão");?>:</label>
		   	<div class="col-sm-2 <?php if(!empty($matriculaErr)) { echo "has-error"; } ?>">		  
			   <input type="text" class="form-control" id="matricula" name="matricula" placeholder="XXXXXXXX"
				  <?php 
				  if(isset($_POST['matricula'])) {
				    echo " value=\"".$_POST['matricula']."\"";
				  } else {
					echo " value=\"".$user->matricula."\"";
				  }
				  if($user->categoria_id < 7) {
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
				  } else {
					echo " value=\"".$user->endereco->logradouro."\"";
				  }
				  ?> required>
			</div>
			<label for="input_numero" class="col-sm-1 control-label col-form-cbiot <?php if(!empty($numeroErr)) { echo "has-error"; } ?>"><?php echo8("Número");?>:</label>
		   	<div class="col-sm-1 col-form-cbiot <?php if(!empty($numeroErr)) { echo "has-error"; } ?>">		  
			   <input type="text" class="form-control" id="numero" name="numero" placeholder="XXX"
				  <?php 
				  if(isset($_POST['numero'])) {
				    echo " value=\"".$_POST['numero']."\"";
				  } else {
					echo " value=\"".$user->endereco->numero."\"";
				  }
				  ?> required>
			</div>
			<label for="input_complemento" class="col-sm-1 control-label col-form-cbiot">Comp.:</label>
		   	<div class="col-sm-1" style="padding-left: 5px">		  
			   <input type="text" class="form-control" id="complemento" name="complemento" placeholder="Ap. XX"
				  <?php 
				  if(isset($_POST['complemento'])) {
				    echo " value=\"".$_POST['complemento']."\"";
				  } else {
					echo " value=\"".$user->endereco->complemento."\"";
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
				  } else {
					echo " value=\"".$user->endereco->bairro."\"";
				  }
				  ?> required>
			</div>
			<label for="input_cep" class="col-sm-1 control-label col-form-cbiot <?php if(!empty($cepErr)) { echo "has-error"; } ?>">CEP:</label>
		   	<div class="col-sm-3 col-form-cbiot <?php if(!empty($cepErr)) { echo "has-error"; } ?>" style="padding-right: 15px">		  
			   <input type="text" class="form-control" id="cep" name="cep" placeholder="XXXXX-XXX"
				  <?php 
				  if(isset($_POST['cep'])) {
				    echo " value=\"".$_POST['cep']."\"";
				  } else {
					echo " value=\"".$user->endereco->cep."\"";
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
				<input type="text" class="form-control" id="contato_0" name="contato_0" placeholder="<?php echo8("E-mail ou número de acordo com o tipo selecionado.");?>" >
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
	  
	  <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title">Dados CBIOT</h3>
        </div>
	    <div class="panel-body">
		  <div class="form-group">
		    <label for="input_categoria" class="col-sm-1 control-label col-form-cbiot">Categoria:</label>
		   	<div class="col-sm-11">	
			<?php
			if($user->categoria_id == 1 OR $user->categoria_id > 9) : //se for professor ou visitante/outros não pode alterar categoria
			?>
				<select class="form-control" name="categoria" id="categoria" disabled>
				  <?php
					$sql = "SELECT * FROM categoria WHERE id > 0";
					$result = query($sql);
					while($row = $result->fetch_assoc()):
				  ?>
				  <option value="<?php echo $row['id']; ?>"><?php echo $row['categoria']; ?></option>
				  <?php endwhile; ?>
			   </select>
			<?php
			elseif($user->categoria_id > 1 AND $user->categoria_id < 7) : //se for estudante/funcionario
			?>
				<select class="form-control" name="categoria" id="categoria">
				  <?php
					$sql = "SELECT * FROM categoria WHERE id > 1 AND id < 7";
					$result = query($sql);
					while($row = $result->fetch_assoc()):
				  ?>
				  <option value="<?php echo $row['id']; ?>"><?php echo $row['categoria']; ?></option>
				  <?php endwhile; ?>
			   </select>
			<?php
			elseif($user->categoria_id > 6 AND $user->categoria_id < 10) : //se for funcionário
			?>
				<select class="form-control" name="categoria" id="categoria">
				  <?php
					$sql = "SELECT * FROM categoria WHERE id > 6 AND id < 10";
					$result = query($sql);
					while($row = $result->fetch_assoc()):
				  ?>
				  <option value="<?php echo $row['id']; ?>"><?php echo $row['categoria']; ?></option>
				  <?php endwhile; ?>
			   </select>
			<?php
			endif;
			?>
			</div>
		  </div> <!-- /form group -->
		  <?php
		  if($user->categoria_id >= 7) : //apenas para funcionarios e visitantes
		  ?>
		  <div class="form-group <?php if(!empty($cargoErr)) { echo "has-error"; } ?>">
		    <label for="input_cargo" class="col-sm-1 control-label col-form-cbiot">Cargo:</label>
		   	<div class="col-sm-11">		  
			   <input type="text" class="form-control" id="cargo" name="cargo" placeholder="<?php echo8("Descrição do Cargo");?>"
				  <?php 
				  if(isset($_POST['cargo'])) {
				    echo " value=\"".$_POST['cargo']."\"";
				  } else {
					echo " value=\"".$user->cargo."\"";
				  }
				  ?> required>
			</div>
		  </div> <!-- /form group -->
		  <?php
		  endif;
		  ?>
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
	  
	  <div class="panel panel-info">
	    <div class="panel-heading">
          <h3 class="panel-title"><?php echo8("Acesso ao sistema");?></h3>
        </div>
		<div class="panel-body">
		  <div class="form-group">
		    <label for="input_usuario" class="col-sm-2 control-label col-form-cbiot <?php if(!empty($usuarioErr)) { echo "has-error"; } ?>">Usuario:</label>
		   	<div class="col-sm-10 <?php if(!empty($usuarioErr)) { echo "has-error"; } ?>">		  
			   <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Seu usuario de acesso"
				  <?php 
				  if(isset($_POST['usuario'])) {
				    echo " value=\"".$_POST['usuario']."\"";
				  } else {
					echo " value=\"".$user->acesso->usuario."\"";
				  }
				  ?> required>
			</div>
		  </div> <!-- /form group -->
		  <div class="form-group">
			<label for="input_confirmacao" class="col-sm-2 control-label col-form-cbiot <?php if(!empty($confirmacaoErr)) { echo "has-error"; } ?>"><?php echo8("Senha Atual");?>:</label>
		   	<div class="col-sm-10 <?php if(!empty($confirmacaoErr)) { echo "has-error"; } ?>">		  
			   <input type="password" class="form-control" id="confirmacao" name="confirmacao" placeholder="Senha Atual">
			</div>
		  </div> <!-- /form group -->
		  <div class="form-group">
			<label for="input_senha" class="col-sm-2 control-label col-form-cbiot <?php if(!empty($senhaErr)) { echo "has-error"; } ?>"><?php echo8("Nova Senha");?>:</label>
		   	<div class="col-sm-10 <?php if(!empty($senhaErr)) { echo "has-error"; } ?>">		  
			   <input type="password" class="form-control" id="senha" name="senha" placeholder="Nova Senha de Acesso ao Sistema">
			   <span class="help-block" id="disp_help" ><?php echo8("* A senha só sera alterada se os campos acima (senha atual e nova senha) forem preenchidos."); ?></span>
			</div>
		  </div> <!-- /form group -->
		</div>
	  </div> <!-- /panel -->
	  
	  <div class="panel panel-info">
	    <div class="panel-heading">
          <h3 class="panel-title"><?php echo8("Informações Adicionais");?></h3>
        </div>
		<div class="panel-body">
		  <div class="form-group">
		    <div class="col-sm-12">		  
			   <textarea class="form-control" id="info_adicional" name="info_adicional" rows="4" placeholder="<?php echo8("Digite aqui qualquer informação adicional relevante."); ?>"><?php 
				 if(isset($_POST['info_adicional'])) {
				    echo $_POST['info_adicional'];
				 } else {
					echo $user->comentario;
				  }
				 ?></textarea>
			</div>
		  </div> <!-- /form group -->
		</div>
	  </div> <!-- /panel -->
    <button class="btn btn-lg btn-primary btn-block" type="submit">Atualizar Dados</button>
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
		
	<!-- Keep values of select and checkbox after submit -->
	<script>
<?php if(isset($_POST['sexo'])) : ?>		document.getElementsByName('sexo')[0].value = '<?php echo $_POST['sexo']; ?>';
<?php else : ?>		document.getElementsByName('sexo')[0].value = '<?php echo strtolower($user->sexo); ?>';
<?php endif;?>
<?php if(isset($_POST['estado_civil'])) : ?>		document.getElementsByName('estado_civil')[0].value = '<?php echo $_POST['estado_civil']; ?>';
<?php else : ?>		document.getElementsByName('estado_civil')[0].value = '<?php echo $user->estado_civil_id; ?>';
<?php endif;?>
<?php if(isset($_POST['escolaridade'])) : ?>		document.getElementsByName('escolaridade')[0].value = '<?php echo $_POST['escolaridade']; ?>';
<?php else : ?>		document.getElementsByName('escolaridade')[0].value = '<?php echo $user->escolaridade_id; ?>';
<?php endif;?>
<?php if(isset($_POST['municipio'])) : ?>		document.getElementsByName('municipio')[0].value = '<?php echo $_POST['municipio']; ?>';
<?php else : ?>		document.getElementsByName('municipio')[0].value = '<?php echo $user->endereco->municipio_id; ?>';
<?php endif;?>
<?php if(isset($_POST['pais'])) : ?>		document.getElementsByName('pais')[0].value = '<?php echo $_POST['pais']; ?>';
<?php else : ?>		document.getElementsByName('pais')[0].value = '<?php echo $user->endereco->pais_id; ?>';
<?php endif;?>
<?php if(isset($_POST['categoria'])) : ?>		document.getElementsByName('categoria')[0].value = '<?php echo $_POST['categoria']; ?>';
<?php else : ?>		document.getElementsByName('categoria')[0].value = '<?php echo $user->categoria_id; ?>';
<?php endif;?>

		//Dynamic part of contacts
		<?php
			$i=0;
			if(isset($_POST["contato_".$i])) :
				while(isset($_POST["contato_".$i])) :
					if($i > 0) :
		?>window.onload = addContact();
		<?php		endif; ?>document.getElementById(<?php echo "'contato_".$i."'"; ?>).value = '<?php echo $_POST["contato_".$i]; ?>';
		document.getElementById(<?php echo "'tipo_contato_".$i."'"; ?>).value = '<?php echo $_POST["tipo_contato_".$i]; ?>';
		<?php 
					$i++;
				endwhile;
			else :
				foreach($user->contatos as $contato) :
						if($i > 0) :
		?>window.onload = addContact();
		<?php		endif; ?>document.getElementById(<?php echo "'contato_".$i."'"; ?>).value = '<?php echo $contato->contato;  ?>';
		document.getElementById(<?php echo "'tipo_contato_".$i."'"; ?>).value = '<?php echo $contato->tipos_contato_id; ?>';
		<?php
					$i++;
				endforeach;
			endif;
		?>
		
		//Dynamic part of devices
		<?php
			$i=0;
			if(isset($_POST["dispositivo_mac_".$i])) :
				while(isset($_POST["dispositivo_mac_".$i])) :
					if($i > 0) :
		?>window.onload = addDevice();
		<?php		endif; ?>document.getElementById(<?php echo "'dispositivo_mac_".$i."'"; ?>).value = '<?php echo $_POST["dispositivo_mac_".$i]; ?>';
		document.getElementById(<?php echo "'tipo_dispositivo_".$i."'"; ?>).value = '<?php echo $_POST["tipo_dispositivo_".$i]; ?>';
		document.getElementById(<?php echo "'dispositivo_registration_".$i."'"; ?>).value = '<?php echo $_POST["dispositivo_registration_".$i];  ?>';
		document.getElementById(<?php echo "'dispositivo_hostname_".$i."'"; ?>).value = '<?php echo $_POST["dispositivo_hostname_".$i];  ?>';
		document.getElementById(<?php echo "'dispositivo_location_".$i."'"; ?>).value = '<?php echo $_POST["dispositivo_location_".$i]; ?>';
		<?php 
					$i++;
				endwhile;
			else :
				foreach($user->dispositivos as $dispositivo) :
					if($i > 0) :
		?>window.onload = addDevice();
		<?php		endif; ?>document.getElementById(<?php echo "'dispositivo_mac_".$i."'"; ?>).value = '<?php echo $dispositivo->endereco_mac;  ?>';
		document.getElementById(<?php echo "'dispositivo_location_".$i."'"; ?>).value = '<?php echo $dispositivo->localizacao;  ?>';
		document.getElementById(<?php echo "'dispositivo_registration_".$i."'"; ?>).value = '<?php echo $dispositivo->patrimonio;  ?>';
		document.getElementById(<?php echo "'dispositivo_hostname_".$i."'"; ?>).value = '<?php echo $dispositivo->hostname;  ?>';
		document.getElementById(<?php echo "'tipo_dispositivo_".$i."'"; ?>).value = '<?php echo $dispositivo->tipos_dispositivo_id; ?>';
		<?php
					$i++;
				endforeach;
			endif;
		?>
		
		//Selected Groups
		<?php
			if($_SERVER["REQUEST_METHOD"] == "POST") :
				$sql = "SELECT * FROM grupo";
				$result = query($sql);
				while($row = $result->fetch_assoc()):
		?>document.getElementById(<?php echo "'grupo_".$row['id']."'"; ?>).checked = <?php if(isset($_POST["grupo_".$row['id']])) echo "true"; else echo "false"; ?>;
		<?php 	endwhile;
			else :
				foreach($user->grupos as $grupo) :
		?>document.getElementById(<?php echo "'grupo_".$grupo->id."'"; ?>).checked = true;
		<?php	endforeach;
			endif;
		?>
	</script>

		}
	</script>
	<?php include("footer.php"); ?>	
	</body>
</html>

<?php
endif;
if($showsuccess) :
// botão de reeditar - voltar para página anterior - voltár para inicial
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
			<div class="alert alert-success" style="text-align: center; font-weight: bold;">
				<?php echo8("Dados do usuário alterados com sucesso!"); ?>
			</div>
		</div>
	</div> <!-- /wrap -->
	<meta http-equiv="refresh" content="1;url=<?php if($access_level == 2) echo "dashboard_prof.php"; else echo "dashboard_user.php"; ?>">
	<?php include("footer.php"); ?>	
</body>
</html>

<?php
endif;
if($showerror) :
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
			<div class="alert alert-danger" style="text-align: center; font-weight: bold;">
				<?php echo8("Ops, ocorreu um erro inesperado, favor contatar a administração!"); ?>
			</div>
			<?php 
				$log->lwrite("ERRO: ".var_export($_POST,true));
			?>
		</div> <!-- /container -->
	</div> <!-- /wrap -->
	<?php include("footer.php"); ?>	
</body>
</html>

<?php endif; ?>