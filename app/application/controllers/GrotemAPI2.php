<?php


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
