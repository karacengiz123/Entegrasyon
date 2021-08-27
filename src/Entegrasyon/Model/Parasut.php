<?php

namespace App\Entegrasyon\Model;


class Parasut
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

    /**
     * @var string
     */
    private $client_id;

    /**
     * @var string
     */
    private $client_secret;

    /**
     * @var string
     */
    private $grant_type;

    /**
     * @var string
     */
    private $redirect_uri;

    /** @var string  */
    private $company_id;

    public function __construct()
    {
        $this->apiUrl = "https://api.parasut.com";
        $this->company_id = "{companiId}";
        $this->userName = "{userName}";//Mail de olabilir
        $this->password = "{password}";
        #TODO: AŞAĞIDAKİ BİLGİLERİ PARASÜT DESTEKTEN ALMANIZ GEREKİYOR
        $this->client_id = "{client_id}";
        $this->client_secret = "{client_secret}";
        $this->grant_type = "password";
        $this->redirect_uri = "urn:ietf:wg:oauth:2.0:oob";
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

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->client_id;
    }

    /**
     * @param string $client_id
     */
    public function setClientId(string $client_id): void
    {
        $this->client_id = $client_id;
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->client_secret;
    }

    /**
     * @param string $client_secret
     */
    public function setClientSecret(string $client_secret): void
    {
        $this->client_secret = $client_secret;
    }

    /**
     * @return string
     */
    public function getGrantType(): string
    {
        return $this->grant_type;
    }

    /**
     * @param string $grant_type
     */
    public function setGrantType(string $grant_type): void
    {
        $this->grant_type = $grant_type;
    }

    /**
     * @return string
     */
    public function getRedirectUri(): string
    {
        return $this->redirect_uri;
    }

    /**
     * @param string $redirect_uri
     */
    public function setRedirectUri(string $redirect_uri): void
    {
        $this->redirect_uri = $redirect_uri;
    }

    /**
     * @return string
     */
    public function getCompanyId(): string
    {
        return $this->company_id;
    }

    /**
     * @param string $company_id
     */
    public function setCompanyId(string $company_id): void
    {
        $this->company_id = $company_id;
    }


}
