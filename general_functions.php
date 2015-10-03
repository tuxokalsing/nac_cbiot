<?php
/**
 *	Universidade Federal do Rio Grande do Sul - Centro de Biotecnologia
 *
 *	Desenvolvedor(es): Arthur Kalsing - arthurkalsing@gmail.com
 *
 *	Descrição: Este arquivo agrupa outras funções úteis do sistema.
 *
 *	Observação: 
 *
**/


/**
 * Meu var dump pre formatado
 * 
 * @param 	string $var 	A variavel a ser debugada
 */
function my_var_dump($var) {
        echo '<pre>';
        print_r($var);
        echo  '</pre>';
}

/**
 * Função para mandar emails
 * 
 * @param 	string $destino 	Email do destinatario
 * @param 	string $assunto 	Assunto da mensagem
 * @param 	string $mensagem 	Corpo do email
 */
function SendMail($destino, $assunto, $mensagem) {		
	try {
		if(!mail($destino, $assunto, $mensagem, "")){
			throw new Exception('mail failed');
			}else{
			// echo 'mail sent'; (debug purpouses)
		}
	} catch(Exception $e) {
		echo $e->getMessage() ."\n";
	}
}

/**
 * Gera senha randomica
 * 
 * @return 	string $pass	Senha de 8 caracteres
 */
function RandomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); 						//remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; 	//put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}


/**
 * Test input usado em formularios para evitar injection
 * 
 * @param 	string $data 	Variavel a ser verificada
 * @return 	string $data	Valor final verificado
 */
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  //$data = htmlspecialchars($data);
  return $data;
}

/**
 * Exibicao de erros com o layout do sistema
 * 
 * @param 	string $msg 	Mensagem a ser exibida
 * @param 	string $level 	1 - erro : 2 - warning
 */
function display_error($msg,$level) {
	switch($level) {
		case 1:
			echo "<div class=\"alert alert-danger\" align=\"center\">
							<strong> $msg </strong>
						  </div>";
			break;
		case 2:
			echo "<div class=\"alert alert-warning\" align=\"center\">
							<strong> $msg </strong>
						  </div>";
			break;
		default:
			echo "<div class=\"alert alert-danger\" align=\"center\">
							<strong> $msg </strong>
						  </div>";
	}
}

/**
 * Exibicao de informacoes e sucesso com o layout do sistema
 * 
 * @param 	string $msg 	Mensagem a ser exibida
 * @param 	string $level 	1 - sucesso : 2 - informativa
 */
function display_message($msg,$level) {
	switch($level) {
		case 1:
			echo "<div class=\"alert alert-success\" align=\"center\">
							<strong> $msg </strong>
						  </div>";
			break;
		case 2:
			echo "<div class=\"alert alert-info\" align=\"center\">
							<strong> $msg </strong>
						  </div>";
			break;
		default:
			echo "<div class=\"alert alert-success\" align=\"center\">
							<strong> $msg </strong>
						  </div>";
	}
}

/**
 * Echo personalizado para problemas com caracteres especiais.
 * 
 * @param 	string $txt 	Texto a ser convertido
 */
function echo8($txt) {
	echo $txt;
	//echo utf8_decode($txt);
}

/**
 * Converte data entre os formatos do banco e normal
 * 
 * @param 	string $dateString 		Texto a ser convertido (dd/mm/YYYY) ou (YYYY-mm-dd)
 * @return 	string $newDateString 	Texto a ser convertido
 */
function date_conv($dateString) {
	$newDateString = "00/00/0000";
	
	// remover aspas simples e duplas
	$dateString = str_replace("'","",$dateString);
	$dateString = str_replace("\"","",$dateString);
	//se estiver no formato normal 	dd/mm/YYYY
	if( strlen($dateString) == 10 &&
		substr($dateString,2,1)=='/' &&
		substr($dateString,5,1)=='/') {
		$date = explode("/",$dateString);
		$newDateString = $date[2]."-".$date[1]."-".$date[0];
	}
	//se estiver no formato do banco YYYY-mm-dd
	elseif( strlen($dateString) == 10 &&
			substr($dateString,4,1)=='-' &&
			substr($dateString,7,1)=='-') {
		$date = explode("-",$dateString);
		$newDateString = $date[2]."/".$date[1]."/".$date[0];
	}
	return $newDateString;
}

/**
 * Adiciona parametros ao link informado
 * 
 * @param 	string $link 		Link da pagina
 * @param 	string $param_name 	Nome do parametro a ser adicionado
 * @param 	string $value 		Valor do parametro
 * @return 	string $newlink 	Novo link com parametro adicionado
 */
function add_param($link, $param_name, $value) {
	$newlink = "";
	//verifica se ja existe ?
	if(stripos($link,"?")) {
		$newlink = $link."&".$param_name."=".$value;
	} else {
		$newlink = $link."?".$param_name."=".$value;
	}
	return $newlink;
}

/**
 * Logging class:
 * - contains lfile, lwrite and lclose public methods
 * - lfile sets path and name of log file
 * - lwrite writes message to the log file (and implicitly opens log file)
 * - lclose closes log file
 * - first call of lwrite method will open log file implicitly
 * - message is written with the following format: [d/M/Y:H:i:s] (script name) message
 *
 * @author redips
 * @link   http://www.redips.net/license/
 */
class Logging {
	
	private $log_file;			// log file			@var string
	private $fp;				// file pointer		@var file pointer
	
