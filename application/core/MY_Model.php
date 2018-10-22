<?php
class MY_Model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }
		
		$curl = curl_init($host.$method);

		$withReturn = is_null($noReturn) ? TRUE : FALSE;
		
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, $withReturn);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		
		$exec = curl_exec($curl);
		curl_close($curl);

		if(is_null($noReturn)) return $exec;
		return;
	}
}
?>