<?php
	use app\models\Job;
	use app\models\Logger;
	use app\system\core\Controller;
	
	/**
	 * Автономно работающий класс
	 * 1. Инициализация подключений к БД
	 * 2. Подключение Телеграм оповещателя ~DebugAlarm_bot
	 * 3. сообщение о начале работы
	 * 4. Сбор заявок в день запуска
	 * 5. Проверка партнёра
	 * 6. Подготовка списков программ для подключения // удаления
	 * 7. Удаление из анкеты программ подпадающих под удаление
	 * 8. Добавление новых
	 * 9. Запись в БД
	 * 10.Сообщение об окончании работы
	 */
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
			$secret = password_hash('password', PASSWORD_DEFAULT); // Значение ключа
			$key = $this->input->get('key'); // Передаваемый параметр KEY
			if(!password_verify($key,$secret)){die();} // Умираем если ключ нам не передали или он не подходит
			
			require_once ('TelegramAlert.php');
			TelegramAlert::send('Начало работы с заданиями');
			$types = ['standart','action','specaction'];
			set_time_limit(800000);
			$this->load->model('Job');
			$this->load->model('AnketMaster');
			$this->load->model('Logger');
			$day = date('Y-m-d');
			$Jobs = $this->Job->getDateJobs($day);
			if(count($Jobs) > 0){ //Если есть работа на сегодня
				foreach ($Jobs as $item){
					$id = $item['id'];
					$work = json_decode($item['job_body'],true);
					$partner = json_decode($item['partner'],true);
					$partner = $partner['partner_prefix'];
					$partner = $this->AnketMaster->getAnketByPrefix($partner);
					$anketa = unserialize(base64_decode($partner[0]['data']));
					if($partner[0]['active']=='2') {
						$error_id=$this->Logger->log('worker',0,'work',json_encode(['message'=>'Партнер не активен'],256),'Log',$id);
						TelegramAlert::send('Работа по заданию завершилась ошибкой. Партнер не активен. Запись'.$error_id);
						continue;
					}
					if($partner[0]['is_TT']!=='1'){
						$error_id = $this->Logger->log('worker',0,'work',json_encode(['message'=>'Партнер не ТТ'],256),'Log',$id);
						TelegramAlert::send('Работа по заданию завершилась ошибкой. Партнер не TT. Запись'.$error_id);
						continue;
					}
					//$day = date('d.m.y',strtotime('2021-01-27')); //todo убрать strtotime
					$day = date('d.m.y');
					$programs_to_job['start'] =[]; // Массив предстоящих работ по подключению
					$programs_to_job['end'] =[]; // Массив предстоящих работ по отключению
					foreach ($work as $type=>$program){ // Собираем программы по которым предстоит работа
						$Names= array_keys($program);
						for($i=0,$iMax=count($Names); $i <$iMax; $i++){
							if($program[$Names[$i]]['start']==$day){
								$programs_to_job['start'][]=['name'=>$Names[$i],'start'=>$program[$Names[$i]]['start'],'exceptions'=>$program[$Names[$i]]['exceptions']];
							}
							if($program[$Names[$i]]['end']==$day){
								$programs_to_job['end'][]=['name'=>$Names[$i],'end'=>$program[$Names[$i]]['end']];
							}
						}
					}
					//Собираем в кучу все исключения для подключаемых программ
					$ExceptionsRequest = []; //Массив всех исключений которые нам могут встретиться
					for($i=0,$iMax=count($programs_to_job['start']); $i <$iMax; $i++){
						foreach ($types as $type){
							$ExceptionsRequest[]=$programs_to_job['start'][$i]['exceptions'][$type];
						}
					}
					$Exceptions = array_unique($ExceptionsRequest);
					unset($ExceptionsRequest[count($ExceptionsRequest)-1]);
					$ExceptionsResult = $this->Job->getTechNames($ExceptionsRequest,true); // Собран массив всех исключений с нормальным именем и техническим
					// Проверяем программы на работоспособность в день их подключения (Работаем с массивом $programs_to_job['start'])
					foreach($programs_to_job['start'] as $program){
						$StartResult[] = $program['name'];
					}
					$StartResult = $this->Job->getTechNames($StartResult);
					if(count($StartResult)==0){
						$error_id = $this->Logger->log('worker',0,'work',json_encode(['message'=>'Нет ни одной рабочей программы для подключения'],256),'Log',$id);
						TelegramAlert::send('Нет ни одной рабочей программы для подключения'.$error_id);
						continue;
					}
				}
			}
			$toKill=[]; // Массив кандидатов для удаления
			for ($i=0, $iMax=count($anketa['programm'][0]['programms']); $i <$iMax; $i++){
				foreach ($ExceptionsResult as $Exception){
					if($Exception['record'] == $anketa['programm'][0]['programms'][$i]){
						$toKill[]=$i; // Формирование целей убийств существующих в анкете программ по причине Exception
					}
				}
			}
			// Собираем массив программ которые нам надо удалить в этом задании
			$EndResult = [];
			foreach($programs_to_job['end'] as $program){
				$EndResult[] = $program['name'];
			}
			if(count($EndResult) > 0) {
				$EndResult = $this->Job->getTechNames($EndResult, true);
				for ($i=0, $iMax=count($anketa['programm'][0]['programms']); $i <$iMax; $i++){
					foreach ($EndResult as $End){
						if($End['record'] == $anketa['programm'][0]['programms'][$i]){
							$toKill[]=$i; // Формирование целей убийств существующих в анкете программ по причине Отключения
						}
					}
				}
			}
			$killTechNames='';
			foreach ($toKill as $key=>$val){
				$killTechNames.=' '.$anketa['programm'][0]['programms'][$key].' ';
				
			}
			$added='';
			for($i=0,$iMax=count($StartResult); $i < $iMax; $i++){
				$added.=' '.$StartResult[$i]['record'];
			}
			$result=['before'=>$anketa['programm'][0]['programms'],'killed'=>$killTechNames,'added'=>$added];
			foreach ($toKill as $kill){
				unset($anketa['programm'][0]['programms'][$kill]);
			}
			foreach ($StartResult as $start){
				$anketa['programm'][0]['programms'][]=$start['record'];
			}
			foreach ($anketa['programm'][0]['programms'] as $programm){
				$temp[]=$programm;
			}
			$anketa['programm'][0]['programms']=$temp; // теперь программы опять по порядку индексов
			$writeData = base64_encode(serialize($anketa));
			
			$this->db_setup->query('UPDATE application SET `data` =\''.$writeData.'\' WHERE `id` = '.$partner[0]['id']);
			$this->Logger->log('worker',1,'work',json_encode(['result'=>$result],256),'Log',$id,'application',$partner[0]['id']);
			TelegramAlert::send('Работа с заданиями закончено');
			
		}
	}
