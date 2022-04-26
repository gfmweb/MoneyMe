<?php
	
	use app\system\core\Controller;
	
	class Synchron extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->db_programms = $this->load->database('db_programms', true, true);
		}
		
		public function index(){
		
		}
	}
