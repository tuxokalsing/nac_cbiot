<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Este arquivo agrupa todas as principais classes do sistema.
 *			   Cada tabela do banco de dados foi modelada como uma classe.
 *
 *	Observação: 
 *
**/

require_once("database.php");			// Funções para manipulação do banco de dados
require_once("general_functions.php");	// Outras funções utilizadas pelas classes


//Definições de arquivo (termos de contrato, cadastro usuarios)
define("LINK_DOCUMENTO_1","./docs/politica-rede-cbiot.pdf");
define("LINK_DOCUMENTO_2","#");

// Definições de tamanho dos campos de acordo com o banco de dados

// USUARIO
define("MAX_NOME",100);
define("MAX_RG",10);
define("MAX_EMISSOR_RG",10);
define("MAX_CPF",14);
define("MAX_MATRICULA",20);
define("MAX_INSTITUICAO",100);
define("MAX_NOME_MAE",100);
define("MAX_NOME_PAI",100);
define("MAX_CARGO",254);
define("MAX_COMENTARIO",254);

// ENDEREÇO
define("MAX_LOGRADOURO",254);
define("MAX_NUMERO",10);
define("MAX_COMPLEMENTO",100);
define("MAX_BAIRRO",100);
define("MAX_CEP",100);

// CONTATO
define("MAX_CONTATO",100);

// ACESSO
define("MAX_USUARIO",50);
define("MAX_SENHA",64);

// DISPOSITIVOS
define("MAX_ENDERECO_MAC",18);
define("MAX_PATRIMONIO",32);
define("MAX_HOSTNAME",256);
define("MAX_IP",46);
define("MAX_LOCALIZACAO",254);

// GRUPO
define("MAX_GRUPO",254);
define("MAX_ACRONIMO",50);

// CATEGORIA
define("MAX_CATEGORIA",50);
define("MAX_DESCRICAO",254);

// PENDENCIAS
// -> Tabela
define("USUARIOS",1);
define("DISPOSITIVOS",2);
// -> Motivo
define("NOVO",1);
define("ALTERADO",2);
define("REMOVIDO",3);
define("EXPIRACAO",4);
define("REATIVACAO",5);
// -> Informacao
define("MAX_INFORMACAO",1024);

// Pendencias são adicionadas automaticamente nas funções
// SignThisUser, RemoveThisUser, SignThisDevice, RemoveThisDevice.

// -> Echo functions
function echo_motivo($motivo){
	switch($motivo) {
		case NOVO:		echo "Novo"; break;
		case ALTERADO: 	echo utf8_encode("Alteração"); break;
		case REMOVIDO:	echo "Removido"; break;
		case EXPIRACAO:	echo utf8_encode("Expiração"); break;
		case REATIVACAO:	echo utf8_encode("Reativação"); break;
		default: echo "Indefinido";
	}
}

function echo_tabela($tabela){
	switch($tabela) {
		case USUARIOS:		echo utf8_encode("Usuário"); break;
		case DISPOSITIVOS: 	echo "Dispositivo"; break;
		default: echo "Indefinido";
	}
}


/**
 * Classe para entidade usuario
 * 
 * Esta classe modela todas funções necessárias para manipulação
 * de usuarios no sistema.
 *
**/
class User {

	private $id;					// Identificador único do usuário @var integer
	private $nome;					// Nome completo do usuário @var string
	private $rg;					// Registro geral do usuário @var string
	private $data_rg;				// Data do RG @var date
	private $emissor_rg;			// Emissor do RG @var string
	private $cpf;					// Cadastro Pessoa Física @var string
	private $matricula;				// Número de matrícula @var string
	private $instituicao;			// Instituição de ensino @var string
	private $nascimento;			// Data de nascimento @var date
	private $sexo;					// Definição sexual @var char
	private $nome_mae;				// Nome da mãe @var string
	private $nome_pai;				// Nome do pai @var string
	private $endereco_id;			// Chave estrangeira para endereço @var integer
	private $endereco;				// Objeto do tipo Address @var Address
	private $categoria_id;			// Chave estrangeira para categoria @var integer
	private $categoria;				// Categoria do usuário @var string
	private $escolaridade_id;		// Chave estrangeira para escolaridade @var integer
	private $escolaridade;			// Escolaridade do usuário @var string
	private $estado_civil_id;		// Chave estrangeira para estado civil @var integer
	private $estado_civil;			// Estado Civil @var string
	private $cargo;					// Cargo do usuário @var string
	private $professor_lider;		// Define se o professor é líder @var bool
	private $professor_externo;		// Define se o professor é externo ao CBIOT @var bool
	private $orientador_id;			// Chave estrangeira para outro usuário (orientador) @var integer
	private $orientador;			// Objeto do tipo User @var User
	private $lider_id;				// Chave estrangeira para outro usuário (lider) @var integer
	private $lider;					// Objeto do tipo User @var User
	private $acesso_id;				// Chave estrangeira para o acesso @var integer
	private $acesso;				// Objeto do tipo Access @var Access
	private $data_cadastro;			// Data em que foi realizado o cadastro @var date
	private $data_expiracao;		// Data em que o cadastro expira @var date
	private $ativo;					// Define se o usuário está ativo ou não @var bool
	private $comentario;			// Informações adicionais ao cadastro @var string
	private $contatos;				// Array de objetos do tipo contato @array Contact
	private $grupos;				// Array de objetos do tipo grupo @array Group
	private $dispositivos;			// Array de objetos do tipo dispositivo @array Device
		
	/**
	 * Getter, retorna o valor do atributo
	 * 
	 * @param 	string $attr 	O atributo desejado
	 * @return 	void 			O valor do atributo
	 */
	function __get($attr) {
		return $this->$attr;
	}
	
    /**
	 * Setter, altera o valor do atributo
	 * 
	 * @param string 	$attr 	O atributo desejado
	 * @param void		$value	Valor atribuído
	 */
	function __set($attr, $value) {
		$this->$attr = $value;
	}
	
	/**
	 * Construtor da classe
	 * 
	 * Define todas as informações para um valor padrão/nulo, exceto pelo nome.
	 *
	 * @param string 	$nome 	Nome completo do usuário
	 */
	function __construct($nome) {
		$this->id = NULL;
		$this->nome = $nome;
		$this->rg = "XXXXXXXXXX";
		$this->data_rg = date("d/m/Y");
		$this->emissor_rg = "XXX"; 
		$this->cpf = "XXX.XXX.XXX-XX";
		$this->matricula = "XXXXXX";
		$this->instituicao = "Indefinido";
		$this->nascimento =  "00/00/0000";
		$this->sexo = "x";
		$this->nome_mae = "Indefinido";
		$this->nome_pai = "Indefinido";
		$this->endereco_id = NULL;
		// Endereco: (objeto do tipo Address)
		$this->endereco = new Address("Indefinido", 0000, "XXX", "Indefinido", "00000-000", 0, 0);
		$this->categoria_id = 0;
		$this->categoria = NULL;
		$this->escolaridade_id = 0;
		$this->escolaridade = NULL;
		$this->estado_civil_id = 0;
		$this->estado_civil = NULL;
		$this->cargo = "Indefinido";
		$this->professor_lider = 0;
		$this->professor_externo = 0;
		// Identificadores dos responsaveis:
		$this->orientador_id = NULL;
		$this->orientador = NULL;
		$this->lider_id = NULL;
		$this->lider = NULL;
		$this->acesso_id = NULL;
		// Acesso: (objeto do tipo Access)
		$this->acesso = new Access(NULL, NULL,NULL);
		$this->data_cadastro = date("d/m/Y");
		$this->data_expiracao = "00/00/0000";
		// Atividade do usuário. Padrão inativo = 0 (ativo = 1)
		$this->ativo = 0;	
		$this->comentario  = NULL;
		$this->contatos = NULL;
		$this->dispositivos = NULL;
		$this->grupos = NULL;
	}
	
