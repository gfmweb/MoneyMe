<?php

use app\models\Curl;
use app\models\Logger;
use app\models\Jobs;
use app\system\core\Controller;

class grotemAPI2 extends CI_Controller
{
	
	
    public function __construct()
    {
        parent::__construct();
        $this->db_programms = $this->load->database('db_programms', true, true);
        $this->db_region = $this->load->database('region', true, true);
		
    }

    /*
     * Установка новых программ
     * Служебная Записка
     */
    function setProgramm()
    {
        $programm = $this->input->post('data');

        $ip = $_SERVER['REMOTE_ADDR'];

        if (!empty($programm) && $ip == '192.168.100.188') {
            $this->db_programms->insert('grotem_insert_data', array('data' => base64_encode($programm), 'request' => base64_encode(serialize($_REQUEST)), 'ip' => $ip, 'succes' => '1'));
            //TODO Обработка автоматизации Григорян

            echo json_encode(array('succes' => 'True'));
        } else {
            $this->db_programms->insert('grotem_insert_data', array('data' => base64_encode($programm), 'request' => base64_encode(serialize($_REQUEST)), 'ip' => $ip, 'succes' => '0'));
            echo json_encode(array('succes' => 'False', 'Error' => 'True', 'CodeError' => '1'));
        }
    }
	
	/**
	 * @param array $data
	 * @return array
	 * 1. Собираем программы
	 * 2. Проверяем наличие такого партнера
	 * 3. Проверяем программы на статус (работает || не работает)
	 * 4. Получаем списки исключений по программам
	 * 5. Фильтруем результат по принципу работает программа или нет
	 * 6. Фильтруем результат по принципу взаимоисключающих программ
	 * 7. Создаем даты в которые обработчик должен работать с этой заявкой
	 * -- Структура ответа [status=> true || false, message=>описание reject(не обязательный), data=>Массив данных после сбора и фильтрации, dates=>[Массив  [0]самое ранее начало // [count-1]самый поздний конец] ]
	 * -- Структура поля data:
	 *
	['standart'||'action'||'specaction'||'nal' - значения в переменной types (начало метода)] => [
				[Стандарт -- Имя программы] => [
						[start] => 23.03.22 -- подключить
						[end] =>  -- отключить
						[exceptions] => [ -- Исключения по типам
							[standard] => Стандарт Плюс
							[stock] => Оптимальный Плюс
							[specstock] => Возможно всё Плюс
						]
				]
	]
	 */
	private function rebuildData(array $data): array
	{
		$programs = [];
		$types = ['standart','action','specaction','nal'];
		foreach($types as $item) { // циклично формируем массив программ с их типом=>названием=>[start,stop]
			$arr =[]; // объявление пустого массива для добавления
			if (isset($data[$item.'_new_schema'])&&(($data[$item.'_new_schema']))) { //Проверка наличия и не пустой записи
				$body = explode('|', $data[$item.'_new_schema']);
				$start = explode('|', $data[$item.'_datastart']);
				$stop = explode('|', $data[$item.'_dataend']);
				for ($i = 0, $Imax = count($body); $i < $Imax; $i++) { // Добавление дат подключения и отключения
					$arr[$body[$i]]['start'] = (is_null($start[$i])) ? null : $start[$i];
					$arr[$body[$i]]['end'] = (is_null($stop[$i])) ? null : $stop[$i];
				}
				$programs[$item] = $arr;
			}
		}
		if(count($programs)==0){
			return ['status'=>false,'message'=>'Нет программ'];
		}
		/*
		 * Массив собран проверяем наличие партнера
		 */
		$partner = $this->db_region->select('id')
			->from('prefixes_cb')
			->where(['partner_prefix'=>$data['partner_prefix']])
			->get()
			->num_rows();
		if($partner===0){
			return ['status'=>false,'message'=>'Партнер не найден'];
		}
		/*
		 * Проверка жива-ли программа
		 */
		$queryArrayLive = '('; // Объявляем массив запроса к БД
		foreach ($programs as $type=>$program){ // Формируем массив запроса к БД жива программа или нет
			foreach ($program as $key=>$val){
				$queryArrayLive.=' \''.$key.'\',';
			}
		}
		$queryArrayLive = mb_substr($queryArrayLive, 0, -1);
		$queryArrayLive.=')'; // Окончание формирования WHERE IN
		$program_is_alive = $this->db_programms->query('SELECT DISTINCT(`programm_syn`) FROM programm_margin WHERE `active` IS NULL AND `programm_syn` IN '.$queryArrayLive.'')->result();
		
		$queryExceptionsForPrograms ='('; // Начало формирования второго запроса на выявление исключений в анкете
		foreach ($program_is_alive as $row){
			$queryExceptionsForPrograms.=' \''.$row->programm_syn.'\',';
		}
		$queryExceptionsForPrograms = mb_substr($queryExceptionsForPrograms, 0, -1);
		$queryExceptionsForPrograms .=')'; // Конец формирования
		
		$Exceptions = $this->db_programms->query('SELECT `сlient_name`,`exceptions_standard`,`exceptions_stock`,`exceptions_specstock` FROM programm_line WHERE `сlient_name` IN '.$queryExceptionsForPrograms.'')->result();
		$ExceptionsInjection = [];
		foreach ($Exceptions as $row){
			$ExceptionsInjection[$row->сlient_name]=['standard'=>$row->exceptions_standard,'stock'=>$row->exceptions_stock,'specstock'=>$row->exceptions_specstock];
		}
		foreach ($programs as $type=>$neededPrograms){ // Фильтрация по принципу не работающей программы
			$Check = array_keys($neededPrograms);
			foreach ($Check as $item){
				$pos = strpos($queryExceptionsForPrograms,$item);
				if($pos === false){ // Удаление желанной программы в следствии того что она не работает вообще
					unset($programs[$type][$item]);
					if(count($programs[$type])==0){	unset($programs[$type]);}
				}
				else{ // Добавляем исключения которые есть у этой программы
					$programs[$type][$item]['exceptions']=$ExceptionsInjection[$item];
				}
			}
		}
		//todo фильтрация на взаимоисключающие программы
		/*
		 * Throw new reject
		 */
		$startDates=[];
		$finishDates=[];
			foreach ($programs as $types=>$value){
				foreach ($value as $key){
					if($key['start']){
						array_push($startDates,date('Y-m-d',strtotime($key['start'])));
					}
					if($key['end']){
						array_push($finishDates,date('Y-m-d',strtotime($key['end'])));
					}
				}
				
			}
			$startDates = array_unique($startDates);
			$finishDates = array_unique($finishDates);
			
			function date_sort($a, $b) {return strtotime($a) - strtotime($b);}
			
			usort($startDates, "date_sort");
			usort($finishDates, "date_sort");
			// Если у нас нет ни одной даты начала // конца срока работы воркера по этой заявке
			if(!isset($startDates[0])){$startDates[0]=date('Y-m-d',strtotime("-1 days"));}
			if(!isset($finishDates[0])){$finishDates[0]=date('Y-m-d',strtotime("-1 days"));}
			
		return ['status'=>true,'data'=>$programs,'dates'=>['start'=>$startDates,'finish'=>$finishDates]];
	}
	
