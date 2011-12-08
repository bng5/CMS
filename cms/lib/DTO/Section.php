<?php

/**
 * Description of User
 *
 * @author pablo
 */
class DTO_Section extends DTO {

    protected $_valores = array(
        'superior_id' => 0,
        'orden' => null,
        'identificador' => '',
        'info' => 0,
        'items' => 0,
        'items_anidados' => 0,
        'categorias' => 0,
        'categorias_prof' => 0,
        'salida_sitio' => 1,
        'menu' => 1,
        'rev' => null,
        'propietario' => null,
        'grupo' => null,
        'permiso_grupo' => null,
        'title' => array(),
        'url' => array(),
	);


/*****
$array = array(
      'superior_id' => '0',
      'orden' => '2',
      'identificador' => 'novedades',
      'info' => '0',
      'items' => '1',
      'items_anidados' => '0',
      'categorias' => '0',
      'categorias_prof' => '0',
      'salida_sitio' => '1',
      'menu' => '0',
      'rev' => '1',
      'propietario' => '0',
      'grupo' => '0',
      'permiso_grupo' => '0',
      'title' => array(
          ['es'] =>

      ),
);
*****/

    protected function _nuevo($a, $b = '') {
        var_dump($a, $b);
    }

    public function setTitle($value, $lang_code = '') {
        $this->_valores['title'][$lang_code] = $value;
        return $this;
    }

    public function setUrl($value, $lang_code = '') {
        $this->_valores['url'][$lang_code] = $value;
        return $this;
    }

}