	/**
	 * Cadastra usuário tendo todas as informações necessárias
	 *
	 * @param string 	$nome 				Nome completo do usuário
	 * @param string 	$rg 				Registro Geral
	 * @param string 	$data_rg 			Data do RG
	 * @param string 	$emissor_rg 		Emissor do RG
	 * @param string 	$cpf 				Cadastro de Pessoa Física
	 * @param string 	$matricula 			Número de matricula na instituição
	 * @param string 	$instituicao 		Nome da instituição
	 * @param string 	$nascimento 		Data de nascimento
	 * @param string 	$sexo 				Identificador de genero
	 * @param string 	$nome_mae 			Nome completo da mãe
	 * @param string 	$nome_pai 			Nome completo do pai
	 * @param string 	$endereco 			Objeto do tipo Address
	 * @param string 	$categoria_id 		Identificador de categoria
	 * @param string 	$escolaridade_id 	Identificador de escolaridade
	 * @param string 	$estado_civil_id 	Identificador de estado_civil
	 * @param string 	$cargo 				Descrição do cargo
	 * @param string 	$professor_lider 	Definição de professor líder
	 * @param string 	$professor_externo 	Definição de professor externo
	 * @param string 	$orientador_id		Identificador do orientador
	 * @param string 	$lider_id 			Identificador do líder
	 * @param string 	$acesso 			Objeto do tipo Access
	 * @param string 	$comentario 		Comentário adicional
	 * @param string 	$grupos 			Array de objetos do tipo Group
	 * @param string 	$contatos 			Array de objetos do tipo Contact
	 * @param string 	$dispositivos		Array de objetos do tipo Device
	 *
	 * @return integer	0 se cadastrado com sucesso, >0 caso contrário.
	 */
	function SignUserCompleteInfo( $nome, $rg, $data_rg, $emissor_rg, $cpf, $matricula, $instituicao, 
								   $nascimento, $sexo, $nome_mae, $nome_pai, $endereco, $categoria_id,
								   $escolaridade_id, $estado_civil_id, $cargo, $professor_lider,
								   $professor_externo, $orientador_id, $lider_id, $acesso, $comentario,
								   $grupos, $contatos, $dispositivos ) {
		global $db;
		$db_err = 0;
		
		$this->nome = $nome;
		$this->rg = $rg;
		$this->data_rg = $data_rg;
		$this->emissor_rg = $emissor_rg;
		$this->cpf = $cpf;
		$this->matricula = $matricula;
		$this->instituicao = $instituicao;
		$this->nascimento = $nascimento;
		$this->sexo = $sexo;
		$this->nome_mae = $nome_mae;
		$this->nome_pai = $nome_pai;
		$this->endereco = $endereco;				
		$this->categoria_id = $categoria_id;
		$this->escolaridade_id = $escolaridade_id;
		$this->estado_civil_id = $estado_civil_id;
		$this->cargo = $cargo;
		$this->professor_lider = $professor_lider;
		$this->professor_externo = $professor_externo;
		$this->orientador_id = $orientador_id;
		$this->lider_id = $lider_id;
		$this->acesso = $acesso;
		$this->comentario = $comentario;
		$this->grupos = $grupos;
		$this->contatos = $contatos;
		$this->dispositivos = $dispositivos;
		
		$this->data_cadastro = date("d/m/Y");
		$this->ativo = 0;	// default inativo = 0 (administração deve validar cadastro)
				
		// Temos todas as informações, cadastramos o usuário no banco (endereço e acesso também)
		$db_err += $this->SignThisUser();
				
		// Agora devemos linkar os grupos ao usuario
		if(!empty($this->grupos)) {
			foreach($this->grupos as $grupo) {
				$db_err += $this->AddGroup($grupo);
			}
		}
	
		// Registrar os contatos do usuario
		if(!empty($this->contatos)) {
			foreach($this->contatos as $contato) {
				$db_err += $this->AddContact($contato);
			}
		}
		
		// Registrar os dispositivos do usuario
		if(!empty($this->dispositivos)) {
			foreach($this->dispositivos as $dispositivo) {
				$db_err += $this->AddDevice($dispositivo);
			}
		}
		
		// Se houve algum erro, rollback
		if($db_err > 0) {
			$db->rollback();
			return $db_err; //retorna a quantidade de erros ocorridos
		} else {
			$db->commit();
			
			$statement = $db->prepare("SELECT esc.escolaridade, est.estado_civil, 
													ori.nome AS orientador,
													lid.nome AS lider 
											FROM escolaridade AS esc 
											LEFT JOIN estado_civil AS est ON est.id = ? 
											LEFT JOIN usuarios AS ori ON ori.id = ? 
											LEFT JOIN usuarios AS lid ON lid.id = ? 
											WHERE esc.id = ?");
			
			$statement->bind_param('iiii',
									$this->estado_civil_id,
									$this->orientador_id,
									$this->lider_id,
									$this->escolaridade_id);
			
			$statement->execute();
			$statement->bind_result($this->escolaridade,
									$this->estado_civil,
									$this->orientador,
									$this->lider);
			$statement->fetch();
			$statement->free_result();
			
			return $db_err;	//tudo OK!
		}
	}	
	
	/**
	 * Cadastra usuário no banco de dados
	 * 
	 * @return Integer	0 se cadastrado com sucesso, >0 caso contrário.
	**/	
	function SignThisUser() {
		global $db;
		global $log;
		$db_err = 0;
		
		// Desabilitamos o autocommit para garantir todas as inserções
		$db->autocommit(FALSE);
		
		// Cadastramos o endereço
		if($this->endereco) {
			$db_err += $this->endereco->SignThisAddress();
			// Recuperamos o id do endereco
			$this->endereco_id = $this->endereco->id;		
		}
		// Cadastramos o acesso:
		if($this->acesso->usuario) {
			$db_err += $this->acesso->SignThisAccess();
			// Recuperamos o id do acesso:
			$this->acesso_id = $this->acesso->id;
		} else {
			
		}
		
		// Calcula data de expiracao
		$this->categoria = new Category(NULL,NULL,NULL);
		$db_err += $this->categoria->GetDataFromDB($this->categoria_id);
		if($this->categoria->periodo_expiracao) {
			$tmp = $this->categoria->periodo_expiracao;
			$this->data_expiracao = date('d/m/Y', 
								strtotime("+ $tmp months", 
								strtotime(date("d-m-Y"))));
		} else {
			$this->data_expiracao = "00/00/0000";
		}
		
		// Cadastramos o usuario
		$stmt = $db->prepare("INSERT INTO usuarios
										(nome,
										rg,
										data_rg,
										emissor_rg,
										cpf,
										matricula,
										instituicao,
										nascimento,
										sexo,
										nome_mae,
										nome_pai,
										endereco_id,
										categoria_id,
										escolaridade_id,
										estado_civil_id,
										cargo,
										professor_lider,
										professor_externo,
										orientador_id,
										lider_id,
										acesso_id,
										data_cadastro,
										data_expiracao,
										ativo,
										comentario_adicional)
							VALUES (?,?,?,?,?,
									?,?,?,?,?,
									?,?,?,?,?,
									?,?,?,?,?,
									?,?,?,?,?)");
		
		$data_rg = date_conv($this->data_rg);
		$nascimento = date_conv($this->nascimento);
		$data_cadastro = date_conv($this->data_cadastro);
		$data_expiracao = date_conv($this->data_expiracao);
		
		$stmt->bind_param('sssssssssssiiiisiississis',
						$this->nome,
						$this->rg,
						$data_rg,
						$this->emissor_rg,
						$this->cpf,
						$this->matricula,
						$this->instituicao,
						$nascimento,
						$this->sexo,
						$this->nome_mae,
						$this->nome_pai,
						$this->endereco_id,
						$this->categoria_id,
						$this->escolaridade_id,
						$this->estado_civil_id,
						$this->cargo,
						$this->professor_lider,
						$this->professor_externo,
						$this->orientador_id,
						$this->lider_id,
						$this->acesso_id,
						$data_cadastro,
						$data_expiracao,
						$this->ativo,
						$this->comentario);
		// Verificação
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: Não foi possível cadastrar usuário. SQL: ".$this->nome);
			$db_err ++;	// erro
		} else {
			$this->id = $db->insert_id; // Se deu certo já podemos recuperar seu ID.
			// Podemos tambem recuperar dados de cadastro.
			$sql = "SELECT esc.escolaridade, est.estado_civil, 
							ori.nome AS orientador,
							lid.nome AS lider 
					FROM escolaridade AS esc 
					LEFT JOIN estado_civil AS est ON est.id = %d 
					LEFT JOIN usuarios AS ori ON ori.id = %d 
					LEFT JOIN usuarios AS lid ON lid.id = %d 
					WHERE esc.id = %d";
			$result = query($sql,
							$this->estado_civil_id,
							$this->orientador_id,
							$this->lider_id,
							$this->escolaridade_id);
			$dbdata = $result->fetch_assoc();
			$this->escolaridade = $dbdata['escolaridade'];
			$this->estado_civil = $dbdata['estado_civil'];
			$this->orientador = $dbdata['orientador'];
			$this->lider = $dbdata['lider'];
			$result->free();
			
			//Adicionamos pendencia:
			$pending = new Pending(USUARIOS, $this->id, NOVO, $this->nome);
			$pending->SignThisPending();
			
			$log->lwrite("Novo usuário cadastrado. Nome: ".$this->nome." ID: ".$this->id);
		}
		$stmt->free_result();
		
		// Verificação final
		if($db_err > 0) {
			// Se houver qualquer erro, não salvar nada no bd
			$db->rollback();
		} else {
			// Se não houver erro, salva tudo no banco de dados
			$db->commit();
		}
			
		// Habilitamos novamente o autocommit
		$db->autocommit(TRUE);
		
		return $db_err;
	}
	
	/**
	 * Cadastra um contato para este usuário
	 *
	 * @return integer	0 se cadastrado com sucesso, > 0 caso contrário
	 */
	function AddContact($contato) {
		$contato->usuarios_id = $this->id;
		return $contato->SignThisContact();
	}
	
	/**
	 * Remove o contato do usuário
	 *
	 * @return integer	0 se removido com sucesso, > 0 caso contrário
	 */
	function RemoveContact($contato) {
		global $db;
		global $log;
		$db_err = 0;
		// Remover contato
		$sql = "DELETE FROM contato WHERE usuarios_id = %d AND id = %d";
		$result = query($sql,$this->id, $contato->id);
		if($result === false) {
			$log->lwrite("ERRO: Erro ao remover contato do usuário. Nome: ".$this->nome);
			$db_err++;		//erro
		}
		return $db_err;	
	}
	
	/**
	 * Remove todos os contatos deste usuário
	 *
	 * @return integer	0 se removidos com sucesso, > 0 caso contrário
	 */
	function RemoveAllContacts() {
		global $db;
		global $log;
		$db_err = 0;
		// Remover todos os contatos deste usuário
		$sql = "DELETE FROM contato WHERE usuarios_id = %d";
		$result = query($sql,$this->id);
		if($result === false) {
			$log->lwrite("ERRO: Erro ao remover todos os contatos do usuário.");
			$db_err++;		//erro
		}
		return $db_err;	
	}

	/**
	 * Cadastra um dispositivo para este usuário
	 *
	 * @return integer	0 se cadastrado com sucesso, > 0 caso contrário
	 */
	function AddDevice($dispositivo) {
		$dispositivo->usuarios_id = $this->id;
		return $dispositivo->SignThisDevice();
	}
	
	/**
	 * Remove o dispositivo do usuário
	 *
	 * @return integer	0 se removido com sucesso, > 0 caso contrário
	 */
	function RemoveDevice($dispositivo) {
		global $db;
		global $log;
		$db_err = 0;
		// Remover dispositivo
		$sql = "DELETE FROM dispositivos WHERE usuarios_id = %d AND id = %d";
		$result = query($sql,$this->id, $dispositivo->id);
		if($result === false) {
			$log->lwrite("ERRO: Erro ao remover dispositivo do usuário.");
			$db_err++;		//erro
		} else {
			//Adicionamos pendencia:
			$pending = new Pending(DISPOSITIVOS, $dispositivo->id, REMOVIDO, "Hostname: ".$dispositivo->hostname." MAC: ".$dispositivo->endereco_mac." Local: ".$dispositivo->localizacao);
			$pending->SignThisPending();
			$log->lwrite("Removido dispositivo: ID: ".$dispositivo->id." Hostname: ".$dispositivo->hostname." MAC: ".$dispositivo->endereco_mac." Local: ".$dispositivo->localizacao);
		}
		return $db_err;	
	}
	
	/**
	 * Remove todos os dispositivos deste usuário
	 *
	 * @return integer	0 se removidos com sucesso, > 0 caso contrário
	 */
	function RemoveAllDevices() {
		global $db;
		global $log;
		$db_err = 0;
		// Remover todos os dispositivos deste usuário
		$sql = "DELETE FROM dispositivos WHERE usuarios_id = %d";
		$result = query($sql,$this->id);
		if($result === false) {
			$log->lwrite("ERRO: Erro ao remover todos os dispositivo do usuário.");
			$db_err++;		//erro
		} else {
			$log->lwrite("Removidos todos os dispositivos do usuário ".$this->nome);
		}
		return $db_err;	
	}
	
	/**
	 * Cadastra este usuário em um grupo
	 *
	 * @return integer	0 se cadastrado com sucesso, > 0 caso contrário
	 */
	function AddGroup($grupo) {
		global $db;
		global $log;
		$db_err = 0;
		$stmt = $db->prepare("INSERT INTO grupos_usuario
										(usuarios_id, 
										grupo_id)
							VALUES  (?,?)");
		$stmt->bind_param('ii',
						$this->id,
						$grupo->id);
		// Verificação
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: Não foi possível cadastrar o usuário ".$this->nome." no grupo ".$grupo->grupo.".");
			$db_err ++;	// erro
		} else {
			$log->lwrite("Novo grupo ".$grupo->grupo." cadastrado para o usuário ".$this->nome);
		}
		$stmt->free_result();
		return $db_err;	
	}
	
	/**
	 * Remove o usuário do grupo
	 *
	 * @return integer	0 se removido com sucesso, > 0 caso contrário
	 */
	function RemoveGroup($grupo) {
		global $db;
		global $log;
		$db_err = 0;
		// Remover grupo
		$sql = "DELETE FROM grupos_usuario WHERE usuarios_id = %d AND grupo_id = %d";
		$result = query($sql,$this->id, $grupo->id);
		if($result === false) {
			$log->lwrite("ERRO: Erro ao remover grupo do usuário.");
			$db_err++;		//erro
		} else {
			$log->lwrite("Removido grupo ".$grupo->acronimo." do usuário ".$this->nome);
		}
		return $db_err;	
	}
	
	/**
	 * Remove o usuário de todos os grupos
	 *
	 * @return integer	0 se removido com sucesso, > 0 caso contrário
	 */
	function RemoveAllGroups() {
		global $db;
		global $log;
		$db_err = 0;
		// Remover grupos				
		$sql = "DELETE FROM grupos_usuario WHERE usuarios_id = %d";
		$result = query($sql,$this->id);
		if($result === false) {
			$log->lwrite("ERRO: Erro ao remover todos os grupos do usuário.");
			$db_err++;		//erro
		} else {
			$log->lwrite("Removidos todos os grupos do usuário ".$this->nome);
		}
		return $db_err;	
	}
		
	/**
	 * Altera os dados do usuário no banco de dados.
	 * 
	 * @return integer	0 se alterado com sucesso, > 0 caso contrário
	 */
	function UpdateUserData() {
		global $db;
		global $log;
		$db_err = 0;
		$stmt = $db->prepare("UPDATE usuarios 
							SET	nome = ?,
								rg = ?,
								data_rg = ?,
								emissor_rg = ?,
								cpf = ?,
								matricula = ?,
								instituicao = ?,
								nascimento = ?,
								sexo = ?,
								nome_mae = ?,
								nome_pai = ?,
								endereco_id = ?,
								categoria_id = ?,
								escolaridade_id = ?,
								estado_civil_id = ?,
								cargo = ?,
								professor_lider = ?,
								professor_externo = ?,
								orientador_id = ?,
								lider_id = ?,
								acesso_id = ?,
								data_cadastro = ?,
								data_expiracao = ?,
								ativo = ?,
								comentario_adicional = ?
							WHERE id = ?");
		
		$data_rg = date_conv($this->data_rg);
		$nascimento = date_conv($this->nascimento);
		$data_cadastro = date_conv($this->data_cadastro);
		$data_expiracao = date_conv($this->data_expiracao);
		
		$stmt->bind_param('sssssssssssiiiisiississisi',
						$this->nome,
						$this->rg,
						$data_rg,
						$this->emissor_rg,
						$this->cpf,
						$this->matricula,
						$this->instituicao,
						$nascimento,
						$this->sexo,
						$this->nome_mae,
						$this->nome_pai,
						$this->endereco_id,
						$this->categoria_id,
						$this->escolaridade_id,
						$this->estado_civil_id,
						$this->cargo,
						$this->professor_lider,
						$this->professor_externo,
						$this->orientador_id,
						$this->lider_id,
						$this->acesso_id,
						$data_cadastro,
						$data_expiracao,
						$this->ativo,
						$this->comentario,
						$this->id);
	
		// Verificação
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: Erro ao alterar dados do usuário. Nome: ".$this->nome);
			$db_err ++;	// erro
		} else {
			$this->GetDataFromDB($this->id);
			$log->lwrite("Alteração nos dados de ".$this->nome);
		}
		$stmt->free_result();
		return $db_err;
	}
	
	/**
	 * Altera a data de expiração do usuario.
	 * 
	 * @return integer	0 se alterado com sucesso, > 0 caso contrário
	 */
	function UpdateUserExpirationDate($date) {
		global $db;
		global $log;
		$db_err = 0;
		$stmt = $db->prepare("UPDATE usuarios 
							SET	data_expiracao = ?
							WHERE id = ?");
		
		//se estiver no formato normal 	dd/mm/YYYY
		if( strlen($date) == 10 &&
			substr($date,2,1)=='/' &&
			substr($date,5,1)=='/') {
			$this->data_expiracao = date_conv($date);
		} else {
			$this->data_expiracao = $date;
		}
				
		$stmt->bind_param('si', $this->data_expiracao, $this->id);
	
		// Verificação
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: Não foi possível alterar data de expiracao.");
			$db_err ++;	// erro
		} else {
			$this->GetDataFromDB($this->id);
			$log->lwrite("Alteração da data de expiração do usuário ".$this->nome);
		}
		$stmt->free_result();
		return $db_err;
	}
	
	/**
	 * Remove o cadastro deste usuário e todas as informaçõse relevantes
	 * do bd de acordo com sua ID.
	 *
	 * Atenção: Também serão removidos endereços, acesso, dispositivos, contatos.
	 * 
	 * @return integer	0 se removido com sucesso, > 0 caso contrário
	 */
	function RemoveThisUser() {
		global $db;
		global $log;
		$db_err = 0;
		// Desabilitamos o autocommit para garantir todas as remoções
		$db->autocommit(FALSE);
		
		// Primeiro checamos se o cadastro existe
		$sql = "SELECT * FROM usuarios WHERE id = %d";
		$result = query($sql,$this->id);
		// Se existe o cadastro
		if($result->num_rows) {
			// Primeiro verificamos se algum outro usuario depende deste (lider/orientador)
			$sql = "SELECT * FROM usuarios 
					WHERE lider_id = %d OR
						  orientador_id = %d";
			$result = query($sql,$this->id,$this->id);
			if($result->num_rows) {
				$log->lwrite("ERRO: Não é possível remover este usuário por ser lider/orientador de outros usuários. Nome: ".$this->nome);
				$db_err++;
				$result->free();
			} else {
				// Necessario deletar (cuidado com a ordem): 
				// contatos do usuario
				// grupos do usuario - dispositivos
				// usuario - acesso - endereco
											
				// Remover contatos
				$sql = "DELETE FROM contato WHERE usuarios_id = %d";
				$result = query($sql,$this->id);
				if($result === false) {
					$log->lwrite("ERRO: Erro ao remover contatos do usuário. Nome: ".$this->nome);
					$db_err++;		//erro
				}
				// Remover grupos				
				$sql = "DELETE FROM grupos_usuario WHERE usuarios_id = %d";
				$result = query($sql,$this->id);
				if($result === false) {
					$log->lwrite("ERRO: Erro ao remover grupos do usuário. Nome: ".$this->nome);
					$db_err++;		//erro
				}
				
				// Remover dispositivos (necessita foreach para gerar pendencias)
				foreach($this->dispositivos as $dispositivo) {
					$db_err += $dispositivo->RemoveThisDevice();
				}
				
				// Remover usuario
				$sql = "DELETE FROM usuarios WHERE id = %d";
				$result = query($sql,$this->id);
				if($result === false) {
					$log->lwrite("ERRO: Erro ao remover o usuário. Nome: ".$this->nome);
					$db_err++;		//erro
				}
				// Remover acesso:
				$sql = "DELETE FROM acesso WHERE id = %d";
				$result = query($sql,$this->acesso_id);
				if($result === false) {
					$log->lwrite("ERRO: Erro ao remover acesso do usuário. Nome: ".$this->nome);
					$db_err++;		//erro
				}
				// Remover endereco
				$sql = "DELETE FROM endereco WHERE id = %d";
				$result = query($sql,$this->endereco_id);
				if($result === false) {
					$log->lwrite("ERRO: Erro ao remover endereço do usuário. Nome: ".$this->nome);
					$db_err++;		//erro
				}
				// Verificação final
				if($db_err > 0) {
					// Se houver qualquer erro, não salvar nada no bd
					$db->rollback();
				} else {
					// Se não houver erro, salva tudo no banco de dados
					$db->commit();
					//Adicionamos pendencia:
					$pending = new Pending(USUARIOS, $this->id, REMOVIDO, $this->nome);
					$pending->SignThisPending();
					$log->lwrite("Removido o usuário ".$this->nome." e todos os dados relativos a ele.");
				}
			}
		} else {
			$log->lwrite("ERRO: Identificador de usuário inexistente.");
			$db_err++;
		}
		
		// Habilitamos novamente o autocommit
		$db->autocommit(TRUE);
		return $db_err;			
	}
	
	/**
	 * Recupera todos os dados do usuário do bd.
	 * 
	 * @return integer	0 se encontrado, > 0 caso contrário
	 */
	function GetDataFromDB($id) {
		global $log;
		$db_err = 0;
		$sql = "SELECT usuarios.*, esc.escolaridade, est.estado_civil, cat.categoria,
						ori.nome AS orientador,
						lid.nome AS lider 
				FROM usuarios
				LEFT JOIN escolaridade AS esc ON esc.id = usuarios.escolaridade_id
				LEFT JOIN estado_civil AS est ON est.id = usuarios.estado_civil_id
				LEFT JOIN categoria AS cat ON cat.id = usuarios.categoria_id
				LEFT JOIN usuarios AS ori ON ori.id = usuarios.orientador_id 
				LEFT JOIN usuarios AS lid ON lid.id = usuarios.lider_id
				WHERE usuarios.id = %d";
		$result = query($sql,$id);
		if($result->num_rows) {
			$dbdata = $result->fetch_assoc();
			$this->id = $id;
			$this->nome = $dbdata['nome'];
			$this->rg = $dbdata['rg'];
			$this->data_rg = date_conv($dbdata['data_rg']);
			$this->emissor_rg = $dbdata['emissor_rg'];
			$this->cpf = $dbdata['cpf'];
			$this->matricula = $dbdata['matricula'];
			$this->instituicao = $dbdata['instituicao'];
			$this->nascimento = date_conv($dbdata['nascimento']);
			$this->sexo = $dbdata['sexo'];
			$this->nome_mae = $dbdata['nome_mae'];
			$this->nome_pai = $dbdata['nome_pai'];
			//fk classe Address
			$this->endereco_id = $dbdata['endereco_id'];
			$this->endereco = new Address(NULL,NULL,NULL,NULL,NULL,NULL,NULL);
			$this->endereco->getDataFromDB($this->endereco_id);
			
			// Categoria
			$this->categoria_id = $dbdata['categoria_id'];
			$this->categoria = $dbdata['categoria'];
			// Escolaridade
			$this->escolaridade_id = $dbdata['escolaridade_id'];
			$this->escolaridade = $dbdata['escolaridade'];
			// Estado_civil
			$this->estado_civil_id = $dbdata['estado_civil_id'];
			$this->estado_civil = $dbdata['estado_civil'];
			$this->cargo = $dbdata['cargo'];
			$this->professor_lider = $dbdata['professor_lider'];
			$this->professor_externo = $dbdata['professor_externo'];
			// Responsaveis, se existirem: fk classe User
			$this->orientador_id = $dbdata['orientador_id'];
			$this->orientador = $dbdata['orientador'];
			$this->lider_id = $dbdata['lider_id'];
			$this->lider = $dbdata['lider'];
			// fk classe Access
			$this->acesso_id = $dbdata['acesso_id'];
			if($this->acesso_id) {
				$this->acesso = new Access(NULL,NULL,NULL);
				$this->acesso->getDataFromDB($this->acesso_id);
			}
			// Datas
			$this->data_cadastro = date_conv($dbdata['data_cadastro']);
			$this->data_expiracao = date_conv($dbdata['data_expiracao']);
			$this->ativo = $dbdata['ativo'];
			$this->comentario = $dbdata['comentario_adicional'];
			
			// Recuperar todos os contatos:
			$this->contatos = array();
			$sqlquery = "SELECT * FROM contato WHERE usuarios_id = %d ";
			$result = query($sqlquery,$this->id);
			//se existirem contatos cadastrados vamos recuperar todos e colocar no array de contatos.
			while ($dbdata = $result->fetch_assoc()) {
				$tmp = new Contact(NULL,NULL,NULL);	
				$tmp->getDataFromDB($dbdata['id']);
				array_push($this->contatos, $tmp);
			}
			
			// Recuperar todos os grupos
			$this->grupos = array();
			$sqlquery = "SELECT * FROM grupos_usuario WHERE usuarios_id = %d ";
			$result = query($sqlquery,$this->id);
			//se existirem grupos cadastrados vamos recuperar todos e colocar no array de grupos
			if($result->num_rows > 0) { 
				while ($dbdata = $result->fetch_assoc()) {
					$tmp = new Group(NULL,NULL);	
					$tmp->getDataFromDB($dbdata['grupo_id']);
					array_push($this->grupos, $tmp);
				}
			}
			
			// Recuperar todos os dispositivos:
			$this->dispositivos = array();
			$sqlquery = "SELECT * FROM dispositivos WHERE usuarios_id = %d ";
			$result = query($sqlquery,$this->id);
			//se existirem dispositivos cadastrados vamos recuperar todos e colocar no array de dispositivos
			if($result->num_rows > 0) { 
				while ($dbdata = $result->fetch_assoc()) {
					$tmp = new Device(NULL,NULL,NULL,NULL,NULL,NULL,NULL);	
					$tmp->getDataFromDB($dbdata['id']);
					array_push($this->dispositivos, $tmp);
				}
			}
			$result->free();	
		} else {
			//cadastro não encontrado
			$log->lwrite("ERRO: ID inválido, usuário não encontrado. (ID: $id)");
			$db_err++;
		}
		return $db_err;
	}
	
	/**
	 * Recupera todos os dados do usuário através do nome.
	 * 
	 * @return integer	0 se encontrado, > 0 caso contrário
	 */
	function GetDataFromDBbyName($nome) {
		global $log;
		$db_err = 0;
		$sql = "SELECT id FROM usuarios WHERE nome = '%s' ";
		$result = query($sqlquery,$nome);
		
		if($result->num_rows) { 
			$dbdata = $result->fetch_assoc();
			$this->id = $dbdata['id'];
			$this->GetDataFromDB($this->id);
			$result->free();
			return true;
		} else {
			//cadastro não encontrado
			$log->lwrite("Nome inválido, usuário não encontrado. (Nome: $nome)");
			$db_err++;
			return false;
		}
	}
   
}

/**
 * Classe para entidade Endereço
 * 
 * Esta classe modela todas funções necessárias para manipulação
 * de endereços de usuários no sistema.
 *
**/
class Address {
   
