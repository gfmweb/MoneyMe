<?php
	
	class Job extends CI_Model
	{
		public function __construct()
		{
			parent::__construct();
			
		}
		
		public function addJob(string $job_start, string $job_finish, string $job_dates, string $job_body, int $parent_income_id):int
		{
			 $this->db_programms->insert('Jobs',['job_start'=>$job_start,'job_finish'=>$job_finish,'job_dates'=>$job_dates,'job_body'=>$job_body,'job_parent_income_id'=>$parent_income_id]);
			 return $this->db_programms->insert_id();
		}
		
		public function getDateJobs(string $date)
		{
			$workDate = '2021-01-27'; //todo Убрать $WorkDate заменить на $date
			return  $this->db_programms->query('SELECT id, job_body FROM `Jobs` WHERE DATE(job_start) <= "'.$date.'" AND DATE(job_finish) >= "'.$date.'" AND JSON_CONTAINS (job_dates, \'["'.$workDate.'"]\')')->result_array();
		}
	}