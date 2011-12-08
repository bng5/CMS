<?php

/**
 * Description of Document
 *
 * @author pablo
 */
class View_Admin_Document extends View_Admin {

    public function __construct() {
        $this->cms = CMS::getInstance();
    }


    public $title;
    public $lang;
    //private $_children;
    private $_config = array();

    public function show() {
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="'.$this->lang->code.'" lang="'.$this->lang->code.'">
<head>
 <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
 <title>'.$this->title.' - '.SITIO_TITULO.'</title>
 <link rel="stylesheet" type="text/css" href="/themes/bng5/style.css" />

'.$this->_head.'
';
		//$this->incluirCSS();
		//$this->incluirJS();












/******************************************************************************/
?>
</head>
<body>
<noscript><div id="no_js_alerta"><img src="/img/warning" alt="" /> Para visualizar correctamente este sitio su navegador debe contar con <b>JavaScript</b> habilitado.</div></noscript>
<div id="cabezal_der">
 <div id="cabezal_izq">
  <div id="cabezal">
   <em><?php echo SITIO_TITULO; ?></em>
   <div id="finalizarses">
        <?php
            $session = Session::getCurrentSession();
            //echo '<b>'.Session::getCurrentSession()->user['username'].'</b>&nbsp;[<a href="'.$this->cms->uri->link(array('controller' => 'users', )).'">Mi cuenta</a>&nbsp;-&nbsp;<form action="'.$this->cms->uri->link(array('controller' => 'login')).'" method="post"><input type="hidden" name="action" value="salir" /><a onclick="this.parentNode.submit()">Finalizar sesi&oacute;n</a></form>]';
            echo '<b>'.$session->user['username'].'</b>&nbsp;[<a href="'.$this->cms->uri->link(array('controller' => 'users', 'action' => 'edit', 'params' => array('id' => $session->user['id']))).'">Mi cuenta</a>&nbsp;-&nbsp;<a href="'.$this->cms->uri->link(array('controller' => 'login', 'params' => array('action' => 'salir'))).'">Finalizar sesi&oacute;n</a>]';
            /* <a href=\"cuenta?sesion=".$sesion."\">Cuenta</a> */
        ?>
   </div>
  </div>
 </div>
</div>
<div id="contenedor">
<?php

if($this->_config['menu'] && $this->sections) {

    $uri_params = array('controller' => 'items', 'params' => array());
    echo '
	<div id="menu">
        <ul>';
    foreach($this->sections AS $k => $sect) {

        $uri_params['params']['section'] = $k;
        echo "<li>";
        if($sect['items'] || $sect['info']) {
            $uri_params['controller'] = $sect['items'] ? 'items' : 'info';
            echo "<a href=\"".$this->cms->uri->link($uri_params)."\">{$sect[$this->cms->lang->code]['titulo']}</a>";
        }
        else
            echo $sect[$this->cms->lang->code]['titulo'];
        echo "</li>";
    }
    echo '
        </ul>
    </div>';

}

/******************************************************************************
////echo ">".$_SESSION['admin_secciones']."</div><div";
//$incluir = "iacache/menu".md5($_SESSION['admin_secciones']).".php";
//if(!@include($incluir))
//	include("templates/iamenu.php");
$CMSsecciones = new adminsecciones();
$CMSsecciones_nombres = $CMSsecciones->secciones_arr;//var_export($secciones->ssecciones_arr, false);
adminsecciones::$CMSsecciones_nombres_d = var_export($CMSsecciones->secciones_arr, true);

function guardar_encabezado($bufer) {
  //global $incluir, $CMSsecciones_nombres_d
  global $seleccionado, $seleccionado_id;//, $seleccionadoUlt;
  $menu_hash = md5($_SESSION['admin_secciones']);
  Session::getCurrentSession()->menuHash = $menu_hash;
  $outfile = fopen(RUTA_CARPETA."iacache/menu".$menu_hash.".php", "w");
  if($outfile) {
		$et_php = array("'.", ".'");
		$et_php_remp = array("<?php echo ", " ?>");
		fwrite($outfile, "<?php\n\$seleccionado[\$seccion_id] = \"activo \";\n\$seleccionado_id[\$seccion_id] = \" id=\\\"menu_activo\\\"\";\n\$secciones_nombres = ".adminsecciones::$CMSsecciones_nombres_d.";\n?>\n".str_replace($et_php, $et_php_remp, $bufer));
		fclose($outfile);
   }
  eval("\$bufer = '$bufer';");
  return $bufer;
 }

ob_start();//"guardar_encabezado");
$seleccionado[$seccion_id] = "activo ";
$seleccionado_id[$seccion_id] = " id=\"menu_activo\"";
//$seleccionadoUlt[$seccion_id] = " seleccionado";
echo $CMSsecciones->imprimir();
ob_end_flush();

******************************************************************************/



?>

 <div id="contenido">
     <h1><?php echo $this->section->title[$this->cms->lang->code]; ?></h1>
  <h1><?php

if($titulo_nav)
 {
  for($nav = 0; $nav < count($titulo_nav); $nav++)
   {
    if(!$link_nav)
     { $mst_link_nav = $seccion; }
    elseif(is_array($link_nav))
     { $mst_link_nav = $link_nav[$nav]; }
    elseif(is_string($link_nav))
     { $mst_link_nav = $link_nav; }
    echo "<a href=\"/".$mst_link_nav."?sesion=".$sesion.$extra_nav."\" target=\"_top\">".$titulo_nav[$nav]."</a>&nbsp;&gt;&nbsp;";
   }
 }
echo $seccion_id ? $secciones_nombres[$seccion_id] : $titulo;
echo "</h1>
<div id=\"div_mensaje\"";
if(!$div_mensaje)
	echo " style=\"display:none;\"";
echo ">{$div_mensaje}&nbsp;</div>

<!-- /encabezado -->


";




/******************************************************************************/














		$seccion_id = $this->_seccion->id;
		//include('./vistas/iaencab.php');
		//echo $this->_mostrarMenu();

        $this->showChildren();

        //else {
		//	echo '<tr><td>No existe ningún campo. <a href="/configuracion?seccion=${seccion_id}">Configuración de items</a></td></tr>';
		//}
		echo '
<!-- pie -->
  <div class="separador"></div>
 </div>
</div>
</body>
</html>';
    }

