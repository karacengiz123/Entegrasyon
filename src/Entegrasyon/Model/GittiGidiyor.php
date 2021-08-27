<?php

namespace App\Entegrasyon\Model;


class GittiGidiyor
{
    /**
     * @var string
     */
    private $apiUrl;

    /**
     * @var string
     */
    private $developerId;

    /**
     * @var string
     */
    private $userName;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $secretKey;

    public function __construct()
    {
        $this->apiUrl = "https://dev.gittigidiyor.com:8443/listingapi/ws";
        $this->developerId = "{DeveloperId}";
        $this->userName = "{userName}";
        $this->password = "{password}";
        #TODO: ALTTAKİ BİLGİLER CREATE APPLİCATİON FONKSİYONU SONRASI GELECEK OLAN BİLGİLER
        $this->apiKey = "{apiKey}";
        $this->secretKey = "{secretKey}";
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
     * @return $this
     */
    public function setApiUrl(string $apiUrl): self
    {
        $this->apiUrl = $apiUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getDeveloperId(): string
    {
        return $this->developerId;
    }

    /**
     * @param string $developerId
     * @return $this
     */
    public function setDeveloperId(string $developerId): self
    {
        $this->developerId = $developerId;

        return $this;
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
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
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
     * @return $this
     */
    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     * @return $this
     */
    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    /**
     * @param string $secretKey
     * @return $this
     */
    public function setSecretKey(string $secretKey): self
    {
        $this->secretKey = $secretKey;

        return $this;
    }
}
