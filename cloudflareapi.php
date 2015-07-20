<?php

Class CloudFlareApi {

    protected $url = 'https://api.cloudflare.com/client/v4/';

    protected $email = '';
    protected $key = '';
    
    public function __construct($email, $key) {
        $this->email = $email;
        $this->key = $key;
    }

    public function call($method, $params = array(), $m = 'GET') {
        $content = $this->request($this->url . str_replace(".", "/", $method), $params, $m);
        $data = json_decode($content, true);
        return $data;
    }

    public function request($url, $data = array(), $method = 'GET') {
        if (function_exists('curl_init'))
            return $this->curl($url, $data, $method);
        else
            return $this->getcontents($url, $data, $method);
    }

    protected function getHeaders() {
        return array(
            'Content-Type:application/json',
            'X-Auth-Key:' . $this->key,
            'X-Auth-Email:' . $this->email
        );
    }


    protected function curl($url, $data = array(), $method = 'GET') {
        $d = curl_version();
        $curl = curl_init();

        if ($d['ssl_version_number'] == 0)
            return $this->getcontents($url, $data, $method);

        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->getHeaders());

        if ($method == 'POST' || $method == 'PUT' || $method == 'DELETE') {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            if ($method == 'POST')
                curl_setopt($curl, CURLOPT_POST, true);
            if ($method == 'PUT') {
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                //curl_setopt($curl, CURLOPT_PUT, true);
            }

            if ($method == 'DELETE')
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");

            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            $out = curl_exec($curl);
            curl_close($curl);
        } else if ($method == 'GET') {
            curl_setopt($curl, CURLOPT_URL, $url . "?" .http_build_query($data));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            $out = curl_exec($curl);
            //$err = curl_errno($curl);
            //$errmsg = curl_error($curl);
            //d(curl_version());
            curl_close($curl);
        }

        return $out;
    }

    protected function getcontents($url, $data = array(), $method = 'GET') {
        set_time_limit(300);
        if ($method == 'POST' || $method == 'PUT' || $method == 'DELETE') {
            $http = array(
                'method' => $method,
                'content' => json_encode($data)
            );

            
            $http['header'] = implode("\r\n", $this->getHeaders());
            $context = stream_context_create(array('http' =>
                $http
            ));
            $out = @file_get_contents($url, false, $context);
        }

        if ($method == 'GET') {

            $opts = array(
                'http' => array(
                    'method' => "GET",
                    'header' => implode("\r\n", $this->getHeaders())
                )
            );

            $context = stream_context_create($opts);
            $out = @file_get_contents($url . "?" . http_build_query($data), false, $context);
        }

        return $out;
    }

}
