<?php


namespace App\Entegrasyon\Processor;


use App\Entegrasyon\Model\Parasut;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ParasutProcessor
{
    /** @var Parasut */
    protected $parasut;

    /** @var string */
    protected $token = null;

    /** @var string */
    protected $refreshToken = null;

    /** @var Client */
    protected $httpClient;

    /** @var ContainerInterface */
    protected $container;

    #TODO: Burada sürekli login işlemi yapmamak için yapıcı method içerisine tanımlamasını yaptık. Token üretip login oluyoruz.
    #TODO: Eğer tokenımız varsa refresh token ile işleme devam ediyoruz.
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->parasut = new Parasut();
        $this->httpClient = new Client();
        try {
            $request = $this->httpClient->request("POST", $this->parasut->getApiUrl() . "/oauth/token", [
                'headers' => [
                    "Accept" => "application/json",
                    "Content-Type" => "application/json",
                ],
                "body" => json_encode([
                    "client_id" => $this->parasut->getClientId(),
                    "client_secret" => $this->parasut->getClientSecret(),
                    "grant_type" => $this->parasut->getGrantType(),
                    "username" => $this->parasut->getUserName(),
                    "password" => $this->parasut->getPassword(),
                    "redirect_uri" => $this->parasut->getRedirectUri()
                ])
            ]);
            $result = json_decode($request->getBody()->getContents(), true);
            $this->token = $result["access_token"];
            $this->refreshToken = $result["refresh_token"];
        } catch (\GuzzleHttp\Exception\BadResponseException $exception) {
            $error = json_decode($exception->getResponse()->getBody()->getContents(), true)["error"];
            try {
                $request = $this->httpClient->request("POST", $this->parasut->getApiUrl() . "/oauth/token/" . $error["refreshToken"], [
                    "headers" => [
                        "Accept" => "application/json",
                        "Content-Type" => "application/json",
                    ]
                ]);
                $result = json_decode($request->getBody()->getContents(), true);
                $this->token = $result["access_token"];
                $this->refreshToken = $result["refresh_token"];
            } catch (\GuzzleHttp\Exception\BadResponseException $exception) {
                $error = json_decode($exception->getResponse()->getBody()->getContents(), true);
                $this->token = null;
                $this->refreshToken = null;
            }
        }

    }

    /**
     * @return Client
     */
    public function getHttpClient(): Client
    {
        return $this->httpClient;
    }

    /**
     * @return Parasut
     */
    public function getParasut(): Parasut
    {
        return $this->parasut;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    /**
     * @return string
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    public function totalPage()
    {
        return
            [
                "page" => [
                    "number" => -1 #TODO: -1 vererek sayfa sınırlaması kaldırılıyor
                ]
            ];
    }

    #TODO: Paraşüt Kullanıcı Bilgisini Getiren Fonksiyon
    /**
     * @param string $customer
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSingleUser(string $customer)
    {
        try {
            $request = $this->getHttpClient()->request("GET", $this->getParasut()->getApiUrl() . "/v4" . "/" . $this->parasut->getCompanyId() . "/contacts" . "/" . $customer, [
                "headers" => [
                    "Accept" => "application/json",
                    "Content-Type" => "application/json",
                    "Authorization" => "BEARER " . $this->getToken()
                ],
                "body" => json_encode($this->totalPage(), true)
            ]);
            $result = json_decode($request->getBody()->getContents(), true);
            return $result;
        } catch (\GuzzleHttp\Exception\BadResponseException $exception) {
            $error = json_decode($exception->getResponse()->getBody()->getContents(), true);
            return $error;
        }
    }

    #TODO: Paraşüt Kullanıcı Kullanıcı Oluşturmak için Gerekli Data
    /**
     * @param array $postData
     * @return array[]
     */
    public function createParasutCustomerData(array $postData)
    {
        return
            [
                "data" => [
                    "type" => "contacts",
                    "attributes" => [
                        "email" => $postData["register"]['mail'],
                        "name" => $postData["register"]["name"],
                        "short_name" => $postData["register"]["name"],
                        "contact_type" => "person",
                        "tax_office" => $postData["register"]["name"],
                        "tax_number" => $postData["register"]["name"],
                        "district" => "Fatih",
                        "city" => "İstanbul",
                        "address" => "address",
                        "phone" => $postData["register"]["phone"],
                        "account_type" => "customer",
                    ],
                ]
            ];
    }


    #TODO: Paraşüt Kullanıcı Kullanıcı Oluşturmak için Gerekli Fonksiyon
    /**
     * @param array $postData
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createCustomer(array $postData)
    {
        try {
            $request = $this->getHttpClient()->request("POST", $this->getParasut()->getApiUrl() . "/v4" . "/" . $this->parasut->getCompanyId() . "/contacts", [
                "headers" => [
                    "Accept" => "application/json",
                    "Content-Type" => "application/json",
                    "Authorization" => "BEARER " . $this->getToken()
                ],
                "body" => json_encode($this->createParasutCustomerData($postData), true)
            ]);
            $result = json_decode($request->getBody()->getContents(), true);
            return $result;
        } catch (\GuzzleHttp\Exception\BadResponseException $exception) {
            $error = json_decode($exception->getResponse()->getBody()->getContents(), true);
            return $error;
        }
    }


    #TODO: Fatura Oluşrumak için Gereken Data
    public function createParasutSalesInvoicesData(object $postData)
    {
        if (is_null($postData->getOwner())) {
            $parasutCustomerId = $postData->getBuyerInfoDetail()->getParasutId();
        } else {
            $parasutCustomerId = $postData->getOwner()->getParasutId();
        }

        $totalQuantity = 0;
        $getSumOldPrice = 0;
        foreach ($postData->getOrderProducts()->toArray() as $product) {
            $totalQuantity += $product->getQuantity();
            $getSumOldPrice += $product->getOldPrice();
        }
        return
            [
                "data" => [
                    "type" => "sales_invoices",
                    "attributes" => [
                        "item_type" => "invoice",
                        "description" => "açıklama" . " - " . $postData->getId(),
                        "issue_date" => (new \DateTime())->format("Y-m-d H:i:s"),
                        "due_date" => (new \DateTime())->format("Y-m-d H:i:s"),
                        "invoice_series" => "test",
                        "invoice_id" => $postData->getId(),
                        "billing_address" => $postData->getBuyerInfoDetail()->getAddress(),
                        "billing_phone" => $postData->getBuyerInfoDetail()->getPhone(),
                        "country" => $postData->getBuyerInfoDetail()->getCountry(),
                        "city" => $postData->getBuyerInfoDetail()->getCity(),
                        "district" => $postData->getBuyerInfoDetail()->getDistrict(),
                        "shipment_addres" => $postData->getBuyerInfoDetail()->getAddress(),
                        "currency" => "TRL"
                    ],
                    "relationships" => [
                        "details" => [
                            "data" => [
                                [
                                    "type" => "sales_invoice_details",
                                    "attributes" => [
                                        "quantity" => $totalQuantity,
                                        "unit_price" => $getSumOldPrice,
                                        "vat_rate" => 18,
                                        "discount_type" => "percentage",
                                        "discount_value" => 0,
                                        "description" => "açıklama"
                                    ],
                                    "relationships" => [
                                        "product" => [
                                            "data" => [
                                                "id" => "{parasütProductId}",//parasütteki satılan ürün id si ile tanımlanmalıdır
                                                "type" => "products"
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        "contact" => [
                            "data" => [
                                "id" => $parasutCustomerId,
                                "type" => "contacts"
                            ]
                        ]
                    ]
                ]
            ];
    }



    #TODO: Paraşütte Satış Faturası Oluşturmak için Gerekli Fonksiyon
    /**
     * @param object $postData
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createParasutSalesInvoices(object $postData)
    {
        try {
            $request = $this->getHttpClient()->request("POST", $this->getParasut()->getApiUrl() . "/v4" . "/" . $this->parasut->getCompanyId() . "/sales_invoices", [
                "headers" => [
                    "Accept" => "application/json",
                    "Content-Type" => "application/json",
                    "Authorization" => "BEARER " . $this->getToken()
                ],
                "body" => json_encode($this->createParasutSalesInvoicesData($postData), true)
            ]);
            $result = json_decode($request->getBody()->getContents(), true);
            return $result;
        } catch (\GuzzleHttp\Exception\BadResponseException $exception) {
            $error = json_decode($exception->getResponse()->getBody()->getContents(), true);
            return $error;
        }
    }
}