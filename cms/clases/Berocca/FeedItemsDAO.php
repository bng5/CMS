<?php

class Berocca_FeedItemsDAO extends DAO {

    public function __construct() {
        $this->db = DB::instance();
        $this->_insert_feedItem = $this->db->prepare("INSERT INTO feeds_items (`id_feed`, `title`, `link`, `pubDate`, `description`, `guid`, `guid_isPermaLink`, `content_encoded`) VALUES (:id_feed, :title, :link, :pubDate, :description, :guid, :guid_isPermaLink, :content_encoded)");
    }

    public function save($item) {

        $valores = array(
            ':id_feed' => $item->feedId,
            ':title' => (string) $item->title,
            ':link' => (string) $item->link,
            ':pubDate' => $item->pubDate->format("U"),
            ':description' => (string) $item->description,
            ':guid' => (string) $item->guid,
            ':guid_isPermaLink' => (int) ($item->guid->attributes()->isPermaLink == 'true'),
            ':content_encoded' => $item->content,
        );
        if($this->_insert_feedItem->execute($valores)) {
            $item->id = (int) $this->db->lastInsertId();
        }
        $this->_insert_feedItem->closeCursor();
    }

    public function load($item) {

        // gettype($item), get_class($item)
        
        if(is_subclass_of($item, 'Listado')) {

            if($item->username) {

                $usuario = Usuarios::obtenerPorUsuario($item->username);
                if(!$usuario) {
                    throw new Exception(sprintf("No existe el usuario %s.", $item->username), 1);
                }
                $item->total = $this->db->query("SELECT COUNT(*) FROM usuarios_a_feeds uaf, feeds_items fi JOIN feeds f ON fi.id_feed = f.id WHERE uaf.id_feed = f.id AND uaf.id_usuario = {$usuario->id}")->fetchColumn();
                $query = $this->db->query("SELECT fi.id, fi.title, fi.link, fi.pubDate, fi.description, f.title AS feed_title, f.link AS feed_link, f.description AS feed_description, content_encoded IS NULL AS contentNull, basico FROM usuarios_a_feeds uaf, feeds_items fi JOIN feeds f ON fi.id_feed = f.id WHERE uaf.id_feed = f.id AND uaf.id_usuario = {$usuario->id} ORDER BY fi.pubDate DESC LIMIT ".(($item->pagina - 1) * $item->rpp).", ".$item->rpp, PDO::FETCH_ASSOC);
            }
            else {
                $item->total = $this->db->query("SELECT COUNT(*) FROM feeds_items fi JOIN feeds f ON fi.id_feed = f.id WHERE f.basico = 1")->fetchColumn();
                $query = $this->db->query("SELECT fi.id, fi.title, fi.link, fi.pubDate, fi.description, f.title AS feed_title, f.link AS feed_link, f.description AS feed_description, content_encoded IS NULL AS contentNull, basico FROM feeds_items fi JOIN feeds f ON fi.id_feed = f.id WHERE f.basico = 1 ORDER BY fi.pubDate DESC LIMIT ".(($item->pagina - 1) * $item->rpp).", ".$item->rpp, PDO::FETCH_ASSOC);
            }

            if($item->total == 0) {
                throw new Exception("No hay noticias disponibles.", 1);
            }
//            $item->total = $this->db->query("SELECT COUNT(*) FROM feeds_items")->fetchColumn();

            //$pagina = intval($_GET['pagina']) ? intval($_GET['pagina']) : 1;
            $item->paginas = ceil($item->total / $item->rpp);

//            $stmt = $this->db->prepare("SELECT fi.id, fi.title, fi.link, fi.pubDate, fi.description, f.title AS feed_title, f.link AS feed_link, f.description AS feed_description, content_encoded IS NULL AS contentNull FROM feeds_items fi JOIN feeds f ON fi.id_feed = f.id ORDER BY fi.pubDate LIMIT ".(($item->pagina - 1) * $item->rpp).", ".$item->rpp);
//            $stmt->setFetchMode(PDO::FETCH_ASSOC);
//            $stmt->execute();
//            $item->setIterator($stmt);
            $item->setIterator($query);

            return $query;
        }

        //Berocca_FeedsList
    }
}
