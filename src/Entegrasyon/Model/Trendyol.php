<?php

namespace App\Entegrasyon\Model;


class Trendyol
{
    /**
     * @var string
     */
    private $apiUrl;

    /**
     * @var string
     */
    private $userName;

    /**
     * @var string
     */
    private $password;

    public function __construct()
    {
        $this->apiUrl = "https://api.trendyol.com/sapigw/suppliers/{supplierID}/orders";
        $this->userName = "{username}";
        $this->password = "{password}";
    }

    /**
     * @return string
     */
    public function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    /**
     * @param string $apiUrl
     */
    public function setApiUrl(string $apiUrl): void
    {
        $this->apiUrl = $apiUrl;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     */
    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
