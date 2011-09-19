<?php

$titulo = "Administradores";
$seccion = "permisos";
$seccion_id = 3;

require('inc/iniciar.php');
//$secciones = new adminsecciones();
require('inc/ad_sesiones.php');

$id = $_REQUEST['id'];
$ia = $_REQUEST["ia"];

$mysqli = BaseDatos::Conectar();
/*
$atributos = array();
if(!$atributos_tipos = $mysqli->query("SELECT ua.id, sugerido, unico, tipo, subtipo, nombre, identificador FROM usuarios_atributos ua LEFT JOIN usuarios_atributos_nombres uan ON ua.id = uan.id AND leng_id = '1' ORDER BY orden")) echo __LINE__." - ".$mysqli->error;
if($fila_at = $atributos_tipos->fetch_row())
 {
  do
   {
	$atributos[$fila_at[0]] = array('sugerido' => $fila_at[1], 'unico' => $fila_at[2], 'tipo' => $fila_at[3], 'subtipo' => $fila_at[4], 'nombre' => $fila_at[5], 'identificador' => $fila_at[6]);
   }while($fila_at = $atributos_tipos->fetch_row());
  $atributos_tipos->close();
 }
*/
// modificar
if(($_POST['mult_submit'] || $_POST['clave_submit']) && $_POST['lista_item'])
 {
  $modificar = $_POST['lista_item'];
  $modificadas = 0;

  // borrar
  if($_POST['mult_submit'] == "Eliminar completamente")
   {
    $modificacion_tipo_accion = "eliminados";
    //$borrar = new Item_borrar($seccion);
    for($i = 0; $i < count($modificar); $i++)
     {
      $mysqli->query("DELETE FROM admin_permisos WHERE `admin_id` = '{$modificar[$i]}'");//$borrar->Item($modificar[$i], true);
	  $mysqli->query("DELETE FROM usuarios WHERE `id` = '{$modificar[$i]}'");
	  $mysqli->query("DELETE FROM usuarios_permisos WHERE `usuario_id` = '{$modificar[$i]}'");
	  $mysqli->query("DELETE FROM usuarios_valores WHERE `usuario_id` = '{$modificar[$i]}'");
	  $modificadas++;
     }
    // = $borrar->modificadas;
   }
  /*
  // habilitar
  elseif($_POST['mult_submit'] == "Habilitar")
   {
    $modificacion_tipo_accion = "habilitados";
    $publicar = new Item_publicarBarriola($seccion);
    for($i = 0; $i < count($modificar); $i++)
     {
      $publicar->Item($modificar[$i]);
	  $mysqli->query("UPDATE `usuarios` SET `estado` = '1' WHERE `id` = '{$modificar[$i]}' LIMIT 1");
     }
    $modificadas = $publicar->modificadas;
   }
  */
  // deshabilitar
  elseif($_POST['mult_submit'] == "Revocar permisos")
   {
    $modificacion_tipo_accion = "inhabilitados";
    //$borrar = new Item_borrar($seccion);
    for($i = 0; $i < count($modificar); $i++)
     {
      $mysqli->query("DELETE FROM admin_permisos WHERE `admin_id` = '{$modificar[$i]}'");//$borrar->Item($modificar[$i], true);
      $mysqli->query("UPDATE usuarios SET admin = '0' WHERE id = '{$modificar[$i]}'");//$borrar->Item($modificar[$i], true);
      $modificadas++;
     }
    //$borrar->modificadas;
   }
  if($modificadas > 0)
   {
    $div_mensaje = "Usuarios clientes ".$modificacion_tipo_accion.": ".$modificadas;
    //tabla_informacion("Galer&iacute;as ".$modificacion_tipo_accion.": ".$modificadas);
   }
 }

