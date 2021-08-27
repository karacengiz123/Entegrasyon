<?php

namespace App\Entegrasyon\Processor;

use App\Entegrasyon\Model\Trendyol;
use GuzzleHttp\Client;

class TrendyolProcessor
{
    /** @var Trendyol */
    protected $trendyol;

    /** @var Client */
    protected $httpClient;


    public function __construct()
    {
        //TODO: TRENDYOL API BAĞLANTI BİLGİLERİ
        $this->trendyol = new Trendyol();
        $this->httpClient = new Client();
    }


    /**
     * @return Trendyol
     */
    public function getTrendyol(): Trendyol
    {
        return $this->trendyol;
    }

    /**
     * @return Client
     */
    public function getHttpClient(): Client
    {
        return $this->httpClient;
    }

    /**
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getOrders()
    {
        $firstDate = (new \DateTime())->modify("-14 days,+3 hours");
        $firstDayStamp = gmmktime($firstDate->format("H"),$firstDate->format("i"),$firstDate->format("s"),$firstDate->format("m"),$firstDate->format("d"),$firstDate->format("Y"))*1000;

        $lastDate= (new \DateTime())->modify("+3 hours");
        $lastDayStamp = gmmktime($lastDate->format("H"),$lastDate->format("i"),$lastDate->format("s"),$lastDate->format("m"),$lastDate->format("d"),$lastDate->format("Y"))*1000;

        try {
            $request = $this->httpClient->request("GET", $this->trendyol->getApiUrl()."?startDate=".$firstDayStamp."&endDate=".$lastDayStamp,
                [
//                    'headers' => [
//                        "Accept" => "application/json",
//                        "Content-Type" => "application/json",
//                        'Authorization' => [
//                            'Basic' . base64_encode($this->trendyol->getUserName() . ':' . $this->trendyol->getPassword())
//                        ],
//                    ]

                    'auth' => [$this->trendyol->getUserName(), $this->trendyol->getPassword()]
                ]);

            $result = json_decode($request->getBody()->getContents(), true);
            return $result;
        } catch (\GuzzleHttp\Exception\BadResponseException $exception) {
            $error = json_decode($exception->getResponse()->getBody()->getContents(), true);
            return $error;
        }
    }
}

