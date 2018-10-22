<?php
class MY_Model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }		// Peticiones POST	protected function doPost($host, $method, $params, $noReturn = NULL) {
		
		$curl = curl_init($host.$method);

		$withReturn = is_null($noReturn) ? TRUE : FALSE;
		
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, $withReturn);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		
		$exec = curl_exec($curl);
		curl_close($curl);

		if(is_null($noReturn)) return $exec;
		return;
	}		// Petición GET	protected function doGet($url) {				$curl = curl_init();				curl_setopt_array($curl, array(			CURLOPT_RETURNTRANSFER => 1,			CURLOPT_URL => $url		));				$exec = curl_exec($curl);		curl_close($curl);		return $exec; 		}
}
?>