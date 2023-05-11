<?php

namespace shophy\tclexiang;

use shophy\tclexiang\helper\Utils;

class Api
{
	const VERSION = 'v1';
	const MAIN_URL = 'https://lxapi.lexiangla.com/cgi-bin';

	protected $appKey;
	protected $appSecret;
	protected $accessToken = '';

	protected $staffId;
    protected $listeners;

    protected $response;

    public function __construct($app_key = '', $app_secret = '')
    {
    	Utils::checkNotEmptyStr($appKey, "app_key");
        Utils::checkNotEmptyStr($appSecret, "app_secret");

        $this->appKey = $app_key;
        $this->appSecret = $app_secret;
    }
}