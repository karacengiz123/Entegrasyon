<?php


namespace App\Entegrasyon\Processor;


use App\Entegrasyon\Model\GittiGidiyor;
use SoapClient;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class GittiGidiyorProcessor
{
    /** @var GittiGidiyor */
    protected $gittiGidiyor;

    /** @var TokenStorageInterface */
    private $tokenStorage;


    /** @var ContainerInterface */
    private $container;


    public function __construct(TokenStorageInterface $tokenStorage, ContainerInterface $container)
    {
        $this->gittiGidiyor = new GittiGidiyor();
        $this->container = $container;
    }

    #TODO: 1- CREATEAPPLİCATİON İÇİN GÖNDERİLMESİ GEREKEN ÖRNEK DATA
    /**
     * @return array
     */
    public function createAplicationData()
    {
        return [
            "developerId" => $this->gittiGidiyor->getDeveloperId(),
            "name" => "ÖRNEK AD",
            "description" => "AÇIKLAMA",
            "accessType" => "I",
            "appType" => "W",
            "descDetail" => "",
            "successReturnUrl" => "",
            "failReturnUrl" => "",
            "lang" => "tr",
        ];
    }


    #TODO: 2- ILK OLARAK BİR APP OLUŞTURMAK GEREKİYOR. BİZE APİKEY VE SECRETKEY DEĞERLERİNİ SAĞLIYOR. HER ÇAĞIRIMDA YENİ KEYLER OLUŞTURUR.
    # BİR DEFA OLUŞTURMANIZ YETERLİ. GELEN KEY DEGERLERI İLE BİLGİLERİ ALABİLİRİZ
    /**
     * @return mixed
     * @throws \SoapFault
     */
    public function createApplication()
    {
        $soapClient = new SoapClient($this->gittiGidiyor->getApiUrl().'/ApplicationService?wsdl', [
            "trace" => 1,
            "exceptions" => 1,
            'encoding' => 'utf-8',
            'login' => $this->gittiGidiyor->getUserName(),
            'password' => $this->gittiGidiyor->getPassword(),
        ]);

        $sendData = $this->createAplicationData();
        $result = $soapClient->__soapCall("createApplication",$sendData);
        return $result;
    }


    #TODO: 3- SATIŞLARI GÖRMEK İÇİN GÖNDERİLMESİ GEREKLİ OLAN DATA
    /**
     * @return array
     */
    public function getSalesServiceData()
    {
        $date = (new \DateTime())->modify("D")->getTimestamp() * 1000;
        $signature = strtoupper(md5($this->gittiGidiyor->getApiKey() . $this->gittiGidiyor->getSecretKey() . $date));

        return [
            "apiKey" => $this->gittiGidiyor->getApiKey(),
            "sign" => $signature,
            "time" => $date,
            "withData" => true,
            "byStatus" => "R",
//              "byUser" => "",
            "orderBy" => "A",
            "orderType" => "D",
            "pageNumber" => 1,
            "pageSize" => 10,
            "lang" => "tr",
        ];
    }

    #TODO: 4- IndividualSaleService' ine DATALARI GÖNDERİLMESİ DURUMUNDA SATIŞLARA ERİŞEBİLİRSİNİZ
    /**
     * @return mixed
     * @throws \SoapFault
     */
    public function getSaleService()
    {
        $soapClient = new SoapClient($this->gittiGidiyor->getApiUrl().'/IndividualSaleService?wsdl', [
            "trace" => 1,
            "exceptions" => 1,
            'encoding' => 'utf-8',
            'login' => $this->gittiGidiyor->getUserName(),
            'password' => $this->gittiGidiyor->getPassword(),
        ]);

        $sendData = $this->getSalesServiceData();
        $result = $soapClient->__soapCall('getPagedSales', $sendData);#TODO: SOAPCALL İLE FONKSİYON ÖRNEĞİ ÇAĞIRILIR

        return $result;

    }

    #TODO: 5- AKTİF SATIŞTAKİ ÜRÜNLERİ GÖRMEK İÇİN GÖNDERİLMESİ GEREKEN DATA
    /**
     * @return array
     */
    public function getProductsData()
    {
        $date = (new \DateTime())->modify("D")->getTimestamp() * 1000;

        #TODO: SİGN BİLGİSİNE İHTİYACIMIZ VAR. OLUŞTURMAK İÇİN KEY VE DATE BİLGİLERİNİ ŞİFRELEYİP GÖNDERMEMİZ GEREKİYOR.
        $signature = strtoupper(md5($this->gittiGidiyor->getApiKey() . $this->gittiGidiyor->getSecretKey() . $date));

        return [
            "apiKey" => $this->gittiGidiyor->getApiKey(),
            "sign" => $signature,
            "time" => $date,
            "startOffSet" => 0,
            "rowCount" => 100,
            "status" => "A",
            "withData" => true,
            "lang" => "tr",
        ];
    }


    #TODO: 4- IndividualSaleService' ine DATALARIN GÖNDERİLMESİ DURUMUNDA GETPRODUCTS FONKSİYONU ÇAĞIRILARAK ÜRÜNLERE ERİŞEBİLİRSİNİZ.
    /**
     * @return mixed
     * @throws \SoapFault
     */
    public function getProducts()
    {
        $soapClient = new SoapClient($this->gittiGidiyor->getApiUrl().'/IndividualProductService?wsdl', [
            "trace" => 1,
            "exceptions" => 1,
            'encoding' => 'utf-8',
            'login' => $this->gittiGidiyor->getUserName(),
            'password' => $this->gittiGidiyor->getPassword(),
        ]);

        $sendData = $this->getProductsData();
        $result = $soapClient->__soapCall('getProducts', $sendData);

        return $result;

    }
}