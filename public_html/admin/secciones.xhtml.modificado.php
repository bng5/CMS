<?php

//$seccion_id = 2;
require('inc/iniciar.php');
require('inc/ad_sesiones.php');

$seccion = Secciones::obtenerPorIdentificador('__secciones');

$ia = $_REQUEST['ia'];
if($_GET['nueva'])
  $div_mensaje = 'La sección ha sido agregada correctamente.';

$id = (int) $_REQUEST['id'];

if($_SERVER['REQUEST_METHOD'] == 'POST')
 {
  //lista_item%5B%5D=1&mult_submit%5Beliminar%5D=Eliminar
  // Submit múltiple
  if($_POST['mult_submit'] && $_POST['lista_item'])
   {
/*
    $modificar = $_POST['lista_item'];
    $modificadas = 0;
    $mult_submit = key($_POST['mult_submit']);
    // borrar
    if($mult_submit == 'eliminar')
     {
      $modificacion_tipo_accion = "eliminadas";
	  $secc = new Seccion();
      foreach($modificar AS $modificari)
       {
		$secc->setId($modificari);
		$secc->Eliminar();
        if($mysqli->affected_rows == 1)
         {
          $modificadas++;
		  // (trigger)
		  
		  eliminar_cache($modificari);
		  //@unlink(RUTA_CARPETA."iacache/menu".md5($_SESSION['admin_secciones']).".php");
		  unset($_SESSION['permisos']['admin_seccion'][$modificari]);
		  $_SESSION['admin_secciones'] = "-".implode("-", array_keys($_SESSION['permisos']['admin_seccion']))."-";
         }
       }
     }
    // habilitar
    elseif($mult_submit == 'habilitar')
     {
      $modificacion_tipo_accion = "habilitadas";
      for($i = 0; $i < count($modificar); $i++)
       {
        //TODO
        if($mysqli->affected_rows)
          $modificadas++;
       }
     }
    // deshabilitar
    elseif($mult_submit == 'deshabilitar')
     {
      $modificacion_tipo_accion = "deshabilitadas";
      for($i = 0; $i < count($modificar); $i++)
       {
        //TODO
        if($mysqli->affected_rows)
          $modificadas++;
       }
     }

    if($modificadas > 0)
     {
      $div_mensaje = "Secciones ".$modificacion_tipo_accion.": ".$modificadas;
      include('./secciones_const.php');
     }
    */
   }
  else
   {
    $_seccion = new Seccion($_POST['identificador']);
    $_seccion->setId($_POST['id']);
    $_seccion->setRevision($_POST['rev']);
    $_seccion->setSuperior($_POST['superior']);
    $_seccion->setInfo($_POST['info']);
    $_seccion->setItems($_POST['items']);
    $_seccion->setCategorias($_POST['categorias']);
    $_seccion->setCategorias_prof($_POST['prof_categorias']);
    $_seccion->setPublicacion($_POST['salida']);
    $_seccion->setMenu($_POST['enmenu']);
    $_seccion->setNombres($_POST['nombre']);
    try
     {
	  $guardar = $_seccion->Guardar();
	  if(is_int($guardar))
	   {
	    header("Location: /secciones?id=".$guardar."&nueva=1");
	    exit(" ");
	   }
	  elseif($guardar == true)
	    $div_mensaje = 'Los cambios fueron guardados correctamente.';
     }
    catch(Exception $e)
     {
	  $id = null;
	  switch($e->getCode())
	   {
		case 1:
		  $div_mensaje = sprintf('Debe especificar un valor para el campo \'%s\'.', $e->getMessage());
		  break;
		case 1062: // mysql: campo duplicado
		  $div_mensaje = sprintf('El identificador \'%s\' ya está en uso.', $_seccion->getIdentificador());
		  break;
		default:
		  $div_mensaje = "Ha ocurrido un error inesperado.";
		  new Excepcion($div_mensaje, 0, $e);
		  break;
	   }
     }
   }
 }