// nuevo
if(($_POST['ia'] == "agregar" || $_POST['ia'] == "modificar") && $_POST['confirmar'] == "Guardar")
 {
  if($_POST['ia'] == "modificar") $cond = "AND id != '".$_POST['id']."'";
  $resultado = $mysqli->query("SELECT `usuario`, `email` FROM `usuarios` WHERE (`usuario` = '".$_POST['usuario']."' OR `email` = '".$_POST['email']."') ${cond} LIMIT 1");
  $fila = $resultado->fetch_row();
  $resultado->close();
  if(empty($_POST['usuario']))
   { $mensaje_error[] = "Debe completar el campo 'Nombre de usuario'."; }
  else
   {
    if(strlen($_POST['usuario']) < 5 || strlen($_POST['usuario']) > 22)
     { $mensaje_error[] = "El nombre de usuario debe contener entre 5 y 22 caracteres."; }
    else
     {
	  if($fila[0] == strtolower($_POST['usuario']))
	   { $mensaje_error[] = "El nombre de usuario '".$_POST['usuario']."' no est&aacute; disponible."; }
	 }
   }

  if(empty($_POST['email']))
   { $mensaje_error[] = "Debe ingresar una casilla de correos."; }
  else
   {
    if(!eregi('^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$', $_POST['email']))
	 { $mensaje_error[] = "La casilla ingresada no parece ser una casilla v&aacute;lida."; }
	else
	 {
	  if($fila[1] == strtolower($_POST['email'])) $mensaje_error[] = "La casilla de correo ya se encuentra registrada en otro usuario.";
	  else $casilla = $_POST['email'];
	 }
   }

  if(($_POST['ia'] == "agregar" && (empty($_POST['adclave']) || empty($_POST['adclave2']))) || ($_POST['ia'] == "modificar" && (empty($_POST['adclave']) xor empty($_POST['adclave2']))))
   { $mensaje_error[] = "Ingrese la nueva contrase&ntilde;a dos veces."; }
  elseif($_POST['ia'] == "agregar" || ($_POST['ia'] == "modificar" && (!empty($_POST['adclave']) && !empty($_POST['adclave2']))))
   {
	if(strlen($_POST['adclave']) < 6)
	 { $mensaje_error[] = "La nueva contrase&ntilde;a debe contener al menos 6 caracteres."; }
	else
	 {
	  if($_POST['adclave'] !== $_POST['adclave2']) $mensaje_error[] = "La nueva contrase&ntilde;a y su confirmaci&oacute;n no coinciden.";
	  else $clave = $_POST['adclave'];
	 }
   }

  // mysql_errno()
  // 1062

  // substr(mysql_error(), -1)
  // 2 = usuario
  // 3 = admin_mail
  if($mensaje_error && $_POST['ia'] == "agregar")
   {
    $ia = "editar";
    $fila = $_POST;
   }
  elseif($_POST['ia'] == "agregar")
   {
	$nombre = $_POST['nombre'] ? "'".$_POST['nombre']."'" : "null";
	$apellido = $_POST['apellido'] ? "'".$_POST['apellido']."'" : "null";
	$mysqli->query("INSERT INTO `usuarios` (`usuario`, `nombre_mostrar`, `clave`, `email`, `admin`, `creado`) VALUES ('".$_POST['usuario']."', '".$_POST['usuario']."', SHA1('".$_POST['adclave']."'), LCASE('".$_POST['email']."'), '1', now())");
	if($id = $mysqli->insert_id)
	 {
	  $mysqli->query("INSERT INTO admin_permisos VALUES({$id},15,2),({$id},8,3)");
	  header("Location: /permisos?id=".$id."#permisos");
	 }
	else
	 {
	  $ia = "editar";
	  $fila = $_POST;
	 }
   }
  elseif($_POST['ia'] == "modificar")
   {
    $id = $_POST['id'];
	if($casilla) $cambios[] = "`email` = LCASE('${casilla}')";
	if($clave) $cambios[] = "`clave` = SHA1('${clave}')";
	if($cambios) $mysqli->query("UPDATE `usuarios` SET ".implode(", ", $cambios)." WHERE `usuario` = '".$_POST['usuario']."' AND id = '".$_POST['id']."'");
	if($_POST['dato']['m'])
	 {
	  foreach($_POST['dato']['m'] AS $attri => $attra)
	   {
	    foreach($attra AS $attrk => $attrv)
	     {
	      if(empty($attrv)) $mysqli->query("DELETE FROM `usuarios_valores` WHERE `id` = '${attrk}'");
	      else $mysqli->query("UPDATE `usuarios_valores` SET `${attri}` = '${attrv}' WHERE `id` = '${attrk}'");
		 }
	   }
	 }

	if($_POST['dato']['n'])
	 {
	  foreach($_POST['dato']['n'] AS $attri => $attra)
	   {
		foreach($attra AS $attrv)
		 {
		  if(empty($attrv)) continue;
		  $mysqli->query("INSERT INTO `usuarios_valores` (`atributo_id`, `usuario_id`, `{$atributos[$attri]['tipo']}`) VALUES ('${attri}', '".$_POST['id']."', '${attrv}')");
		 }
	   }
	 }
   }
 }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
 <meta http-equiv="content-type" content="text/html; charset=utf-8" />
 <title><?php echo $titulo." - ".SITIO_TITULO; ?></title>

