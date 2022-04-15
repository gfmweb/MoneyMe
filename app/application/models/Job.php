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
	}