	private $id;			// Identificador único do endereço @var integer
	private $logradouro;	// Logradouro do endereço (rua, avenida...) @var string
	private $numero;		// O número da residencia @var integer
	private $complemento;	// Complemento (apartamento, bloco, unidade...) @var string
	private $bairro;		// Bairro @var string
	private $cep;			// Código postal @var string
	private $municipio_id;	// Chave estrangeira para o município @var integer
	private $municipio;		// Município @var string
	private $uf;			// Unidade Federal @var string
	private $estado;		// Estado @var string
	private $pais_id;		// Chave estrangeira para o País @var integer
	private $pais;			// País de residência @var string

	/**
	 * Getter, retorna o valor do atributo
	 * 
	 * @param 	string $attr 	O atributo desejado
	 * @return 	void 			O valor do atributo
	 */
	function __get($attr) {
		return $this->$attr;
	}
	
    /**
	 * Setter, altera o valor do atributo
	 * 
	 * @param string 	$attr 	O atributo desejado
	 * @param void		$value	Valor atribuído
	 */
	function __set($attr, $value) {
		$this->$attr = $value;
	}
	
	/**
	 * Define todas as informações do Endereço (exceto id, município, uf, estado e pais)
	 * 
	 * @param string 	$logradouro		Logradouro de residência
	 * @param integer	$numero			Número da residência
	 * @param string	$complemento	Complemento (ap. bloco unidade)
	 * @param string	$bairro			Bairro de residência
	 * @param string	$cep			Código postal
	 * @param integer	$municipio_id	Identificador do município e estado
	 * @param integer	$pais_id		Identificador do pais
	 */
	function __construct($logradouro, $numero, $complemento, $bairro,
						 $cep, $municipio_id, $pais_id) {
		$this->logradouro = $logradouro;
		$this->numero = $numero;
		$this->complemento = $complemento;
		$this->bairro = $bairro;
		$this->cep = $cep;
		$this->municipio_id = $municipio_id;
		$this->pais_id = $pais_id;
	}
	
