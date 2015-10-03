<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descri��o: Este arquivo agrupa todas as principais classes do sistema.
 *			   Cada tabela do banco de dados foi modelada como uma classe.
 *
 *	Observa��o: 
 *
**/

require_once("database.php");			// Fun��es para manipula��o do banco de dados
require_once("general_functions.php");	// Outras fun��es utilizadas pelas classes


//Defini��es de arquivo (termos de contrato, cadastro usuarios)
define("LINK_DOCUMENTO_1","./docs/politica-rede-cbiot.pdf");
define("LINK_DOCUMENTO_2","#");

// Defini��es de tamanho dos campos de acordo com o banco de dados

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

// ENDERE�O
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

// Pendencias s�o adicionadas automaticamente nas fun��es
// SignThisUser, RemoveThisUser, SignThisDevice, RemoveThisDevice.

// -> Echo functions
function echo_motivo($motivo){
	switch($motivo) {
		case NOVO:		echo "Novo"; break;
		case ALTERADO: 	echo utf8_encode("Altera��o"); break;
		case REMOVIDO:	echo "Removido"; break;
		case EXPIRACAO:	echo utf8_encode("Expira��o"); break;
		case REATIVACAO:	echo utf8_encode("Reativa��o"); break;
		default: echo "Indefinido";
	}
}

function echo_tabela($tabela){
	switch($tabela) {
		case USUARIOS:		echo utf8_encode("Usu�rio"); break;
		case DISPOSITIVOS: 	echo "Dispositivo"; break;
		default: echo "Indefinido";
	}
}


/**
 * Classe para entidade usuario
 * 
 * Esta classe modela todas fun��es necess�rias para manipula��o
 * de usuarios no sistema.
 *
**/
class User {

	private $id;					// Identificador �nico do usu�rio @var integer
	private $nome;					// Nome completo do usu�rio @var string
	private $rg;					// Registro geral do usu�rio @var string
	private $data_rg;				// Data do RG @var date
	private $emissor_rg;			// Emissor do RG @var string
	private $cpf;					// Cadastro Pessoa F�sica @var string
	private $matricula;				// N�mero de matr�cula @var string
	private $instituicao;			// Institui��o de ensino @var string
	private $nascimento;			// Data de nascimento @var date
	private $sexo;					// Defini��o sexual @var char
	private $nome_mae;				// Nome da m�e @var string
	private $nome_pai;				// Nome do pai @var string
	private $endereco_id;			// Chave estrangeira para endere�o @var integer
	private $endereco;				// Objeto do tipo Address @var Address
	private $categoria_id;			// Chave estrangeira para categoria @var integer
	private $categoria;				// Categoria do usu�rio @var string
	private $escolaridade_id;		// Chave estrangeira para escolaridade @var integer
	private $escolaridade;			// Escolaridade do usu�rio @var string
	private $estado_civil_id;		// Chave estrangeira para estado civil @var integer
	private $estado_civil;			// Estado Civil @var string
	private $cargo;					// Cargo do usu�rio @var string
	private $professor_lider;		// Define se o professor � l�der @var bool
	private $professor_externo;		// Define se o professor � externo ao CBIOT @var bool
	private $orientador_id;			// Chave estrangeira para outro usu�rio (orientador) @var integer
	private $orientador;			// Objeto do tipo User @var User
	private $lider_id;				// Chave estrangeira para outro usu�rio (lider) @var integer
	private $lider;					// Objeto do tipo User @var User
	private $acesso_id;				// Chave estrangeira para o acesso @var integer
	private $acesso;				// Objeto do tipo Access @var Access
	private $data_cadastro;			// Data em que foi realizado o cadastro @var date
	private $data_expiracao;		// Data em que o cadastro expira @var date
	private $ativo;					// Define se o usu�rio est� ativo ou n�o @var bool
	private $comentario;			// Informa��es adicionais ao cadastro @var string
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
	 * @param void		$value	Valor atribu�do
	 */
	function __set($attr, $value) {
		$this->$attr = $value;
	}
	
