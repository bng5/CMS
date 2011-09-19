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
  $resultado = $mysqli->query("SELECT `usuario`, `email` FROM `usuarios` WHERE (`usuario` = '".$_POST['usuario']."' OR `email` = '".$_POST['email']."') {$cond} LIMIT 1");
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
	$mysqli->query("INSERT INTO `usuarios` (`usuario`, `nombre_mostrar`, `clave`, `email`, `creado`, `creado_por`, `admin`) VALUES ('".$_POST['usuario']."', '".$_POST['usuario']."', SHA1('".$_POST['adclave']."'), LCASE('".$_POST['email']."'), now(), {$_SESSION['usuario_id']}, '1')");
	if($id = $mysqli->insert_id)
	 {
	  //$mysqli->query("INSERT INTO admin_permisos VALUES({$id},15,2),({$id},8,3)");
	  $mysqli->query("INSERT INTO usuarios_datos (id, nombre, apellido, pais_id, estado, ciudad, direccion, telefono, celular) VALUES ({$id}, '{$_POST['nombre']}', '{$_POST['apellido']}', '{$_POST['pais']}', '{$_POST['estado']}', '{$_POST['ciudad']}', '{$_POST['direccion']}', '{$_POST['telefono']}', '{$_POST['celular']}')");
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
	if($casilla) $cambios[] = "`email` = LCASE('{$casilla}')";
	if($clave) $cambios[] = "`clave` = SHA1('{$clave}')";
	if($cambios) $mysqli->query("UPDATE `usuarios` SET ".implode(", ", $cambios)." WHERE `usuario` = '".$_POST['usuario']."' AND id = '".$_POST['id']."'");
/*
	if($_POST['dato']['m'])
	 {
	  foreach($_POST['dato']['m'] AS $attri => $attra)
	   {
	    foreach($attra AS $attrk => $attrv)
	     {
	      if(empty($attrv)) $mysqli->query("DELETE FROM `usuarios_valores` WHERE `id` = '{$attrk}'");
	      else $mysqli->query("UPDATE `usuarios_valores` SET `{$attri}` = '{$attrv}' WHERE `id` = '{$attrk}'");
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
		  $mysqli->query("INSERT INTO `usuarios_valores` (`atributo_id`, `usuario_id`, `{$atributos[$attri]['tipo']}`) VALUES ('{$attri}', '".$_POST['id']."', '{$attrv}')");
		 }
	   }
	 }
*/

	$datos_modif = array();
	if(isset($_POST['nombre'])) $datos_modif[] = "nombre = '{$_POST['nombre']}'";
	if(isset($_POST['apellido'])) $datos_modif[] = "apellido = '{$_POST['apellido']}'";
	if(isset($_POST['pais'])) $datos_modif[] = "pais = '{$_POST['pais']}'";
	if(isset($_POST['departamento'])) $datos_modif[] = "estado = '{$_POST['departamento']}'";
	if(isset($_POST['ciudad'])) $datos_modif[] = "ciudad = '{$_POST['ciudad']}'";
	if(isset($_POST['direccion'])) $datos_modif[] = "direccion = '{$_POST['direccion']}'";
	if(isset($_POST['telefono'])) $datos_modif[] = "telefono = '{$_POST['telefono']}'";
	if(isset($_POST['celular'])) $datos_modif[] = "celular = '{$_POST['celular']}'";
	if(count($datos_modif)) $mysqli->query("UPDATE `usuarios_datos` SET ".implode(",", $datos_modif)." WHERE id = {$_POST['id']}");
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
	  if(!$result = $mysqli->query("SELECT u.`id`, u.`usuario`, u.`creado`, u.`email`, ud.nombre, ud.apellido, ud.pais_id, ud.estado, ud.ciudad, ud.direccion, ud.telefono, ud.celular FROM `usuarios` u LEFT JOIN usuarios_datos ud ON u.id = ud.id WHERE u.`id` = '{$_REQUEST['id']}'")) echo __LINE__." - ".$mysqli->error;
	  else
	   {
	  	if($fila = $result->fetch_assoc())
	     {
	      $filaDB = true;
	      $transaccion = "Editar";
		  echo "
	<!-- form name=\"edicion\" method=\"post\" action=\"".php_self()."_guardar\" target=\"frguardar\" -->
	<form name=\"edicion\" method=\"post\" action=\"/administradores?id={$_REQUEST['id']}\">
	 <input type=\"hidden\" name=\"id\" value=\"{$fila['id']}\" />";
		  $v_ia = "modificar";
	 	  $result->close();
		 }
	   }
	  $valores = array();
	  if(!$cons_valores = $mysqli->query("SELECT atributo_id, id, string, UNIX_TIMESTAMP(`date`), text, `int` FROM usuarios_valores WHERE usuario_id = '".$_REQUEST['id']."'")) echo __LINE__." - ".$mysqli->error;
	  if($fila_valores = $cons_valores->fetch_row())
	   {
	    do
	     {
		  $valor = $fila_valores[0];
		  $valores[$valor][] = array($fila_valores[1], $fila_valores[2], $fila_valores[3], $fila_valores[4], $fila_valores[5]);
	     }while($fila_valores = $cons_valores->fetch_row());
	 	$cons_valores->close();
	   }
	 }
   }

  if($fila && (!$mensaje_error || $_POST['ia'] == "modificar"))
   {
	echo "<br /><div class=\"solapas\"><ul><li><span>Información</span></li><li><a href=\"/permisos?id={$id}\">Permisos</a></li></ul></div>";
   }

  if(!$filaDB)
  //else //if(!$fila || $mensaje_error)
   {
	echo "
	<form name=\"edicion\" method=\"post\" action=\"".php_self().$sesion1."\">";
	$v_ia = "agregar";
   }
  echo "
	 <input type=\"hidden\" name=\"ia\" value=\"{$v_ia}\" />";

