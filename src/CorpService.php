<?php

namespace shophy\tclexiang;

use GuzzleHttp\Psr7\Request;
use Http\Adapter\Guzzle6\Client;
use WoohooLabs\Yang\JsonApi\Client\JsonApiClient;
use WoohooLabs\Yang\JsonApi\Response\JsonApiResponse;

abstract class CorpService extends Api
{
    use traits\NotifyTrait;

	const MAIN_URL = 'https://lxapi.lexiangla.com/cgi-bin/service';
    const SUITE_TICKET_PREFIX = 'LX-SUITE-TICKET-';
    const SUITE_ACCESS_TOKEN_PREFIX = 'LX-SUITE-ACCESS-TOKEN-';

    protected $suite_id;
    protected $suite_secret;
    protected $suite_ticket;
    protected $suite_access_token = '';
    protected $corp_access_tokens = [];

    public function __construct($suite_id, $suite_secret)
    {
        helpers\Utils::checkNotEmptyStr($suite_id, "suite_id");
        helpers\Utils::checkNotEmptyStr($suite_secret, "suite_secret");

        $this->suite_id = $suite_id;
        $this->suite_secret = $suite_secret;
    }

    public function getSuiteTicket()
    {
        if ($this->suite_ticket) {
            return $this->suite_ticket;
        }

        $this->suite_ticket = $this->getCache(self::SUITE_TICKET_PREFIX . $this->suite_id);
        if ($this->suite_ticket) {
            return $this->suite_ticket;
        }

        throw new exceptions\ApiException('无效的suite_ticket');
    }

    public function setSuiteTicket($suite_ticket)
    {
        $this->suite_ticket = $suite_ticket;
        $this->setCache(self::SUITE_TICKET_PREFIX . $this->suite_id, $this->suite_ticket, 600);
    }

    public function getSuiteToken()
    {
        if ($this->suite_access_token) {
            return $this->suite_access_token;
        }

        $cache_key = self::SUITE_ACCESS_TOKEN_PREFIX . $this->suite_id;
        $this->suite_access_token = $this->getCache($cache_key);
        if ($this->suite_access_token) {
            return $this->suite_access_token;
        }

        $options = ['json' => [
            'grant_type' => 'client_credentials',
            'suite_id' => $this->suite_id,
            'suite_secret' => $this->suite_secret,
            'suite_ticket' => $this->getSuiteTicket(),
        ]];
        $client = new \GuzzleHttp\Client();
        $response = $client->post(self::MAIN_URL . '/get_suite_token', $options);
        $response = json_decode($response->getBody()->getContents(), true);
        $this->suite_access_token = $response['suite_access_token'];
        $this->setCache($cache_key, $this->suite_access_token, $response['expires_in']);
        return $this->suite_access_token;
    }

    public function getCorpAccessToken($company_id, $permanent_code)
    {
        if (isset($this->corp_access_tokens[$company_id]) && $this->corp_access_tokens[$company_id]) {
            return $this->corp_access_tokens[$company_id];
        }

        $options = ['json' => [
            'grant_type' => 'client_credentials',
            'company_id' => $company_id,
            'permanent_code' => $permanent_code,
        ]];
        $client = new \GuzzleHttp\Client();
        $response = $client->post(self::MAIN_URL . '/get_corp_token?suite_access_token=' . $this->getSuiteToken(), $options);
        $response = json_decode($response->getBody()->getContents(), true);
        $this->corp_access_tokens[$company_id] = $response['access_token'];
        return $this->corp_access_tokens[$company_id];
    }

    public function getCorpPermanentCode($auth_code)
    {
        $options = ['json' => [
            'auth_code' => $auth_code,
        ]];
        $client = new \GuzzleHttp\Client();
        $response = $client->post(self::MAIN_URL . '/get_permanent_code?suite_access_token=' . $this->getSuiteToken(), $options);
        return json_decode($response->getBody()->getContents(), true);
    }

    public function getCorpAdminList($company_id)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->get(self::MAIN_URL . '/get_admin_list?suite_access_token=' . $this->getSuiteToken() . '&company_id=' . $company_id);
        return json_decode($response->getBody()->getContents(), true);
    }

    public function getUserInfoByCode($code)
    {
        $options = ['json' => [
            'code' => $code,
        ]];
        $client = new \GuzzleHttp\Client();
        $response = $client->post(self::MAIN_URL . '/get_user_info?suite_access_token=' . $this->getSuiteToken(), $options);
        return json_decode($response->getBody()->getContents(), true);
    }
}