if($id)
 {
  try
   {
    $_seccion = Seccion::carga($id);
   }
  catch(Exception $e)
   {
	echo $e->getMessage();
   }
  $transaccion_txt = "Editar Sección";
 }
else
 {
  $transaccion_txt = "Aregar Sección";
  if($ia == 'agregar')
   {
    $_seccion = new Seccion();
    $_seccion->setSuperior($_REQUEST['superior']);
    $id = null;
   }
 }

if($id !== null)
  $secciones = Secciones::Listado($_SESSION['leng_id'], array('superior_id' => $id, 'sistema' => 0));

echo "<!--

";
var_dump($secciones);
echo "

-->";


//$vista = new Vista_XHTML();
$vista = new VistaAdmin_Documento($seccion);
//include('./vistas/doctype.php');
//include('./vistas/iaencab.php');
ob_start();
include('./vistas/secciones.php');
$buffer = ob_get_contents();
ob_end_clean();
$vista->html($buffer);
$vista->mostrar();
//unset($vista);//include('inc/iapie.php');

exit;























function reordenar_cat($saltear_id = false, $saltear_pos = false)
 {
  global $mysqli;
  $consulta = $mysqli->query("SELECT `id`, `orden` IS NULL AS ordennull FROM `admin_secciones` WHERE sistema = 0 ORDER BY ordennull, `orden`");
  if($fila = $consulta->fetch_row())
   {
	$reor_num = 0;
	do
	 {
	  if($fila[0] == $saltear_id)
	    continue;
	  $reor_num++;
	  if($reor_num == $saltear_pos)
	    $reor_num++;
	  $mysqli->query("UPDATE `admin_secciones` SET `orden` = '${reor_num}' WHERE `id` = '".$fila[0]."'");
	  $mysqli->query("UPDATE `secciones` SET `orden` = '${reor_num}' WHERE `id` = '".$fila[0]."'");
	  //$sqlite->queryExec("UPDATE galerias SET orden = '${reor_num}' WHERE id = '".$fila[0]."'");
	 } while($fila = $consulta->fetch_row());
	$consulta->close();
   }
 }

function eliminar_cache($id = '')
 {
  foreach(glob(RUTA_CARPETA."iacache/menu*.php") as $nombre_archivo)// *-${id}-*
	unlink($nombre_archivo);
 }
$mysqli = BaseDatos::Conectar();

// modificar
if(($_POST['mult_submit'] || $_POST['clave_submit']) && $_POST['lista_item'])
 {
  $modificar = $_POST['lista_item'];
  $modificadas = 0;
  // borrar
  if($_POST['mult_submit'] == "Eliminar")
   {
    $modificacion_tipo_accion = "eliminadas";
    foreach($modificar AS $modificari)
     {
	  $mysqli->query("DELETE FROM `admin_secciones` WHERE `id` = '".$modificari."' AND sistema = 0 LIMIT 1");
	  $mysqli->query("DELETE FROM `secciones_nombres` WHERE `id` = '".$modificari."' LIMIT 1");
	  $mysqli->query("DELETE FROM `secciones` WHERE `id` = '".$modificari."' LIMIT 1");
      if($mysqli->affected_rows == 1)
       {
        $modificadas++;
		// (trigger)
		$mysqli->query("DELETE FROM `usuarios_permisos` WHERE area_id = 2 AND item_id = '".$modificari."'");// or die (__LINE__." - ".mysql_error());
		eliminar_cache($modificari);
		//@unlink(RUTA_CARPETA."iacache/menu".md5($_SESSION['admin_secciones']).".php");
		unset($_SESSION['permisos']['admin_seccion'][$modificari]);
		$_SESSION['admin_secciones'] = "-".implode("-", array_keys($_SESSION['permisos']['admin_seccion']))."-";
       }
     }
   }
  // habilitar
  elseif($_POST['mult_submit'] == "Habilitar")
   {
    $modificacion_tipo_accion = "habilitadas";
    for($i = 0; $i < count($modificar); $i++)
     {
      $mysqli->query("UPDATE `secciones` SET `estado` = '1' WHERE `id` = '".$modificar[$i]."' LIMIT 1");
      if($mysqli->affected_rows)
       { $modificadas++; }
     }
   }
  // deshabilitar
  elseif($_POST['mult_submit'] == "Deshabilitar")
   {
    $modificacion_tipo_accion = "deshabilitadas";
    for($i = 0; $i < count($modificar); $i++)
     {
      $mysqli->query("UPDATE `secciones` SET `estado` = '0' WHERE `id` = '".$modificar[$i]."' LIMIT 1");
      if($mysqli->affected_rows)
       { $modificadas++; }
     }
   }

  if($modificadas > 0)
   {
    $div_mensaje = "Secciones ${modificacion_tipo_accion}: ${modificadas}";
    include('./secciones_const.php');
   }
 }

