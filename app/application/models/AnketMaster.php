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
		
		/**
		 * @return array
		 * Получить все активные префиксы
		 */
		public function getPrefixes(): array
		{
			return $this->onlineMaster->query('SELECT id, prefix, kurator FROM application WHERE prefix IS NOT NULL AND is_TT = 1 AND active = 1')->result_array();
		}
		
		public function actualizeCurator(array $data){
			return $this->onlineMaster->query('UPDATE application SET kurator = '.$data['must_update'].' WHERE id = '.$data['id']);
		}
	}
