<?php
	
	class AnketMaster extends CI_Model
	{
		public function __construct()
		{
			parent::__construct();
		}
		
		public function getAnketByPrefix(string $partner_prefix):array
		{
		return 	$this->db_setup->query('SELECT * FROM application WHERE `prefix` = \''.$partner_prefix.'\' LIMIT 1')->result_array();
		}
	}