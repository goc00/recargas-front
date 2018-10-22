<?php
class Services extends MY_Model {
	
    function __construct() {
		parent::__construct();
    }
	
	// Obtiene valor de archivo de configuración
	public function getConfigItem($name) {
		
		$o = new stdClass();
		$o->name = $name;
		
		$res = $this->doPost(SERVICE_URL, "GetConfigItem", $o);
		return $res;
	
    }

	// Verifica si el ANI es válido o no (Movistar)
    public function IsAniValid($ani) {
		
		$o = new stdClass();
		$o->ani = $ani;
		
		$res = $this->doPost(SERVICE_URL, "IsAniValidMovistar", $o);
		return $res;
	
    }
	
	// Obtiene (de existir) al usuario por el ANI
	public function getUserByAni($ani) {
		
		$o = new stdClass();
		$o->ani = $ani;
		
		$res = $this->doPost(SERVICE_URL, "GetUserByAni", $o);
		return $res;
	
    }
	
			// Obtiene (de existir) al usuario por el ANI
	public function getUserByAniwhitelist($ani) {
				
		$o = new stdClass();
		$o->ani = $ani;
	
		$res = $this->doPost(SERVICE_URL, "GetUserByAniwhiteList", $o);
	return $res;
			
	}

		
	// inserta usuario no login
	public function addUsernotlogin($ani) {
		
		$o = new stdClass();
		$o->ani = $ani;
		
		$res = $this->doPost(SERVICE_URL, "AddUsernotlogin", $o);
		return $res;
	
    }
	
	// Obtiene (de existir) al usuario por el idUser
	public function getUserByIdUser($idUser) {
		
		$o = new stdClass();
		$o->idUser = $idUser;
		
		$res = $this->doPost(SERVICE_URL, "GetUserByIdUser", $o);
		return $res;
	
    }

	// Obtiene usuario en función del vendorId
	public function getUserByVendorId($vendorId) {
		
		$o = new stdClass();
		$o->vendorId = $vendorId;
		
		$res = $this->doPost(SERVICE_URL, "GetUserByVendorId", $o);
		return $res;
	
    }
	
	// Crea usuario
	public function createUser($ani) {
		
		$o = new stdClass();
		$o->ani = $ani;
		
		$res = $this->doPost(SERVICE_URL, "CreateUser", $o);
		return $res;
	
    }
	
	// Activa la cuenta del usuario
	public function activeUser($idUser) {
		
		$o = new stdClass();
		$o->idUser = $idUser;
		
		$res = $this->doPost(SERVICE_URL, "ActiveUser", $o);
		return $res;
	
    }
	
	// Crea código de autorización
	public function createAuthorizationCode() {
		
		$res = $this->doPost(SERVICE_URL, "CreateAuthorizationCode", NULL);
		return $res;
	
    }
	
	// Registra acceso de usuario
	public function createUserAccess($idUser) {
		
		$o = new stdClass();
		$o->idUser = $idUser;
		
		$res = $this->doPost(SERVICE_URL, "CreateUserAccess", $o);
		return $res;
	
    }
	
	// Envío de MT a ANI
	public function sendMt($ani, $trx, $text) {
		
		$o = new stdClass();
		$o->ani = $ani;
		$o->trx = $trx;
		$o->text = $text;
		
		$res = $this->doPost(SERVICE_URL, "SendMt", $o);
		return $res;
	
    }
	
	// Listado de bolsas disponibles
	public function getListBags() {
		$res = $this->doPost(SERVICE_URL, "GetListBags", NULL);
		return $res;
    }
	
	
	// Obtiene bolsa por id
	public function getBagByIdBag($idBag) {
		
		$o = new stdClass();
		$o->idBag = $idBag;
		
		$res = $this->doPost(SERVICE_URL, "GetBagByIdBag", $o);
		return $res;
    }
	
