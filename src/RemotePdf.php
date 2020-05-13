<?php

namespace Meevo\RemotePdf;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use RuntimeException;

class RemotePdf
{
    public function request($view, $options = [])
    {
        $encrypter = app('remotepdf.encrypter');

        $config = app('config')->get('remote-pdf');

        $endpoint = $this->getEndpoint($config);
        $appKey = $this->getAppKey($config);

        $req = new Request(
            'POST',
            $endpoint,
            [
                'Content-Type' => 'application/json'
            ],
            \GuzzleHttp\json_encode([
                'key' => $appKey,
                'html' => base64_encode($encrypter->encrypt($view)),
                'options' => is_array($options) ? $options : [],
            ])
        );

        $client = new Client();
        $req = $client->send($req);


        try {
            $resp = \GuzzleHttp\json_decode((string) $req->getBody());
            $pdf = $encrypter->decrypt(base64_decode($resp->pdf));
        } catch (\InvalidArgumentException $e) {
            $pdf = '';
            report($e);
        }

        return $pdf;
    }

    protected function getEndpoint(array $config)
    {
        return tap($config['endpoint'], function ($key) {
            if (empty($key)) {
                throw new RuntimeException(
                    'No endpoint has been specified.'
                );
            }
        });
    }

    protected function getAppKey(array $config)
    {
        return tap($config['application_key'], function ($key) {
            if (empty($key)) {
                throw new RuntimeException(
                    'No application key for remote pdf has been specified.'
                );
            }
        });
    }
}