	/**
	 * Cadastra o endereço no banco de dados
	 *
	 * @return integer	0 se cadastrado com sucesso, > 0 caso contrário
	 */
	function SignThisAddress() {
		global $db;
		global $log;
		$db_err = 0;
		$stmt = $db->prepare("INSERT INTO endereco
										(logradouro,
										numero,
										complemento,
										bairro,
										cep,
										municipio_id,
										pais_id)
							VALUES (?,?,?,?,?,?,?)");
		$stmt->bind_param('sisssii',
						$this->logradouro,
						$this->numero,
						$this->complemento,
						$this->bairro,
						$this->cep,
						$this->municipio_id,
						$this->pais_id);
		// Verificação
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: Não foi possível cadastrar este endereço.");
			$db_err ++;	// erro
		} else {
			$this->id = $db->insert_id; // Se deu certo já podemos recuperar seu ID.
			//se deu certo já podemos recuperar os dados do cadastro.
			$sql = "SELECT  municipio.uf, estado.nome as estado,
							municipio.nome as municipio, pais.nome as pais 
					FROM municipio 
					LEFT JOIN estado ON municipio.estado_id = estado.id
					LEFT JOIN pais ON pais.id = %d
					WHERE municipio.id = %d";
			$result = query($sql,
							$this->pais_id,
							$this->municipio_id);
			$dbdata = $result->fetch_assoc();
			$this->uf = $dbdata['uf'];
			$this->estado = $dbdata['estado'];
			$this->municipio = $dbdata['municipio'];
			$this->pais = $dbdata['pais'];
			$result->free();
			$log->lwrite("Novo endereço cadastrado ID: ".$this->id);
		}
		$stmt->free_result();
		return $db_err;
	}
	
	/**
	 * Altera os dados do endereço no banco de dados.
	 * 
	 * @return integer	0 se alterado com sucesso, > 0 caso contrário
	 */
	function UpdateAddressData() {
		global $db;
		global $log;
		$db_err = 0;
		$stmt = $db->prepare("UPDATE endereco 
							SET	logradouro = ?,
								numero = ?,
								complemento = ?,
								bairro = ?,
								cep = ?,
								municipio_id = ?,
								pais_id = ?
							WHERE id = ?");
		$stmt->bind_param('sisssiii',
						$this->logradouro,
						$this->numero,
						$this->complemento,
						$this->bairro,
						$this->cep,
						$this->municipio_id,
						$this->pais_id,
						$this->id);
	
		// Verificação
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: Não foi possível alterar os dados do endereço.");
			$db_err ++;	// erro
		} else {
			$log->lwrite("Alterações no endereço cadastrado ID: ".$this->id);
		}
		$stmt->free_result();
		return $db_err;
	}
	
