<?php

/**
 * Items DTO
 *
 * @package CMS
 * @author pablo
 */
class DAO_Languages extends DAO {

    public function getAll() {
        $this->_db->query("SELECT id, codigo AS code, superior AS parent, dir, leng_poromision, estado, nombre_nativo FROM languages");
        

    }




}
