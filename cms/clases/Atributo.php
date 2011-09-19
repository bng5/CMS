<?php

class Atributo
 {
  const TEXTO_LINEA = 1;// "Campo de texto"
  const COLOR = 2;// "Color"
  const CLAVE = 3;// "Contraseña"
  const FECHA_HORA = 4;// "Fecha y hora"
  //const FECHA = 5;// "Fecha"
  const NUM_NATURAL = 6;// "Número natural (ℕ)"
  const DATO_EXTERNO = 7;// "Dato externo"
  const IMAGEN = 8;// "Imagen"
  const ARCHIVO = 9;// "Archivo"
  const GALERIA_IMGS = 10;// "Galería de imágenes"
  //const HORA = 11;

  const TEXTO = 15;// "Texto"
  const PRECIO = 16;// "Precio"
  const NUM_ENTERO = 17;// "Número entero (ℤ)"

  const AREA = 19;// "Área"

  const ENLACE = 22;// "Enlace externo (dato)"
  const FORMULARIO = 23;// "Formulario"

  const NUM_DECIMAL = 25;// "Número decimal"
  
  const VIDEO_YOUTUBE = 27;// "YouTube Video"

  //obsoletos
  const TEXTO_LINEA_NO_LENG = 21;


//const 11;// "Radio"
//const 12;// "Checkbox"
//const 13;// "Selector múltiple"
//const 14;// "Selector"
//const 18;// "Rango"
//const 20;// "Alineación asociativa"
//const 21;// "Campo de texto (no leng)"
//const 24;// "Texto con formato"
//const 26;// "Enlace"

/*
id		tipo			subtipo		op_listado	op_oculto	nodo_tipo	estado_id	nombre
1		"string"					1			0			1			1			"Campo de texto"
2		"string"		1			1			0			1			1			"Color"
3		"string"		2			0			0			1			0			"Contraseña"
4		"date"						1			0			1			1			"Fecha y hora"
5		"date"			1			1			0			1			1			"Fecha"
6		"int"						1			1			1			1			"Número natural (ℕ)"
7		"int"			1			1			1			1			1			"Dato externo"
8		"int"			2			1			0			2			1			"Imagen"
9		"int"			3			1			0			2			1			"Archivo"
10		"int"			4			0			0			2			1			"Galería de imágenes"
11		"int"			8			1			1			1			1			"Radio"
12		"string"		4			1			0			1			1			"Checkbox"
13		"string"		3			1			0			1			0			"Selector múltiple"
14		"int"			5			1			1			1			1			"Selector"
15		"text"						1			0			1			1			"Texto"
16		"num"						1			1			1			1			"Precio"
17		"num"			1			1			1			1			1			"Número entero (ℤ)"
18		"int"			6			1			0			1			0			"Rango"
19		"int"			7			0			0			3			1			"Área"
20		"string"		5			1			0			1			0			"Alineación asociativa"
21		"string"		6			1			0			1			1			"Campo de texto (no leng)"
22		"text"			1			1			0			1			1			"Enlace externo (dato)"
23		"int"			9			0			0			3			1			"Formulario"
24		"text"			2			1			0			1			1			"Texto con formato"
25		"num"			2			1			1			1			1			"Número decimal"
26		"text"			3			1			0			2			1			"Enlace"
27		"string"		7			0			0			4			1			"YouTube Video"
*/

  /* niveles
   *  1 no puede contener
   *  2 puede contener
   *  3 es netamente contenedor
   */

  static $topos = "

Un string

";
  static $tipos = array(
	   self::TEXTO_LINEA 	=> array('almacen' => 'string', 'bloque' => 1),
	   self::COLOR 			=> array('almacen' => 'string', 'bloque' => 1),
	   self::CLAVE			=> array('almacen' => 'string', 'bloque' => 1),
	   self::FECHA_HORA		=> array('almacen' => 'date', 'bloque' => 1),
	   self::NUM_NATURAL	=> array('almacen' => 'int', 'bloque' => 1),
	   self::DATO_EXTERNO	=> array('almacen' => 'int', ),
	   self::IMAGEN			=> array('almacen' => 'int', 'bloque' => 2),
	   self::ARCHIVO		=> array('almacen' => 'int', 'bloque' => 2),
	   self::GALERIA_IMGS	=> array('almacen' => 'int', 'bloque' => 3),
	   self::TEXTO			=> array('almacen' => 'text', 'bloque' => 1),
	   self::PRECIO			=> array('almacen' => array('num', 'int'), 'bloque' => 1),
	   self::NUM_ENTERO		=> array('almacen' => 'int', 'bloque' => 1),
	   self::AREA			=> array('almacen' => 'int', 'bloque' => 3),
	   self::ENLACE			=> array('almacen' => 'text', ),
	   self::FORMULARIO		=> array('almacen' => 'int', 'bloque' => 3),
	   self::NUM_DECIMAL	=> array('almacen' => 'num', 'bloque' => 1),
	   self::VIDEO_YOUTUBE	=> array('almacen' => 'string', 'bloque' => 1),
   );
  static $noenlistado = array(AREA => true, GALERIA_IMGS => true);

/*
 * sugerido
 *  0 no
 *  1 si
 *  2 obligatorio
 */
/*
 * unico
 *  0 no
 *  1 si
 *  2 multilingüe
 */
  public $id, $identificador, $sugerido, $unico, $tipo_id, $extra, $formato, $seccion_id, $orden, $por_omision, $en_listado, $salida, $superior, $su;
  public $etiquetas = array();
/*
  function getEtiquetas($idioma = false)
   {
	if(count($this->etiquetas))
	 {
	  return
	 }
	$db = BaseDatos::Conectar();
   }

  function setEtiqueta($etiqueta, $idioma)
   {
	$this->etiquetas[$idioma] = $etiqueta;
   }
*/
 }

?>