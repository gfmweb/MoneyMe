<?php
class Curl extends CI_Model{

    /*
     *  отсылаем курл запрос на $url, с параметрами $data,
     * $metod - или пост если пустой то гет
     * возвращает либо ответ либо json вида
     * ['error'=>true, 'error_msg'=>$error_msg,
                'error_code'=>$error_code]
     */
    public function sendCurl( string $url, array $data =[], string $method ='post')
    {
        $curl = curl_init($url);

        $options = $data;
        if ($method == 'post') {
            curl_setopt($curl, CURLOPT_POST, 1);
            $query = http_build_query($options);

        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER,array("Expect:"));
        $response = curl_exec($curl);
        $head = curl_getinfo($curl);
        $error_code = curl_errno($curl);
        $error_msg = curl_error($curl);

        if ($head['http_code'] !=200)
        {
            $error_code = $head['http_code'];
            $error_msg = 'Ошибка сервера запроса '.$error_msg;
        }

        curl_close($curl);
        if ($error_code==0)
        {

            return $response;

        }
        else {


            return json_encode(['error'=>true, 'error_msg'=>$error_msg,
                'error_code'=>$error_code]);
        }
    }
}