?>

	<table class="tabla">
	 <thead>
	 <tr>
	  <th colspan="2"><?php echo $transaccion; ?> administrador</th></tr>
<?php

  if($mensaje_error)
   {
	echo "
	 <tr>
	  <td colspan=\"2\"><ul>";
	foreach($mensaje_error as $error)
	 { echo "<li>".$error."</li>"; }
	echo "</ul></td></tr>";
   }
  $req = $filaDB ? false : "<sup>*</sup>";

?>
	 </thead>
	 <tfoot>
	  <!-- tr id="avisoguardar" style="display:none;"><td colspan="2"><div style="font-weight:bold;color:#134679;">&nbsp;</div><div><a href="<?php echo php_self()."?de=".$_REQUEST['de']."&amp;".SID; ?>">Regresar</a></div></td></tr -->
	  <tr>
	   <td align="center" colspan="2"><input type="button" value="Cancelar" onclick="document.location.href='/permisos?de=<?php echo $_REQUEST['de']; ?>'" />&nbsp;&nbsp;<input type="submit" name="confirmar" id="guardar" value="Guardar" tabindex="7" /></td></tr>
	 </tfoot>
	 <tbody>
	  <tr>
	   <td><label for="usuario">Nombre de usuario</label>:</td>
	   <td><?php

  if(empty($fila['id'])) echo "<input type=\"text\" name=\"usuario\" id=\"usuario\" value=\"{$fila['usuario']}\" size=\"22\" maxlength=\"22\" tabindex=\"1\" />*";
  else
   {
	$ultimo_login = $fila['ultimo_login'] ? formato_fecha($fila[4], false) : "nunca";
    echo $fila['usuario']."<input type=\"hidden\" name=\"usuario\" id=\"usuario\" value=\"{$fila['usuario']}\" /></td></tr>
	  <tr>
	   <td>Creado</td>
	   <td>".formato_fecha($fila['creado'], false);
	/*  <tr>
	   <td>&Uacute;ltimo login</td>
	   <td>".$ultimo_login;
	*/
   }
