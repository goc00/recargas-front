<?php
	header("content-type: application/x-javascript");

	if (!defined('BASEPATH')) exit('No direct script access allowed');
	
	class Own extends CI_Controller {	
	
		function __construct() {
			parent::__construct();
		}

		function index() {
			echo 'var SITIO = "'.base_url().'";';	// Ruta del sitio
		}
	}

/* End of file Own.php */