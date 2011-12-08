<?php

/**
 * Users DTO
 *
 * @package CMS
 * @author pablo
 */
class DAO_Sections extends DAO {


    /**
     *
     */
    public function getAll($recursive = false, $parent_id = 0) {
        $stmt = $this->_db->query("SELECT * FROM secciones ORDER BY orden");
        $stmt->setFetchMode(DB::FETCH_ASSOC);
        $sections = array();
        foreach($stmt AS $v) {
            $k = array_shift($v);
            $sections[$k] = $v;
        }
        return $sections;
    }

    /**
     * Use getAll instead
     * 
     * @deprecated
     * @param boolean $recursive
     * @param int $parent_id
     */
    public function getSections($recursive = false, $parent_id = 0) {
        $this->getAll($recursive, $parent_id);
    }

    public function loadLangTexts(&$sections, $lang) {
        //$sections
        $stmt = $this->_db->query("SELECT * FROM secciones_nombres WHERE leng_id = {$lang->id}");
        $stmt->setFetchMode(DB::FETCH_ASSOC);
        foreach($stmt AS $row) {
            $k = array_shift($row);
            $sections[$k][$lang->code] = $row;
        }
    }

    public function getById($id) {

        //var_dump($langs->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC));


		$stmt = $this->_db->prepare("SELECT * FROM secciones WHERE id = ? LIMIT 1");
        $stmt->execute(array($id));
        $stmt->setFetchMode(DB::FETCH_CLASS, 'DTO_Section');//, array $ctorargs )
//        foreach($stmt AS $sect) {
//            $id = $sect->id;
//            //$sect->
//            $section[$id][$lan] = '';
//        }
		if($section = $stmt->fetch()) {
            $langsStmt = $this->_db->prepare("SELECT sn.id, sn.leng_id, l.codigo, sn.titulo, sn.url FROM secciones_nombres sn LEFT JOIN languages l ON sn.leng_id = l.id WHERE sn.id = ?");
            $langsStmt->execute(array($id));
            foreach($langsStmt AS $lang) {
                $section->setTitle($lang['titulo'], $lang['codigo']);
                $section->setUrl($lang['url'], $lang['codigo']);
//      'id' => string '2' (length=1)
//      'leng_id' => string '1' (length=1)
//      'codigo' => string 'es' (length=2)
//      'titulo' => string 'Novedades' (length=9)
//      'url' => string 'novedades' (length=9)

            }
			return $section;
        }
		else
			return false;
    }

}