	// Compra de bolsa por usuario
	public function buyBag($idUser, $idBag, $code) {
		
		$o = new stdClass();
		$o->idUser = $idUser;
		$o->idBag = $idBag;
		$o->code = $code;
		
		$res = $this->doPost(SERVICE_URL, "BuyBag", $o);
		return $res;
    }
	
	// Obtiene la transacción por el trx
	public function getTransactionByTrx($trx) {
		
		$o = new stdClass();
		$o->trx = $trx;

		$res = $this->doPost(SERVICE_URL, "GetTransactionByTrx", $o);
		return $res;
    }
	
	
	// Listado de métodos de pago del comercio
	public function getListPaymentMethods() {
		$res = $this->doPost(SERVICE_URL, "GetListPaymentMethods", NULL);
		return $res;
    }
	
	
	// Listado de métodos de pago para bolsa específica
	public function getPaymentsForBag($idBag) {
		
		$o = new stdClass();
		$o->idBag = $idBag;
		
		$res = $this->doPost(SERVICE_URL, "GetPaymentsForBag", $o);
		
		return $res;
    }
	
	
	// Listado de métodos de pago del comercio
	public function getTrxDetailsByCodExternal($codExternal) {
		
		$o = new stdClass();
		$o->codExternal = $codExternal;
		
		$res = $this->doPost(SERVICE_URL, "GetTrxDetailsByCodExternal", $o);
		
		return $res;
    }
	
	
	// Actualiza el acceso de usuario para los NO Movistar (vincular email con registro)
	public function updateUserAccessWithEmail($idUserAccess, $emailNoUser) {
		
		$o = new stdClass();
		$o->idUserAccess = $idUserAccess;
		$o->emailNoUser = $emailNoUser;
		
		$res = $this->doPost(SERVICE_URL, "UpdateUserAccessWithEmail", $o);
		
		return $res;
    }
	
	
	// Actualiza estado de transacción
	public function UpdateTrxState2Reverse($idTrx) {
		
		$o = new stdClass();
		$o->idTrx = $idTrx;
		
		$res = $this->doPost(SERVICE_URL, "UpdateTrxStateReverse", $o);
		
		return $res;
    }
	
	// Reprocesa el mismo día
	public function UpdateTrxState2Reprocess($idTrx) {
		
		$o = new stdClass();
		$o->idTrx = $idTrx;
		
		$res = $this->doPost(SERVICE_URL, "UpdateTrxStateReprocess", $o);
		
		return $res;
    }
	
	
	// Reproceso para día siguiente
	public function UpdateTrxState2ReprocessAfter($idTrx) {
		
		$o = new stdClass();
		$o->idTrx = $idTrx;
		
		$res = $this->doPost(SERVICE_URL, "UpdateTrxStateReprocessAfter", $o);
		
		return $res;
    }
	
	
	
	// -------------------------- MÉTODOS DE PAGO --------------------------
	
	// Inicio (creación) de transacción en Motor de Pagos
	public function makePaymentTrx($trx, $urlOk, $urlError) {
		
		$obj = new stdClass();
		$obj->trx = $trx;
		$obj->urlOk = $urlOk;
		$obj->urlError = $urlError;
		
		// No se le pasa 4to param, porque HAY retorno
		return $this->doPost(SERVICE_URL, "MakePaymentTrx", $obj);
    }
	
	// Visualización de formulario de canales de pago
	// TRX encriptada
	public function showPaymentChannels($paymentTrx, $trx) {
		
		$obj = new stdClass();
		$obj->paymentTrx = $paymentTrx;
		$obj->trx = $trx;
	
		// SIN retorno
		$this->doPost(SERVICE_URL, "ShowPaymentChannels", $obj, FALSE);
    }
	

	
	
	
	
	
	
