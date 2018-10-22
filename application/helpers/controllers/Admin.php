<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {

	//private $data;
	private $index;
	
	const PIVOT = "3GMOTION";

	public function __construct() {
		
		parent::__construct();
		
		$this->load->model('services');
		//$this->load->model('services_admin');
		$this->load->helper('string');
		
		$this->data = array();
		
		$this->index = base_url()."admin";
		
	}

	/**
	 * Pantalla de inicio, login de usuario administrador
	 */
	public function index() {
		$this->load->view("admin/index_view", $this->data);
	}
	
	
	/**
	 * Carga de opciones de backend
	 */
	public function menu() {
		if($this->_logged()) {
			$this->load->view("admin/menu_view", $this->data);
		} else {
			redirect($this->index);
		}
	}
	
	/**
	 * Permite el reverso de transacciones
	 */
	public function reverseTrxs() {
		
		if($this->_logged()) {
			
			// Busca bolsas en el sistema
			try {

				$this->load->view("admin/reverse_trxs_view", $this->data);
				
			} catch(Exception $e) {
				
				$this->_error($e->getMessage());
				
			}

			
		} else {
			redirect($this->index);
		}
	}
	
	/**
	 * Configuración de métodos de pago para las bolsas existentes
	 */
	public function confBags() {
		
		if($this->_logged()) {
			
			// Busca bolsas en el sistema
			try {
				
				// Listado de tipos de pago desde API de motor de pagos
				$listPayments = json_decode($this->services->getListPaymentMethods());
				if($listPayments->code != 0) throw new Exception("No se pudo obtener el listado de métodos de pago", 1001);
				
				// Bolsas disponibles
				$bags = json_decode($this->services->getListBags());
				if($bags->code != 0) throw new Exception("No se pudo obtener el listado bolsas disponibles", 1002); 
				
				
				
				$this->data["listPayments"] = $listPayments->result;
				$this->data["bags"] = $bags->result;
				$this->data["action"] = $this->index."/getPaymentMethodsBagAction";
				
				$this->load->view("admin/conf_bags_view", $this->data);
				
			} catch(Exception $e) {
				
				$this->_error($e->getMessage());
				
			}

			
		} else {
			redirect($this->index);
		}
	}
	
	
	/**
	 * Vista para agregar nueva bolsa
	 */
	public function addBag() {
		
		if($this->_logged()) {
			$this->load->view("admin/add_bag_view", $this->data);
		} else {
			redirect($this->index);
		}
		
	}
	
	
	
	// ------------------------- ACTIONS -------------------------
	
	
	
	/**
	 * Autentificación administrador
	 */
	public function loginAction() {
		
		try {
			
			$post = $this->input->post(NULL, TRUE);
		
			if(empty($post)) $this->_exception("No se pudo procesar la solicitud", 1000);
			
			// Obtiene datos de POST
			$userName = trim($post["username_txt"]);
			$pass = trim($post["password_txt"]);
			if(empty($userName) || empty($pass)) $this->_exception("Debes completar todos los datos", 1001);
			
			// Valida que el usuario sea administrador
			if($userName != ADMIN_USER || md5($pass) != ADMIN_PASS)
				$this->_exception("No tienes privilegios para acceder al sistema", 1003);
			
			// Crea session
			$encode = $this->_encodeData(self::PIVOT);
			$session = array("pivot" => $encode->result);
			$this->session->set_userdata($session);
			
			// Creada la session, redirige a menú principal
			redirect("admin/menu");
			//$this->menu();
			
		} catch(Exception $e) {
			$err = $e->getMessage();
			log_message("error", $err);
			$this->_error($err);
		}
		
	}
	
	
	/**
	 * Reversa transacción
	 */
	public function reverseTrxAction() {
		
		$res = new stdClass();
		$res->code = -1;
		$res->message = "";
		$res->result = NULL;
		
		try {
	
			if(!$this->_logged()) throw new Exception("No estás autenticado en el sistema", 1000);
			
			$buyOrder = trim($this->input->post("buyOrder"));
			if(empty($buyOrder)) throw new Exception("Debes ingresar el número de compra para continuar", 1001);

			// {"code":0,"message":"","result":null}
			// Solicita reversa de orden de compra
			$create = $this->services->reverseTrx($buyOrder);
			
			$post = json_decode($create);
			if($post->code != 0) throw new Exception($post->message, 1002);
				
			// OK
			$res->code = 0;
			$res->message = "Se ha reversado exitosamente la transacción número $buyOrder";
			
		} catch(Exception $e) {
			$res->code = $e->getCode();
			$res->message = $e->getMessage();
			
		}
		
		echo json_encode($res);
		
	}
	
	
	/**
	 * Obtiene métodos de pago de bolsa seleccionada
	 */
	public function getPaymentMethodsBagAction() {
		
		$res = new stdClass();
		$res->code = -1;
		$res->message = "";
		$res->result = NULL;
		
		try {
			
			if(!$this->_logged()) throw new Exception("No estás autenticado en el sistema", 1000);
			
			$idBag = trim($this->input->post("idBag"));
			if(empty($idBag)) throw new Exception("No se pudo identificar la bolsa", 1001);
			
			// Se trae los métodos de pago relacionados a la bolsa
			$paymentsForBag = $this->services->getPaymentsForBag($idBag);
			$paymentsForBag = json_decode($paymentsForBag);
			if((int)$paymentsForBag->code != 0) throw new Exception($paymentsForBag->message, 1002);
				
			// OK
			$res->code = 0;
			$res->message = "OK";
			$res->result = $paymentsForBag->result;
			
		} catch(Exception $e) {
			$res->code = $e->getCode();
			$res->message = $e->getMessage();
			
		}
		
		echo json_encode($res);
		
	}
	
	
	/**
	 * Vincula (o desvincula) métodos de pago a una bolsa
	 */
	public function confPtsBagAction() {
		
		$res = new stdClass();
		$res->code = -1;
		$res->message = "";
		$res->result = NULL;
		
		try {
			
			if(!$this->_logged()) throw new Exception("No estás autenticado en el sistema", 1000);
			
			$idBag = trim($this->input->post("idBag"));
			$pts = $this->input->post("pts"); // llega vacío si nada se completa
			if(empty($idBag)) throw new Exception("No has seleccionado ninguna bolsa", 1001);
		
			$ptsStr = empty($pts) ? "" : implode(",", $pts);
			//print_r($ptsStr); exit;
			
			// {"code":0,"message":"","result":null}
			$create = $this->services->createPts4Bag($idBag, $ptsStr);
			
			$post = json_decode($create);
			if($post->code != 0) throw new Exception($post->message, 1002);
				
			// OK
			$res->code = 0;
			$res->message = "Actualización de métodos de pago a bolsa realizada satisfactoriamente";
			
		} catch(Exception $e) {
			$res->code = $e->getCode();
			$res->message = $e->getMessage();
			
		}
		
		echo json_encode($res);
		
	}
	
	
	
	// ------------------------- PRIVATE METHODS -------------------------
	
	
	
	// Verifica si está autenticado como Administrador
	private function _logged() {
		
		if(empty($this->session->userdata("pivot"))) return FALSE;
		
		$val = $this->session->userdata("pivot");
		$decoded = $this->_decodeData($val);
		
		if($decoded->result != self::PIVOT) return FALSE;
		
		return TRUE;
	}
	
	
	// Carga vista de error
	private function _error($message) {
		$this->data["message"] = $message;
		$this->load->view("admin/error_view", $this->data);
	}
	
	// Lanza excepción
	private function _exception($message, $code) {
		throw new Exception($message, $code);
	}

}
