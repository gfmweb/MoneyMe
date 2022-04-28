<?php
	
	use app\system\core\Controller;
	
	class Synchron extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->onlineMaster = $this->load->database('setup', true, true);
			$this->load->model('AnketMaster');
		}
		
		public function index(){
			$prefixes = $this->AnketMaster->getPrefixes(); // Получили нумерованный массив префиксов вида [0][['id']['prefix']['curator']]
			$dataArray = []; // перевернутый массв ключами которого являются префиксы
			foreach ($prefixes as $prefix)
			{
				$dataArray[$prefix['prefix']] = $prefix;
				unset($dataArray[$prefix['prefix']]['prefix']);
			}
			foreach ($prefixes as $prefix){
				//$response = json_decode(self::transport(ServiceOne,'prefix',$prefix['prefix']),true);
				if(isset($response['curator_mail'])){ // если первый сервис ответил нам что есть такая буква в этом слове
					$dataArray[$prefix['prefix']]['email'] = $response['curator_email']; // Дописываем в рабочий массив email куратора
				}
			}
			
			$dataNames = array_keys($dataArray); // Собрали имена в нумерованный массив для удобства вызова
			for($i=0,$IMax=count($dataNames); $i < $IMax; $i++){
				if (isset($dataArray[$dataNames[$i]]['email'])) { // работаем только с теми у кого есть email
					//$response =   json_decode(self::transport(ServiceTwo,'email',$item['email']),true);
					if (isset($response['id'])){ // если Мыло найдено и есть его ID
						if($dataArray[$dataNames[$i]['kurator']!==$response['id']]){ // проверяем соответствие
							$dataArray[$dataNames[$i]]['must_update'] = $response['id'];
						}
					}
				}
			}
			
			foreach ( $dataArray as $item){
				if(isset($item['must_update'])){
					$this->AnketMaster->actualizeCurator($item); // обновляем если есть необходимость
				}
			}
			
			
		}
		
		/**
		 * @param string $service
		 * @param string $keyName
		 * @param mixed $keyValue
		 * @return mixed
		 * CURL бегалка к сервисам
		 */
		private static function transport(string $service, string $keyName, $keyValue):mixed
		{
			$ch = curl_init();
			curl_setopt_array(
				$ch,
				[
					CURLOPT_URL => 'https://HOST/'.$service,
					CURLOPT_POST => TRUE,
					CURLOPT_RETURNTRANSFER => TRUE,
					CURLOPT_TIMEOUT => 10,
					CURLOPT_POSTFIELDS => [$keyName=>$keyValue]
				
				]
			);
			$result = curl_exec($ch);
			curl_close($ch);
			return $result;
			
		}
		
		
	}