	/**
	 * Construtor da classe
	 * 
	 * Define todas as informa��es para um valor padr�o/nulo, exceto pelo nome.
	 *
	 * @param string 	$nome 	Nome completo do usu�rio
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
		// Atividade do usu�rio. Padr�o inativo = 0 (ativo = 1)
		$this->ativo = 0;	
		$this->comentario  = NULL;
		$this->contatos = NULL;
		$this->dispositivos = NULL;
		$this->grupos = NULL;
	}
	
	/**
	 * Cadastra usu�rio tendo todas as informa��es necess�rias
	 *
	 * @param string 	$nome 				Nome completo do usu�rio
	 * @param string 	$rg 				Registro Geral
	 * @param string 	$data_rg 			Data do RG
	 * @param string 	$emissor_rg 		Emissor do RG
	 * @param string 	$cpf 				Cadastro de Pessoa F�sica
	 * @param string 	$matricula 			N�mero de matricula na institui��o
	 * @param string 	$instituicao 		Nome da institui��o
	 * @param string 	$nascimento 		Data de nascimento
	 * @param string 	$sexo 				Identificador de genero
	 * @param string 	$nome_mae 			Nome completo da m�e
	 * @param string 	$nome_pai 			Nome completo do pai
	 * @param string 	$endereco 			Objeto do tipo Address
	 * @param string 	$categoria_id 		Identificador de categoria
	 * @param string 	$escolaridade_id 	Identificador de escolaridade
	 * @param string 	$estado_civil_id 	Identificador de estado_civil
	 * @param string 	$cargo 				Descri��o do cargo
	 * @param string 	$professor_lider 	Defini��o de professor l�der
	 * @param string 	$professor_externo 	Defini��o de professor externo
	 * @param string 	$orientador_id		Identificador do orientador
	 * @param string 	$lider_id 			Identificador do l�der
	 * @param string 	$acesso 			Objeto do tipo Access
	 * @param string 	$comentario 		Coment�rio adicional
	 * @param string 	$grupos 			Array de objetos do tipo Group
	 * @param string 	$contatos 			Array de objetos do tipo Contact
	 * @param string 	$dispositivos		Array de objetos do tipo Device
	 *
	 * @return integer	0 se cadastrado com sucesso, >0 caso contr�rio.
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
		$this->ativo = 0;	// default inativo = 0 (administra��o deve validar cadastro)
				
		// Temos todas as informa��es, cadastramos o usu�rio no banco (endere�o e acesso tamb�m)
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
	 * Cadastra usu�rio no banco de dados
	 * 
	 * @return Integer	0 se cadastrado com sucesso, >0 caso contr�rio.
	**/	
	function SignThisUser() {
		global $db;
		global $log;
		$db_err = 0;
		
		// Desabilitamos o autocommit para garantir todas as inser��es
		$db->autocommit(FALSE);
		
		// Cadastramos o endere�o
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
		// Verifica��o
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: N�o foi poss�vel cadastrar usu�rio. SQL: ".$this->nome);
			$db_err ++;	// erro
		} else {
			$this->id = $db->insert_id; // Se deu certo j� podemos recuperar seu ID.
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
			
			$log->lwrite("Novo usu�rio cadastrado. Nome: ".$this->nome." ID: ".$this->id);
		}
		$stmt->free_result();
		
		// Verifica��o final
		if($db_err > 0) {
			// Se houver qualquer erro, n�o salvar nada no bd
			$db->rollback();
		} else {
			// Se n�o houver erro, salva tudo no banco de dados
			$db->commit();
		}
			
		// Habilitamos novamente o autocommit
		$db->autocommit(TRUE);
		
