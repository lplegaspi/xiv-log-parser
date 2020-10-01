<?php
class CurlManager
{
    const DEFAULT_RETURNTRANSFER = true;

    private $curl;

    public function __construct()
    {
        $this->curl = curl_init();
        $this->setDefault();
    }

    public function __destruct()
    {
        $this->close();
    }

    public function close()
    {
        if (is_resource($this->curl)) {
            curl_close($this->curl);
        }
    }

    public function setDefault()
    {
        $this->setOpt(CURLOPT_RETURNTRANSFER, self::DEFAULT_RETURNTRANSFER);
    }

    public function setUrl($url)
    {
        $this->setOpt(CURLOPT_URL, $url);
    }

    private function setOpt($option, $value)
    {
        curl_setopt($this->curl, $option, $value);

        return $this;
    }

    public function get($url)
    {
        $this->setOpt(CURLOPT_URL, $url);
        $this->setOpt(CURLOPT_HTTPGET, true);

        return $this->exec();
    }

    public function exec()
    {
        $rawResponse    = curl_exec($this->curl);
        $parsedResponse = json_decode($rawResponse, true);

        return $parsedResponse;
    }
}