// agregar / editar
if($_REQUEST["ia"] == "agregar" || !empty($_REQUEST['id']))
 {
  if($_POST['accion'] == "agregar" || $_POST['accion'] == "modificar")
   {
	eliminar_cache();
	$pos = $_POST['pos'];
    $antesde = $_POST['antesde'];
    //$antesde--;
	if(empty($_POST['nombre']))
	  $div_mensaje = "Debe indicar un nombre para la nueva sección.";
	else
	 {
	  //$leng_p = key($_POST['nombre']);
	  setlocale(LC_CTYPE, 'es_UY.UTF-8');

	  $orden = ($pos == 1) ? "NULL" : "'${antesde}'";
	  if($_POST['accion'] == "agregar")
	   {
		$saltear_form = true;
	   	$prof_categorias = is_numeric($_POST['prof_categorias']) ? $_POST['prof_categorias'] : 'NULL';
	   	$mysqli->query("INSERT INTO admin_secciones (`superior_id`, `orden`, `identificador`, `info`, `items`, `categorias`, `prof_categorias`) VALUES ('{$_POST['superior']}', ${orden}, '".str_replace(" ", "_", strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $_POST['identificador'])))."', {$_POST['info']}, {$_POST['items']}, {$_POST['categorias']}, ${prof_categorias})");
	    if($id = $mysqli->insert_id)
	     {
		  $mysqli->query("INSERT INTO secciones (`id`, `superior`, `orden`, `estado`, `tipo`, `icono`, `permiso_min`) VALUES (${id}, '{$_POST['superior']}', ${orden}, 0, '{$_POST['tipo']}', '{$_POST['icono']}', '{$_POST['permiso_min']}')");
		  foreach($_POST['nombre'] AS $nombre_k => $nombre)
		   {
		   	if(empty($nombre))
			 {
			  unset($_POST['nombre'][$nombre_k]);
			  continue;
			 }
		    $mysqli->query("INSERT INTO secciones_nombres (`id`, `leng_id`, `titulo`) VALUES (${id}, {$nombre_k}, '{$nombre}')");
		   }
		  //$mysqli->query("INSERT INTO galerias_categorias_textos (`id`, `titulo`) VALUES ('${id}', '{$_POST['nombre']}')");
		  $mysqli->query("INSERT INTO usuarios_permisos (usuario_id, area_id, item_id, permiso_id) VALUES ({$_SESSION['usuario_id']}, 2, ${id}, {$_SESSION['permisos']['admin_seccion'][$seccion_id]}), ({$_SESSION['usuario_id']}, 3, ${id}, {$_SESSION['permisos']['admin_seccion'][$seccion_id]})");
		  $div_mensaje = "Ha sido agregada la sección <b>".reset($_POST['nombre'])."</b>.";
		  $mysqli->query("UPDATE admin_secciones SET `nombre` = '".current($_POST['nombre'])."' WHERE `id` = '${id}'");
		  //@unlink("../../iacache/menu{$_SESSION['admin_secciones']}.php");
		  $_SESSION['permisos']['admin_seccion'][$id] = $_SESSION['permisos']['admin_seccion'][$seccion_id];
		  ksort($_SESSION['permisos']['admin_seccion']);
		  $_SESSION['admin_secciones'] = "-".implode("-", array_keys($_SESSION['permisos']['admin_seccion']))."-";
		 }
		if($pos == 2)
		 {
		  $saltear_id = $id;
		  $saltear_pos = $antesde;
		 }
	   }
	  elseif($_POST['accion'] == "modificar" && !empty($_POST['id']))
	   {
		$saltear_form = true;
		$id = $_POST['id'];
		if(!empty($pos))
		 {
		  if($pos == 2)
		   {
		    $saltear_id = $id;
		    if(!empty($_POST['pos_actual']) && $_POST['pos_actual'] < $antesde)
		     {
		      $antesde--;
		      if($orden != "NULL") $orden = "'${antesde}'";
		     }
		    $saltear_pos = $antesde;
		   }
		  //$mysqli->query("UPDATE admin_secciones SET `orden` = ${orden} WHERE `id` = '${id}'");
		  $Qorden = ", `orden` = ${orden}";
		 }
		$prof_categorias = is_numeric($_POST['prof_categorias']) ? $_POST['prof_categorias'] : 'NULL';
		$mysqli->query("UPDATE admin_secciones SET `identificador` = '{$_POST['identificador']}',`info` = {$_POST['info']}, `items` = {$_POST['items']}, `categorias` = {$_POST['categorias']}, `prof_categorias` = {$prof_categorias} WHERE `id` = '${id}'");
		$mysqli->query("UPDATE secciones SET `tipo` = '{$_POST['tipo']}'${Qorden}, `icono` = '{$_POST['icono']}', `permiso_min` = '{$_POST['permiso_min']}' WHERE `id` = '${id}'");
		$mysqli->query("DELETE FROM secciones_nombres WHERE `id` = '${id}'");
		foreach($_POST['nombre'] AS $nombre_k => $nombre)
		 {
		  if(empty($nombre))
		   {
		    unset($_POST['nombre'][$nombre_k]);
			continue;
		   }
		  $mysqli->query("INSERT INTO secciones_nombres (`id`, `leng_id`, `titulo`) VALUES (${id}, {$nombre_k}, '{$nombre}')");
		 }
		$mysqli->query("UPDATE admin_secciones SET `nombre` = '".reset($_POST['nombre'])."' WHERE `id` = '${id}'");

		if($mysqli->affected_rows == 1)
		  eliminar_cache($id);
		include('./secciones_const.php');
	   }
	  reordenar_cat($saltear_id, $saltear_pos);
	 }
	$id = $_POST['superior'];
   }
  else
    $pos = 1;
 }