		return $db_err;
	}
	
	/**
	 * Cadastra um contato para este usu�rio
	 *
	 * @return integer	0 se cadastrado com sucesso, > 0 caso contr�rio
	 */
	function AddContact($contato) {
		$contato->usuarios_id = $this->id;
		return $contato->SignThisContact();
	}
	
	/**
	 * Remove o contato do usu�rio
	 *
	 * @return integer	0 se removido com sucesso, > 0 caso contr�rio
	 */
	function RemoveContact($contato) {
		global $db;
		global $log;
		$db_err = 0;
		// Remover contato
		$sql = "DELETE FROM contato WHERE usuarios_id = %d AND id = %d";
		$result = query($sql,$this->id, $contato->id);
		if($result === false) {
			$log->lwrite("ERRO: Erro ao remover contato do usu�rio. Nome: ".$this->nome);
			$db_err++;		//erro
		}
		return $db_err;	
	}
	
	/**
	 * Remove todos os contatos deste usu�rio
	 *
	 * @return integer	0 se removidos com sucesso, > 0 caso contr�rio
	 */
	function RemoveAllContacts() {
		global $db;
		global $log;
		$db_err = 0;
		// Remover todos os contatos deste usu�rio
		$sql = "DELETE FROM contato WHERE usuarios_id = %d";
		$result = query($sql,$this->id);
		if($result === false) {
			$log->lwrite("ERRO: Erro ao remover todos os contatos do usu�rio.");
			$db_err++;		//erro
		}
		return $db_err;	
	}

	/**
	 * Cadastra um dispositivo para este usu�rio
	 *
	 * @return integer	0 se cadastrado com sucesso, > 0 caso contr�rio
	 */
	function AddDevice($dispositivo) {
		$dispositivo->usuarios_id = $this->id;
		return $dispositivo->SignThisDevice();
	}
	
	/**
	 * Remove o dispositivo do usu�rio
	 *
	 * @return integer	0 se removido com sucesso, > 0 caso contr�rio
	 */
	function RemoveDevice($dispositivo) {
		global $db;
		global $log;
		$db_err = 0;
		// Remover dispositivo
		$sql = "DELETE FROM dispositivos WHERE usuarios_id = %d AND id = %d";
		$result = query($sql,$this->id, $dispositivo->id);
		if($result === false) {
			$log->lwrite("ERRO: Erro ao remover dispositivo do usu�rio.");
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
	 * Remove todos os dispositivos deste usu�rio
	 *
	 * @return integer	0 se removidos com sucesso, > 0 caso contr�rio
	 */
	function RemoveAllDevices() {
		global $db;
		global $log;
		$db_err = 0;
		// Remover todos os dispositivos deste usu�rio
		$sql = "DELETE FROM dispositivos WHERE usuarios_id = %d";
		$result = query($sql,$this->id);
		if($result === false) {
			$log->lwrite("ERRO: Erro ao remover todos os dispositivo do usu�rio.");
			$db_err++;		//erro
		} else {
			$log->lwrite("Removidos todos os dispositivos do usu�rio ".$this->nome);
		}
		return $db_err;	
	}
	
	/**
	 * Cadastra este usu�rio em um grupo
	 *
	 * @return integer	0 se cadastrado com sucesso, > 0 caso contr�rio
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
		// Verifica��o
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: N�o foi poss�vel cadastrar o usu�rio ".$this->nome." no grupo ".$grupo->grupo.".");
			$db_err ++;	// erro
		} else {
			$log->lwrite("Novo grupo ".$grupo->grupo." cadastrado para o usu�rio ".$this->nome);
		}
		$stmt->free_result();
		return $db_err;	
	}
	
	/**
	 * Remove o usu�rio do grupo
	 *
	 * @return integer	0 se removido com sucesso, > 0 caso contr�rio
	 */
	function RemoveGroup($grupo) {
		global $db;
		global $log;
		$db_err = 0;
		// Remover grupo
		$sql = "DELETE FROM grupos_usuario WHERE usuarios_id = %d AND grupo_id = %d";
		$result = query($sql,$this->id, $grupo->id);
		if($result === false) {
			$log->lwrite("ERRO: Erro ao remover grupo do usu�rio.");
			$db_err++;		//erro
		} else {
			$log->lwrite("Removido grupo ".$grupo->acronimo." do usu�rio ".$this->nome);
		}
		return $db_err;	
	}
	
	/**
	 * Remove o usu�rio de todos os grupos
	 *
	 * @return integer	0 se removido com sucesso, > 0 caso contr�rio
	 */
	function RemoveAllGroups() {
		global $db;
		global $log;
		$db_err = 0;
		// Remover grupos				
		$sql = "DELETE FROM grupos_usuario WHERE usuarios_id = %d";
		$result = query($sql,$this->id);
		if($result === false) {
			$log->lwrite("ERRO: Erro ao remover todos os grupos do usu�rio.");
			$db_err++;		//erro
		} else {
			$log->lwrite("Removidos todos os grupos do usu�rio ".$this->nome);
		}
		return $db_err;	
	}
		
	/**
	 * Altera os dados do usu�rio no banco de dados.
	 * 
	 * @return integer	0 se alterado com sucesso, > 0 caso contr�rio
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
	
		// Verifica��o
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: Erro ao alterar dados do usu�rio. Nome: ".$this->nome);
			$db_err ++;	// erro
		} else {
			$this->GetDataFromDB($this->id);
			$log->lwrite("Altera��o nos dados de ".$this->nome);
		}
		$stmt->free_result();
		return $db_err;
	}
	
	/**
	 * Altera a data de expira��o do usuario.
	 * 
	 * @return integer	0 se alterado com sucesso, > 0 caso contr�rio
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
	
		// Verifica��o
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: N�o foi poss�vel alterar data de expiracao.");
			$db_err ++;	// erro
		} else {
			$this->GetDataFromDB($this->id);
			$log->lwrite("Altera��o da data de expira��o do usu�rio ".$this->nome);
		}
		$stmt->free_result();
		return $db_err;
	}
	
	/**
	 * Remove o cadastro deste usu�rio e todas as informa��se relevantes
	 * do bd de acordo com sua ID.
	 *
	 * Aten��o: Tamb�m ser�o removidos endere�os, acesso, dispositivos, contatos.
	 * 
	 * @return integer	0 se removido com sucesso, > 0 caso contr�rio
	 */
	function RemoveThisUser() {
		global $db;
		global $log;
		$db_err = 0;
		// Desabilitamos o autocommit para garantir todas as remo��es
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
				$log->lwrite("ERRO: N�o � poss�vel remover este usu�rio por ser lider/orientador de outros usu�rios. Nome: ".$this->nome);
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
					$log->lwrite("ERRO: Erro ao remover contatos do usu�rio. Nome: ".$this->nome);
					$db_err++;		//erro
				}
				// Remover grupos				
				$sql = "DELETE FROM grupos_usuario WHERE usuarios_id = %d";
				$result = query($sql,$this->id);
				if($result === false) {
					$log->lwrite("ERRO: Erro ao remover grupos do usu�rio. Nome: ".$this->nome);
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
					$log->lwrite("ERRO: Erro ao remover o usu�rio. Nome: ".$this->nome);
					$db_err++;		//erro
				}
				// Remover acesso:
				$sql = "DELETE FROM acesso WHERE id = %d";
				$result = query($sql,$this->acesso_id);
				if($result === false) {
					$log->lwrite("ERRO: Erro ao remover acesso do usu�rio. Nome: ".$this->nome);
					$db_err++;		//erro
				}
				// Remover endereco
				$sql = "DELETE FROM endereco WHERE id = %d";
				$result = query($sql,$this->endereco_id);
				if($result === false) {
					$log->lwrite("ERRO: Erro ao remover endere�o do usu�rio. Nome: ".$this->nome);
					$db_err++;		//erro
				}
				// Verifica��o final
				if($db_err > 0) {
					// Se houver qualquer erro, n�o salvar nada no bd
					$db->rollback();
				} else {
					// Se n�o houver erro, salva tudo no banco de dados
					$db->commit();
					//Adicionamos pendencia:
					$pending = new Pending(USUARIOS, $this->id, REMOVIDO, $this->nome);
					$pending->SignThisPending();
					$log->lwrite("Removido o usu�rio ".$this->nome." e todos os dados relativos a ele.");
				}
			}
		} else {
			$log->lwrite("ERRO: Identificador de usu�rio inexistente.");
			$db_err++;
		}
		
		// Habilitamos novamente o autocommit
		$db->autocommit(TRUE);
		return $db_err;			
	}
	
	/**
	 * Recupera todos os dados do usu�rio do bd.
	 * 
	 * @return integer	0 se encontrado, > 0 caso contr�rio
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
			//cadastro n�o encontrado
			$log->lwrite("ERRO: ID inv�lido, usu�rio n�o encontrado. (ID: $id)");
			$db_err++;
		}
		return $db_err;
	}
	
	/**
	 * Recupera todos os dados do usu�rio atrav�s do nome.
	 * 
	 * @return integer	0 se encontrado, > 0 caso contr�rio
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
			//cadastro n�o encontrado
			$log->lwrite("Nome inv�lido, usu�rio n�o encontrado. (Nome: $nome)");
			$db_err++;
			return false;
		}
	}
   
}

/**
 * Classe para entidade Endere�o
 * 
 * Esta classe modela todas fun��es necess�rias para manipula��o
 * de endere�os de usu�rios no sistema.
 *
**/
class Address {
   
