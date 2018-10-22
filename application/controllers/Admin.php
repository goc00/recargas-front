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
	 * Obtiene todas las transacciones (ventas) del sistema
	 */
	public function getAllTrxs() {
		
		if($this->_logged()) {
			
			// Obtiene 
			try {

				$this->load->view("admin/get_all_trxs_view", $this->data);
				
			} catch(Exception $e) {
				
				$this->_error($e->getMessage());
				
			}

			
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
	
	
	/**
	 * Manejo de transacciones
	 */
	public function manageTrxs() {
		if($this->_logged()) {
			

			try {
				
				// Busca todos las transacciones que estén marcadas con algún error
				if (!$_GET['s']){
					$trxs = $this->services->getAllIncompleteTrxs();
				} else {
					$trxs = $this->services->searchAllTrxs($_GET['s']);
				}
				
				if(empty($trxs)) throw new Exception("Error conectando con el servicio", 1001);
				
				$trxs = json_decode($trxs);
				
				$this->data["totalTrxs"] = 0;
				
				if($trxs->code == 0) {
					// Hay transacciones
					$arr = $trxs->result;
					
					$requireActions = array("FAILED_LOAD_BAG",
											"NO_SEGMENT_CLIENT",
											"REACHED_MAX_LOADS");
					
					// Proceso de transacciones
					foreach($arr as $o) {
						
						if(in_array($o->stateName, $requireActions)) {
							
							switch($o->stateName) {
								case "FAILED_LOAD_BAG":
									$o->doAction = base_url()."admin/reprocessAction";
									$o->actionName = "Reprocesar";
									break;
									
								case "NO_SEGMENT_CLIENT":
									$o->doAction = base_url()."admin/reverseAction";
									$o->actionName = "Reversar";
									break;
									
								case "REACHED_MAX_LOADS":
									$o->doAction = base_url()."admin/reprocessAfterAction";
									$o->actionName = "Reprocesar día siguiente";
									break;
							}
							
							
						} else {
							$o->doAction = NULL;
						}
						
						$o->value = "$".number_format($o->value, 0, ",", ".");
						$o->creationDate = date("d/m/Y H:i:s", strtotime($o->creationDate));
						
					}
					
					$this->data["totalTrxs"] = count($arr);
					$this->data["trxs"] = $arr;
					$this->data["message"] = $trxs->message;

				} else if($trxs->code == 1002) {
					// NO trxs
					$this->data["message"] = $trxs->message;
				} else {
					// Error
					throw new Exception($trxs->message, 1003);
				}
			
				$this->load->view("admin/manage_trxs_view", $this->data);
				
			} catch(Exception $e) {
				
				$this->_error($e->getMessage());
				
			}

			
		} else {
			redirect($this->index);
		}
	}
	
	
	
	// ------------------------- ACTIONS -------------------------
	
	/**
	 * Obtiene todas las trxs
	 */
	public function getAllTrxsAction() {
		
		try {
			
			// Todas las transacciones con su detalle
			$resA = $this->services->getAllCompleteTrxs();
			if(empty($resA)) $this->_exception("No se pudo actualizar el estado de la transacción", 1004);
			
			$resA = json_decode($resA);
	
			if($resA->code == 0) {
				
				// Transacciones
				$trxs = $resA->result;

				/*echo "<pre>";
				print_r($trxs);
				echo "</pre>";
				exit;*/
				
				$hoy = date("Ymd");
				$path = FCPATH."reports/";
				$nombre = "ventas_".$hoy."_".str_replace(".", "", microtime(TRUE)).".xls";

				// Con la data obtenida, se genera el archivo excel
				$header = "Fecha" . "\t";
				$header .= "Hora" . "\t";
				$header .= "Ani" . "\t";
				$header .= "Monto" . "\t";
				$header .= "Medio Pago" . "\t";
				
				$data = '';
				foreach($trxs as $o){
					
					$row = array();
					$toTime = strtotime($o->creationDate);
					$time = date("H:i:s", $toTime);
					$day = date("d/m/Y", $toTime);
					
					$row[] = $day;
					$row[] = $time;
					$row[] = $o->ani;
					$row[] = $o->value;
					$row[] = $o->namePaymentType;
					
					$data .= join("\t", $row)."\n";

				}
				
				$archivo = $path.$nombre;
				$res = file_put_contents($archivo, $header."\n".$data);
				
				if($res === FALSE) {
					throw new Exception("El archivo de conciliación no pudo ser generado");
				}
				
				
				echo "Se ha generado el archivo satisfactoriamente
						<br /><a href='".base_url()."reports/".$nombre."'>Descargar Archivo</a>
						<br /><a href='".base_url()."admin/manageTrxs"."'>Volver</a>";
			} else {
				$this->_exception($resA->message, 1005);
			}
			
			
		} catch(Exception $e) {
			$err = $e->getMessage();
			log_message("error", $err);
			$this->_error($err);
		}
		
	}
	
	/**
	 * Lógica de reprocesamiento de transacciones
	 */
	public function reprocessAction() {
		
		try {
			
			$post = $this->input->post(NULL, TRUE);
		
			if(empty($post)) $this->_exception("No se pudo procesar la solicitud", 1000);
			
			// Obtiene datos de POST
			$idTrx = trim($post["idTrx"]);
			if(empty($idTrx)) $this->_exception("Debes completar todos los datos", 1001);
			
			// Modifica el estado de la transacción para no reprocesar
			$resA = $this->services->UpdateTrxState2Reprocess($idTrx);
			if(empty($resA)) $this->_exception("No se pudo actualizar el estado de la transacción", 1004);
			
			$resA = json_decode($resA);
			if($resA->code == 0) {
				echo "Se ha solicitado reprocesar nuevamente la transacción<br /><a href='".base_url()."admin/manageTrxs"."'>Volver</a>";
			} else {
				$this->_exception($resA->message, 1005);
			}
			
			
		} catch(Exception $e) {
			$err = $e->getMessage();
			log_message("error", $err);
			$this->_error($err);
		}
		
	}
	
	/**
	 * Lógica de reprocesamiento de transacciones para el día siguiente
	 */
	public function reprocessAfterAction() {
		
		try {
			
			$post = $this->input->post(NULL, TRUE);
		
			if(empty($post)) $this->_exception("No se pudo procesar la solicitud", 1000);
			
			// Obtiene datos de POST
			$idTrx = trim($post["idTrx"]);
			if(empty($idTrx)) $this->_exception("Debes completar todos los datos", 1001);
			
			// Modifica el estado de la transacción para no reprocesar
			$resA = $this->services->UpdateTrxState2ReprocessAfter($idTrx);
			if(empty($resA)) $this->_exception("No se pudo actualizar el estado de la transacción", 1004);
			
			$resA = json_decode($resA);
			if($resA->code == 0) {
				echo "Se ha solicitado reprocesar nuevamente la transacción<br /><a href='".base_url()."admin/manageTrxs"."'>Volver</a>";
			} else {
				$this->_exception($resA->message, 1005);
			}
			
			
		} catch(Exception $e) {
			$err = $e->getMessage();
			log_message("error", $err);
			$this->_error($err);
		}
		
	}
	
	/**
	 * Proceso de solicitud de reversa de transacción
	 */
	public function reverseAction() {
		try {
			
			$post = $this->input->post(NULL, TRUE);
		
			if(empty($post)) $this->_exception("No se pudo procesar la solicitud", 1000);
			
			// Obtiene datos de POST
			$idTrx = trim($post["idTrx"]);
			if(empty($idTrx)) $this->_exception("Debes completar todos los datos", 1001);
			
	
			// Invoca servicio para enviar correo
			$subject = "Solicitud de reversa GigaGo";
			$message = "Se ha solicitado la reversa de la TRANSACCIÓN $idTrx, por favor ejecutar ID en Administrador";
			$to = "gorellana@3gmotion.com";
			
			$res = $this->services->sendEmail($subject, $message, $to);
			if(empty($res)) $this->_exception("No se pudo notificar la solicitud de reversa", 1002);
			
			$res = json_decode($res);
			if($res->code == 0) {
				// Modifica el estado de la transacción para no reprocesar
				$resA = $this->services->UpdateTrxState2Reverse($idTrx);
				if(empty($resA)) $this->_exception("No se pudo actualizar el estado de la transacción", 1004);
				
				$resA = json_decode($resA);
				if($resA->code == 0) {
					echo "Proceso de solicitud de reversa solicitado satisfactoriamente<br /><a href='".base_url()."admin/manageTrxs"."'>Volver</a>";
				} else {
					$this->_exception($resA->message, 1005);
				}
				
				
				
			} else {
				// Error
				$this->_exception($res->message, 1003);
			}
			
		} catch(Exception $e) {
			$err = $e->getMessage();
			log_message("error", $err);
			$this->_error($err);
		}
	}
	
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
			$buyOrder = 2175;
			if(empty($buyOrder)) throw new Exception("Debes ingresar el número de compra para continuar", 1001);

			// {"code":0,"message":"","result":null}
			// Solicita reversa de orden de compra
			$create = $this->services->reverseTrx($buyOrder);
			
			
			
			$post = json_decode($create);
			print_r($create); exit;
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
	
	public function exportExcelAction (){
		header('Content-Type: application/force-download');
		header('Content-disposition: attachment; filename=export.xls');
		// Fix for crappy IE bug in download.
		header("Pragma: ");
		header("Cache-Control: ");
		echo $_REQUEST['datatodisplay'];
		
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
