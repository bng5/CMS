<?php

/**
 * 
 */
class Feeds_Rss implements Iterator {

    private $_id;
    private $_rss;
    private $_items;
    private $_k;

    /**
     *
     * @param SimpleXMLElement $xml 
     */
    public function  __construct(SimpleXMLElement $xml) {
        $this->_rss = $xml;
        $this->_rss->registerXPathNamespace('content', 'http://purl.org/rss/1.0/modules/content/');
        $this->_k = 0;
    }

    public function setId($id) {
        $this->_id = $id;
    }

    public function __get($attr) {
        $metodo = 'get'.ucfirst($attr);
        if(method_exists($this, $metodo)) {
            return $this->$metodo();
        }
        return $this->_rss->channel->{$attr};
    }

    public function getId() {
        return $this->_id;
    }

    public function getType() {
        return 'RSS';
    }
    
    public function getLastBuildDate() {
        //try {
            return new DateTime($this->_rss->channel->lastBuildDate);
        //} catch (Exception $exc) {
            //$console->error($exc->getMessage());
            //$console->groupEnd();
        //}
    }

    function rewind() {
        $this->_k = 0;
    }

    function current() {
        return new Feeds_RssItem($this->_rss->channel->item[$this->_k], $this);
    }

    function key() {
        return $this->_k;
    }

    function next() {
        ++$this->_k;
    }

    function valid() {
        return isset($this->_rss->channel->item[$this->_k]);
    }
}









/*******************************************************************************
*******************************************************************************/







class Feeds_RssItem {

    private $_id;
    private $_feedId;
    private $_rssItem;
    public function __construct(SimpleXMLElement $item, Iterator $feed) {

        $this->_feedId = $feed->id;
        $this->_rssItem = $item;
    }


    public function __get($attr) {
        $metodo = 'get'.ucfirst($attr);
        if(method_exists($this, $metodo)) {
            return $this->$metodo();
        }
        return $this->_rssItem->{$attr};
    }

    public function getId() {
        return $this->_id;
    }

    public function setId($id) {
        $this->_id = $id;
    }

    public function getFeedId() {
        return $this->_feedId;
    }

    public function getPubDate() {
        return new DateTime($this->_rssItem->pubDate);
    }

    public function getDescription() {
        $respuesta = $this->_rssItem->description;
        $respuesta = preg_replace('/<img src="http:\/\/feeds.feedburner.com\/[^"]+" height="1" width="1"\/\>$/', '', $respuesta);
        $respuesta = trim($respuesta);
        $respuesta = preg_replace('/^(<a href="http:\/\/[^"]+"><img border="0" src="http:\/\/[^"]+" alt="" \/><\/a>)<br\s*\/><br\s*\/>/', '$1', $respuesta);
        $respuesta = preg_replace('/<div class="feedflare">.*$/ms', '', $respuesta);
        //$respuesta = preg_replace('/<a href="http:\/\/feeds.feedburner.com\/[^"]+"><img src="http:\/\/feeds.feedburner.com\/[^"]+" border="0"><\/img><\/a> <a href="http:\/\/feeds.feedburner.com\/[^"]+"><img src="http:\/\/feeds.feedburner.com\/[^"]+" border="0"><\/img><\/a> <a href="http:\/\/feeds.feedburner.com\/[^"]+"><img src="http:\/\/feeds.feedburner.com\/[^"]+" border="0"><\/img><\/a> <a href="http:\/\/feeds.feedburner.com\/[^"]+"><img src="http:\/\/feeds.feedburner.com\/[^"]+" border="0"><\/img><\/a>$/', '', $respuesta);
        return $respuesta;
    }

    public function getContent() {
        $content_encoded = @$this->_rssItem->xpath('./content:encoded');
        return $content_encoded[0];
    }

}
