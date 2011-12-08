<?php

/**
 * Description of Feeds
 *
 * @author pablo
 */
class Feeds {


    public static function fromXML(SimpleXMLElement $xml) {
        $rootName = $xml->getName();
        switch($rootName) {
            case 'rss':
                $fuente = new Feeds_Rss($xml);
                break;
//            case 'feed':
//                $fuente = new Feeds_Atom($xml);
//                break;
            default:
                throw new Exception('Formato: Desconocido', 1);
                break;
        }
        return $fuente;
    }
}

