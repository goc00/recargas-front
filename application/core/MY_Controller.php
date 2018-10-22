<?php
class MY_Controller extends CI_Controller {

	protected $data;

	const SEP 		= "@@REC@@";
	const MAX_LENGTH_ANI		= 8; // para formato prefijo-7 (ej. 62376897)

	function __construct() {
		parent::__construct();
		//date_default_timezone_set("America/Santiago");
		
		//$this->load->model('services');
		//$this->load->helper('string');
		
		$this->data = array();
	}
	
	
	// Peticiones POST
	protected function doPost($host, $method, $params) {
		
		$curl = curl_init($host.$method);
		
		//$data_string = (array)$params;
	
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		
		$exec = curl_exec($curl);
		curl_close($curl);

		return $exec; 	
	}
	
	// Petición GET
	protected function doGet($url) {
		
		$curl = curl_init();
		
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $url
		));
		
		$exec = curl_exec($curl);
		curl_close($curl);

		return $exec; 	
	}
	
	
	protected function post() {
		return file_get_contents("php://input");
	}
	
	protected function outputJson($data) {
		$this->output
				->set_content_type('application/json')
				->set_output(json_encode($data));
	}
	
	
	/**
	 * Verifica si hay session activa
	 *
	 * @return boolean
	 */
	protected function _isLogged() {
		
		$token = $this->session->userdata("ua"); // User Ani
		//print_r("hola". $token); exit;
		if(!empty($token))
			// Si está, valida que sea un token válido
			if($this->_isDataValid($token)) return TRUE;
		
		return FALSE;
	}
	
	/**
	 * Verifica si hay campaña activa
	 *
	 * @return boolean
	 */
	protected function _isActiveCampaign() {
		
		$ac = $this->session->userdata("ac"); // Active Campaign
		if(!empty($ac))
			// Si está, valida que sea un token válido
			if((int)$ac == 1) return TRUE;
		
		return FALSE;
	}
	
	
	/**
	 * Crea session en función del id del usuario (idUser)
	 *
	 * @return void
	 */
	protected function _createSession($idUser) {
		
		$pos = rand(0,1); // obtiene 2 posiciones, pre(0) o post(1)
		$encryp = ($pos == 0) ? self::SEP.$idUser : $idUser.self::SEP;
		$encryp = $encryp.$pos;
		// Encoded data
		$encoded = $this->_encodeData($encryp);
		
		// Revisa si al momento de autenticar, existe campaña activa
		$activeCampaign = 0;
		$res = json_decode($this->services->getActiveCampaign());
		if($res->code == 0) { $activeCampaign = 1; }

		// Pasa variables a sesión
		$session = array(
			"ua" => $encoded->result,
			"ac" => $activeCampaign
		);
		
		// Registra acceso
		$this->services->createUserAccess($idUser);
		
		$this->session->set_userdata($session);

	}
	
	/**
	 * Es data válida, tomando separador y posición
	 *
	 * @return boolean
	 */
	protected function _isDataValid($data) {
	
		// Desencripta
		$decoded = $this->_decodeData($data);
		if(empty($decoded)) return FALSE;
		
		$valor = $decoded->result;

		// Toma la posición del separador
		$pos = substr($valor, strlen($valor)-1, 1);
		$sepL = strlen(self::SEP);

		if((int)$pos == 1) {
			$sep = substr($valor, strlen($valor)-(1+$sepL), $sepL);
		} else {
			$sep = substr($valor, 0, $sepL);
		}

		return $sep == self::SEP;	
	}
	
	/**
	 * Es data válida, tomando separador y posición
	 */
	protected function _extractIdUser($valor) {

		$noSep = str_replace(self::SEP, "", $valor);
		$noPos = substr($noSep, 0, strlen($noSep)-1);
		
		return $noPos;
		
	}
	
	
	/**
	 * Crea session del usuario, con el ANI pero encriptado en MW por seguridad
	 */
	protected function _encodeData($data) {
		return json_decode($this->services->encodeData($data));
	}
	
	protected function _decodeData($data) {
		return json_decode($this->services->decodeData($data));
	}
	
	
	/**
	 * Lanza exception
	 */
	protected function exception($mensaje, $num) {
		throw new Exception($mensaje, $num);
	}
	
}

