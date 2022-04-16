<?php
	
	/**
	 * моделька работы с таблицей AnketaMaster.application
	 */
	class AnketMaster extends CI_Model
	{
		public function __construct()
		{
			parent::__construct();
		}
		
		/**
		 * @param string $partner_prefix
		 * @return array
		 * Возвращает текущего партнёра по его префиксу
		 */
		public function getAnketByPrefix(string $partner_prefix):array
		{
		return 	$this->db_setup->query('SELECT * FROM application WHERE `prefix` = \''.$partner_prefix.'\' LIMIT 1')->result_array();
		}
	}