	private $id;			// Identificador �nico do endere�o @var integer
	private $logradouro;	// Logradouro do endere�o (rua, avenida...) @var string
	private $numero;		// O n�mero da residencia @var integer
	private $complemento;	// Complemento (apartamento, bloco, unidade...) @var string
	private $bairro;		// Bairro @var string
	private $cep;			// C�digo postal @var string
	private $municipio_id;	// Chave estrangeira para o munic�pio @var integer
	private $municipio;		// Munic�pio @var string
	private $uf;			// Unidade Federal @var string
	private $estado;		// Estado @var string
	private $pais_id;		// Chave estrangeira para o Pa�s @var integer
	private $pais;			// Pa�s de resid�ncia @var string

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
	 * @param void		$value	Valor atribu�do
	 */
	function __set($attr, $value) {
		$this->$attr = $value;
	}
	
	/**
	 * Define todas as informa��es do Endere�o (exceto id, munic�pio, uf, estado e pais)
	 * 
	 * @param string 	$logradouro		Logradouro de resid�ncia
	 * @param integer	$numero			N�mero da resid�ncia
	 * @param string	$complemento	Complemento (ap. bloco unidade)
	 * @param string	$bairro			Bairro de resid�ncia
	 * @param string	$cep			C�digo postal
	 * @param integer	$municipio_id	Identificador do munic�pio e estado
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
	 * Cadastra o endere�o no banco de dados
	 *
	 * @return integer	0 se cadastrado com sucesso, > 0 caso contr�rio
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
		// Verifica��o
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: N�o foi poss�vel cadastrar este endere�o.");
			$db_err ++;	// erro
		} else {
			$this->id = $db->insert_id; // Se deu certo j� podemos recuperar seu ID.
			//se deu certo j� podemos recuperar os dados do cadastro.
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
			$log->lwrite("Novo endere�o cadastrado ID: ".$this->id);
		}
		$stmt->free_result();
		return $db_err;
	}
	
	/**
	 * Altera os dados do endere�o no banco de dados.
	 * 
	 * @return integer	0 se alterado com sucesso, > 0 caso contr�rio
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
	
		// Verifica��o
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: N�o foi poss�vel alterar os dados do endere�o.");
			$db_err ++;	// erro
		} else {
			$log->lwrite("Altera��es no endere�o cadastrado ID: ".$this->id);
		}
		$stmt->free_result();
		return $db_err;
	}
	
	/**
	 * Remove o cadastro deste endere�o no bd de acordo com sua ID.
	 * 
	 * @return integer	0 se removido com sucesso, > 0 caso contr�rio
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
				$log->lwrite("ERRO: N�o foi poss�vel remover este endere�o.");
				$db_err++;
			} else {
				$log->lwrite("Removido endere�o ID: ".$this->id);
			}
		} else {
			$log->lwrite("ERRO: Identificador de endere�o inexistente.");
			$db_err++;
		}
		return $db_err;
	}
	
	/**
	 * Recupera todos os dados do endere�o do bd.
	 * 
	 * @return integer	0 se encontrado, > 0 caso contr�rio
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
			//cadastro n�o encontrado
			$log->lwrite("ERRO: ID inv�lido, endere�o n�o encontrado. ID: ".$id);
			$db_err++;
		}
		return $db_err;
	}
}

/**
 * Classe para entidade Acesso
 * 
 * Esta classe modela todas fun��es necess�rias para manipula��o
 * de acesso de usu�rios ao sistema.
 *
**/
class Access {
   
	private $id;			// Identificador �nico do acesso @var integer
	private $usuario;		// Login do usuario no sistema @var string
	private $senha;			// Senha n�o criptografada (usada apenas no ato do cadastro) @var string
	private $nivel;			// N�vel de acesso do usuario no sistema @var integer
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
	 * @param void		$value	Valor atribu�do
	 */
	function __set($attr, $value) {
		if(strcmp($attr,"nivel") == 0) {
			if($this->nivel != 1) {		// administrador n�o perde acesso de admin jamais *hotfix*
				$this->$attr = $value;
			}
		} else {
			$this->$attr = $value;
		}
	}
	
