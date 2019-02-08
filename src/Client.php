<?php
namespace Bayzet\ChekOnline;

use Bayzet\ChekOnline\Model\Fields;

class Client
{

    const TEST_MODE = false;
    
    protected $certificate;
    protected $privateKey;

    const URL_TEST = "https://fce.chekonline.ru:4443/fr/api/v2/Complex";
    const URL = "https://kkt.chekonline.ru/fr/api/v2/Complex";

    public function __construct($certificate, $privateKey)
    {
        $this->certificate = $certificate;
        $this->privateKey = $privateKey;
    }

    public function send(Fields $datas)
    {
        try{
            $mydatas = json_encode($datas->getReadyData());

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $mydatas);

            if($this->TEST_MODE){
                curl_setopt($curl, CURLOPT_URL,$this->URL_TEST);
            }else{
                curl_setopt($curl, CURLOPT_URL,$this->URL);
            }

            curl_setopt($curl,CURLOPT_SSLCERT,__DIR__ . '/' . $this->certificate); // Сертификат
            curl_setopt($curl,CURLOPT_SSLKEY,__DIR__ . '/' . $this->privateKey); // Закрытый ключ
            
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
}