	public function touch(){
		$datab64 = $this->db_programms->select('*')
			->from('grotem_insert_data')
			->where(['id'=>24842,'succes' => '1', 'data <>' => ''])
			->order_by('RAND()')
			->limit(1)
			->get()
			->row_array();
		$data=base64_decode($datab64['data']);
		;
	    $result=$this->rebuildData(json_decode($data,true));
		$this->load->model('Logger');
		echo '<pre>'; print_r($result); echo '</pre>';
	}
	
    /*
     * Функция для отправки рандомного сообщения от Гротема
     */
    public function test()
    {
        $this->load->model('Curl');
        $data = $this->db_programms->select('data')
            ->from('grotem_insert_data')
            ->where(['succes' => '1', 'data <>' => ''])
            ->order_by('RAND()')
            ->limit(1)
            ->get()
            ->row_array();

        $this->Curl->sendCurl($_SERVER['SERVER_NAME'] . "/index.php/GrotemAPI2/setProgramm",
            ['data'=>base64_decode($data['data'])]
        );
    }


    /*
     *  Задача 1.
     * в метод setProgramm идут заявки на переподключение программ по префиксам
     *   - задача разобрать входящий массив данных (программы как отключаются так и подключаются)
     *   - проверить существование префикса(база catalog_cb.prefixes_cb.partner_prefix)
     *   - проверить существование и активность программы(onlineAnketa.programm_margin)
     *   - проверить смежность программ (onlineAnketa.programm_line)
     * создать таблицу с разобранными данными, для пересборки нарезанных анкет в указанные даты
     * заявки хранятся в таблице grotem_insert_data в поле data в формате base64 в базе onlineAketa
     *
     * Задача 2.
     * Сделать обработчик который будет перенарезать анкеты в соответствующие даты
     * нарезанные анкеты хранятся в  onlineMaster.application.data формат base64
     * анкеты могут быть не активными поле active =2
     * и должны принадлежать тт поле is_TT = 1
     *
     *
     */

}