	/**
	 * Define todas as informa��es do Acesso (exceto id)
	 * 
	 * @param string 	$usuario 		Login do usu�rio
	 * @param string	$senha			Senha n�o criptografada
	 * @param integer	$nivel			N�vel de acesso para esta conta
	 */
	function __construct($usuario, $senha, $nivel) {
		$this->usuario = $usuario;
		$this->senha = $senha;
		$this->nivel = $nivel;
	}
	
	/**
	 * Cadastra o acesso no banco de dados
	 *
	 * @return integer	0 se cadastrado com sucesso, > 0 caso contr�rio
	 */
	function SignThisAccess() {
		global $db;
		global $log;
		$db_err = 0;
				
		$sql = "SELECT  acesso.* FROM acesso WHERE acesso.usuario = '%s'";
		$result = query($sql,$this->usuario);
		if($result->num_rows) {
			//Cadastro j� existe, nome duplicado
			$log->lwrite("ERRO: Nome de usu�rio j� existe. Usu�rio: ".$this->usuario);
			$db_err++;
			$result->free();	
		} else {
			$stmt = $db->prepare("INSERT INTO acesso
										(usuario,
										senha,
										nivel)
							VALUES (?,?,?)");
			// Obtemos a hash para inser��o no banco
			$this->hash = Bcrypt::hash($this->senha);
			$stmt->bind_param('ssi',
							$this->usuario,
							$this->hash,
							$this->nivel);
			// Verifica��o
			if($stmt->execute() === false) {
				$log->lwrite("ERRO: N�o foi poss�vel cadastrar este acesso.");
				$db_err ++;	// erro
			} else {
				$this->id = $db->insert_id; // Se deu certo j� podemos recuperar seu ID.
				$log->lwrite("Novo acesso cadastrado ID: ".$this->id);
			}
			$stmt->free_result();
		}
		return $db_err;
	}
	
	/**
	 * Altera os dados do acesso no banco de dados.
	 * 
	 * @return integer	0 se alterado com sucesso, > 0 caso contr�rio
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
			// Obtemos a hash para inser��o no banco
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
			// Obtemos a hash para inser��o no banco
			$this->hash = Bcrypt::hash($this->senha);
			$stmt->bind_param('ssii',
							$this->usuario,
							$this->hash,
							$this->nivel,
							$this->id);
		}	
		// Verifica��o
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: N�o foi poss�vel alterar os dados do acesso. ID: ".$this->id);
			$db_err ++;	// erro
		} else {
			$log->lwrite("Altera��o no acesso ID: ".$this->id);
		}
		$stmt->free_result();
		return $db_err;
	}
	
	/**
	 * Remove o cadastro deste acesso no bd de acordo com sua ID.
	 * 
	 * @return integer	0 se removido com sucesso, > 0 caso contr�rio
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
				$log->lwrite("ERRO: N�o foi poss�vel remover este acesso.");
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
	 * @return integer	0 se encontrado, > 0 caso contr�rio
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
			//cadastro n�o encontrado
			$log->lwrite("ERRO: ID inv�lido, acesso n�o encontrado. ID: ".$id);
			$db_err++;
		}
		return $db_err;
	}
}

/**
 * Classe para entidade Dispositivos
 * 
 * Esta classe modela todas fun��es necess�rias para manipula��o
 * de dispositivos de usu�rios no sistema.
 *
**/
class Device {
  
	private $id;					// Identificador �nico do dispositivo @var integer
	private $usuarios_id;			// Chave estrageira para o usu�rio @var integer
	private $tipos_dispositivo_id;	// Chave estrageira para o tipo de dispositivo @var integer
	private $endereco_mac;			// Endere�o MAC do dispositivo (XX:XX:XX:XX:XX:XX) @var string
	private $patrimonio;			// Se for um dispositivo patrimoniado @var string
	private $hostname;				// Nome da maquina @var string
	private $ip;					// IP da m�quina (XXX.XXX.XXX.XXX) @var string
	private $localizacao;			// A localiza��o do dispositivo @var string
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
	 * @param void		$value	Valor atribu�do
	 */
	function __set($attr, $value) {
		$this->$attr = $value;
	}
	
	/**
	 * Define todas as informa��es do Contato (exceto id)
	 * 
	 * @param integer 	$usuarios_id 			ID do usu�rio
	 * @param integer	$tipos_dispositivo_id	ID do tipo de dispositivo
	 * @param string	$endereco_mac			N�mero ou email
	 * @param string	$patrimonio				Patrimonio
	 * @param string	$hostname				Hostname
	 * @param string	$ip						IP
	 * @param string	$localizacao			Localiza��o do dispositivo
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
	 * @return integer	0 se cadastrado com sucesso, > 0 caso contr�rio
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
		// Verifica��o
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: N�o foi poss�vel cadastrar este dispositivo.");
			$db_err ++;	// erro
		} else {
			$this->id = $db->insert_id; // Se deu certo j� podemos recuperar seu ID.
			// Vamos tamb�m recuperar seu tipo.
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
	 * @return integer	0 se alterado com sucesso, > 0 caso contr�rio
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
	
		// Verifica��o
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: N�o foi poss�vel alterar os dados do dispositivo.");
			$db_err ++;	// erro
		} else {
			$log->lwrite("Altera��o no dispositivo ID: ".$this->id);
		}
		$stmt->free_result();
		return $db_err;
	}
	
	/**
	 * Remove o cadastro deste dispositivo no bd de acordo com sua ID.
	 * 
	 * @return integer	0 se removido com sucesso, > 0 caso contr�rio
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
				$log->lwrite("ERRO: N�o foi poss�vel remover este dispositivo. ID: ".$this->id);
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
	 * @return integer	0 se encontrado, > 0 caso contr�rio
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
			//cadastro n�o encontrado
			$log->lwrite("ERRO: ID inv�lido, contato n�o encontrado. ID: ".$id);
			$db_err++;
		}
		return $db_err;
	}
  
}

/**
 * Classe para entidade Contatos
 * 
 * Esta classe modela todas fun��es necess�rias para manipula��o
 * de contatos de usu�rios no sistema.
 *
**/
class Contact {
  
	private $id;					// Identificador �nico do contato @var integer
	private $usuarios_id;			// Chave estrangeira para o usu�rio @var integer
	private $tipos_contato_id;		// Chave estrangeira para o tipo de contato @var integer
	private $contato;				// O contato, n�meros ou emails @var string
	private $tipo_contato;			// O tipo de contato (n�mero, email, ramal ...) @var string
	
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
	 * @param void		$value	Valor atribu�do
	 */
	function __set($attr, $value) {
		$this->$attr = $value;
	}
	
	/**
	 * Define todas as informa��es do Contato (exceto id)
	 * 
	 * @param integer 	$usuarios_id 		ID do usu�rio
	 * @param integer	$tipos_contato_id	ID do tipo de contato
	 * @param string	$contato			N�mero ou email
	 */
	function __construct($usuarios_id, $tipos_contato_id, $contato) {
		$this->usuarios_id = $usuarios_id;
		$this->tipos_contato_id = $tipos_contato_id;
		$this->contato = $contato;
	}
	
	/**
	 * Cadastra o contato no banco de dados
	 *
	 * @return integer	0 se cadastrado com sucesso, > 0 caso contr�rio
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
		// Verifica��o
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: N�o foi poss�vel cadastrar este contato.");
			$db_err ++;	// erro
		} else {
			$this->id = $db->insert_id; // Se deu certo j� podemos recuperar o ID dela.
			// Vamos tamb�m recuperar seu tipo.
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
	 * @return integer	0 se alterado com sucesso, > 0 caso contr�rio
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
	
		// Verifica��o
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: N�o foi poss�vel alterar os dados do contato.");
			$db_err ++;	// erro
		} else {
			$log->lwrite("Altera��o no contato. ID: ".$this->id);
		}
		$stmt->free_result();
		return $db_err;
	}
	
	/**
	 * Remove o cadastro deste contato no bd de acordo com sua ID.
	 * 
	 * @return integer	0 se removido com sucesso, > 0 caso contr�rio
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
				$log->lwrite("ERRO: N�o foi poss�vel remover este contato. ID: ".$this->id);
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
	 * @return integer	0 se encontrado, > 0 caso contr�rio
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
			//cadastro n�o encontrado
			$log->lwrite("ERRO: ID inv�lido, contato n�o encontrado. ID: ".$id);
			$db_err++;
		}
		return $db_err;
	}
}

/**
 * Classe para entidade Grupos	
 * 
 * Esta classe modela todas fun��es necess�rias para manipula��o
 * de grupos de usu�rios no sistema.
 *
**/
class Group {
  
	private $id;					// Identificador �nico do grupo @var integer
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
	 * @param void		$value	Valor atribu�do
	 */
	function __set($attr, $value) {
		$this->$attr = $value;
	}
	
	/**
	 * Define todas as informa��es do Grupo (exceto id)
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
	 * @return integer	0 se cadastrado com sucesso, > 0 caso contr�rio
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
		// Verifica��o
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: N�o foi poss�vel cadastrar este grupo. ID: ".$this->id);
			$db_err ++;	// erro
		} else {
			$this->id = $db->insert_id; // Se deu certo j� podemos recuperar o ID dela.
			$log->lwrite("Novo grupo cadastrado. ID: ".$this->id);
		}
		$stmt->free_result();
		return $db_err;
	}
	
	/**
	 * Altera os dados do grupo no banco de dados.
	 * 
	 * @return integer	0 se alterado com sucesso, > 0 caso contr�rio
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
	
		// Verifica��o
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: N�o foi poss�vel alterar os dados do grupo. ID: ".$this->id);
			$db_err ++;	// erro
		} else {
			$log->lwrite("Altera��o grupo. ID: ".$this->id);
		}
		$stmt->free_result();
		return $db_err;
	}
	
	/**
	 * Remove o cadastro deste grupo no bd de acordo com sua ID.
	 * 
	 * @return integer	0 se removido com sucesso, > 0 caso contr�rio
	 */
	function RemoveThisGroup() {
		global $log;
		$db_err = 0;
		// Primeiro checamos se o cadastro existe
		$sql = "SELECT * FROM grupo WHERE id = %d";
		$result = query($sql,$this->id);
		// Se existe o cadastro
		if($result->num_rows) {
			// Necess�rio checar se existem usuarios neste grupo
			$sql = "SELECT * FROM grupos_usuario WHERE grupo_id = %d";
			$result = query($sql,$this->id);
			// Se existirem usu�rios neste grupo n�o sera poss�vel excluir
			if($result->num_rows) {
				$log->lwrite("ERRO: Existem usuarios cadastrados neste grupo, n�o ser� poss�vel remover o grupo. ID: ".$this->id);
				$db_err++;
			} else {
				// Sen�o o grupo pode ser exclu�do
				$sql = "DELETE FROM grupo WHERE id = %d";
				$result = query($sql,$this->id);
				if($result === false) {
					$log->lwrite("ERRO: N�o foi poss�vel remover este grupo. ID: ".$this->id);
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
	 * Se existirem usu�rios vinculados, ser�o desvinculados automaticamente.
	 * 
	 * @return integer	0 se removido com sucesso, > 0 caso contr�rio
	 */
	function ForceRemoveThisGroup() {
		global $db;
		global $log;
		$db_err = 0;
		
		// Desabilitamos o autocommit para garantir todas as remo��es
		$db->autocommit(FALSE);
		
		// Primeiro checamos se o cadastro existe
		$sql = "SELECT * FROM grupo WHERE id = %d";
		$result = query($sql,$this->id);
		// Se existe o cadastro
		if($result->num_rows) {
			
			// Necess�rio remover todos os usu�rios vinculados
			$sql = "DELETE FROM grupos_usuario WHERE grupo_id = %d";
			$result = query($sql,$this->id);
			
			// Agora o grupo pode ser exclu�do
			$sql = "DELETE FROM grupo WHERE id = %d";
			$result = query($sql,$this->id);
			if($result === false) {
				$log->lwrite("ERRO: N�o foi poss�vel remover este grupo. ID: ".$this->id);
				$db_err++;		//erro
			}
			
			// Verifica��o final
			if($db_err > 0) {
				// Se houver qualquer erro, n�o salvar nada no bd
				$db->rollback();
			} else {
				// Se n�o houver erro, salva tudo no banco de dados
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
	 * @return integer	0 se encontrado, > 0 caso contr�rio
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
			//cadastro n�o encontrado
			$log->lwrite("ERRO: ID inv�lido, grupo n�o encontrado. ID: ".$id);
			$db_err++;
		}
		return $db_err;
	}
  
}

/**
 * Classe para entidade Categoria
 * 
 * Esta classe modela todas fun��es necess�rias para manipula��o
 * de categorias de usu�rios no sistema.
 *
**/
class Category {
  
	private $id;					// Identificador �nico da categoria @var integer
	private $categoria;				// Categoria definida @var string
	private $periodo_expiracao;		// Periodo de expira��o para usu�rios cadastrados @var integer
	private $descricao;				// Descri��o da categoria @var string

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
	 * @param void		$value	Valor atribu�do
	 */
	function __set($attr, $value) {
		$this->$attr = $value;
	}
	
	 /**
	 * Define todas as informa��es da Categoria (exceto id)
	 * 
	 * @param string 	$categoria 	A Categoria
	 * @param integer	$periodo_expiracao	Tempo em que um cadastro de usu�rio vai expirar
	 * @param string	$descricao	Descri��o e informa��es sobre a categoria
	 */
	function __construct($categoria, $periodo_expiracao, $descricao) {
		$this->categoria = $categoria;
		$this->periodo_expiracao = $periodo_expiracao;
		$this->descricao = $descricao;
	}
	
	 /**
	 * Cadastra a categoria no banco de dados
	 *
	 * @return integer	0 se cadastrado com sucesso, > 0 caso contr�rio
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
		
		// Verifica��o
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: N�o foi poss�vel cadastrar esta categoria.");
			$db_err ++;	// erro
		} else {
			$this->id = $db->insert_id; // Se deu certo j� podemos recuperar o ID dela.
			$log->lwrite("Nova categoria cadastrada. ID: ".$this->id);
		}
		$stmt->free_result();
		return $db_err;
	}
	
	/**
	 * Altera os dados da categoria no banco de dados.
	 * 
	 * @return integer	0 se alterado com sucesso, > 0 caso contr�rio
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
	
		// Verifica��o
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: N�o foi poss�vel alterar os dados da categoria. ID: ".$this->id);
			$db_err ++;	// erro
		} else {
			$log->lwrite("Altera��o na categoria. ID: ".$this->id);
		}
		$stmt->free_result();
		return $db_err;
	}
	
	/**
	 * Remove o cadastro desta categoria no bd de acordo com sua ID.
	 * 
	 * @return integer	0 se removido com sucesso, > 0 caso contr�rio
	 */
	function RemoveThisCategory() {
		global $log;
		$db_err = 0;
		// Primeiro checamos se o cadastro existe
		$sql = "SELECT * FROM categoria WHERE id = %d";
		$result = query($sql,$this->id);
		// Se existe o cadastro
		if($result->num_rows) {
			// Necess�rio checar se existem usu�rios nesta categoria
			$sql = "SELECT * FROM usuarios WHERE categoria_id = %d";
			$result = query($sql,$this->id);
			// Se existirem usu�rios com esta categoria n�o sera poss�vel excluir
			if($result->num_rows) {
				$log->lwrite("ERRO: Existem usuarios cadastrados nesta categoria, n�o ser� poss�vel remover esta categoria. ID: ".$this->id);
				$db_err++;
			} else {
				// Sen�o a categoria pode ser exclu�da
				$sql = "DELETE FROM categoria WHERE id = %d";
				$result = query($sql,$this->id);
				if($result === false) {
					$log->lwrite("ERRO: N�o foi poss�vel remover esta categoria. ID: ".$this->id);
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
	 * @return integer	0 se encontrado, > 0 caso contr�rio
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
			//cadastro n�o encontrado
			$log->lwrite("ERRO: ID inv�lido, categoria n�o encontrada. ID: ".$id);
			$db_err++;
		}
		return $db_err;
	}
}

/**
 * Classe para entidade Pendencias
 * 
 * Esta classe modela todas fun��es necess�rias para manipula��o
 * de pendencias do sistema. Os valores padr�es s�o definidos no in�cio
 * desde arquivo.
 *
**/
class Pending {
  
	private $id;					// Identificador �nico da pendencia @var integer
	private $tabela;				// A tabela onde a modifica��o foi feita @var integer [valores definidos no inicio do arquivo]
	private $tabela_id;				// O identificador do registro alterado @var integer
	private $motivo;				// O motivo da notifica��o @var integer [valores definidos no inicio do arquivo]
	private $informacao;			// Descri��o da informa��o ou dados do registro excluido @var string
	private $resolvida;				// Booleano se diz se foi resolvido ou n�o @var boolean
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
	 * @param void		$value	Valor atribu�do
	 */
	function __set($attr, $value) {
		$this->$attr = $value;
	}
	
	 /**
	 * Define todas as informa��es da Categoria (exceto id)
	 * 
	 * @param integer 	$tabela 	Tabela de onde veio a notifica��o
	 * @param integer	$tabela_id	ID onde ocorreu a altera��o
	 * @param integer	$motivo		Motivo da altera��o
	 * @param string	$informacao	Informa��o adicional ou dados do registro excluido
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
	 * @return integer	0 se cadastrado com sucesso, > 0 caso contr�rio
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
		
		// Verifica��o
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: N�o foi poss�vel cadastrar esta pendencia. ID: ".$this->id);
			$db_err ++;	// erro
		} else {
			$this->id = $db->insert_id; // Se deu certo j� podemos recuperar o ID dela.
			$log->lwrite("Nova pendencia cadastrada. ID: ".$this->id);
		}
		$stmt->free_result();
		return $db_err;
	}
	
	/**
	 * Altera os dados da pendencia no banco de dados.
	 * 
	 * @return integer	0 se alterado com sucesso, > 0 caso contr�rio
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
	
		// Verifica��o
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: N�o foi poss�vel alterar os dados da pendencia. ID: ".$this->id);
			$db_err ++;	// erro
		} else {
			$log->lwrite("Altera��o na pend�ncia. ID: ".$this->id);
		}
		$stmt->free_result();
		return $db_err;
	}
	
	/**
	 * Remove o cadastro desta pendencia no bd de acordo com sua ID.
	 * 
	 * @return integer	0 se removido com sucesso, > 0 caso contr�rio
	 */
	function RemoveThisPending() {
		global $log;
		$db_err = 0;
		// Primeiro checamos se o cadastro existe
		$sql = "SELECT * FROM pendencias WHERE id = %d";
		$result = query($sql,$this->id);
		// Se existe o cadastro
		if($result->num_rows) {			
			// Sen�o a pendencia pode ser exclu�da
			$sql = "DELETE FROM pendencias WHERE id = %d";
			$result = query($sql,$this->id);
			if($result === false) {
				$log->lwrite("ERRO: N�o foi poss�vel remover esta pendencia. ID: ".$this->id);
				$db_err++;		//erro
			} else {
				$log->lwrite("Removida pend�ncia: ID: ".$this->id);
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
	 * @return integer	0 se encontrado, > 0 caso contr�rio
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
			//cadastro n�o encontrado
			$log->lwrite("ERRO: ID inv�lido, pendencias n�o encontrada. ID: ".$id);
			$db_err++;
		}
		return $db_err;
	}
	
	/**
	 * Marca a pendencia como resolvida
	 * 
	 * @return integer	0 se sucesso, > 0 caso contr�rio
	 */
	public function MarkAsResolved() {
		global $db;
		global $log;
		$db_err = 0;
		$stmt = $db->prepare("UPDATE pendencias 
							SET	resolvida = 1
							WHERE id = ?");
		$stmt->bind_param('i', $this->id);
	
		// Verifica��o
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: N�o foi poss�vel marcar esta pendencia como resolvida. ID: ".$this->id);
			$db_err ++;	// erro
		} else {
			$log->lwrite("Resolvida pend�ncia ID: ".$this->id);
		}
		$stmt->free_result();
		return $db_err;
	}
	
	/**
	 * Marca a pendencia como n�o resolvida
	 * 
	 * @return integer	0 se sucesso, > 0 caso contr�rio
	 */
	public function MarkAsUnresolved() {
		global $db;
		global $log;
		$db_err = 0;
		$stmt = $db->prepare("UPDATE pendencias 
							SET	resolvida = 0
							WHERE id = ?");
		$stmt->bind_param('i', $this->id);
	
		// Verifica��o
		if($stmt->execute() === false) {
			$log->lwrite("ERRO: N�o foi poss�vel marcar esta pendencia como n�o resolvida. ID: ".$this->id);
			$db_err ++;	// erro
		} else {
			$log->lwrite("Unresolvida pend�ncia ID: ".$this->id);
		}
		$stmt->free_result();
		return $db_err;
	}
	
}
?>