<?php

/**
 * Description of FeedsDAO
 *
 * @author pablo
 */
class Berocca_FeedsDAO extends DAO {

    private $_update_feed;
    
    public function __construct() {
        $this->db = DB::instance();
        $this->_update_feed = $this->db->prepare("UPDATE feeds SET `title` = :title, `url` = :url, `link` = :link, `description` = :description, `lastRequest` = :lastRequest, `charset` = :charset, `lastBuildDate` = :lastBuildDate, `HttpLastModified` = :HttpLastModified, `HttpETag` = :HttpETag WHERE id = :id LIMIT 1");
    }

    public function load(Listado $list) {
        $stmt = $this->db->query("SELECT * FROM feeds WHERE estado = 1 ORDER BY lastBuildDate ASC LIMIT {$list->rpp}", PDO::FETCH_OBJ);
        $list->setIterator($stmt);
//        $insert_feedItem = $db->prepare("INSERT INTO feeds_items (`title`, `link`, `pubDate`, `description`, `guid`, `guid_isPermaLink`, `content_encoded`) VALUES (:title, :link, :pubDate, :description, :guid, :guid_isPermaLink, :content_encoded)");
    }

    public function save($feed) {
        $this->_update_feed->execute(array(
            ':id' => $feed->id,
            ':title' => $feed->title,
            ':url' => $feed->url,
            ':link' => $feed->link,
            ':description' => $feed->description,
            ':lastRequest' => time(),
            ':charset' => $feed->charset,
            ':lastBuildDate' => $feed->lastBuildDate->format('U'),
            ':HttpLastModified' => $feed->HttpLastModified,
            ':HttpETag' => $feed->HttpETag,
        ));
        $this->_update_feed->closeCursor();
    }
}


/**

$db = new PDO('mysql:dbname=rss;host=localhost', 'pablo', 'popi');
$db->exec('SET CHARACTER SET utf8');


 */