<?php

include('inc/iaencab.php');

// agregar / editar
if($ia == "editar" || !empty($id))
 {
  $no_poromision = TRUE;
  $transaccion = "Agregar";
  if(!$mensaje_error || $_POST['ia'] == "modificar")
   {
	if(!empty($_REQUEST['id']))
	 {
	  if(!$result = $mysqli->query("SELECT `id`, `usuario`, `creado`, `email` FROM `usuarios` WHERE `id` = '{$_REQUEST['id']}'")) echo __LINE__." - ".$mysqli->error;
	  else
	   {
	  	if($fila = $result->fetch_assoc())
	     {
	      $filaDB = true;
	      $transaccion = "Editar";
		  $v_ia = "modificar";
	 	  $result->close();
		 }
	   }
	 }
   }


  $req = $filaDB ? false : "<sup>*</sup>";



  if($fila && (!$mensaje_error || $_POST['ia'] == "modificar"))
   {
	echo "
<br />
<div class=\"solapas\"><ul><li><a href=\"/administradores?id=${id}\">Información</a></li><li><span>Permisos</span></li></ul></div>
	<form name=\"formedicion\" method=\"post\" action=\"/permisos_guardar\" onsubmit=\"return enviarPost(this, 'permisos_guardar', 'unHandler', document.getElementById('avisoguardar'))\" ><!-- target=\"frguardar\" -->
	 <input type=\"hidden\" name=\"permisos\" value=\"1\" />
	 <input type=\"hidden\" name=\"id\" value=\"{$fila['id']}\" />";




?>

	<table class="tabla" id="tabla_permisos"
	 ><thead>
	  <tr>
	   <th colspan="3">Permisos</th></tr>
	  <tr>
	   <td>Sección</td><td>Info/items</td><td>Categorías</td></tr>
	 </thead
	 ><tfoot>
	  <tr id="avisoguardar" style="display:none;"><td colspan="3"><div style="font-weight:bold;color:#134679;">&nbsp;</div></td></tr>
	  <tr>
	   <td align="center" colspan="3"><input type="button" value="Cancelar" onclick="document.location.href='/administradores?id=<?php echo $id ?>'" />&nbsp;&nbsp;<input type="submit" name="confirmar2" id="guardar2" value="Guardar" /></td></tr>
	 </tfoot
	 ><tbody><?php

    if(!$permisos_tipos = $mysqli->query("SELECT * FROM `permisos_tipos` WHERE leng_id = '1' ORDER BY `id`")) echo __LINE__." - MySQL: ".$mysqli->error."<br />\n";
    else
	 {
	  if($fila_permtipos = $permisos_tipos->fetch_row())
	   {
		$as_permisos[] = array(0, "Ninguno");
		$as_permisos2[] = "Ninguno";
		do
		 {
		  $as_permisos[] = array($fila_permtipos[0], $fila_permtipos[2]);
		  $as_permisos2[] = $fila_permtipos[2];
		 }while($fila_permtipos = $permisos_tipos->fetch_row());
		$permisos_tipos->close();
	   }
	 }

	$admin_permisos = array();
    //if(!$secciones_n = $mysqli->query("SELECT ads.id, nombre, ads.id, permiso_max, permiso FROM `admin_secciones` ads LEFT JOIN `admin_permisos` ap ON ads.id = ap.seccion_id AND admin_id = '".$_REQUEST['id']."' WHERE `superior_id` = '0' GROUP BY ads.id ORDER BY `orden`")) echo __LINE__." - MySQL: ".$mysqli->error."<br />\n";
    if(!$secciones_n = $mysqli->query("SELECT item_id, area_id, permiso_id FROM usuarios_permisos u WHERE usuario_id = {$_REQUEST['id']} ORDER BY item_id, area_id")) echo __LINE__." - MySQL: ".$mysqli->error."<br />\n";
    else
     {
	  if($fila_secciones = $secciones_n->fetch_row())
	   {
		do
		 {
		  $admin_permisos[$fila_secciones[0]][$fila_secciones[1]] = $fila_secciones[2];
    	 }while($fila_secciones = $secciones_n->fetch_row());
    	$secciones_n->close();
       }
     }

//SELECT ads.id, nombre, ads.id, permiso_max, permiso_id FROM `admin_secciones` ads LEFT JOIN `usuarios_permisos` ap ON ads.id = ap.item_id AND usuario_id = '".$_REQUEST['id']."' WHERE `superior_id` = '0' GROUP BY ads.id ORDER BY `orden`";

    function seleccionarPerm($limite = 0, $seleccionado = false)
     {
      global $as_permisos;
      if($limite >= count($as_permisos)) $limite = (count($as_permisos) - 1);
      if(!$seleccionado) $seleccionado = 0;
      if($seleccionado > $limite) $seleccionado = $limite;
      $i = 0;
      $perm_opciones = "";
      do
       {
        $perm_opciones .= "<option value=\"".$as_permisos[$i][0]."\"";
        if($as_permisos[$i][0] == $seleccionado) $perm_opciones .= " selected=\"selected\"";
        $perm_opciones .= ">".$i."_ ".$as_permisos[$i][1]."</option>";
        $i++;
       }while($i <= $limite);
      return $perm_opciones;
     }

/*    foreach($secciones_max as $indice => $valor)
     {
      if(!$_SESSION['permisos']['admin_seccion'][$indice] || $admin_permisos[$indice] > $_SESSION['permisos']['admin_seccion'][$indice]) continue;
      echo "
	  <tr>
	   <td><label for=\"seccion{$indice}\">{$secciones_nombres[$indice]}</label>:</td>
	   <td>";
	  echo ($admin_permisos[$indice] == $_SESSION['permisos']['admin_seccion'][$indice]) ? "<span id=\"seccion${indice}\">{$admin_permisos[$indice]}_ {$as_permisos[$admin_permisos[$indice]][1]}</span>" : "<select name=\"seccion[{$secciones_ids[$indice]}]\" id=\"seccion{$indice}\">".seleccionarPerm(min($_SESSION['permisos']['admin_seccion'][$indice], $valor), $admin_permisos[$indice])."</select>";

//	  if($admin_permisos[$indice] == $_SESSION['permisos'][$indice]) echo $admin_permisos[$indice]."_ ".$as_permisos[$admin_permisos[$indice]][1];
//	  else echo "<select name=\"seccion[".$secciones_ids[$indice]."]\" id=\"seccion".$indice."\">".seleccionarPerm($_SESSION['permisos'][$indice], $admin_permisos[$indice])."</select>";
	  echo "</td></tr>";
     }
*/



    if(!$secciones_n = $mysqli->query("SELECT ads.id, permiso_max, categorias FROM `admin_secciones` ads WHERE `superior_id` = '0' ORDER BY sistema, `orden`")) echo __LINE__." - MySQL: ".$mysqli->error."<br />\n";
    else
     {
	  if($fila_secciones = $secciones_n->fetch_row())
	   {
		do
		 {
		  $sec_link = $fila_secciones[0];
		  if(!$_SESSION['permisos']['admin_seccion'][$sec_link]) continue;// || $admin_permisos[2][$sec_link] > $_SESSION['permisos']['admin_seccion'][$sec_link]
	      echo "<tr><td><label for=\"seccion{$sec_link}\">{$secciones_nombres[$sec_link]}</label></td><td>";
		  echo ($admin_permisos[2][$sec_link] > $_SESSION['permisos']['admin_seccion'][$sec_link]) ? "<span id=\"seccion${sec_link}\">{$admin_permisos[$sec_link][2]}_ {$as_permisos[$admin_permisos[$sec_link][2]][1]}</span>" : "<select name=\"seccion[{$sec_link}][2]\" id=\"seccion{$sec_link}\">".seleccionarPerm(min($_SESSION['permisos']['admin_seccion'][$sec_link], $fila_secciones[1]), $admin_permisos[$sec_link][2])."</select>";
		  echo "</td><td>";
		  if($fila_secciones[2]) echo "<select name=\"seccion[{$sec_link}][3]\" id=\"seccionc{$sec_link}\">".seleccionarPerm($_SESSION['permisos']['admin_seccion_c'][$sec_link], $admin_permisos[$sec_link][3])."</select>";
//	  if($admin_permisos[$indice] == $_SESSION['permisos'][$indice]) echo $admin_permisos[$indice]."_ ".$as_permisos[$admin_permisos[$indice]][1];
//	  else echo "<select name=\"seccion[".$secciones_ids[$indice]."]\" id=\"seccion".$indice."\">".seleccionarPerm($_SESSION['permisos'][$indice], $admin_permisos[$indice])."</select>";
	  echo "</td></tr>";
		  //if($fila_secciones[4]) $admin_permisos[$sec_link] = $fila_secciones[4];
    	 }while($fila_secciones = $secciones_n->fetch_row());
    	$secciones_n->close();
       }
     }

?></tbody>
	</table>
	</form>


<script type="text/javascript">
var subSecciones = {};
<?php

echo "\n\n//SELECT superior_id, id, permiso_max, categorias FROM `admin_secciones` WHERE superior_id > 0 ORDER BY superior_id, orden DESC\n\n";
	echo "var permisosTipos = ['".implode("', '", $as_permisos2)."'];\n";
    if(!$secciones_n = $mysqli->query("SELECT superior_id, id, permiso_max, categorias FROM `admin_secciones` WHERE superior_id > 0 ORDER BY superior_id, orden DESC")) echo __LINE__." - MySQL: ".$mysqli->error."<br />\n";
    else
     {
	  if($fila_secciones = $secciones_n->fetch_row())
	   {
	   	$curr_sup = 0;
		do
		 {
		  if(!$_SESSION['permisos']['admin_seccion'][$fila_secciones[0]] || !$_SESSION['permisos']['admin_seccion'][$fila_secciones[1]]) continue;
		  if($curr_sup != $fila_secciones[0])
		   {
			$curr_sup = $fila_secciones[0];
		   	echo "subSecciones[${curr_sup}] = {};\n";
		   }
		  echo "subSecciones[${curr_sup}][{$fila_secciones[1]}] = ['{$secciones_nombres[$fila_secciones[1]]}', ".min($fila_secciones[2], $_SESSION['permisos']['admin_seccion'][$fila_secciones[1]]).", ".($fila_secciones[3] ? 'true' : 'false').", ".($admin_permisos[$fila_secciones[1]][2] ? $admin_permisos[$fila_secciones[1]][2] : '0').", ".($admin_permisos[$fila_secciones[1]][3] ? $admin_permisos[$fila_secciones[1]][3] : '0').", ".($_SESSION['permisos']['admin_seccion_c'][$fila_secciones[1]] ? $_SESSION['permisos']['admin_seccion_c'][$fila_secciones[1]] : '0')."];\n";
    	 }while($fila_secciones = $secciones_n->fetch_row());
    	$secciones_n->close();
       }
     }

?>

acondTablaPerm();
</script>

<?php

   }
 }

