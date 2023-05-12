<?php

namespace shophy\tclexiang\traits;

trait NotifyTrait
{
    protected $callback_secret;

    public function setCallbackSecret($callback_secret)
    {
        helpers\Utils::checkNotEmptyStr($callback_secret, "callback_secret");
        $this->callback_secret = $callback_secret;
    }

    public function verifyMessage($suite_id, $nonce, $timestamp, $sign)
    {
        if ($suite_id != $this->suite_id) {
            throw new exceptions\ArgumentException('无效的suite_id', $suite_id);
        }
        if ($sign != sha1($nonce . $this->callback_secret . $timestamp)) {
            throw new exceptions\ArgumentException('无效的签名', $sign);
        }
    }

    public function handleMessage($action, $attributes)
    {
        set_time_limit(0);
        ignore_user_abort();
        function_exists('fastcgi_finish_request') && fastcgi_finish_request();
        
        if ($action === 'service/suite_ticket') {
            if (isset($attributes['suite_ticket'])) {
                $this->setSuiteTicket($attributes['suite_ticket']);
            }
        } else {
            $method = str_replace('/', '_', $action);
            if (method_exists($this, $method)) {
                $this->$method();
            }
        }
    }
}