	// Actualiza el parámetro que sea del usuario
	public function updateAttributeUser($idUser, $param, $value) {
		
		$o = new stdClass();
		$o->idUser = $idUser;
		$o->param = $param;
		$o->value = $value;

		// Si espera respuesta (hay retorno)
		$res = $this->doPost(SERVICE_URL, "UpdateAttributeUser", $o);

		return $res;
    }
	
	
	// Encripta y desencripta valores contra middleware
	public function encodeData($data) {
		
		$o = new stdClass();
		$o->data = $data;
		
		$res = $this->doPost(SERVICE_URL, "EncodeData", $o);
		return $res;
	
    }
	public function decodeData($data) {
		
		$o = new stdClass();
		$o->data = $data;
		
		$res = $this->doPost(SERVICE_URL, "DecodeData", $o);
		return $res;
	
    }
	
	public function getTotalPurchasesByUser($idUser, $fStart, $fEnd) {
		
		$o = new stdClass();
		$o->idUser = $idUser;
		$o->fStart = $fStart;
		$o->fEnd = $fEnd;
		
		$res = $this->doPost(SERVICE_URL, "GetTotalPurchasesByUser", $o);
		return $res;
	
    }
	
	public function allowBuyBag($idUser, $fStart, $fEnd) {
		
		$o = new stdClass();
		$o->idUser = $idUser;
		$o->fStart = $fStart;
		$o->fEnd = $fEnd;
		
		$res = $this->doPost(SERVICE_URL, "AllowBuyBag", $o);
		return $res;
	
    }
	
	
	
	
	
	
	// --------------------------- MOTOR DE PINES --------------------------------
	
	// Se trae (si existe) última campaña activa
	public function getActiveCampaign() {
		$res = $this->doPost(SERVICE_URL, "GetActiveCampaign", NULL);
		return $res;
    }
	
	// Se trae (si existe) última campaña activa
	public function getCampaignByCode($idUser, $code) {
		$o = new stdClass();
		$o->idUser = $idUser;
		$o->code = $code;
		
		$res = $this->doPost(SERVICE_URL, "GetCampaignByCode", $o);
		return $res;
    }
	
	public function encodeDataPines($data) {
		
		$o = new stdClass();
		$o->data = $data;
		
		$res = $this->doPost(SERVICE_URL, "EncodeDataPines", $o);
		return $res;
	
    }
	public function decodeDataPines($data) {
		
		$o = new stdClass();
		$o->data = $data;
		
		$res = $this->doPost(SERVICE_URL, "DecodeDataPines", $o);
		return $res;
	
    }
	
	
	// Envía correo
	public function sendEmail($subject, $message, $to) {
		
		$o = new stdClass();
		$o->subject = $subject;
		$o->message = $message;
		$o->to = $to;
		
		$res = $this->doPost(SERVICE_URL, "SendEmail", $o);
		return $res;
	
    }
	
	
	
	
	
	
	
	// -------------------------- ADMIN --------------------------
	
	
	public function createPts4Bag($idBag, $pts) {
		
		$o = new stdClass();
		$o->idBag = $idBag;
		$o->pts = $pts;
		
		$res = $this->doPost(SERVICE_URL, "CreatePts4Bag", $o);
		
		return $res;
	
    }
	
	
	// Permite reversar transacción en Oneclick
	public function reverseTrx($buyOrder) {
		
		$o = new stdClass();
		$o->buyOrder = $buyOrder;
		
		$res = $this->doPost(SERVICE_URL, "ReverseTrx", $o);
		
		return $res;
    }
	
	// Transacciones incompletas
	public function getAllIncompleteTrxs() {
		
		$res = $this->doPost(SERVICE_URL, "GetAllIncompleteTrxs", NULL);
		
		return $res;
    }
	
	// Transacciones completas
	public function getAllCompleteTrxs() {
		
		$res = $this->doPost(SERVICE_URL, "GetAllCompleteTrxs", NULL);
		
		return $res;
	}

	// Buscador Transacciones
	public function searchAllTrxs($key) {
	    $o = new stdClass();
		$o->key = $key;

		$res = $this->doPost(SERVICE_URL, "SearchAllTrxs", $o);
		
		return $res;
	}
	
	
}

