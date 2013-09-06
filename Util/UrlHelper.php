<?php

namespace Voltash\FbApplicationBundle\Util;

class UrlHelper
{
    private $appConfig;

    public function __construct(array $config)
    {
        $this->appConfig = $config;
    }

    public function encode(array $data)
    {
        $encodedData = strtr(base64_encode(json_encode($data)), '+/=', '-_,');
        return $encodedData;
    }

    public function decode($data)
    {
        $decodedData = null;
        if (!is_null($data))
            $decodedData = base64_decode(strtr(json_decode($data, false),'-_,', '+/='));
        return $decodedData;
    }

    public function parsePageSignedRequest()
    {
        if (isset($_REQUEST['signed_request']))
        {
            $encoded_sig = null;
            $payload = null;
            list($encoded_sig, $payload) = explode('.', $_REQUEST['signed_request'], 2);
            $sig = base64_decode(strtr($encoded_sig, '-_', '+/'));
            $data = json_decode(base64_decode(strtr($payload, '-_', '+/'), true));

            $expected_sig = hash_hmac('sha256', $payload, $this->appConfig['secret'], $raw = true);
            if ($sig !== $expected_sig)
            {
                error_log('Bad Signed JSON signature!');
                die('Bad Signed JSON signature!');
            }
            return $data;
        }
        return false;
    }
}