    /**
     * @todo
     * @param string $attr
     * @param mixed $value
     */
    public function setConfig($attr, $value) {
        $this->_config[$attr] = $value;
    }
}








/******************************************************************************/


// FIXME
class adminsecciones {
	public static $CMSsecciones_nombres_d;

	public function __construct() {
		global $seccion_id;
		$this->seccion = $seccion_id ? $seccion_id : false;
		$this->separador_niv = "  ";
		$this->secciones = array();
		$this->superior = array();
		$this->actual_superior = array();
		$db = DB::instance();

		$tbsubcat = $db->query("SELECT s.id, sn.titulo, s.superior_id, s.link_cms, s.link_cms_params, s.info, s.items, s.identificador FROM usuarios_permisos up, `secciones` s LEFT JOIN secciones_nombres sn ON s.id = sn.id AND sn.leng_id = 1 WHERE up.item_id = s.id AND up.usuario_id = ".Session::getCurrentSession()->user['id']." GROUP BY s.id ORDER BY s.`superior_id`, s.`sistema`, s.`orden`", DB::FETCH_ASSOC);// or die(mysql_error());  WHERE sistema != 0 OR (info != 0 OR items != 0)
		if($row_subcat = $tbsubcat->fetch()) {
			do {
				$row_subcat['nombre'] = $row_subcat['titulo'] ? $row_subcat['titulo'] : $row_subcat['identificador'];
				$this->secciones_arr[$row_subcat['id']] = $row_subcat['nombre'];
				$this->superior[$row_subcat['id']] = $row_subcat['superior_id'];
				$this->secciones[$row_subcat['superior_id']][$row_subcat['id']] = $row_subcat;
			}while($row_subcat = $tbsubcat->fetch());
		}
	}

	public function imprimir($subseccion = false, $nivel = 0) {
		if($subseccion == false)
			$subseccion = current($this->secciones);
		//echo "\n".str_repeat($this->separador_niv, $nivel)."><ul";
		$retorno = str_repeat($this->separador_niv, $nivel)."<ul>";
		$pasan = 0;
		foreach($subseccion AS $a) {
			//if(!array_key_exists($a['id'], $_SESSION['permisos']['admin_seccion']))
			//	continue;// && $nivel == 0
			$link = $a['link_cms'];
			$eslink = true;
			if($link == "listar") {
				$link .= "?seccion={$a['id']}";
				$eslink = (!$a['info'] && !$a['items']) ? false : true;
			}
			//$link .= $a['link_param'] ? "?".$a['link_param'] : "";
			$retorno .= str_repeat($this->separador_niv, $nivel)."<li class=\"'.\$seleccionado[{$a['id']}].'";
			if(is_array($this->secciones[$a['id']])) {
				array_shift($this->actual_superior);
				if($retorno_r = $this->imprimir($this->secciones[$a['id']], ++$nivel)) {
					$despleg = true;
				}
				$nivel--;
			}
			$retorno .= ($despleg ? "desplegable" : "simple")."\"'.\$seleccionado_id[{$a['id']}].'><span".($despleg ? " onclick=\"menuDesplegar(this)\"" : "")."><a".($eslink ? " href=\"".APU.$link."\"" : "").">{$a['nombre']}</a></span>{$retorno_r}</li>";
			$despleg = false;
			$retorno_r = false;
			$pasan++;
		}
		return $pasan ? $retorno.str_repeat($this->separador_niv, $nivel)."</ul>\n" : false;
	}

	public function __toString() {
		return $this->titulo ? $this->titulo : "Título";
	}
}

