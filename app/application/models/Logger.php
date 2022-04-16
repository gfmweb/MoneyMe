<?php
	
	
	class Logger extends CI_Model
	{
		public function __construct()
		{
			parent::__construct();
			
		}
		
		/**
		 * @param string $owner || 'builder','worker'
		 * @param bool $status || 1 - успешно 0 - Провал
		 * @param string $operation || ENUM create_job, work
		 * @param string $writing_data || Json поле для служебных записей
		 * @param string|null $parent_table || Указание на таблицу - первоисточник
		 * @param int|null $parent_id || Указание на id записи в таблице первоисточника
		 * @param string|null $recipient_table || Указание таблицы в которую делается запись
		 * @param int|null $recipient_id || ID записи в таблице приемнике
		 * @return bool
		 * Запись логов операций
		 */
		public function log(string $owner, bool $status, string $operation,string $writing_data, string $parent_table = null, int $parent_id = null, string $recipient_table = null, int $recipient_id = null ):int
		{
			 $this->db_programms->insert('Log',
					[
						'log_owner'=>$owner,'log_status'=>$status,'log_operation'=>$operation,
						'log_parent_table'=>$parent_table,'log_parent_id'=>$parent_id,
						'log_recipient_table'=>$recipient_table,'log_recipient_id'=>$recipient_id,
						'log_writing_data'=>$writing_data
					]);
			return $this->db_programms->insert_id();
		}
	}