	/**
	 * Remove o cadastro deste endereço no bd de acordo com sua ID.
	 * 
	 * @return integer	0 se removido com sucesso, > 0 caso contrário
	 */
	function RemoveThisAddress() {
		$db_err = 0;
		// Primeiro checamos se o cadastro existe
		$sql = "SELECT * FROM endereco WHERE id = %d";
		$result = query($sql,$this->id);
		// Se existe o cadastro
		if($result->num_rows) {
			$sql = "DELETE FROM endereco WHERE id = %d";
			$result = query($sql,$this->id);
			if($result === false) {
				$log->lwrite("ERRO: Não foi possível remover este endereço.");
				$db_err++;
			} else {
				$log->lwrite("Removido endereço ID: ".$this->id);
			}
		} else {
			$log->lwrite("ERRO: Identificador de endereço inexistente.");
			$db_err++;
		}
		return $db_err;
	}
	
	/**
	 * Recupera todos os dados do endereço do bd.
	 * 
	 * @return integer	0 se encontrado, > 0 caso contrário
	 */
	public function GetDataFromDB($id) {
		global $log;
		$db_err = 0;
		$sql = "SELECT  endereco.*, municipio.uf, estado.nome as estado,
						municipio.nome as municipio, pais.nome as pais 
				FROM endereco 
				LEFT JOIN municipio ON endereco.municipio_id = municipio.id
				LEFT JOIN estado ON municipio.estado_id = estado.id
				LEFT JOIN pais ON endereco.pais_id = pais.id 
				WHERE endereco.id = %d";
		$result = query($sql,$id);
		if($result->num_rows) {
			$dbdata = $result->fetch_assoc();
			$this->id = $id;
			$this->logradouro = $dbdata['logradouro'];
			$this->numero = $dbdata['numero'];
			$this->complemento = $dbdata['complemento'];
			$this->bairro = $dbdata['bairro'];
			$this->cep = $dbdata['cep'];
			$this->municipio_id = $dbdata['municipio_id'];
			$this->municipio = $dbdata['municipio'];
			$this->uf = $dbdata['uf'];
			$this->estado = $dbdata['estado'];
			$this->pais_id = $dbdata['pais_id'];
			$this->pais = $dbdata['pais'];
			$result->free();	
		} else {
			//cadastro não encontrado
			$log->lwrite("ERRO: ID inválido, endereço não encontrado. ID: ".$id);
			$db_err++;
		}
		return $db_err;
	}
}

/**
 * Classe para entidade Acesso
 * 
 * Esta classe modela todas funções necessárias para manipulação
 * de acesso de usuários ao sistema.
 *
**/
class Access {
   
	private $id;			// Identificador único do acesso @var integer
	private $usuario;		// Login do usuario no sistema @var string
	private $senha;			// Senha não criptografada (usada apenas no ato do cadastro) @var string
	private $nivel;			// Nível de acesso do usuario no sistema @var integer
	private $hash;			// Senha criptografada, armazenada no banco (blowfish) @var string
	
	/**
	 * Getter, retorna o valor do atributo
	 * 
	 * @param 	string $attr 	O atributo desejado
	 * @return 	void 			O valor do atributo
	 */
	function __get($attr) {
		return $this->$attr;
	}
	
    /**
	 * Setter, altera o valor do atributo
	 * 
	 * @param string 	$attr 	O atributo desejado
	 * @param void		$value	Valor atribuído
	 */
	function __set($attr, $value) {
		if(strcmp($attr,"nivel") == 0) {
			if($this->nivel != 1) {		// administrador não perde acesso de admin jamais *hotfix*
				$this->$attr = $value;
			}
		} else {
			$this->$attr = $value;
		}
	}
	
	/**
	 * Define todas as informações do Acesso (exceto id)
	 * 
	 * @param string 	$usuario 		Login do usuário
	 * @param string	$senha			Senha não criptografada
	 * @param integer	$nivel			Nível de acesso para esta conta
	 */
	function __construct($usuario, $senha, $nivel) {
		$this->usuario = $usuario;
		$this->senha = $senha;
		$this->nivel = $nivel;
	}
	
