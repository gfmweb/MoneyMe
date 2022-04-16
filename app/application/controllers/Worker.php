<?php
	use app\models\Job;
	use app\models\Logger;
	use app\system\core\Controller;
	
	class Worker extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->db_programms = $this->load->database('db_programms', true, true);
		}
		
		public function index()
		{
			set_time_limit(800000);
			$this->load->model('Job');
			$day = date('Y-m-d');
			$Jobs = $this->Job->getDateJobs($day);
			if(count($Jobs) > 0){
				foreach ($Jobs as $item){
					$id = $item['id'];
					$work = json_decode($item['job_body'],true);
					//todo пересобрать анкету (Модель  onlineMaster » application)
					echo '<pre>'; print_r($work); echo '</pre>';
				}
			}
			
		}
	}