?>
</td></tr>
	  <tr>
	   <td><label for="adclave">Contrase&ntilde;a</label>:</td>
	   <td><input type="password" name="adclave" id="adclave" size="22" maxlength="30" tabindex="2" /><?php echo $req; ?></td></tr>
	   <tr>
	   <td><label for="adclave2">Repita contrase&ntilde;a</label>:</td>
	   <td><input type="password" name="adclave2" id="adclave2" size="22" maxlength="30" tabindex="3" /><?php echo $req; ?></td></tr>
	  <tr>
	   <td><label for="email">E-mail</label>:</td>
	   <td><input type="text" name="email" id="email" value="<?php echo $fila['email']; ?>" size="22" maxlength="70" tabindex="6" /><?php echo $req; ?></td></tr>

	  <tr>
	   <th colspan="2">Datos personales</th></tr>
	  <tr>
	   <td><label for="nombre">Nombre</label></td>
	   <td><input type="text" name="nombre" id="nombre" value="<?php echo $fila['nombre'] ?>" /></td></tr>
	  <tr>
	   <td><label for="apellido">Apellido</label></td>
	   <td><input type="text" name="apellido" id="apellido" value="<?php echo $fila['apellido'] ?>" /></td></tr>
<?php

	  if(!$cons_paises = $mysqli->query("SELECT p.id, COALESCE(pn.nombre, p.codigo) FROM paises p LEFT JOIN paises_nombres pn ON p.id = pn.id AND pn.leng_id = 1")) echo __LINE__." - ".$mysqli->error;
	  if($cons_paises->num_rows > 1)
	   {
	   	echo '<tr><td><label for="pais">País</label></td><td><select name="pais">';
	    while($fila_paises = $cons_paises->fetch_row())
	     {
		  echo "<option value=\"{$fila_paises[0]}\"".($fila_paises[0] == $fila['pais_id'] ? ' selected="selected"' : '').">{$fila_paises[1]}</option>";
	     }
	 	$cons_paises->close();
	 	echo '</select></td></tr>';
	   }


?>
	  <tr>
	   <td><label for="departamento">Departamento</label></td>
	   <td><input type="text" name="departamento" id="departamento" value="<?php echo $fila['estado'] ?>" /></td></tr>
	  <tr>
	   <td><label for="ciudad">Ciudad</label></td>
	   <td><input type="text" name="ciudad" id="ciudad" value="<?php echo $fila['ciudad'] ?>" /></td></tr>
	  <tr>
	   <td><label for="direccion">Dirección</label></td>
	   <td><input type="text" name="direccion" id="direccion" value="<?php echo $fila['direccion'] ?>" /></td></tr>
	  <tr>
	   <td><label for="telefono">Teléfono</label></td>
	   <td><input type="text" name="telefono" id="telefono" value="<?php echo $fila['telefono'] ?>" /></td></tr>
	  <tr>
	   <td><label for="celular">Celular</label></td>
	   <td><input type="text" name="celular" id="celular" value="<?php echo $fila['celular'] ?>" /></td></tr>


<?php

/*
	include('inc/formulario.php');
	$i = 1;
	foreach($atributos AS $k => $a)
	 {
	  $req = ($a['sugerido'] == 2) ? "<span>*</span>": false;
	  if($a['sugerido'] == 0 && !$valores[$k]) continue;
	  $v =  is_array($valores[$k][0]) ? $valores[$k][0][0] : false;
	  //$x = var_export($valores[$k], true);
	  echo "
	  <tr>
	   <td>".pedir_dato($k, $v, $valores[$k], $a['tipo'], $a['subtipo'], $a['nombre'], $i, $a['identificador'])."{$req}</td></tr>";
	  $i++;
	 }
*/
	echo "
	 </tbody>
	</table>
	</form>";

 }