include('inc/iaencab.php');

// agregar / editar
if($_REQUEST["ia"] == "agregar" || !empty($_REQUEST['id']))
 {
  if(!$saltear_form)
   {
	$transaccion = "Agregar";
	$accion = "agregar";
	$de_sistema = 0;
	$min_cats_orden = 0;
	$info = 0;
	$items = 0;
	$categorias = 0;
	//$rss = 0;
	if(!empty($_REQUEST['id']))
	 {
	  $mysqli = BaseDatos::Conectar();
	  if(!$consulta_item = $mysqli->query("SELECT ads.id, ads.nombre, ads.superior_id, ads.orden, ads.info, ads.items, ads.categorias, ads.rss, ads.sistema, ads.identificador, s.tipo, s.icono, ads.prof_categorias, s.permiso_min FROM admin_secciones ads LEFT JOIN secciones s ON ads.id = s.id, usuarios_permisos up WHERE ads.id = up.item_id AND ads.id = '{$_REQUEST['id']}' AND up.area_id = 2 AND up.usuario_id = {$_SESSION['usuario_id']} LIMIT 1")) die("\n".__LINE__." mySql: ".$mysqli->error);
	  if($fila_item = $consulta_item->fetch_assoc())
	   {
	    $transaccion = "Editar";
	    $id = $fila_item['id'];
	    $superior = $fila_item['superior_id'];
	    $orden = $fila_item['orden'];
	    $nombre = $fila_item['nombre'];
	    $info = $fila_item['info'];
	    $items = $fila_item['items'];
	    $categorias = $fila_item['categorias'];
	    $prof_categorias = $fila_item['prof_categorias'];
	    //$rss = $fila_item['rss'];
	    $superior = $fila_item['superior_id'];
	    $de_sistema = $fila_item['sistema'];
	    $consulta_item->close();
	    $accion = "modificar";
	    $pos = 0;
	    $min_cats_orden = 1;
	   }
	  else
	    $no_poromision = true;
     }
    else
	  $no_poromision = true;
	$sel_pos[$pos] = " checked=\"checked\"";
	$sel_info[$info] = " checked=\"checked\"";
	$sel_items[$items] = " checked=\"checked\"";
	$sel_categorias[$categorias] = " checked=\"checked\"";
	//$sel_rss[$rss] = " checked=\"checked\"";

    if(is_array($secciones->secciones[$seccion_id]))
      $subcategorias = $secciones->secciones[$seccion_id];
    elseif($secciones->superior[$seccion_id] != 0)
      $subcategorias = $secciones->secciones[$secciones->superior[$seccion_id]];
	//include('inc/iaencab.php');
	echo "
	<form name=\"formedicion\" id=\"formedicion\" action=\"/secciones?ia={$_GET['ia']}\" method=\"post\">
	 <input type=\"hidden\" name=\"id\" value=\"${id}\" />
	 <input type=\"hidden\" name=\"pos_actual\" value=\"${orden}\" />
	 <input type=\"hidden\" name=\"superior\" value=\"${superior}\" />
	 <input type=\"hidden\" name=\"accion\" value=\"${accion}\" />\n";

	$lengs = array();
	$titulo = array();
	$cons_lengs = $mysqli->query("SELECT l.id, l.codigo, sn.titulo, l.dir FROM lenguajes l LEFT JOIN secciones_nombres sn ON l.id = sn.leng_id AND sn.id = '${id}' ORDER BY leng_poromision DESC");// WHERE l.estado > 0 AND l.estado < 5
	if($fila_lengs = $cons_lengs->fetch_row())
	 {
	  $leng_poromision = $fila_lengs[0];
	  do
	   {
		$lengs[$fila_lengs[0]] = array($fila_lengs[1], $fila_lengs[3]);
		$titulo[$fila_lengs[0]] = $fila_lengs[2];
	   }while($fila_lengs = $cons_lengs->fetch_row());
	  $cons_lengs->close();
	 }

?>

	<table class="tabla">
	 <thead>
	  <tr>
	   <th colspan="2"><?php echo $transaccion; ?> Sección</th></tr>
	 </thead>
	 <tfoot>
	  <tr>
	   <td align="center" colspan="2"><input type="button" value="Cancelar" onclick="document.location.href='/secciones'" />&nbsp;&nbsp;<input type="submit" name="btGuardar" id="guardar" value="Guardar" /></td></tr>
	 </tfoot>
	 <tbody>
	  <tr>
	   <td><label>Sección</label>:</td>
	   <td><ul class="campo_lista">
<?php

	foreach($lengs AS $leng_id => $leng_cod)
	  echo "<li><label for=\"nombre{$leng_cod[0]}\" class=\"etiqueta_idioma\"><tt>({$leng_cod[0]})</tt></label>&nbsp;<input type=\"text\" name=\"nombre[{$leng_id}]\" id=\"nombre{$leng_cod[0]}\" value=\"".htmlspecialchars($titulo[$leng_id])."\" lang=\"{$leng_cod[0]}\" dir=\"{$leng_cod[1]}\" size=\"32\" maxlength=\"32\" onblur=\"completarIdent(this)\" /></li>";

?></ul></td></tr>
<?php

	if($_SESSION['permisos']['admin_seccion'][2] >= 5)
	 {
?>
	  <tr>
	   <td><label for="identificador">Identificador</label>:</td>
	   <td><input type="text" name="identificador" id="identificador" value="<?php echo $fila_item['identificador'] ?>" maxlength="32" /></td></tr>
	  <tr>
	   <td><label>Información</label>:</td>
	   <td><input type="radio" name="info" id="info1" value="1" <?php echo $sel_info[1] ?> /><label for="info1">si</label> <input type="radio" name="info" id="info0" value="0" <?php echo $sel_info[0] ?> /><label for="info0">no</label></td></tr>
	  <tr>
	   <td><label>Items</label>:</td>
	   <td><input type="radio" name="items" id="items1" value="1" <?php echo $sel_items[1] ?> /><label for="items1">si</label> <input type="radio" name="items" id="items0" value="0" <?php echo $sel_items[0] ?> /><label for="items0">no</label></td></tr>
	  <tr>
	   <td><label>Categorías</label>:</td>
	   <td><input type="radio" name="categorias" id="categorias1" value="1" <?php echo $sel_categorias[1] ?> onchange="habProfCats(this)" /><label for="categorias1">si</label> <input type="radio" name="categorias" id="categorias0" value="0" <?php echo $sel_categorias[0] ?> onchange="habProfCats(this)" /><label for="categorias0">no</label> / profundidad: <input type="text" name="prof_categorias" value="<?php echo $prof_categorias ?>" size="2" <?php echo $sel_categorias[0] ? 'disabled="disabled"' : '' ?> /></td></tr>
<?php

	  if(!$cons_perfiles = $mysqli->query("SELECT id, nombre FROM config_perfiles ORDER BY nombre")) die("\n".__LINE__." mySql: ".$mysqli->error);
	  if($fila_perfiles = $cons_perfiles->fetch_row())
	   {
		echo '
	  <tr>
	   <td><label>Configuración predeterminada</label>:</td>
	   <td><select name="preset"><option value=""> </option>';
	    do
		 {
		  echo "<option value=\"{$fila_perfiles[0]}\">{$fila_perfiles[1]}</option>";
		 }while($fila_perfiles = $cons_perfiles->fetch_row());
		echo '</select></td></tr>';
	   }
	 }
	$mysqli = BaseDatos::Conectar();
    if(!$categorias = $mysqli->query("SELECT s.orden, sn.nombre, s.id, s.orden IS NULL AS ordennull FROM secciones s JOIN admin_secciones sn ON s.id = sn.id, usuarios_permisos up WHERE s.id = up.item_id AND up.area_id = 2 AND up.usuario_id = {$_SESSION['usuario_id']} AND superior = ${superior} ORDER BY ordennull, s.orden")) echo __LINE__." - ".$mysqli->error;
	if($categorias->num_rows > $min_cats_orden)
     {
	  echo "
	  <tr>
	   <td><label>Orden</label>:</td>
	   <td>
<ul style=\"list-style-type:none;\">
 <li><input type=\"radio\" name=\"pos\" value=\"1\" id=\"pos1\"{$sel_pos[1]} /> <label for=\"pos1\">Último</label></li>
 <li><input type=\"radio\" name=\"pos\" value=\"2\" id=\"pos2\"{$sel_pos[2]} /> <label for=\"pos2\">Antes de </label><select name=\"antesde\">";
	  while($fila_cat = $categorias->fetch_row())
	   {
	    echo "<option value=\"{$fila_cat[0]}\"";
	    if($fila_cat[0] == $_POST['antesde'])
		  echo " selected=\"selected\"";
	    if($fila_cat[2] == $id)
		  echo " disabled=\"disabled\"";
	    echo ">{$fila_cat[1]}</option>";
	   }
	  echo "
</select></li>
</ul></td></tr>";
	  $categorias->close();
     }

	$sel_prmiso_min[$fila_item['permiso_min']] = " selected=\"selected\"";
?>
	  <tr>
	   <td><label for="permiso_min">Permiso mínimo</label>:</td>
	   <td><select name="permiso_min" id="permiso_min"><option value="0">0</option><option value="1"<?php echo "{$sel_prmiso_min[1]}>1</option><option value=\"2\"{$sel_prmiso_min[2]}>2</option><option value=\"3\"{$sel_prmiso_min[3]}>3</option><option value=\"4\"{$sel_prmiso_min[4]}>4</option><option value=\"5\"{$sel_prmiso_min[5]}" ?>>5</option></select></td></tr>
	 </tbody>
	</table>
	</form>
<div class="div_alerta">Agregar usuarios y permisos para sección seleccionada o link.</div>

<?php

   }
 }