	/**
	 * Cadastra o acesso no banco de dados
	 *
	 * @return integer	0 se cadastrado com sucesso, > 0 caso contrário
	 */
	function SignThisAccess() {
		global $db;
		global $log;
		$db_err = 0;
				
		$sql = "SELECT  acesso.* FROM acesso WHERE acesso.usuario = '%s'";
		$result = query($sql,$this->usuario);
		if($result->num_rows) {
			//Cadastro já existe, nome duplicado
			$log->lwrite("ERRO: Nome de usuário já existe. Usuário: ".$this->usuario);
			$db_err++;
			$result->free();	
		} else {
			$stmt = $db->prepare("INSERT INTO acesso
										(usuario,
										senha,
										nivel)
							VALUES (?,?,?)");
			// Obtemos a hash para inserção no banco
			$this->hash = Bcrypt::hash($this->senha);
			$stmt->bind_param('ssi',
							$this->usuario,
							$this->hash,
							$this->nivel);
			// Verificação
			if($stmt->execute() === false) {
				$log->lwrite("ERRO: Não foi possível cadastrar este acesso.");
				$db_err ++;	// erro
			} else {
				$this->id = $db->insert_id; // Se deu certo já podemos recuperar seu ID.
				$log->lwrite("Novo acesso cadastrado ID: ".$this->id);
			}
			$stmt->free_result();
		}
		return $db_err;
	}
	
	/**
	 * Altera os dados do acesso no banco de dados.
	 * 
	 * @return integer	0 se alterado com sucesso, > 0 caso contrário
	 */
	function UpdateAccessData() {
		global $db;
		global $log;
		$db_err = 0;
		
		if(empty($this->senha)){
			$stmt = $db->prepare("UPDATE acesso 
								SET	usuario = ?,
									nivel = ?
								WHERE id = ?");
			// Obtemos a hash para inserção no banco
			$stmt->bind_param('sii',
							$this->usuario,
							$this->nivel,
							$this->id);
		} else {
			$stmt = $db->prepare("UPDATE acesso 
								SET	usuario = ?,
									senha = ?,
									nivel = ?
								WHERE id = ?");
			// Obtemos a hash para inserção no banco
			$this->hash = Bcrypt::hash($this->senha);
			$stmt->bind_param('ssii',
							$this->usuario,
							$this->hash,
							$this->nivel,
							$this->id);
		}	
		// Verificação
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: Não foi possível alterar os dados do acesso. ID: ".$this->id);
			$db_err ++;	// erro
		} else {
			$log->lwrite("Alteração no acesso ID: ".$this->id);
		}
		$stmt->free_result();
		return $db_err;
	}
	
	/**
	 * Remove o cadastro deste acesso no bd de acordo com sua ID.
	 * 
	 * @return integer	0 se removido com sucesso, > 0 caso contrário
	 */
	function RemoveThisAccess() {
		global $log;
		$db_err = 0;
		// Primeiro checamos se o cadastro existe
		$sql = "SELECT * FROM acesso WHERE id = %d";
		$result = query($sql,$this->id);
		// Se existe o cadastro
		if($result->num_rows) {
			$sql = "DELETE FROM acesso WHERE id = %d";
			$result = query($sql,$this->id);
			if($result === false) {
				$log->lwrite("ERRO: Não foi possível remover este acesso.");
				$db_err++;		//erro
			} else {
				$log->lwrite("Removido acesso ID: ".$this->id);
			}
		} else {
			$log->lwrite("ERRO: Identificador de acesso inexistente. ID: ".$this->id);
			$db_err++;
		}
		return $db_err;
	}
	
	/**
	 * Recupera todos os dados do acesso do bd.
	 * 
	 * @return integer	0 se encontrado, > 0 caso contrário
	 */
	public function GetDataFromDB($id) {
		global $log;
		$db_err = 0;
		$sql = "SELECT * FROM acesso WHERE id = %d";
		$result = query($sql,$id);
			
		if($result->num_rows) {
			$dbdata = $result->fetch_assoc();
			$this->id = $id;
			$this->usuario = $dbdata['usuario'];
			$this->hash = $dbdata['senha'];
			$this->nivel = $dbdata['nivel'];
			$result->free();	
		} else {
			//cadastro não encontrado
			$log->lwrite("ERRO: ID inválido, acesso não encontrado. ID: ".$id);
			$db_err++;
		}
		return $db_err;
	}
}

/**
 * Classe para entidade Dispositivos
 * 
 * Esta classe modela todas funções necessárias para manipulação
 * de dispositivos de usuários no sistema.
 *
**/
class Device {
  
	private $id;					// Identificador único do dispositivo @var integer
	private $usuarios_id;			// Chave estrageira para o usuário @var integer
	private $tipos_dispositivo_id;	// Chave estrageira para o tipo de dispositivo @var integer
	private $endereco_mac;			// Endereço MAC do dispositivo (XX:XX:XX:XX:XX:XX) @var string
	private $patrimonio;			// Se for um dispositivo patrimoniado @var string
	private $hostname;				// Nome da maquina @var string
	private $ip;					// IP da máquina (XXX.XXX.XXX.XXX) @var string
	private $localizacao;			// A localização do dispositivo @var string
	private $tipo_dispositivo;		// O tipo do dispositivo @var string
	
	/**
	 * Getter, retorna o valor do atributo
	 * 
	 * @param 	string $attr 	O atributo desejado
	 * @return 	void 			O valor do atributo
	 */
	function __get($attr) {
		return $this->$attr;
	}
	
    /**
	 * Setter, altera o valor do atributo
	 * 
	 * @param string 	$attr 	O atributo desejado
	 * @param void		$value	Valor atribuído
	 */
	function __set($attr, $value) {
		$this->$attr = $value;
	}
	
	/**
	 * Define todas as informações do Contato (exceto id)
	 * 
	 * @param integer 	$usuarios_id 			ID do usuário
	 * @param integer	$tipos_dispositivo_id	ID do tipo de dispositivo
	 * @param string	$endereco_mac			Número ou email
	 * @param string	$patrimonio				Patrimonio
	 * @param string	$hostname				Hostname
	 * @param string	$ip						IP
	 * @param string	$localizacao			Localização do dispositivo
	 */
	function __construct($usuarios_id, $tipos_dispositivo_id, $endereco_mac, $patrimonio, $hostname, $ip, $localizacao) {
		$this->id = NULL;
		$this->usuarios_id = $usuarios_id;
		$this->tipos_dispositivo_id = $tipos_dispositivo_id;
		$this->endereco_mac = $endereco_mac;
		$this->localizacao = $localizacao;
		$this->patrimonio = $patrimonio;
		$this->ip = $ip;
		$this->hostname = $hostname;
		$this->tipo_dispositivo = "";
	}
	
	/**
	 * Cadastra o dispositivo no banco de dados
	 *
	 * @return integer	0 se cadastrado com sucesso, > 0 caso contrário
	 */
	function SignThisDevice() {
		global $db;
		global $log;
		$db_err = 0;
		$stmt = $db->prepare("INSERT INTO dispositivos
										(usuarios_id,
										tipos_dispositivo_id,
										endereco_mac,
										patrimonio,
										hostname,
										ip,
										localizacao)
							VALUES (?,?,?,?,?,?,?)");
		$stmt->bind_param('iisssss',
						$this->usuarios_id,
						$this->tipos_dispositivo_id,
						$this->endereco_mac,
						$this->patrimonio,
						$this->hostname,
						$this->ip,
						$this->localizacao);
		// Verificação
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: Não foi possível cadastrar este dispositivo.");
			$db_err ++;	// erro
		} else {
			$this->id = $db->insert_id; // Se deu certo já podemos recuperar seu ID.
			// Vamos também recuperar seu tipo.
			$sql = "SELECT tipo_dispositivo
					FROM tipos_dispositivo
					WHERE id = %d";
			$result = query($sql,$this->tipos_dispositivo_id);
			$dbdata = $result->fetch_assoc();
			$this->tipo_dispositivo = $dbdata['tipo_dispositivo'];
			$result->free();
			
			//Adicionamos pendencia:
			$pending = new Pending(DISPOSITIVOS, $this->id, NOVO, "Hostname: ".$this->hostname." MAC: ".$this->endereco_mac." Local: ".$this->localizacao);
			$pending->SignThisPending();
			
			$log->lwrite("Novo dispositivo cadastrado ID: ".$this->id);
		}
		$stmt->free_result();
		return $db_err;
	}
	
	/**
	 * Altera os dados do dispositivo no banco de dados.
	 * 
	 * @return integer	0 se alterado com sucesso, > 0 caso contrário
	 */
	function UpdateDeviceData() {
		global $db;
		global $log;
		$db_err = 0;
		$stmt = $db->prepare("UPDATE dispositivos 
							SET	usuarios_id = ?,
								tipos_dispositivo_id = ?,
								endereco_mac = ?,
								patrimonio = ?,
								hostname = ?,
								ip = ?,
								localizacao = ?
							WHERE id = ?");
		$stmt->bind_param('iisssssi',
						$this->usuarios_id,
						$this->tipos_dispositivo_id,
						$this->endereco_mac,
						$this->patrimonio,
						$this->hostname,
						$this->ip,
						$this->localizacao,
						$this->id);
	
		// Verificação
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: Não foi possível alterar os dados do dispositivo.");
			$db_err ++;	// erro
		} else {
			$log->lwrite("Alteração no dispositivo ID: ".$this->id);
		}
		$stmt->free_result();
		return $db_err;
	}
	
	/**
	 * Remove o cadastro deste dispositivo no bd de acordo com sua ID.
	 * 
	 * @return integer	0 se removido com sucesso, > 0 caso contrário
	 */
	function RemoveThisDevice() {
		global $log;
		$db_err = 0;
		// Primeiro checamos se o cadastro existe
		$sql = "SELECT * FROM dispositivos WHERE id = %d";
		$result = query($sql,$this->id);
		// Se existe o cadastro
		if($result->num_rows) {
			$sql = "DELETE FROM dispositivos WHERE id = %d";
			$result = query($sql,$this->id);
			if($result === false) {
				$log->lwrite("ERRO: Não foi possível remover este dispositivo. ID: ".$this->id);
				$db_err++;
			} else {
				//Adicionamos pendencia:
				$pending = new Pending(DISPOSITIVOS, $this->id, REMOVIDO, "Hostname: ".$this->hostname." MAC: ".$this->endereco_mac." Local: ".$this->localizacao);
				$pending->SignThisPending();
			}
		} else {
			$log->lwrite("ERRO: Identificador de dispositivo inexistente. ID: ".$this->id);
			$db_err++;
		}
		
		return $db_err;
	}
	
	/**
	 * Recupera todos os dados do dispositivo do bd.
	 * 
	 * @return integer	0 se encontrado, > 0 caso contrário
	 */
	public function GetDataFromDB($id) {
		global $log;
		$db_err = 0;
		$sql = "SELECT dispositivos.*, tipos_dispositivo.tipo_dispositivo
				FROM dispositivos
				LEFT JOIN tipos_dispositivo ON dispositivos.tipos_dispositivo_id = tipos_dispositivo.id
				WHERE dispositivos.id = %d";
		$result = query($sql,$id);
			
		if($result->num_rows) {
			$dbdata = $result->fetch_assoc();
			$this->id = $id;
			$this->usuarios_id = $dbdata['usuarios_id'];
			$this->tipos_dispositivo_id = $dbdata['tipos_dispositivo_id'];
			$this->endereco_mac = $dbdata['endereco_mac'];
			$this->patrimonio = $dbdata['patrimonio'];
			$this->hostname = $dbdata['hostname'];
			$this->ip = $dbdata['ip'];
			$this->localizacao = $dbdata['localizacao'];
			$this->tipo_dispositivo = $dbdata['tipo_dispositivo'];
			$result->free();	
		} else {
			//cadastro não encontrado
			$log->lwrite("ERRO: ID inválido, contato não encontrado. ID: ".$id);
			$db_err++;
		}
		return $db_err;
	}
  
}

/**
 * Classe para entidade Contatos
 * 
 * Esta classe modela todas funções necessárias para manipulação
 * de contatos de usuários no sistema.
 *
**/
class Contact {
  
	private $id;					// Identificador único do contato @var integer
	private $usuarios_id;			// Chave estrangeira para o usuário @var integer
	private $tipos_contato_id;		// Chave estrangeira para o tipo de contato @var integer
	private $contato;				// O contato, números ou emails @var string
	private $tipo_contato;			// O tipo de contato (número, email, ramal ...) @var string
	
	/**
	 * Getter, retorna o valor do atributo
	 * 
	 * @param 	string $attr 	O atributo desejado
	 * @return 	void 			O valor do atributo
	 */
	function __get($attr) {
		return $this->$attr;
	}
	
    /**
	 * Setter, altera o valor do atributo
	 * 
	 * @param string 	$attr 	O atributo desejado
	 * @param void		$value	Valor atribuído
	 */
	function __set($attr, $value) {
		$this->$attr = $value;
	}
	
	/**
	 * Define todas as informações do Contato (exceto id)
	 * 
	 * @param integer 	$usuarios_id 		ID do usuário
	 * @param integer	$tipos_contato_id	ID do tipo de contato
	 * @param string	$contato			Número ou email
	 */
	function __construct($usuarios_id, $tipos_contato_id, $contato) {
		$this->usuarios_id = $usuarios_id;
		$this->tipos_contato_id = $tipos_contato_id;
		$this->contato = $contato;
	}
	
	/**
	 * Cadastra o contato no banco de dados
	 *
	 * @return integer	0 se cadastrado com sucesso, > 0 caso contrário
	 */
	function SignThisContact() {
		global $db;
		global $log;
		$db_err = 0;
		$stmt = $db->prepare("INSERT INTO contato
										(usuarios_id,
										tipos_contato_id,
										contato)
							VALUES (?,?,?)");
		$stmt->bind_param('iis',
						$this->usuarios_id,
						$this->tipos_contato_id,
						$this->contato);
		// Verificação
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: Não foi possível cadastrar este contato.");
			$db_err ++;	// erro
		} else {
			$this->id = $db->insert_id; // Se deu certo já podemos recuperar o ID dela.
			// Vamos também recuperar seu tipo.
			$sql = "SELECT tipo_contato 
					FROM tipos_contato
					WHERE id = %d";
			$result = query($sql,$this->tipos_contato_id);
			$dbdata = $result->fetch_assoc();
			$this->tipo_contato = $dbdata['tipo_contato'];
			$result->free();
			$log->lwrite("Novo contato cadastrado. ID: ".$this->id);
		}
		$stmt->free_result();
		return $db_err;
	}
	
	/**
	 * Altera os dados do grupo no banco de dados.
	 * 
	 * @return integer	0 se alterado com sucesso, > 0 caso contrário
	 */
	function UpdateContactData() {
		global $db;
		global $log;
		$db_err = 0;
		$stmt = $db->prepare("UPDATE contato 
							SET	usuarios_id = ?,
								tipos_contato_id = ?,
								contato = ?
							WHERE id = ?");
		$stmt->bind_param('iisi',
						$this->usuarios_id,
						$this->tipos_contato_id,
						$this->contato,
						$this->id);
	
		// Verificação
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: Não foi possível alterar os dados do contato.");
			$db_err ++;	// erro
		} else {
			$log->lwrite("Alteração no contato. ID: ".$this->id);
		}
		$stmt->free_result();
		return $db_err;
	}
	
	/**
	 * Remove o cadastro deste contato no bd de acordo com sua ID.
	 * 
	 * @return integer	0 se removido com sucesso, > 0 caso contrário
	 */
	function RemoveThisContact() {
		global $log;
		$db_err = 0;
		// Primeiro checamos se o cadastro existe
		$sql = "SELECT * FROM contato WHERE id = %d";
		$result = query($sql,$this->id);
		// Se existe o cadastro
		if($result->num_rows) {
			$sql = "DELETE FROM contato WHERE id = %d";
			$result = query($sql,$this->id);
			if($result === false) {
				$log->lwrite("ERRO: Não foi possível remover este contato. ID: ".$this->id);
				$db_err++;
			} else {
				$log->lwrite("Removido contato. ID: ".$this->id);
			}
		} else {
			$log->lwrite("ERRO: Identificador de contato inexistente. ID: ".$this->id);
			$db_err++;
		}
		return $db_err;
	}
	
	/**
	 * Recupera todos os dados do contato do bd.
	 * 
	 * @return integer	0 se encontrado, > 0 caso contrário
	 */
	public function GetDataFromDB($id) {
		global $log;
		$db_err = 0;
		$sql = "SELECT contato.*, tipos_contato.tipo_contato 
				FROM contato
				LEFT JOIN tipos_contato ON contato.tipos_contato_id = tipos_contato.id
				WHERE contato.id = %d";
		$result = query($sql,$id);
			
		if($result->num_rows) {
			$dbdata = $result->fetch_assoc();
			$this->id = $id;
			$this->usuarios_id = $dbdata['usuarios_id'];
			$this->tipos_contato_id = $dbdata['tipos_contato_id'];
			$this->contato = $dbdata['contato'];
			$this->tipo_contato = $dbdata['tipo_contato'];
			$result->free();	
		} else {
			//cadastro não encontrado
			$log->lwrite("ERRO: ID inválido, contato não encontrado. ID: ".$id);
			$db_err++;
		}
		return $db_err;
	}
}

/**
 * Classe para entidade Grupos	
 * 
 * Esta classe modela todas funções necessárias para manipulação
 * de grupos de usuários no sistema.
 *
**/
class Group {
  
	private $id;					// Identificador único do grupo @var integer
	private $grupo;					// Grupo definido @var string
	private $acronimo;				// Acronimo para o grupo @var string
	
	/**
	 * Getter, retorna o valor do atributo
	 * 
	 * @param 	string $attr 	O atributo desejado
	 * @return 	void 			O valor do atributo
	 */
	function &__get($attr) {
		return $this->$attr;
	}
	
    /**
	 * Setter, altera o valor do atributo
	 * 
	 * @param string 	$attr 	O atributo desejado
	 * @param void		$value	Valor atribuído
	 */
	function __set($attr, $value) {
		$this->$attr = $value;
	}
	
	/**
	 * Define todas as informações do Grupo (exceto id)
	 * 
	 * @param string 	$grupo 		Nome do grupo
	 * @param integer	$acronimo	Sigla ou acronimo do grupo
	 */
	function __construct($grupo, $acronimo) {
		$this->grupo = $grupo;
		$this->acronimo = $acronimo;
	}
	
	/**
	 * Cadastra o grupo no banco de dados
	 *
	 * @return integer	0 se cadastrado com sucesso, > 0 caso contrário
	 */
	function SignThisGroup() {	
		global $db;
		global $log;
		$db_err = 0;
		$stmt = $db->prepare("INSERT INTO grupo
										(grupo,
										acronimo)
							VALUES (?,?)");
		$stmt->bind_param('ss',
						$this->grupo,
						$this->acronimo);
		// Verificação
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: Não foi possível cadastrar este grupo. ID: ".$this->id);
			$db_err ++;	// erro
		} else {
			$this->id = $db->insert_id; // Se deu certo já podemos recuperar o ID dela.
			$log->lwrite("Novo grupo cadastrado. ID: ".$this->id);
		}
		$stmt->free_result();
		return $db_err;
	}
	
	/**
	 * Altera os dados do grupo no banco de dados.
	 * 
	 * @return integer	0 se alterado com sucesso, > 0 caso contrário
	 */
	function UpdateGroupData() {	
		global $db;
		global $log;
		$db_err = 0;
		$stmt = $db->prepare("UPDATE grupo 
							SET	grupo = ?,
								acronimo = ?
							WHERE id = ?");
		$stmt->bind_param('ssi',
						$this->grupo,
						$this->acronimo,
						$this->id);
	
		// Verificação
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: Não foi possível alterar os dados do grupo. ID: ".$this->id);
			$db_err ++;	// erro
		} else {
			$log->lwrite("Alteração grupo. ID: ".$this->id);
		}
		$stmt->free_result();
		return $db_err;
	}
	
	/**
	 * Remove o cadastro deste grupo no bd de acordo com sua ID.
	 * 
	 * @return integer	0 se removido com sucesso, > 0 caso contrário
	 */
	function RemoveThisGroup() {
		global $log;
		$db_err = 0;
		// Primeiro checamos se o cadastro existe
		$sql = "SELECT * FROM grupo WHERE id = %d";
		$result = query($sql,$this->id);
		// Se existe o cadastro
		if($result->num_rows) {
			// Necessário checar se existem usuarios neste grupo
			$sql = "SELECT * FROM grupos_usuario WHERE grupo_id = %d";
			$result = query($sql,$this->id);
			// Se existirem usuários neste grupo não sera possível excluir
			if($result->num_rows) {
				$log->lwrite("ERRO: Existem usuarios cadastrados neste grupo, não será possível remover o grupo. ID: ".$this->id);
				$db_err++;
			} else {
				// Senão o grupo pode ser excluído
				$sql = "DELETE FROM grupo WHERE id = %d";
				$result = query($sql,$this->id);
				if($result === false) {
					$log->lwrite("ERRO: Não foi possível remover este grupo. ID: ".$this->id);
					$db_err++;
				} else {
					$log->lwrite("Removido grupo. ID: ".$this->id);
				}
			}
		} else {
			$log->lwrite("ERRO: Identificador de grupo inexistente. ID: ".$this->id);
			$db_err++;
		}
		return $db_err;
	}
	
	/**
	 * Remove o cadastro deste grupo no bd de acordo com sua ID.
	 * Se existirem usuários vinculados, serão desvinculados automaticamente.
	 * 
	 * @return integer	0 se removido com sucesso, > 0 caso contrário
	 */
	function ForceRemoveThisGroup() {
		global $db;
		global $log;
		$db_err = 0;
		
		// Desabilitamos o autocommit para garantir todas as remoções
		$db->autocommit(FALSE);
		
		// Primeiro checamos se o cadastro existe
		$sql = "SELECT * FROM grupo WHERE id = %d";
		$result = query($sql,$this->id);
		// Se existe o cadastro
		if($result->num_rows) {
			
			// Necessário remover todos os usuários vinculados
			$sql = "DELETE FROM grupos_usuario WHERE grupo_id = %d";
			$result = query($sql,$this->id);
			
			// Agora o grupo pode ser excluído
			$sql = "DELETE FROM grupo WHERE id = %d";
			$result = query($sql,$this->id);
			if($result === false) {
				$log->lwrite("ERRO: Não foi possível remover este grupo. ID: ".$this->id);
				$db_err++;		//erro
			}
			
			// Verificação final
			if($db_err > 0) {
				// Se houver qualquer erro, não salvar nada no bd
				$db->rollback();
			} else {
				// Se não houver erro, salva tudo no banco de dados
				$db->commit();
				$log->lwrite("Removido (forced) grupo. ID: ".$this->id);
			}
			
		} else {
			$log->lwrite("ERRO: Identificador de grupo inexistente. ID: ".$this->id);
			$db_err++;
		}
		// Habilitamos novamente o autocommit
		$db->autocommit(TRUE);
		return $db_err;	
	}
	
	
	/**
	 * Recupera todos os dados do grupo do bd.
	 * 
	 * @return integer	0 se encontrado, > 0 caso contrário
	 */
	public function GetDataFromDB($id) {
		global $log;
		$db_err = 0;
		$sql = "SELECT * FROM grupo WHERE id = %d";
		$result = query($sql,$id);
			
		if($result->num_rows) {
			$dbdata = $result->fetch_assoc();
			$this->id = $id;
			$this->grupo = $dbdata['grupo'];
			$this->acronimo = $dbdata['acronimo'];
			$result->free();	
		} else {
			//cadastro não encontrado
			$log->lwrite("ERRO: ID inválido, grupo não encontrado. ID: ".$id);
			$db_err++;
		}
		return $db_err;
	}
  
}

/**
 * Classe para entidade Categoria
 * 
 * Esta classe modela todas funções necessárias para manipulação
 * de categorias de usuários no sistema.
 *
**/
class Category {
  
	private $id;					// Identificador único da categoria @var integer
	private $categoria;				// Categoria definida @var string
	private $periodo_expiracao;		// Periodo de expiração para usuários cadastrados @var integer
	private $descricao;				// Descrição da categoria @var string

	/**
	 * Getter, retorna o valor do atributo
	 * 
	 * @param 	string $attr 	O atributo desejado
	 * @return 	void 			O valor do atributo
	 */
	function __get($attr) {
		return $this->$attr;
	}
	
    /**
	 * Setter, altera o valor do atributo
	 * 
	 * @param string 	$attr 	O atributo desejado
	 * @param void		$value	Valor atribuído
	 */
	function __set($attr, $value) {
		$this->$attr = $value;
	}
	
	 /**
	 * Define todas as informações da Categoria (exceto id)
	 * 
	 * @param string 	$categoria 	A Categoria
	 * @param integer	$periodo_expiracao	Tempo em que um cadastro de usuário vai expirar
	 * @param string	$descricao	Descrição e informações sobre a categoria
	 */
	function __construct($categoria, $periodo_expiracao, $descricao) {
		$this->categoria = $categoria;
		$this->periodo_expiracao = $periodo_expiracao;
		$this->descricao = $descricao;
	}
	
	 /**
	 * Cadastra a categoria no banco de dados
	 *
	 * @return integer	0 se cadastrado com sucesso, > 0 caso contrário
	 */
	function SignThisCategory() {
		global $db;
		global $log;
		$db_err = 0;
		$stmt = $db->prepare("INSERT INTO categoria
										(categoria,
										periodo_expiracao,
										descricao)
							VALUES (?,?,?)");
		$stmt->bind_param('sis',
						$this->categoria,
						$this->periodo_expiracao,
						$this->descricao);
		
		// Verificação
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: Não foi possível cadastrar esta categoria.");
			$db_err ++;	// erro
		} else {
			$this->id = $db->insert_id; // Se deu certo já podemos recuperar o ID dela.
			$log->lwrite("Nova categoria cadastrada. ID: ".$this->id);
		}
		$stmt->free_result();
		return $db_err;
	}
	
	/**
	 * Altera os dados da categoria no banco de dados.
	 * 
	 * @return integer	0 se alterado com sucesso, > 0 caso contrário
	 */
	function UpdateCategoryData() {
		global $db;
		global $log;
		$db_err = 0;
		$stmt = $db->prepare("UPDATE categoria 
							SET	categoria = ?,
								periodo_expiracao = ?,
								descricao = ?
							WHERE id = ?");
		$stmt->bind_param('sisi',
						$this->categoria,
						$this->periodo_expiracao,
						$this->descricao,
						$this->id);
	
		// Verificação
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: Não foi possível alterar os dados da categoria. ID: ".$this->id);
			$db_err ++;	// erro
		} else {
			$log->lwrite("Alteração na categoria. ID: ".$this->id);
		}
		$stmt->free_result();
		return $db_err;
	}
	
	/**
	 * Remove o cadastro desta categoria no bd de acordo com sua ID.
	 * 
	 * @return integer	0 se removido com sucesso, > 0 caso contrário
	 */
	function RemoveThisCategory() {
		global $log;
		$db_err = 0;
		// Primeiro checamos se o cadastro existe
		$sql = "SELECT * FROM categoria WHERE id = %d";
		$result = query($sql,$this->id);
		// Se existe o cadastro
		if($result->num_rows) {
			// Necessário checar se existem usuários nesta categoria
			$sql = "SELECT * FROM usuarios WHERE categoria_id = %d";
			$result = query($sql,$this->id);
			// Se existirem usuários com esta categoria não sera possível excluir
			if($result->num_rows) {
				$log->lwrite("ERRO: Existem usuarios cadastrados nesta categoria, não será possível remover esta categoria. ID: ".$this->id);
				$db_err++;
			} else {
				// Senão a categoria pode ser excluída
				$sql = "DELETE FROM categoria WHERE id = %d";
				$result = query($sql,$this->id);
				if($result === false) {
					$log->lwrite("ERRO: Não foi possível remover esta categoria. ID: ".$this->id);
					$db_err++;		//erro
				} else {
					$log->lwrite("Removida categoria: ID: ".$this->id);
				}
			}
		} else {
			$log->lwrite("ERRO: Identificador de categoria inexistente. ID: ".$this->id);
			$db_err++;
		}
		return $db_err;
	}
	
	/**
	 * Recupera todos os dados da categoria do bd.
	 * 
	 * @return integer	0 se encontrado, > 0 caso contrário
	 */
	public function GetDataFromDB($id) {
		global $log;
		$db_err = 0;
		$sql = "SELECT * FROM categoria WHERE id = %d";
		$result = query($sql,$id);
			
		if($result->num_rows) {
			$dbdata = $result->fetch_assoc();
			$this->id = $id;
			$this->categoria = $dbdata['categoria'];
			$this->periodo_expiracao = $dbdata['periodo_expiracao'];
			$this->descricao = $dbdata['descricao'];
			$result->free();	
		} else {
			//cadastro não encontrado
			$log->lwrite("ERRO: ID inválido, categoria não encontrada. ID: ".$id);
			$db_err++;
		}
		return $db_err;
	}
}

/**
 * Classe para entidade Pendencias
 * 
 * Esta classe modela todas funções necessárias para manipulação
 * de pendencias do sistema. Os valores padrões são definidos no início
 * desde arquivo.
 *
**/
class Pending {
  
	private $id;					// Identificador único da pendencia @var integer
	private $tabela;				// A tabela onde a modificação foi feita @var integer [valores definidos no inicio do arquivo]
	private $tabela_id;				// O identificador do registro alterado @var integer
	private $motivo;				// O motivo da notificação @var integer [valores definidos no inicio do arquivo]
	private $informacao;			// Descrição da informação ou dados do registro excluido @var string
	private $resolvida;				// Booleano se diz se foi resolvido ou não @var boolean
	private $data;					// Data e hora de quando a pendencia foi adicionada @var datetime
	
	/**
	 * Getter, retorna o valor do atributo
	 * 
	 * @param 	string $attr 	O atributo desejado
	 * @return 	void 			O valor do atributo
	 */
	function __get($attr) {
		return $this->$attr;
	}
	
    /**
	 * Setter, altera o valor do atributo
	 * 
	 * @param string 	$attr 	O atributo desejado
	 * @param void		$value	Valor atribuído
	 */
	function __set($attr, $value) {
		$this->$attr = $value;
	}
	
	 /**
	 * Define todas as informações da Categoria (exceto id)
	 * 
	 * @param integer 	$tabela 	Tabela de onde veio a notificação
	 * @param integer	$tabela_id	ID onde ocorreu a alteração
	 * @param integer	$motivo		Motivo da alteração
	 * @param string	$informacao	Informação adicional ou dados do registro excluido
	 */
	function __construct($tabela, $tabela_id, $motivo, $informacao) {
		$this->id = NULL;
		$this->tabela = $tabela;
		$this->tabela_id = $tabela_id;
		$this->motivo = $motivo;
		$this->informacao = $informacao;
		$this->resolvida = 0;
		$date = date('Y-m-d H:i:s');
		$this->data = $date;
	}
	
	 /**
	 * Cadastra a pendencia no banco de dados
	 *
	 * @return integer	0 se cadastrado com sucesso, > 0 caso contrário
	 */
	function SignThisPending() {
		global $db;
		global $log;
		$db_err = 0;
		$stmt = $db->prepare("INSERT INTO pendencias
										(tabela,
										tabela_id,
										motivo,
										informacao,
										resolvida,
										data)
							VALUES (?,?,?,?,?,?)");
		$stmt->bind_param('iiisis',
						$this->tabela,
						$this->tabela_id,
						$this->motivo,
						$this->informacao,
						$this->resolvida,
						$this->data);
		
		// Verificação
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: Não foi possível cadastrar esta pendencia. ID: ".$this->id);
			$db_err ++;	// erro
		} else {
			$this->id = $db->insert_id; // Se deu certo já podemos recuperar o ID dela.
			$log->lwrite("Nova pendencia cadastrada. ID: ".$this->id);
		}
		$stmt->free_result();
		return $db_err;
	}
	
	/**
	 * Altera os dados da pendencia no banco de dados.
	 * 
	 * @return integer	0 se alterado com sucesso, > 0 caso contrário
	 */
	function UpdatePendingData() {
		global $db;
		global $log;
		$db_err = 0;
		$stmt = $db->prepare("UPDATE pendencias 
							SET	tabela = ?,
								tabela_id = ?,
								motivo = ?,
								informacao = ?,
								resolvida = ?,
								data = ?
							WHERE id = ?");
		$stmt->bind_param('iiisisi',
						$this->tabela,
						$this->tabela_id,
						$this->motivo,
						$this->informacao,
						$this->resolvida,
						$this->data,
						$this->id);
	
		// Verificação
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: Não foi possível alterar os dados da pendencia. ID: ".$this->id);
			$db_err ++;	// erro
		} else {
			$log->lwrite("Alteração na pendência. ID: ".$this->id);
		}
		$stmt->free_result();
		return $db_err;
	}
	
	/**
	 * Remove o cadastro desta pendencia no bd de acordo com sua ID.
	 * 
	 * @return integer	0 se removido com sucesso, > 0 caso contrário
	 */
	function RemoveThisPending() {
		global $log;
		$db_err = 0;
		// Primeiro checamos se o cadastro existe
		$sql = "SELECT * FROM pendencias WHERE id = %d";
		$result = query($sql,$this->id);
		// Se existe o cadastro
		if($result->num_rows) {			
			// Senão a pendencia pode ser excluída
			$sql = "DELETE FROM pendencias WHERE id = %d";
			$result = query($sql,$this->id);
			if($result === false) {
				$log->lwrite("ERRO: Não foi possível remover esta pendencia. ID: ".$this->id);
				$db_err++;		//erro
			} else {
				$log->lwrite("Removida pendência: ID: ".$this->id);
			}
		} else {
			$log->lwrite("ERRO: Identificador de pendencia inexistente. ID: ".$this->id);
			$db_err++;
		}
		return $db_err;
	}
	
	/**
	 * Recupera todos os dados da pendencia do bd.
	 * 
	 * @return integer	0 se encontrado, > 0 caso contrário
	 */
	public function GetDataFromDB($id) {
		global $log;
		$db_err = 0;
		$sql = "SELECT * FROM pendencias WHERE id = %d";
		$result = query($sql,$id);
			
		if($result->num_rows) {
			$dbdata = $result->fetch_assoc();
			$this->id = $id;
			$this->tabela = $dbdata['tabela'];
			$this->tabela_id = $dbdata['tabela_id'];
			$this->motivo = $dbdata['motivo'];
			$this->informacao = $dbdata['informacao'];
			$this->resolvida = $dbdata['resolvida'];
			$this->data = $dbdata['data'];
			$result->free();	
		} else {
			//cadastro não encontrado
			$log->lwrite("ERRO: ID inválido, pendencias não encontrada. ID: ".$id);
			$db_err++;
		}
		return $db_err;
	}
	
	/**
	 * Marca a pendencia como resolvida
	 * 
	 * @return integer	0 se sucesso, > 0 caso contrário
	 */
	public function MarkAsResolved() {
		global $db;
		global $log;
		$db_err = 0;
		$stmt = $db->prepare("UPDATE pendencias 
							SET	resolvida = 1
							WHERE id = ?");
		$stmt->bind_param('i', $this->id);
	
		// Verificação
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: Não foi possível marcar esta pendencia como resolvida. ID: ".$this->id);
			$db_err ++;	// erro
		} else {
			$log->lwrite("Resolvida pendência ID: ".$this->id);
		}
		$stmt->free_result();
		return $db_err;
	}
	
	/**
	 * Marca a pendencia como não resolvida
	 * 
	 * @return integer	0 se sucesso, > 0 caso contrário
	 */
	public function MarkAsUnresolved() {
		global $db;
		global $log;
		$db_err = 0;
		$stmt = $db->prepare("UPDATE pendencias 
							SET	resolvida = 0
							WHERE id = ?");
		$stmt->bind_param('i', $this->id);
	
		// Verificação
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: Não foi possível marcar esta pendencia como não resolvida. ID: ".$this->id);
			$db_err ++;	// erro
		} else {
			$log->lwrite("Unresolvida pendência ID: ".$this->id);
		}
		$stmt->free_result();
		return $db_err;
	}
	
}
?>