/* por omision */
if(!$no_poromision)
 {
  if($_SESSION['permisos']['admin_seccion'][$seccion_id] >= 2)
   {
	echo "
    <div id=\"opciones\"><a href=\"/administradores?ia=editar\">Agregar administrador</a></div>";
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

  //"SELECT `us`.`id` AS `id`,`us`.`usuario` AS `usuario`,group_concat(`uv`.`string` order by `uv`.`atributo_id` DESC separator ', ') AS `nombre_mostrar`,`us`.`email` AS `email`,unix_timestamp(`us`.`ultimo_login`) AS `ultimo_login`,`us`.`admin` AS `admin` from (`usuarios` `us` left join `usuarios_valores` `uv` on(((`us`.`id` = `uv`.`usuario_id`) and ((`uv`.`atributo_id` = _utf8'1') or (`uv`.`atributo_id` = _utf8'2'))))) WHERE (`us`.`usuario` <> _utf8'etdp') group by `us`.`id` AND `admin` = '1' ORDER BY {$db_orden} LIMIT {$desde},{$a}";
  //"SELECT us.id, usuario, GROUP_CONCAT(`string` ORDER BY atributo_id DESC SEPARATOR ', ') AS nombre_mostrar, email, ua.tiempo AS ultimo_login, admin FROM `usuarios` us LEFT JOIN usuarios_valores uv ON us.id = uv.usuario_id AND (uv.atributo_id = '1' OR uv.atributo_id = '2') LEFT JOIN usuarios_accesos ua ON us.id = ua.usuario_id WHERE `usuario` != 'etdp' AND us.id <> '".$_SESSION['usuario_id']."' AND `admin` = '1' GROUP BY us.id ORDER BY {$db_orden} LIMIT {$desde},{$a}"
//echo          htmlspecialchars();
  if(!$result = $mysqli->query("SELECT id, usuario, estado_id, nombre_mostrar, email, creado FROM `usuarios` WHERE `creado_por` = {$_SESSION['usuario_id']} AND `admin` = '1' ORDER BY {$db_orden} LIMIT {$desde},{$a}")) echo basename(__FILE__)."[".__LINE__."] - ".$mysqli->error;
  if($fila = $result->fetch_row())
   {
//  <div id="listado_result">2 resultados en 1 páginas</div>
    echo "
	<form action=\"/permisos\" method=\"post\" onsubmit=\"return contarCheck('lista_item[]');\">
	<table class=\"tabla\" style=\"width:auto;\"
	 ><thead
	  ><tr class=\"orden\"
	   ><td style=\"width: 20px; text-align: center;\"><input type=\"checkbox\" name=\"checkTodos\" onclick=\"checkearTodo(this.form, this, 'lista_item[]');\" /></td
	   ><td{$ordencolor[1]}><a href=\"/administradores?orden={$ord_num[1]}\">{$ord_fl[1]}Usuario</a></td
	   ><td{$ordencolor[2]}><a href=\"/administradores?orden={$ord_num[2]}\">{$ord_fl[2]}Nombre</a></td
	   ><td{$ordencolor[3]}><a href=\"/administradores?orden={$ord_num[3]}\">{$ord_fl[3]}E-mail</a></td
	   ><td{$ordencolor[4]}><a href=\"/administradores?orden={$ord_num[4]}\">{$ord_fl[4]}Fecha creado</a></td
	  ></tr
	 ></thead
	 ><tbody";
    do
     {
	  $fecha = $fila[4] ? formato_fecha($fila[5], false) : "nunca";
      echo "
	  ><tr
	   ><td style=\"text-align:center;\"><input type=\"checkbox\" name=\"lista_item[]\" value=\"{$fila[0]}\" onclick=\"selFila(this, 1)\" /></td
	   ><td><a href=\"/administradores?id={$fila[0]}\">{$fila[1]}</a></td
	   ><td>{$fila[3]}</td
	   ><td><a href=\"mailto:{$fila[4]}\">{$fila[4]}</a></td
	   ><td>{$fecha}</td
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
           { echo " <a href=\"/permisos?de={$ia}\">{$i}</a> "; }
          else
           { echo " <b>{$i}</b> "; }
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