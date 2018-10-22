<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Core extends MY_Controller {

	const pivot = "@REC@";

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Pantalla de inicio, utiliza parámetro de instalación
	 */
	public function index() {
		
		// Cuando llega por móvil, recibe el vendorId (identificador de instalación), para confirmar
		// y/o autentificar al usuario que ya existe.
		$vendorId = $this->input->get("vendorId");
		
		/*$this->load->library("geoiploc");
		$ip = "201.236.135.242";
		$code = $this->geoiploc->getCountryFromIP($ip);
		echo $code;*/

		try {
			
			if(!empty($vendorId)) {
			
				/*  LLEGA POR MÓVIL  */
				
				// Verifica que exista el vendorId y que el eventual usuario relacionado a este,
				// se encuentre activo y vigente.
				$oUsuario = $this->services->getUserByVendorId($vendorId);
				if(empty($oUsuario)) throw new Exception("Sucedió un error intentando identificar la sesión", 1000);
				
				$oUsuario = json_decode($oUsuario);
				
				if($oUsuario->code == 0) {
					
					// El usuario existe, así que lo autentifica automáticamente
					$this->_createSession($oUsuario->result->idUser);
					
					// Carga bolsas
					$this->_loadBags();
						
				} else if($oUsuario->code == 1001) {
					
					// No existe el usuario
					// Carga la vista de inicio de sesión (ingreso de ANI)
					// Le pasa el vendorId encriptado obviamente
					$encoded = $this->_encodeData($vendorId);
					$this->data["isLogged"] = FALSE;
					$this->data["vendorId"] = $oUsuario->result;
					
					$this->load->view("index_view", $this->data);
					return;
					
				} else {
					// Error
					throw new Exception($oUsuario->message, 1010);
				}

			} else {
				
				/* No hay vendorId, se interpreta como que llega por WEB */
				// Carga siempre el ingreso de ANI
				// Ingreso de ANI sin vendorId
				$this->data["isLogged"] = FALSE;
				$this->data["vendorId"] = "";
				
				$this->load->view("index_view", $this->data);
	
			}
		
		} catch(Exception $e) {
			// Por ahora redirecciona a página inicio, pero la idea es que envíe
			// a página de error
			redirect(base_url());
		}

	}
	
	
	/**
	 * Vista de ingreso de código de autorización
	 * Recibe un tag por si existe el usuario o no, además
	 * del ANI codificado (por seguridad).
	 *
	 * @return void
	 */
	public function access($exist, $ani) {
		
		if(empty($ani)) redirect(base_url());
		
		// Verifica que el $ani exista realmente en el sistema, sino, redirecciona a inicio
		$decoded = $this->_decodeData($ani);
		$valDecoded = $decoded->result;

		$userByAni = $this->getUserByAni($valDecoded);
	
		if(!is_null($userByAni)) {
			
			// Existe, así que carga vista correspondiente de ingreso de código
			
			$encoded = $this->_encodeData($exist);
			
			$this->data["ani"] = $ani; 					// encriptado
			$this->data["exist"] = $encoded->result;	// encriptado
			$this->data["action"] = base_url()."core/accessAction";
			$this->data["message"] = $exist == 1 ?
									"Ingresa tu código de acceso"
									: "Ingresa el código de acceso de 4 dígitos que te enviamos vía SMS";
			$this->data["isLogged"] = FALSE;
			
			$this->load->view("code_access_view", $this->data);
			
		} else {
			// El ANI no existe en el sistema, lo redirecciona al inicio
			redirect(base_url());
		}

	}
	
	
	/**
	 * Pantalla para la creación de código de seguridad
	 *
	 * @return void
	 */
	public function code() {
		
		if($this->_isLogged()) {
			
			$oUser = $this->_getUser();
			
			$this->data["action"] = base_url()."core/codeAction";
			$this->data["isLogged"] = TRUE;
			$this->data["activeCampaign"] = $this->_isActiveCampaign() ? TRUE : FALSE;
			$this->data["aniLogged"] = "+".$oUser->ani;
			
			$this->load->view("changepin_view", $this->data);
			
		} else {
			redirect(base_url());
		}
	
	}
	
	
	/**
	 * Pantalla para ingresar código promocional
	 *
	 * @return void
	 */
	public function exchangeCode() {
		
		if($this->_isLogged()) {
			
			$oUser = $this->_getUser();
			
			$this->data["action"] = base_url()."core/exchangeCodeAction";
			$this->data["isLogged"] = TRUE;
			$this->data["activeCampaign"] = $this->_isActiveCampaign() ? TRUE : FALSE;
			$this->data["aniLogged"] = "+".$oUser->ani;
			
			$this->load->view("exchange_code_view", $this->data);
			
		} else {
			redirect(base_url());
		}
	
	}
	
	
	
	/**
	 * Vista con listado disponible de bolsas en el sistema
	 * Debe estar autentificado en el sistema para acceder a este módulo
	 * Puede recibir el código promocional por GET encriptado
	 * Si este código llega, genera descuento o visualiza opción de regalo
	 *
	 * @return void
	 */
	public function bags($code = NULL) {
		
		try {
			
			if(!$this->_isLogged()) throw new Exception("No está autentificado en el sistema", 1000);
			
			if(!is_null($code)) {
				
				// Descodifica código
				$decoded = $this->_decodeData($code);
				if($decoded->code != 0) throw new Exception("Lo sentimos, pero no pudimos identificar tu código", 1005);
				
				$code = $decoded->result;
				
				// Vuelve a verificar que el código sea válido
				// Se trae la última campaña vigente
				$res = json_decode($this->services->getActiveCampaign());
				
				if($res->code != 0) throw new Exception($res->message, 1002);
					
				$campaign = $res->result;
				
				// Revisa que el código ingresado, sea respecto a la última campaña vigente
				if($campaign->code != $code) throw new Exception("Lo sentimos, pero el código ingresado no es válido", 1003);
	
			}
			
			// Envía código a la carga de bolsas
			$this->_loadBags($code);
			
		} catch(Exception $e) {
			$this->data["error"] = $e->getMessage();
			$this->load->view("error_view", $this->data);
			//redirect(base_url());
		}

	}
	
	
	/**
	 * Vista de listado de métodos de pago para el producto
	 * Recibe el trx de la transacción de forma encriptada
	 * Ahora hace llamado al motor de pago para la carga de canales de pago
	 *
	 * @return void
	 */
	public function listPaymentMethods($trx = NULL) {
		
		try {
			
			// Verifica si está autentificado
			if(!$this->_isLogged()) throw new Exception("No está autentificado en el sistema", 1000);
	
			// La lógica la debe manejar el MW, así que todas las validaciones respecto
			// a la transacción, son resueltas en este
			$common = base_url()."core/result/";
			$urlOk = $common."ok/$trx";
			$urlError = $common."error/$trx";
			$post = $this->services->makePaymentTrx($trx, $urlOk, $urlError);
			if(empty($post)) throw new Exception("No se podido obtener ninguna respuesta desde el middleware", 1001);
			
			$post = json_decode($post);
			if($post->code != 0) throw new Exception($post->message, 1002);
			
			// Se ha generado una transacción de manera correcta en el motor de pagos
			// se envía a la API del mismo para poder levantar canales de pago
			// El TRX del motor sigue y se envía encriptado ($post->result)
			// Va también, el trx de recarga
			// NO espera respuesta
			$this->services->showPaymentChannels($post->result, $trx);
		
		} catch(Exception $e) {
			$this->data["error"] = $e->getMessage();
			$this->load->view("error_view", $this->data);
		}
		
	}
	
	

	
	
	// -------------------- ACTIONS --------------------------
	
	/**
	 * Autentificación del sistema
	 * Verifica existencia de ANI y resuelve en función del resultado
	 */
	public function loginAction() {
		
		$o = new stdClass();
		$o->code = 0;
		$o->message = "";

		try {
			
			$exist = 1; // Para saber si el usuario existe o no
			$prefijo = "569"; // por ahora, solo Chile
			
			// Validación inicial de recepción de ANI
			$ani = trim($this->input->post("ani"));
			$vendorId = trim($this->input->post("vi")); // vendorId, llega encriptado
			if(empty($ani)) throw new Exception("Debes completar el campo con tu número de teléfono", 1000);
			
			// Verifica el formato del número ingresado
			$lAni = strlen($ani);
			if($lAni < parent::MAX_LENGTH_ANI || $lAni > parent::MAX_LENGTH_ANI) throw new Exception("El número ingresado debe ser de ".parent::MAX_LENGTH_ANI." dígitos", 1005);
			
			$prefixAni = substr($ani, 0, 3);
			if($prefixAni == $prefijo) throw new Exception("No debes ingresar el código de país / celular, solo tu número móvil", 1006);
			
			// Concatena el prefijo al ani ingresado
			$ani = $prefijo.$ani;
				
			// Si el número es correcto, verifica si existe en el sistema
			// Si existe, simplemente solicita el pin de acceso
			// Si no existe, crea el nuevo usuario y solicita generación de pin
			$userByAni = $this->getUserByAni($ani);
	
			if(!is_null($userByAni)) {
				
				// El usuario EXISTE en el sistema
				
				// Si se encuentra activo y vigente, lo autentifica
				// Verifica si la cuenta del usuario está vigente
				if($userByAni->isActive == 0) {
					
					$enc = $this->_encodeData($ani);
					$aniEncoded = $enc->result;
					$style = 'class="a-no-class normal" style="color:#f00"';
					
					throw new Exception('Lo sentimos, pero no has activado tu cuenta. <a href="'.base_url().'core/access/1/'.$aniEncoded.'" '.$style.'>Haz click aquí</a> para escribir el código de autorización o en su defecto, <a id="reSendCode" href="'.base_url().'core/reSendCodeAction" '.$style.'>solicitarlo nuevamente</a>', 1002);
				}
				
				// Verifica si es el mismo vendorId, si no lo es, lo actualiza
				// Toma el vendorId y lo desencripta para validar
				$res = $this->_decodeData($vendorId);
				$vendorId = $res->result;
				if($vendorId != $userByAni->vendorId) {
					
					$post = $this->services->updateAttributeUser($userByAni->idUser, "vendorId", $vendorId);
					$post = json_decode($post);
					if($post->code == 0 && $post->result) $userByAni->vendorId = $vendorId;
				}
				
				// Crea session para el usuario
				$this->_createSession($userByAni->idUser);
				
				// Listado de bolsas disponibles
				$o->result = base_url()."core/bags";
				
				
			} else {
				
				// El usuario NO existe
				$this->load->helper("string");
				
				// Verifica que sea un número Movistar válido
				$doPost = $this->services->IsAniValid($ani);
				$isAniMovistar = json_decode($doPost);
				if($isAniMovistar->result == 0) throw new Exception("ERROR: El número ingresado es incorrecto", 1001);
					
				// Si el usuario no existe en el sistema, lo da de alta y avanza a solicitud de pin
				$doPost = $this->services->createUser($ani);
				$res = json_decode($doPost);
		
				if($res->code != 0) throw new Exception("ERROR: No se pudo crear la cuenta, por favor inténtalo nuevamente", 1003);
				
				// Cuenta del usuario
				$oUser = $res->result;
				
				// Envía MT con código de autorización
				$trx = random_string("sha1");
				$text = $oUser->authorizationCode." es tu codigo de acceso para GigaGo";
				$sendMt = $this->sendMt($ani, $trx, $text);

				if($sendMt->code != 0) throw new Exception("ERROR: No se pudo enviar el mensaje de autorización", 1004);
				
				$exist = 0;
				
				// Lo envía a pantalla para el ingreso del código
				$accessCodeUrl = base_url()."core/access/{EXIST}/{ANI}";
				$encoded = $this->_encodeData($ani);
				$accessCodeUrl = str_replace("{ANI}", $encoded->result, $accessCodeUrl);
				$accessCodeUrl = str_replace("{EXIST}", $exist, $accessCodeUrl);
				
				$o->result = $accessCodeUrl;
				
			}
	
		} catch(Exception $e) {
			$o->code = $e->getCode();
			$o->message = $e->getMessage();
		}
		
		$this->outputJson($o);
		
	}
	
	
	/**
	 * Reenvía código de autorización
	 *
	 * @return void
	 */
	public function reSendCodeAction() {
		
		$o = new stdClass();
		$o->code = -1;
		$o->message = "";
		$o->result = NULL;
		
		try {
			
			$prefijo = "569";
			$aniPrefix = "";
			$ani = trim($this->input->post("ani"));
			$token = trim($this->input->post("token")); // ani encriptado
			
			if(empty($ani) && empty($token)) throw new Exception("La información proporcionada no es válida", 1001);
			
			// Dependiendo de lo que llegue, toma el ani
			
			if(empty($ani) && !empty($token)) {
				
				// Viene token
				$decoded = $this->_decodeData($token);
				$aniPrefix = $decoded->result;
				
			} else if(!empty($ani) && empty($token)) {
				// Viene ANI
				$aniPrefix = $prefijo.$ani;	
			} else {
				// Caso no controlado, gatilla excepción
				throw new Exception("Imposible determinar la consistencia de la información proporcionada", 1003);
			}

			$userByAni = $this->getUserByAni($aniPrefix);
			if(is_null($userByAni)) throw new Exception("No existe información relacionada al número ingresado", 1002);
			
			// Usuario existe, por lo que reenvía código
			$this->load->helper("string");
			
			$trx = random_string("sha1");
			$text = $userByAni->authorizationCode." es tu codigo de acceso para GigaGo";
			$sendMt = $this->sendMt($aniPrefix, $trx, $text);

			if($sendMt->code != 0) throw new Exception("Lo sentimos, pero no pudimos reenviar el mensaje de autorización. Por favor inténtalo nuevamente en unos instantes", 1004);
			
			// OK
			$o->code = 0;
			$o->message = "¡Te hemos enviado nuevamente el código de autorización!";
			
		} catch(Exception $e) {
			$o->code = $e->getCode();
			$o->message = $e->getMessage();
		}
		
		$this->outputJson($o);
		
	}
	
	
	/**
	 * Pantalla para la creación de código de seguridad
	 *
	 * @return void
	 */
	public function codeAction() {
		
		$o = new stdClass();
		$o->code = 0;
		$o->message = "";
		
		try {
			
			$pin_1 = trim($this->input->post("pin_1"));
			$pin_2 = trim($this->input->post("pin_2"));
			$pin_3 = trim($this->input->post("pin_3"));
			$pin_4 = trim($this->input->post("pin_4"));
			$pin_1b = trim($this->input->post("pin_1b"));
			$pin_2b = trim($this->input->post("pin_2b"));
			$pin_3b = trim($this->input->post("pin_3b"));
			$pin_4b = trim($this->input->post("pin_4b"));
			
			if($this->_isEmpty($pin_1)
				|| $this->_isEmpty($pin_2)
				|| $this->_isEmpty($pin_3)
				|| $this->_isEmpty($pin_4)
				
				|| $this->_isEmpty($pin_1b)
				|| $this->_isEmpty($pin_2b)
				|| $this->_isEmpty($pin_3b)
				|| $this->_isEmpty($pin_4b))
				
				throw new Exception("Debes completar todos los campos", 1000);
			
			// Arma códigos y verifica que sean iguales
			$code = $pin_1.$pin_2.$pin_3.$pin_4;
			$codeB = $pin_1b.$pin_2b.$pin_3b.$pin_4b;
			
			if($code != $codeB) throw new Exception("El código de acceso es incorrecto", 1001);
			
			// Obtiene usuario conectado
			// Verifico el estado del usuario
			$oUser = $this->_getUser();
			if(is_null($oUser)) throw new Exception("El usuario no existe en el sistema", 1002);
			if($oUser->isActive == 0) throw new Exception("La cuenta del usuario no está activa", 1003);
			
			// Estando todo OK, guarda el código
			
		} catch(Exception $e) {
			$o->code = $e->getCode();
			$o->message = $e->getMessage();
		}
		
		$this->outputJson($o);
		
	}
	
	
	/**
	 * Compra de bolsa para usuario
	 *
	 * @return void
	 */
	public function buyBagAction() {
		
		$o = new stdClass();
		$o->code = 0;
		$o->message = "";
		
		try {
			
			if(!$this->_isLogged()) throw new Exception("Tu sesión ha expirado. Serás redirigido automáticamente al inicio.", 1000);
			
			// ID de la bolsa + potencial código descuento
			$idBag = trim($this->input->post("bag"));
			$code = trim($this->input->post("ca")); // código activo
			
			if(empty($idBag)) throw new Exception("No se han recibido todos los parámetros para procesar la solicitud", 1001);
			
			// Obtiene la bolsa con el id
			$oBag = json_decode($this->services->getBagByIdBag($idBag));
			if(is_null($oBag->result)) throw new Exception("La bolsa seleccionada no existe en el sistema", 1002);
			
			// Generación transacción
			$oUser = $this->_getUser();
			$idUser =  $oUser->idUser;
			
			
			// Si viene código promocional (encriptado), lo valida
			$ca = NULL;
			if(!empty($code)) {
				$decrypt = $this->_decodeData($code);
				if($decrypt->code != 0) throw new Exception("Ha ocurrido un error en el proceso, por favor, inténtalo nuevamente en unos instantes", 1004);
				$arr = explode(self::pivot, $decrypt->result);
				$code = $arr[0];
				
				$campaign = json_decode($this->services->getCampaignByCode($idUser, $code));
				if($campaign->code != 0) throw new Exception($campaign->message, 1006);
				
				$resCampaign = $campaign->result;
				
				$ca = $resCampaign->code;
			}
			
			
			// CREA LA TRANSACCIÓN PARA INTENTO DE COMPRA DE BOLSA
			$doBuy = json_decode($this->services->buyBag($idUser, $idBag, $ca));
			if($doBuy->code != 0) throw new Exception("Lo sentimos, pero no se pudo obtener la información de la bolsa seleccionada", 1003);
			
			// Envía a medios de pago
			// Encripta trx de resultado
			$encoded = $this->_encodeData($doBuy->result);
			$o->result = base_url()."core/listPaymentMethods/".$encoded->result;
			
		} catch(Exception $e) {
			$o->code = $e->getCode();
			$o->message = $e->getMessage();
			if($o->code == 1000) $o->result = base_url();
		}
		
		$this->outputJson($o);
		
	}
	
	
	
	/**
	 * Procesa el código promocional ingresado por el usuario
	 *
	 * @return void
	 */
	public function exchangeCodeAction() {
		
		$o = new stdClass();
		$o->code = -1;
		$o->message = "";
		$o->result = NULL;
		
		try {
			
			$code = trim($this->input->post("exchange_code"));
			
			if($this->_isEmpty($code))
				throw new Exception("No has ingresado ningún código", 1001);
			
			
			// Se trae la última campaña vigente
			$res = json_decode($this->services->getActiveCampaign());
			
			if($res->code == 0) {
				
				$campaign = $res->result;
				
				// Revisa que el código ingresado, sea respecto a la última campaña vigente
				if($campaign->code != $code) throw new Exception("Lo sentimos, pero el código ingresado no es válido", 1003);
				
				$encode = $this->_encodeData($code);
				if($encode->code != 0) throw new Exception("Ha ocurrido un error en el proceso, por favor, inténtalo nuevamente", 1004);
				
				// OK, envía a listado de bolsas enviando el código encriptado
				$o->result = base_url()."core/bags/".$encode->result;
				$o->code = 0;
				
			} else {
				// No hay campaña activa o vigente
				throw new Exception($res->message, 1002);
			}

		} catch(Exception $e) {
			$o->code = $e->getCode();
			$o->message = $e->getMessage();
		}
		
		$this->outputJson($o);
		
	}
	
	
	
	
	/**
	 * Procesa el código ingresado
	 * Valida y avanza en el flujo de ser correcto
	 *
	 * @return void
	 */
	private function _isEmpty($val) {
		if(!isset($val)) return TRUE;
		if(is_null($val)) return TRUE;
		if($val == "") return TRUE;
		return FALSE;
	}
	public function accessAction() {
		
		$o = new stdClass();
		$o->code = 0;
		$o->message = "";
		
		try {
			
			// Valores recibidos por POST
			$token = trim($this->input->post("token")); // ani encriptado
			$exist = trim($this->input->post("exist"));
			
			$pin_1 = trim($this->input->post("pin_1"));
			$pin_2 = trim($this->input->post("pin_2"));
			$pin_3 = trim($this->input->post("pin_3"));
			$pin_4 = trim($this->input->post("pin_4"));
			
			if($this->_isEmpty($token)
				|| $this->_isEmpty($exist)
				|| $this->_isEmpty($pin_1)
				|| $this->_isEmpty($pin_2)
				|| $this->_isEmpty($pin_3)
				|| $this->_isEmpty($pin_4))
				throw new Exception("No se han recibido todos los parámetros para procesar la solicitud", 1000);
			
			$code = $pin_1.$pin_2.$pin_3.$pin_4;
			
			// Decodifica ANI y valida que exista nuevamente
			$decoded = $this->_decodeData($token);
			$valDecoded = $decoded->result;
			$userByAni = $this->getUserByAni($valDecoded);
			if(is_null($userByAni)) throw new Exception("La información proporcionada no es válida", 1001);
			
			
			// Verifica si el código corresponde o no
			if($userByAni->authorizationCode != $code) throw new Exception("El código de verificación es incorrecto", 1003);
			
			// Verifica si la cuenta del usuario está vigente
			// Valida la forma de acceso a la sección
			$decoded = $this->_decodeData($exist);
			$existDecoded = (int)$decoded->result;
			if($existDecoded != 0 && $existDecoded != 1)
				throw new Exception("No se puede procesar la solicitud", 1004);
			
			//print_r($userByAni); exit;
			
			if($userByAni->must2BeActived) {
				
				// Usuario ya existe, está intentando activar la cuenta
				$update = json_decode($this->services->activeUser($userByAni->idUser));
				if((int)$update->code != 0) throw new Exception($update->message, 1005);
				
			}
			
			// Crea session para el usuario
			$this->_createSession($userByAni->idUser);
			
			// Listado de bolsas disponibles
			$o->result = base_url()."core/bags";
			
		} catch(Exception $e) {
			$o->code = $e->getCode();
			$o->message = $e->getMessage();
		}
		
		$this->outputJson($o);
		
	}
	
	/**
	 * Destruye session activa
	 *
	 * @return void
	 */
	public function logout() {
		$this->session->sess_destroy();
		redirect(base_url());
	}
	

	/**
	 * Obtiene usuario por ANI
	 * 1002 = Usuario NO existe
	 *
	 * @return Object
	 */
	public function getUserByAni($ani) {
		
		$salida = NULL;
		
		try {
			
			$doPost = $this->services->getUserByAni($ani);
			$oUser = json_decode($doPost);
			if($oUser->code != 0) throw new Exception("ERROR: Sucedió un error verificando al usuario", 1000);
			
			$salida = $oUser->result;
			
		} catch(Exception $e) {
			log_message("error", __METHOD__ ." (".$e->getCode().") -> ".$e->getMessage());
		}
		
		return $salida;
		
	}
	
	
	/**
	 * Obtiene usuario por idUser
	 *
	 * @return Object
	 */
	public function getUserByIdUser($idUser) {
		
		$salida = NULL;
		
		try {
			
			$doPost = $this->services->getUserByIdUser($idUser);
			$oUser = json_decode($doPost);
			if($oUser->code != 0) throw new Exception("ERROR: Sucedió un error verificando al usuario", 1000);
			
			$salida = $oUser->result;
			
		} catch(Exception $e) {
			log_message("error", __METHOD__ ." (".$e->getCode().") -> ".$e->getMessage());
		}
		
		return $salida;
		
	}
	
	
	/**
	 * Envío de MT a ANI específica
	 *
	 * @return void
	 */
	public function sendMt($ani, $trx, $text) {
		
		$salida = NULL;
		
		try {
			
			$doPost = $this->services->sendMt($ani, $trx, $text);
			if(empty($doPost)) throw new Exception("No se pudo conectar con el servicio de envío de SMS", 1000);
			
			$salida = json_decode($doPost);
			
		} catch(Exception $e) {
			log_message("error", __METHOD__ ." (".$e->getCode().") -> ".$e->getMessage());
		}
		
		return $salida;
		
	}
	
	
	/**
	 * Páginas de resultado y error
	 * trx = TRX interno (recargas), llega encriptada
	 *
	 * @return void
	 */
	public function result($res, $trx = NULL) {

		try {
			
			if(!$this->_isLogged()) throw new Exception("No estás autentificado en el sistema", 1000);
			
			if(strtolower($res) != "ok") throw new Exception("La transacción no se pudo completar", 1000);
			
			if(empty($trx)) throw new Exception("No se ha podido identificar ninguna transacción", 1001);
			
			// Desencripta la trx para obtener oTrx
			$decoded = $this->_decodeData($trx);
			$trx = $decoded->result;
			
			// Busco transacción
			$oTrx = $this->services->getTransactionByTrx($trx);
			if(empty($oTrx)) throw new Exception("No se ha obtenido respuesta del middleware", 1002);
			
			$oTrx = json_decode($oTrx);
			
			if(empty($oTrx->result)) throw new Exception("No se ha encontrado la transacción en el sistema", 1003);
			
			// Error con glosa correspondiente desde el sistema
			//if($oTrx->result->ok == 0) throw new Exception($oTrx->result->glosaState, 1004);

			// OK
			$oUser = $this->_getUser();
			$this->data["message"] = $oTrx->result->glosaState;
			$this->data["isLogged"] = TRUE;
			$this->data["aniLogged"] = "+".$oUser->ani;
			$this->data["activeCampaign"] = $this->_isActiveCampaign() ? TRUE : FALSE;
			
			$this->load->view("ok_view", $this->data);
			
		} catch(Exception $e) {
			
			log_message("error", __METHOD__ ." (".$e->getCode().") -> ".$e->getMessage());
			
			// Se cambia por redirect inmediato a bolsa de datos
			redirect(base_url()."core/bags");
			
		}
	
	}

	
	
	
	
	// ---------------------- PRIVATE METHODS -------------------------------
	
	
	private function _getUser() {
		$decoded = $this->_decodeData($this->session->userdata("ua"));
		$token = $decoded->result;
		$idUser =  $this->_extractIdUser($token);
		return $this->getUserByIdUser($idUser);
	}
	
	
	/**
	 * Carga de bolsas para usuario
	 * Puede recibir el código promocional
	 *
	 * @return void
	 */
	private function _loadBags($code = NULL) {
		
		$oCampaign = NULL;
		
		// Está autenticado, ingresa a pantalla de listado de bolsas
		$oUser = $this->_getUser();
		$idUser =  $oUser->idUser;
		
		// Carga vista con bolsas disponibles
		$bags = json_decode($this->services->getListBags());
		$arrBags = $bags->result;
	
		// Si viene el código, obtiene información de este
		if(!is_null($code)) {
			
			$campaign = json_decode($this->services->getCampaignByCode($idUser, $code));
			if($campaign->code != 0) throw new Exception($campaign->message, 1003);
			
			$resCampaign = $campaign->result;
			
			// Solo envía a vista lo justo y necesario del objeto campaña
			$oCampaign = new stdClass();
			$oCampaign->code = $resCampaign->code;
			$oCampaign->name = $resCampaign->name;
			$oCampaign->description = $resCampaign->description;
			$oCampaign->value = $resCampaign->value;
			$oCampaign->is_discount = $resCampaign->is_discount;
			
			$encryp = $this->_encodeData($oCampaign->code . self::pivot . str_replace(".", "", microtime(TRUE)));
			if($encryp->code != 0) throw new Exception("Lo sentimos, pero ha ocurrido un error en el proceso, por favor, inténtalo nuevamente en unos instantes", 1004);

			$oCampaign->codeEncrypted = $encryp->result;
		}
		
		
		if(!is_null($arrBags)) {	
			foreach($arrBags as $b) {
				
				// Monto
				// Si existe una campaña activa, la evalúa para modificar el valor a mostrar
				if(!is_null($oCampaign)) {
					
					$valueCampaign = $oCampaign->value;
					
					// Verifica si es descuento o no
					if((int)$oCampaign->is_discount == 1) {
						$b->valueSale = "$".number_format((float)($b->value * ((100-$valueCampaign)/100)), 0, ",", ".");
					} else {
						// No es descuento, toma el valor de la campaña
						$b->valueSale = empty($valueCampaign) ? "¡GRATIS!" : "$".number_format((float)$valueCampaign, 0, ",", ".");
					}
					
				}
				
				$b->value = "$".number_format((float)$b->value, 0, ",", ".");
				
				// Interpreta período
				$label = "Por {PERIOD} {SUFFIX}";
				$label = str_replace("{PERIOD}", $b->period, $label);
				if((int)$b->period == 1) {
					$label = str_replace("{SUFFIX}", "día", $label);
				} else {
					$label = str_replace("{SUFFIX}", "días", $label);
				}
				$b->period = $label;
			}
		}
		
		$this->data["bags"] = $arrBags;
		$this->data["campaign"] = $oCampaign;
		$this->data["action"] = base_url()."core/buyBagAction";
		$this->data["isLogged"] = TRUE;
		$this->data["aniLogged"] = "+".$oUser->ani;
		$this->data["activeCampaign"] = $this->_isActiveCampaign() ? TRUE : FALSE;
		
		$this->load->view("bag_list_view", $this->data);
		
	}
	
}