/* por omision */
if(!$no_poromision)
 {
  if($_SESSION['permisos']['admin_seccion'][$seccion_id] >= 2)
   {
	echo "
    <div id=\"opciones\"><a href=\"/administrador?ia=editar\">Agregar administrador</a></div>";
   }

  $estado_arr = array("Deshabilitado", "Habilitado");
  $clase_estado = array("inactivo", "");
  $a = "25";
  $de = $_REQUEST["de"];
  if(empty($de))
   {
    $desde = "0";
    $limite_pre = $a;
   }
  else
   {
    $desde = $de;
    $limite_pre = ($de + $a);
   }
  if(empty($_REQUEST["orden"]))
   { $orden = "1"; }
  else
   { $orden = $_REQUEST["orden"]; }

  $flechas_par = "fld2d7dd";
  $db_criterios_orden = array("`usuario`", "`nombre_mostrar`", "`email`", "`creado`");
  include('inc/funciones/ordenar_lista.php');
  extract(ordenar_lista($orden, $db_criterios_orden, $flechas_par));

  //"SELECT `us`.`id` AS `id`,`us`.`usuario` AS `usuario`,group_concat(`uv`.`string` order by `uv`.`atributo_id` DESC separator ', ') AS `nombre_mostrar`,`us`.`email` AS `email`,unix_timestamp(`us`.`ultimo_login`) AS `ultimo_login`,`us`.`admin` AS `admin` from (`usuarios` `us` left join `usuarios_valores` `uv` on(((`us`.`id` = `uv`.`usuario_id`) and ((`uv`.`atributo_id` = _utf8'1') or (`uv`.`atributo_id` = _utf8'2'))))) WHERE (`us`.`usuario` <> _utf8'etdp') group by `us`.`id` AND `admin` = '1' ORDER BY ${db_orden} LIMIT ${desde},${a}";
  //"SELECT us.id, usuario, GROUP_CONCAT(`string` ORDER BY atributo_id DESC SEPARATOR ', ') AS nombre_mostrar, email, ua.tiempo AS ultimo_login, admin FROM `usuarios` us LEFT JOIN usuarios_valores uv ON us.id = uv.usuario_id AND (uv.atributo_id = '1' OR uv.atributo_id = '2') LEFT JOIN usuarios_accesos ua ON us.id = ua.usuario_id WHERE `usuario` != 'etdp' AND us.id <> '".$_SESSION['usuario_id']."' AND `admin` = '1' GROUP BY us.id ORDER BY ${db_orden} LIMIT ${desde},${a}"
//echo          htmlspecialchars();
  if(!$result = $mysqli->query("SELECT id, usuario, estado_id, nombre_mostrar, email, creado, creado_por FROM `usuarios` WHERE `usuario` != 'etdp' AND `admin` = '1' ORDER BY ${db_orden} LIMIT ${desde},${a}")) echo basename(__FILE__)."[".__LINE__."] - ".$mysqli->error;
  if($fila = $result->fetch_row())
   {
//  <div id="listado_result">2 resultados en 1 páginas</div>
    echo "
	<form action=\"/permisos\" method=\"post\" onsubmit=\"return contarCheck('lista_item[]');\">
	<table class=\"tabla\" style=\"width:auto;\"
	 ><thead
	  ><tr class=\"orden\"
	   ><td style=\"width: 20px; text-align: center;\"><input type=\"checkbox\" name=\"checkTodos\" onclick=\"checkearTodo(this.form, this, 'lista_item[]');\" /></td
	   ><td{$ordencolor[1]}><a href=\"/permisos?orden={$ord_num[1]}\">{$ord_fl[1]}Usuario</a></td
	   ><td{$ordencolor[2]}><a href=\"/permisos?orden={$ord_num[2]}\">{$ord_fl[2]}Nombre</a></td
	   ><td{$ordencolor[3]}><a href=\"/permisos?orden={$ord_num[3]}\">{$ord_fl[3]}E-mail</a></td
	   ><td{$ordencolor[4]}><a href=\"/permisos?orden={$ord_num[4]}\">{$ord_fl[4]}Fecha creado</a></td
	  ></tr
	 ></thead
	 ><tbody";
    do
     {
	  $fecha = $fila[4] ? formato_fecha($fila[5], false) : "nunca";
      echo "
	  ><tr
	   ><td style=\"text-align:center;\"><input type=\"checkbox\" name=\"lista_item[]\" value=\"{$fila[0]}\" onclick=\"selFila(this, 1)\" /></td
	   ><td><a href=\"/permisos?id={$fila[0]}\">{$fila[1]}</a></td
	   ><td>{$fila[3]}</td
	   ><td><a href=\"mailto:${fila[4]}\">{$fila[4]}</a></td
	   ><td>${fecha}</td
	  ></tr";
     }while($fila = $result->fetch_row());
    $result->close();
    echo "
	 ></tbody
	></table>
	<div id=\"error_check_form\" class=\"div_error\" style=\"display: none;\">No ha seleccionado ningun administrador.</div>
	<div id=\"listado_opciones\" style=\"padding: 4px;\"><img src=\"./img/flecha_arr_der.png\" alt=\"Para los items seleccionados\" style=\"padding: 0pt 5px;\" /><input type=\"submit\" name=\"mult_submit\" value=\"Eliminar completamente\" onclick=\"return confBorrado('lista_item[]');\" /> <input type=\"submit\" name=\"mult_submit\" value=\"Revocar permisos\" /></div>
	</form>";
	if(count($clase_filas))
	 {
	  echo "
	<script type=\"text/javascript\">
	 var celdaClases = new Array();";
	  for($cf = 0; $cf < count($clase_filas); $cf++)
	   { echo "\r\t celdaClases[".$cf."] = '".$clase_filas[$cf]."';"; }
	  echo "
	</script>";
	 }
    if(!$total = $mysqli->query("SELECT id FROM `usuarios` WHERE `usuario` != 'etdp' AND id <> '".$_SESSION['usuario_id']."' AND `admin` = '1'")) echo __LINE__." - mySQL: ".$mysqli->error."<br />\n";
    else
     {
      if ($total->num_rows < $limite_pre) $limit = $total->num_rows;
      else $limit = $limite_pre;
      $paginas = ceil ($total->num_rows / $a);
      $ante = ($limit - ($desde + 1));
      if ($desde == 0) $b = "1";
      else $b = ($limit / $a);
      $c = ceil ($b);
      echo "
	 <div id=\"listado_result\">Resultados <b>".($desde + 1)."</b> - <b>".$limit."</b> de <b>".$total->num_rows."</b><br />P&aacute;gina <b>".$c."</b> de <b>".ceil ($paginas)."</b><br />";
      if (($desde + 1) > 1) { echo "<a href=\"/permisos?de=".($desde - $a)."\">&lt;&lt; Anterior</a>&nbsp;-&nbsp;"; }

      if($paginas > 1)
       {
        if($paginas > 20 && $c > 11)
         { $i = ($c - 9); }
        else
         { $i = 1; }
        $ib = $a;
        $ipg = ($c + 9);
        if($ipg > $paginas)
         { $ipg = $paginas; }
        for($i;$i<=$ipg;$i++)
         {
          $ia = (($i - 1) * $a);
          if($i <> $c)
           { echo " <a href=\"/permisos?de=".$ia."\">${i}</a> "; }
          else
           { echo " <b>${i}</b> "; }
         }
       }
      if ($total->num_rows > $limit)
       { echo "&nbsp;-&nbsp;<a href=\"/permisos?de=".($desde + $a)."');\">Siguiente &gt;&gt;</a>"; }
      echo "</div>";
      $total->close();
     }
   }
  else
   { echo "<div class=\"div_alerta\">No se encontraron otros administradores en la base de datos.</div>"; }
 }

include('inc/iapie.php');

?>