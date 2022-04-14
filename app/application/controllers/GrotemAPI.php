<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class GrotemAPI extends CI_Controller {

    public $countLine;

    function __construct() {
        parent::__construct();
        $this->db_programms = $this->load->database('db_programms', true, true);
        $this->db_region = $this->load->database('region', true, true);
    }

    function getPrograms() {
        $programm = $this->db_programms->select('*')->from('programm_margin')
                        ->where('active IS NULL')
                        ->get()->result_array();
        echo json_encode($programm);
    }

    function getProgramLine() {
        $programm = $this->db_programms->select('*')->from('programm_line')
                        ->get()->result_array();
        echo json_encode($programm);
    }

    function getInstalledPrograms() {
        $prefix = $this->input->post('prefix');


        $select = $this->db_region
                        ->select('partner_prefix, partner_name, nacta_firm, region,'
                                . ' kurator, standart_new_schema_clname, standart_fee_start_date, action_new_schema_clname, action_fee_start_date,'
                                . ' specaction_limit_clname, specaction_limit_value, specaction_limit_startdate, specaction_limit_stopdate')->from('prefixes_cb_new');

        if (!empty($prefix)) {
            $result = $select
                            ->where(array('partner_prefix' => $prefix))->get()->result_array();
        } else {
            $result = $select->get()->result_array();
        }
        echo json_encode($result);
    }

    /*
     * Установка новых программ
     * Служебная Записка
     */
    function setProgramm() {
        $programm = $this->input->post('data');

        $ip = $_SERVER['REMOTE_ADDR'];

        if (!empty($programm)&&$ip=='192.168.100.188') {
            $this->db_programms->insert('grotem_insert_data', array('data' => base64_encode($programm), 'request' => base64_encode(serialize($_REQUEST)), 'ip'=>$ip, 'succes'=>'1'));
            $this->sendMess($programm);
            echo json_encode(array('succes' => 'True'));
        } else {
            $this->db_programms->insert('grotem_insert_data', array('data' => base64_encode($programm), 'request' => base64_encode(serialize($_REQUEST)),  'ip'=>$ip, 'succes'=>'0'));
            echo json_encode(array('succes' => 'False', 'Error' => 'True', 'CodeError' => '1'));
        }
    }



    protected function sendMess($data) {

        $a = json_decode($data);
        if (!empty($a->partner_prefix)) {
            $standart = explode("|", $a->standart_new_schema);
            $action = explode("|", $a->action_new_schema);
            $spec = explode("|", $a->specaction_new_schema);
            $nal = explode("|", $a->nal_new_schema);
        }
        if (count($standart) > 0 || count($action) > 0 || count($spec) > 0) {
            $this->countLine = 1;
            $sd = $this->setLineReport($a->partner_prefix, $standart, $a);
            $sd .= $this->setLineReport($a->partner_prefix, $action, $a);
            $sd .= $this->setLineReport($a->partner_prefix, $spec, $a);
            $sd .= $this->setLineReport($a->partner_prefix, $nal, $a);
        }

        if (strlen($sd) > 0) {
            $table = '<style>'
                    . '.tableReport td{'
                    . 'border:1px solid #000;'
                    . '}'
                    . '</style>'
                    . '<table class="tableReport"><tr><td>№</td><td>с</td><td>до</td><td>Префикс</td><td>Наименование</td><td>Нал</td><td>Стандарт</td><td>Оптимальный</td><td>Удобный</td>'
                    . '<td>Низкодоходные</td><td>«Возможно ВСЁ!»</td>'
                    . '<td>Месяц</td><td>Неделя</td><td>День</td></tr>'
                    . $sd
                    . '</table>';
            $this->load->library('email');
            $config['protocol'] = 'smtp';
            $config['smtp_host'] = '192.168.100.224';
            $config['smtp_port'] = '25';
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            $this->email->from('noreply@nactacredit.com', 'GrotemAPI');
            $this->email->to("victor.burlakov@nactaacredit.com, a.pertsev@nactacredit.com, admin.nacta@nactacredit.com, k.tsyganov@nactacredit.com");
            //$this->email->to("k.tsyganov@nactacredit.com");
            $this->email->subject("Тестовая служебная записка GrotemAPI");
            $this->email->message("<html><head></head><body>"
                    . "<p>email куратора: " . $a->kurator . "</p>"
                    . $table
                    . "</body></html>");
            $this->email->send();
        }
    }

    protected function setLineReport($prefix, $data, $bigdata) {
        $SpecEnd = explode("|", $bigdata->specaction_dataend);
        $SpecStart = explode("|", $bigdata->specaction_datastart);
        $aciiStart = explode("|", $bigdata->action_datastart);
        $aciiEnd = explode("|", $bigdata->action_dataend);
        $standartStart = explode("|", $bigdata->standart_datastart);
        $standartEnd = explode("|", $bigdata->standart_dataend);
        $nalStart = explode("|", $bigdata->nal_datastart);
        $nalEnd = explode("|", $bigdata->nal_dataend);
        $name = $this->db_region->select('partner_name')
                        ->from('prefixes_cb')
                        ->where(array('partner_prefix' => $prefix))->get()->result_array();

        $i = 0;
        $dayLimit = $bigdata->limit_specaction_day;
        $weekLimit = $bigdata->limit_specaction_week;
        $monthLimit = $bigdata->limit_specaction_month;
        $line ="";
        if (!empty($data[0])) {
            foreach ($data as $l) {
                $nal = $standart = $optim = $udobniy = $vozmozhno = $akciya = $dateStart = $dateEnd = '';
                $type ='';
                if (strpos($l, "Стандарт")==0 && gettype (strpos($l, "Стандарт"))==='integer' ){
                    $type = 1;
                }elseif(strpos($l, "Оптимальный")===0 && gettype(strpos($l, "Оптимальный"))=='integer')
                {
                    $type=2;
                }
                elseif (strpos($l, "Удобный")===0 && gettype(strpos($l, "Удобный"))=='integer'){
                    $type=3;
                }
                elseif(strpos($l, "Возможно")===0 && gettype(strpos($l, "Возможно"))=='integer'){
                    $type=4;
                }
                elseif(strpos($l, "Льготный")===0 && gettype(strpos($l, "Льготный"))=='integer'){
                    $type=5;
                }
                elseif(strpos($l, "10/10/10")==0 && gettype(strpos($l, "10/10/10"))=='integer'){
                    
                    $type=6;
                }
                elseif(strpos($l, "Нал") ===0 && gettype(strpos($l, "Нал"))=='integer'){
                    $type=7;
                }
           
                switch ($type) {
                    case 1:
                        $standart = $l;
                        $dateStart = $standartStart[$i];
                        $dateEnd = $standartEnd[$i];
                        break;
                    case 2:
                        $optim = $l;
                        $dateStart = $aciiStart[$i];
                        $dateEnd = $aciiEnd[$i];
                        break;
                    case 3:
                        $udobniy = $l;
                        $dateStart = $aciiStart[$i];
                        $dateEnd = $aciiEnd[$i];
                        break;
                    case 4:
                        $vozmozhno = $l;
                        $dateStart = $SpecStart[$i];
                        $dateEnd = $SpecEnd[$i];
                        break;
                    case 5:
                        $akciya = $l;
                        $dateStart = $aciiStart[$i];
                        $dateEnd = $aciiEnd[$i];
                        break;
                    case 6:
                        $akciya = $l;
                        $dateStart = $aciiStart[$i];
                        $dateEnd = $aciiEnd[$i];
                       
                        break;
                    case 7:
                        $nal = $l;
                        $dateStart = $nalStart[$i];
                        $dateEnd = $nalEnd[$i];
                        break;
                    default:
                        $akciya = $l;
                        $dateStart = $SpecStart[$i];
                        $dateEnd = $SpecEnd[$i];
                        break;
                }

                
                $line .= "<tr><td>" . $this->countLine . "</td><td>" . $dateStart . "</td><td>" . $dateEnd . "</td><td>"
                        . $prefix . "</td><td>" . $name[0]['partner_name'] . "</td>";
                $line .= "<td>" . $nal . "</td><td>" . $standart . "</td><td>" . $optim . "</td><td>" .
                        $udobniy . "</td><td>" . $akciya . "</td><td>" .
                        $vozmozhno . "</td><td>" . $this->getLimit($vozmozhno, $monthLimit) . $this->getLimit($akciya, $monthLimit) . "</td>"
                        . "<td>" . $this->getLimit($vozmozhno, $weekLimit) . $this->getLimit($akciya, $weekLimit) . "</td><td>"
                        . $this->getLimit($vozmozhno, $dayLimit) . $this->getLimit($akciya, $dayLimit) . "</td></tr>";
                $this->countLine++;
                $i++;
            }
        }
        return $line;
    }
    
    protected function getLimit($programm, $arr) {

        foreach ($arr as $line) {
            if ($line->programm == $programm) {
                return $line->limit;
            }
        }
    }

    function test() {
        $res = $this->db_programms->select('*')->from('grotem_insert_data')->where('id', '76')->get()->result_array();
        $a = (base64_decode($res[0]['data']));

        $this->sendMess($a);


        print_R($a);

    }
    
    function getDateId(){
        $date_start = $this->input->post('date_start');
        $date_end = $this->input->post('date_end');
        //todo тут делаем проверку на даты
        if (empty($date_start))
        {
            $date_start=date('Y-m-d')." 00:00:00";
        }
        if (empty($date_end))
        {
            $date_end=date('Y-m-d')." 23:59:59";
        }
        
        $result=$this->db_programms->select('id')
                ->from('grotem_insert_data')
                ->where("date>='".$date_start."' AND date<='".$date_end."'")
                ->get()
                ->result_array();
        
        echo json_encode($result);
    }
        function getDataId()
    {
       $id =$this->input->post('id');
       //$id= 16088;
       //$id = 16085;
       $datas = $this->db_programms->select('data')
               ->from('grotem_insert_data')
               ->where('id', $id)
               ->get()
               ->row_array();
       $data = json_decode(base64_decode($datas['data']));
       $prefix = $data->partner_prefix;
       
       $standatr=$this->programmToArray($id,
               $prefix,
               $data->standart_new_schema, 
               $data->standart_datastart,
               $data->standart_dataend,
               $data->kurator);
  
       $action = $this->programmToArray($id,
               $prefix,
               $data->action_new_schema, 
               $data->action_datastart,
               $data->action_dataend,
               $data->kurator);
         
       $nal = $this->programmToArray($id,
               $prefix,
               $data->nal_new_schema, 
               $data->nal_datastart,
               $data->nal_dataend,
               $data->kurator);
        $spec =  $this->programmToArray($id,
               $prefix,
               $data->specaction_new_schema, 
               $data->specaction_datastart,
               $data->specaction_dataend,
               $data->kurator);
         
        if (!empty($spec))
        {
          
            foreach ($spec as $key=>$val)
            {   
          
                if (count($data->limit_specaction_day)>0)
                {
                    $spec[$key]['limit_day']=$data->limit_specaction_day[$key]->limit;
                }
                elseif (count($data->limit_specaction_month)>0)
                {
                    
                    $spec[$key]['limit_month']=$data->limit_specaction_month[$key]->limit;
                }
                elseif (count($data->limit_specaction_week)>0)
                {
                    $spec[$key]['limit_week']=$data->limit_specaction_week[$key]->limit;
                }
            }
        } 
        $res= array_merge($standatr, $action, $nal, $spec);
        $res= ['id'=>$id, 'list'=>$res];
        echo json_encode($res);
               
    }
    
    function programmToArray($id, $prefix, $programm, $date_start, $date_end, $kurator){
        $standart = explode('|', $programm);
       if (!empty($standart)){
           if (is_array($standart))
           {           
               $i=0;
               $standartTS= explode("|", $date_start);
               $standartTE= explode("|", $date_end);
               foreach ($standart as $line){
                   $result[]=[//'id'=>$id,
                       'prefix'=>$prefix,
                       'schema'=>$line,
                       'date_start'=>$standartTS[$i],
                       'date_end'=>$standartTE[$i],
                       'limit_day'=>NULL,
                       'limit_month'=>NULL,
                       'limit_week'=>NULL,
                       'date_input'=>date('d.m.Y'),
                       'who'=>$kurator                     
                       ];
                   $i++;
               }
           }
           else{
               $result[]=['id'=>$id,
                       'prefix'=>$prefix,
                       'schema'=>$standart,
                       'date_start'=>$date_start,
                       'date_end'=>$date_end,
                       'limit_day'=>NULL,
                       'limit_month'=>NULL,
                       'limit_week'=>NULL,
                       'date_input'=>date('d.m.Y'),
                       'who'=>$kurator                     
                       ];
           }
           return $result;
       }
       return NULL;
    }

}
