<?php

/**
 * Description of Http_Client
 *
 * @author pablo
 */
class Http_Client {
    private $_ch;
    private $_headers;
    private $_responseHeaders;

    public function __construct($clientName = '', $headers = null) {
        $clientName = trim($clientName);
        if(!empty($clientName)) {
            $clientName .= ' ';
        }
        $this->_ch = curl_init();
        $version = curl_version();
        $this->_headers = array(
            "User-Agent: {$clientName}PHP/".phpversion()." curl/{$version['version']} ({$version['host']}) libcurl/{$version['version']} {$version['ssl_version']} zlib/{$version['libz_version']}",
            'Accept: */*',
            //'Accept: application/rss+xml,application/atom+xml,application/xml;q=0.9,*/*;q=0.8',
            //'Accept-Language: es-uy,es;q=0.8',
        );

        // seguir hasta 10 redirecciones
        curl_setopt($this->_ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->_ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($this->_ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($this->_ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->_ch, CURLOPT_HEADER, false);
        curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->_ch, CURLOPT_HEADERFUNCTION, array(&$this, '_readHeader'));
    }

    public function setReferer($url) {
        curl_setopt($this->_ch, CURLOPT_REFERER, $url);
    }

    public function setRequestHeader($headerName, $headerValue) {

    }
    
    public function get($url, $requestHeaders = array()) {
        $this->_responseHeaders = '';
        curl_setopt($this->_ch, CURLOPT_HTTPHEADER, array_merge($this->_headers, $requestHeaders));
        curl_setopt($this->_ch, CURLOPT_HTTPGET, true);
        curl_setopt($this->_ch, CURLOPT_URL, $url);
        return $this->_exec();
    }

    public function post($url, $data, $requestHeaders = array()) {
        $this->_responseHeaders = '';
        curl_setopt($this->_ch, CURLOPT_HTTPHEADER, array_merge($this->_headers, $requestHeaders));
        curl_setopt($this->_ch, CURLOPT_POST, true);
        curl_setopt($this->_ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($this->_ch, CURLOPT_URL, $url);
        return $this->_exec();
    }

    private function _exec() {
        $response = curl_exec($this->_ch);
        $curlInfo = curl_getinfo($this->_ch);
        if($curlInfo['http_code'])
            return new Http_Response($curlInfo['http_code'], $this->_responseHeaders, $response, $curlInfo);
        else
            return null;
    }

    private function _readHeader($ch, $header) {
//        $this->_responseHeaders .= $header;
        $header_parts = explode(':', $header, 2);
        if(count($header_parts) == 2)
            $this->_responseHeaders[strtolower(trim($header_parts[0]))] = trim($header_parts[1]);
        return strlen($header);
    }

    function  __destruct() {
        curl_close($this->_ch);
    }
}

class Http_Response {
    private $status, $headers, $body, $curlInfo;

    /**
     *
     * @param int $statusCode
     * @param array $headers
     * @param string $body
     */
    public function __construct($statusCode, $headers, $body, $curlInfo = null) {
        $this->status = $statusCode;
        $this->headers = $headers;
        $this->body = $body;
        $this->curlInfo = $curlInfo;
    }

    public function __get($attr) {
        if(array_key_exists($attr, $this->curlInfo)) {
            return $this->curlInfo[$attr];
        }
        return $this->{$attr} ? $this->{$attr} : null;
    }

    public function getAllHeaders() {
        return $this->headers;
    }

    function getHeader($header) {
        $header = strtolower($header);
        return array_key_exists($header, $this->headers) ? $this->headers[$header] : null;
    }

    public function __toString() {
        return $this->body;
    }
}




/******************************************************************************/
//
//$cliente = new HttpClient('Identificador/0.1');
//$r = $cliente->get('http://localhost/escritorio/fb');
//echo $r->status;
//echo PHP_EOL;
//echo $response->getHeader('Content-Type');
//echo $r;
//print_r($r->getAllHeaders());
//echo PHP_EOL;
//$r = $cliente->get('http://localhost/escritorio/fb', array('If-None-Match: "e6a12a-d52-4a36d70519480;4acc2f67f1880"'));
//echo $r;
//print_r($r->getAllHeaders());
//echo PHP_EOL;
////$r = $cliente->get('http://localhost/recibe', array('User-Agent: Qwerty'));
////echo $r;
////echo PHP_EOL;
////echo PHP_EOL;

/*
    [Content-Location] => recibe.php
    [Vary] => negotiate
    [TCN] => choice
    [X-Powered-By] => PHP/5.3.2-1ubuntu4.9
    [Transfer-Encoding] => chunked
    [Content-Type] => text/plain; charset=UTF-8
 */