/* por omision */
if(!$no_poromision)
 {
  //include('inc/iaencab.php');
  if(!isset($id))
    $id = 0;
  if($_SESSION['permisos']['admin_seccion'][$seccion_id] >= 2)
   {
	echo "
    <div id=\"opciones\"><a href=\"/secciones?ia=agregar&amp;superior=${id}\">Agregar sección</a></div>";
   }
  $mysqli = BaseDatos::Conectar();
  $clase_estado = array("inactivo", "", "enproceso");
  if(!$consulta = $mysqli->query("SELECT s.id, sn.titulo, s.estado FROM secciones s LEFT JOIN secciones_nombres sn ON s.id = sn.id AND sn.leng_id = (SELECT id FROM lenguajes WHERE leng_poromision = 1 LIMIT 1), usuarios_permisos up WHERE s.id = up.item_id AND up.area_id = 2 AND up.usuario_id = {$_SESSION['usuario_id']} AND superior = ${id} ORDER BY s.orden")) die("<br />\n".__FILE__." ".__LINE__."<br />\n".$mysqli->errno." ".$mysqli->error);
  if($fila = $consulta->fetch_row())
   {
	$clase_filas = array();
	echo "
		<form action=\"/secciones?id=${id}\" method=\"post\" onsubmit=\"return contarCheck('lista_item[]');\">
		<table class=\"tabla\" id=\"tablaListado\"
		 ><thead
		  ><tr class=\"orden\"
		   ><td style=\"width:20px;text-align:center;\"><input type=\"checkbox\" name=\"checkTodos\" onclick=\"checkearTodo(this.form, this, 'lista_item[]');\" /></td
		   ><td>T&iacute;tulo</td
		  ></tr
		 ></thead
		 ><tbody";
	do
     {
      $clase_filas[$fila[0]] = 1;
      echo "
		  ><tr class=\"{$clase_estado[$fila[2]]}\"
		   ><td style=\"width:20px;text-align:center;\"><input type=\"checkbox\" name=\"lista_item[]\" id=\"lista_item".$fila[0]."\" value=\"".$fila[0]."\" onclick=\"selFila(this, '{$clase_estado[$fila[2]]}');\"";
	  //if($fila[2] == 1) echo " disabled=\"disabled\"";
	  $fila[1] = $fila[1] ? $fila[1] : "Sin nombre";
	  echo " /></td
		   ><td><a href=\"/secciones?id={$fila[0]}\">{$fila[1]}</a></td></tr";
	 }while($fila = $consulta->fetch_row());
	echo "
		 ></tbody
		></table>";

	if(count($clase_filas))
	 {
	  echo "
	<script type=\"text/javascript\">
	 var celdaClases = {};";
	  //var celdaClases = new Array();";
	  foreach($clase_filas AS $cfk => $cfv)
	   { echo "\r\t celdaClases[${cfk}] = '${cfv}';"; }
	  echo "
	</script>";
	 }
?>

  <div id="error_check_form" class="div_error" style="display:none;">No ha seleccionado ningún elemento de la lista.</div>
  <div id="listado_opciones" style="padding:4px;"><img src="./img/flecha_arr_der" alt="Para los items seleccionados" style="padding:0 5px;" /><input type="submit" name="mult_submit" value="Eliminar" onclick="return confBorrado('lista_item[]');" />&nbsp;<input type="submit" name="mult_submit" value="Habilitar" />&nbsp;<input type="submit" name="mult_submit" value="Deshabilitar" /></div>
  <div id="listado_result"></div>
	 </form>

<?php
     }
//   }
  else
    echo "<div class=\"div_alerta\">No se encontr&oacute; ning&uacute;na sección.</div>";
 }

include('inc/iapie.php');

?>