<?php
	
	/**
	 * Модель с которой работает класс Worker
	 */
	class Job extends CI_Model
	{
		public function __construct()
		{
			parent::__construct();
		}
		
		/**
		 * @param string $job_start
		 * @param string $job_finish
		 * @param string $job_dates
		 * @param string $job_body
		 * @param int $parent_income_id
		 * @return int
		 * Добавляет задание для Worker в таблицу Log возвращает id записи
		 */
		public function addJob(string $job_start, string $job_finish, string $job_dates, string $job_body, int $parent_income_id):int
		{
			 $this->db_programms->insert('Jobs',['job_start'=>$job_start,'job_finish'=>$job_finish,'job_dates'=>$job_dates,'job_body'=>$job_body,'job_parent_income_id'=>$parent_income_id]);
			 return $this->db_programms->insert_id();
		}
		
		/**
		 * @param string $date
		 * @return array
		 * Возвращает массив работ которые нужно сделать Worker сегодня
		 */
		public function getDateJobs(string $date):array
		{
			
			return  $this->db_programms->query('SELECT j.id, j.job_body, l.log_writing_data as partner  FROM `Jobs` j LEFT JOIN Log l ON j.id = l.log_recipient_id WHERE DATE(job_start) <= "'.$date.'" AND DATE(job_finish) >= "'.$date.'" AND JSON_CONTAINS (job_dates, \'["'.$date.'"]\') AND DATE (job_last_work_day) <> "'.date('Y-m-d').'" OR job_last_work_day IS NULL')->result_array();
		}
		
		/**
		 * @param array $Jobs
		 * @return void
		 * Устанавливает текущую дату заданиям отданным воркеру
		 */
		public function setJobsAtWork(array $Jobs){
			$querySTR ='( ';
			foreach ($Jobs as $id){
				$querySTR.=' \''.$id['id'].'\',';
			}
			$querySTR=mb_substr($querySTR, 0, -1);
			$querySTR.=')';
			$this->db_programms->query('UPDATE Jobs SET job_last_work_day = "'.date('Y-m-d').'" WHERE id IN '.$querySTR);
			return 'UPDATE Jobs SET job_last_work_day = "'.date('Y-m-d').'" WHERE id IN '.$querySTR;
		}
		
		/**
		 * @return int
		 * Текущий показатель состояния работника 1 - сейчас работает; 0 - сейчас не работает
		 */
		public function getWorkerStatus(): int{
			$result = $this->db_programms->query('SELECT * FROM Worker_instance WHERE 1')->result_array();
			return $result[0]['worker_at_work'];
		}
		
		/**
		 * @param int $status
		 * @return bool
		 * Меняет статус Workera
		 */
		public  function updateWorkerStatus(int $status):bool
		{
			return $this->db_programms->query('UPDATE Worker_instance SET worker_at_work = '.$status.' WHERE 1');
		}
		
		/**
		 * @param array $Request
		 * @param bool $Is_exception
		 * @return array
		 * Возвращает все технические названия программ + их пользовательское имя по 2 вариантам
		 * 1. Когда мы проверяем программы, которые собираем подключать, то второй (необязательный ключ должен быть true) - Тогда мы проверяем статус активности этой программы
		 * 2. Когда мы проверяем программы которые будем удалть (Exceptions или просто программы для удаления) - то мы не проверяем статус их активности
		 */
		public function  getTechNames(array $Request, bool $Is_exception=false): array
		{
			
			
			$Query = '(';
			foreach ($Request as $item){
				$Query.=' \''.$item.'\',';
			}
			$Query = mb_substr($Query, 0, -1);
			$Query.=')';
			$preQuery = $this->db_programms->query('SELECT сlient_name, loan_interest FROM programm_line WHERE сlient_name IN '.$Query)->result_array();
			$Query = '';
			foreach ($preQuery as $pre){
				$Query.= ' (`programm_syn` = "'.$pre['сlient_name'].'" AND partner_reward = "'.$pre['loan_interest'].'") OR';
			}
			$Query = mb_substr($Query, 0, -2);
			return ($Is_exception) ? $this->db_programms->query('SELECT DISTINCT(programm_syn) as name, programm_tech as record FROM programm_margin WHERE' . $Query)->result_array() : $this->db_programms->query('SELECT DISTINCT(programm_syn) as name, programm_tech as record FROM programm_margin WHERE ' . $Query)->result_array();
		}
	}
