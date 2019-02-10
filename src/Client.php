<?php
namespace ChekOnline;

use ChekOnline\Methods\Complex;

class Client
{
    public $test_mode = false;

    protected $certificate;
    protected $privateKey;

    const URL_TEST = "https://fce.chekonline.ru:4443/fr/api/v2/";
    const URL = "https://kkt.chekonline.ru/fr/api/v2/";

    public function __construct($certificate, $privateKey)
    {
        $this->certificate = $certificate;
        $this->privateKey = $privateKey;
    }

    public function send($datas)
    {
        try{
            if(!($datas instanceof Method)){
                throw new Exception("Параметр не является экземпляром класса 'Method'");
            }

            $mydatas = json_encode($datas->getReadyData());

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $mydatas);
            curl_setopt($curl, CURLOPT_URL, $this->getUrl($datas::METHOD));
            curl_setopt($curl,CURLOPT_SSLCERT, __DIR__ . '/' . $this->certificate); // Сертификат
            curl_setopt($curl,CURLOPT_SSLKEY, __DIR__ . '/' . $this->privateKey); // Закрытый ключ
            
            $json_response = curl_exec($curl);
            
            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $lastError = curl_error($curl);
            
            curl_close($curl);
    
            $response = json_decode($json_response, 1);

            return ["status" => "success", "data" => $response];
        }catch(Exception $e){
            return ["status" => "error", "data" => $e];
        }
    }

    public function getUrl($method){
        if($this->test_mode){
            return $this->URL_TEST . $method;
        }else{
            return $this->URL . $method;
        }
    } 
}