	/**
	 * Set log file (path and name)
	 * 
	 * @param 	string $path 	the file name (path and name)
	 */
	public function lfile($path) {
		$this->log_file = $path;
	}
	
	/**
	 * Write message to the log file
	 * 
	 * @param 	string $message 	the message to be logged
	 */
	public function lwrite($message) {
		// if file pointer doesn't exist, then open log file
		if (!is_resource($this->fp)) {
			$this->lopen();
		}
		// define script name
		$script_name = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
		// define current time and suppress E_WARNING if using the system TZ settings
		// (don't forget to set the INI setting date.timezone)
		$time = @date('[d/M/Y:H:i:s]');
		// write current time, script name and message to the log file
		fwrite($this->fp, "$time ($script_name) $message" . PHP_EOL);
	}
	
	/**
	 * Close log file
	 * 
	 * OBS: Close log file to avoid corruption and overhead
	 *
	 */
	public function lclose() {
		fclose($this->fp);
	}
	 
	/**
	 * Open log file (private method)
	 *
	 * Uses default if the log is not set
	 */
	private function lopen() {
		// in case of Windows set default log file
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$log_file_default = dirname(__FILE__).'/log/default_logfile.txt';
		}
		// set default log file for Linux and other systems
		else {
			$log_file_default = dirname(__FILE__).'/log/default_logfile.txt';
		}
		// define log file from lfile method or use previously set default
		$lfile = $this->log_file ? $this->log_file : $log_file_default;
		// open log file for writing only and place file pointer at the end of the file
		// (if the file does not exist, try to create it)
		$this->fp = fopen($lfile, 'a') or exit("Can't open $lfile!");
	}
}

// Arquivo de log aberto automaticamente
// $log->lclose() é chamado no footer.php
// Logging class initialization
$log = new Logging();
// set path and name of log file (montly)
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	$dirname = dirname(__FILE__).'\\log\\';
	$filename = "logfile_".date('m-y').".txt";
}
// set default log file for Linux and other systems
else {
	$dirname = dirname(__FILE__).'/log/';
	$filename = "logfile_".date('m-y').".txt";
}
$log->lfile($dirname.$filename);


/**
 * Hashing class utilizada na verificação das senhas
 * Bcrypt hashing class
 * 
 * @author Thiago Belem <contato@thiagobelem.net>
 * @link   https://gist.github.com/3438461
 */
class Bcrypt {
 
/**
 * Default salt prefix
 * 
 * @see http://www.php.net/security/crypt_blowfish.php
 * 
 * @var string
 */
	protected static $_saltPrefix = '2a';
 
/**
 * Default hashing cost (4-31)
 * 
 * @var integer
 */
	protected static $_defaultCost = 8;
 
/**
 * Salt limit length
 * 
 * @var integer
 */
	protected static $_saltLength = 22;
 
/**
 * Hash a string
 * 
 * @param  string  $string The string
 * @param  integer $cost   The hashing cost
 * 
 * @see    http://www.php.net/manual/en/function.crypt.php
 * 
 * @return string
 */
	public static function hash($string, $cost = null) {
		if (empty($cost)) {
			$cost = self::$_defaultCost;
		}
 
		// Salt
		$salt = self::generateRandomSalt();
 
		// Hash string
		$hashString = self::__generateHashString((int)$cost, $salt);
 
		return crypt($string, $hashString);
	}
 
/**
 * Check a hashed string
 * 
 * @param  string $string The string
 * @param  string $hash   The hash
 * 
 * @return boolean
 */
	public static function check($string, $hash) {
		return (crypt($string, $hash) === $hash);
	}
 
/**
 * Generate a random base64 encoded salt
 * 
 * @return string
 */
	public static function generateRandomSalt() {
		// Salt seed
		$seed = uniqid(mt_rand(), true);
 
		// Generate salt
		$salt = base64_encode($seed);
		$salt = str_replace('+', '.', $salt);
 
		return substr($salt, 0, self::$_saltLength);
	}
 
/**
 * Build a hash string for crypt()
 * 
 * @param  integer $cost The hashing cost
 * @param  string $salt  The salt
 * 
 * @return string
 */
	private static function __generateHashString($cost, $salt) {
		return sprintf('$%s$%02d$%s$', self::$_saltPrefix, $cost, $salt);
	}
 
}

/**
 * Build up a table with a mysql result
 * 
 * @param  sqlresult 	$sqlresult 	The result of the sql cost
 * @param  string 		$delim  	The delimitator used to separate lines
 * 
 * @return string 		$htmltable	String with all the results in a table
 */
function sql_to_html_table($sqlresult, $delim="\n") {
  // starting table
  $htmltable =  "<table border='1' width='100%'>" . $delim ;   
  $counter   = 0 ;
  // putting in lines
  while( $row = $sqlresult->fetch_assoc()  ){
    if ( $counter===0 ) {
      // table header
      $htmltable .=   "<tr>"  . $delim;
      foreach ($row as $key => $value ) {
          $htmltable .=   "<th>" . ucwords($key) . "</th>"  . $delim ;
      }
      $htmltable .=   "</tr>"  . $delim ; 
      $counter = 22;
    } 
      // table body
      $htmltable .=   "<tr>"  . $delim ;
      foreach ($row as $key => $value ) {
          $htmltable .=   "<td>" . $value . "</td>"  . $delim ;
      }
      $htmltable .=   "</tr>"   . $delim ;
  }
  // closing table
  $htmltable .=   "</table>"   . $delim ; 
  // return
  return( $htmltable ) ; 
}

?>