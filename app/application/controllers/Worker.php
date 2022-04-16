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
			$this->db_setup = $this->load->database('setup',true,true);
		}
		
		public function index()
		{
			set_time_limit(800000);
			$this->load->model('Job');
			$this->load->model('AnketMaster');
			$day = date('Y-m-d');
			$Jobs = $this->Job->getDateJobs($day);
			if(count($Jobs) > 0){ //Если есть работа на сегодня
				foreach ($Jobs as $item){
					$id = $item['id'];
					$work = json_decode($item['job_body'],true);
					$partner = json_decode($item['partner'],true);
					$partner = $partner['partner_prefix'];
					$partner = $this->AnketMaster->getAnketByPrefix($partner);
					if($partner[0]['active']=='2') {
						// todo logger - партнер не активен
						continue;
					}
					if($partner[0]['is_TT']!=='1'){
						// todo logger - партнер не ТТ
						continue;
					}
					
					//todo
					// 1. Проверяем что именно нам нужно сделать в задании
					// 2. запрос технического названия программы на момент подключения
					// 3. если  условие (active is NULL из програм_маргин) + выбрать программы исключения (технические названия)
					// 4. Проверить анкету на наличие программ исключений
					// 5. Отфильтровать программы и пересобрать массив программ анкеты
					// 6. собрать анкету воедино и опять её сериализовать и base64
					// 7. Logger - задача выполнена успешно
					$anket = unserialize(base64_decode($partner[0]['data']));
					echo '<pre>'; print_r($anket); echo '</pre>';
					
				}
			}
			//require('TelegramAlert.php');
			//TelegramAlert::send('Сообщение');
		}
	}