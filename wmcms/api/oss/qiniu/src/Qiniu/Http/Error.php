<?php
namespace Qiniu\Http;

final class Error
{
    private $url;
    private $response;
    public $error;

    public function __construct($url, $response)
    {
        $this->url = $url;
        $this->response = $response;
        $this->error = $response->error;
    }

    public function code()
    {
        return $this->response->statusCode;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function message()
    {
        return $this->response->error;
    }
}
