<?php
	
	
	class Logger extends CI_Model
	{
		public function __construct()
		{
			parent::__construct();
			
		}
		
		public function log(string $owner, bool $status, string $operation,string $writing_data, string $parent_table = null, int $parent_id = null, string $recipient_table = null, int $recipient_id = null ):bool
		{
			return $this->db_programms->insert('Log',
					[
						'log_owner'=>$owner,'log_status'=>$status,'log_operation'=>$operation,
						'log_parent_table'=>$parent_table,'log_parent_id'=>$parent_id,
						'log_recipient_table'=>$recipient_table,'log_recipient_id'=>$recipient_id,
						'log_writing_data'=>$writing_data
					]);
			
		}
	}