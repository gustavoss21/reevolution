<?php

class Request
{
    public $method, $body, $url, $headers;

    public function __construct($url, $data)
    {
        $this->url = $url;
        $this->setParams($data);
    }

    public function run()
    {
        $ch = curl_init();

        if ($this->method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->body));
        } elseif ($this->method === 'GET' && !empty($this->body)) {
            $this->url .= '?' . http_build_query($this->body);
        }

        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (!empty($this->headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        }

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public function setParams(array $params)
    {
        foreach ($params as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}
