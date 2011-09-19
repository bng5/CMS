<?php

require('inc/iniciar.php');
header('Content-type: text/plain; charset=utf-8');
//header('Content-type: application/json; charset=utf-8');

$extra = $_POST['extra'][$_POST['tipo']] ? "'".serialize($_POST['extra'][$_POST['tipo']])."'" : 'NULL';
echo "

INSERT INTO items_atributos (identificador, sugerido, unico, tipo_id, extra) VALUES ('{$_POST['identificador']}', {$_POST['sugerido']}, {$_POST['unico']}, {$_POST['tipo']}, ${extra});

";
print_r($_POST);


exit;

$mysqli = BaseDatos::Conectar();
/* echo '<?xml version="1.0" encoding="utf-8"?>'; */


  if($_POST['accion'] == "ag_atributo")
   {
    if(!empty($_POST['identificador']))
     {
	  setlocale(LC_CTYPE, 'es_UY.UTF-8');
	  $unarray = array();
	  if($_POST['tipo'] == 8 || $_POST['tipo'] == 10)
	    $extra = "'array (0 => array (0 => \\'recortar\\',1 => 200,2 => 200,),1 => array (0 => \\'recortar\\',1 => 40,2 => 40,),)'";
	  elseif($_POST['tipo'] == 11)
	    $extra = "'array(0 => \\'No\\', \\'Si\\')'";
	  elseif($_POST['tipo'] == 22)
	    $extra = "'array(1 => 1,2,3,4,5)'";
	  else
	    $extra = "NULL";
/*
 si es imagen se guarda este array en `extra`
$unarray[] = array("escalar", false, 225);
$unarray[] = array("recortar", 20, 20);
$elarray = str_replace(array("\n", "  "), "", var_export($unarray, true));
echo $elarray;
*/

$identificador = str_replace(" ", "_", strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $_POST['identificador'])));
$unico = $_POST['unico'] ? $_POST['unico'] : '0';
$tipo = ($_POST['tipo'] + $_POST['conf'][$_POST['tipo']]['idmodif']);
	  $mysqli->query("INSERT INTO items_atributos (`identificador`, `sugerido`, `unico`, `tipo_id`, `extra`) VALUES ('${identificador}', '{$_POST['sugerido']}', '{$unico}', '{$tipo}', ${extra})");
      if($id = $mysqli->insert_id)
       {
       	if(($_POST['tipo'] == 8 || $_POST['tipo'] == 10) && !@is_dir(RUTA_CARPETA.'public_html/img/0/'.$id))
       	 {
		  @mkdir(RUTA_CARPETA.'public_html/img/0/'.$id);
		  @mkdir(RUTA_CARPETA.'public_html/img/1/'.$id);
       	 }
       	$etiqueta = current($_POST['leng']) ? current($_POST['leng']) : $_POST['identificador'];
		$en_listado = 0;//$_POST['en_listado'] ? $_POST['en_listado'] : 0;
		$salida = 0;//($_POST['salida'] == 1) ? 0 : 1;
		$superior = $_POST['superior'] ? $_POST['superior'] : '0';
		$mysqli->query("INSERT INTO items_secciones_a_atributos (`seccion_id`, `atributo_id`, `en_listado`, `salida`, `superior`) VALUES ('{$_REQUEST['seccion']}', '${id}', ${en_listado}, ${salida}, {$superior})");
		$eliminar_pub = true;
        foreach($_POST['leng'] AS $idioma_k => $idioma_v) $mysqli->query("INSERT INTO items_atributos_n (`id`, `leng_id`, `atributo`) VALUES ('${id}', '${idioma_k}', '${idioma_v}')");
		/*
		if(in_array($_POST['tipo'], $camposC_opciones))
		 {
		  foreach($_POST['opciones'] AS $co)
		   {
		   	$primer = current($co);
		   	if(empty($primer)) continue;
			$mysqli->query("INSERT INTO campos_opciones (`campo_id`) VALUES (${id})");
			$co_id = $mysqli->insert_id;
			foreach($co AS $co_k => $co_v)
			 {
			  if(empty($co_v)) continue;
			  $mysqli->query("INSERT INTO campos_opciones_textos (`id`, `leng_id`, `texto`) VALUES (${co_id}, '${co_k}', '${co_v}')");
			 }
		   }
		 }
		*/
       }
      //echo "No fue posible ingresar el atributo.";
     }
   }

$fila = $_POST['conf'][$_POST['tipo']];
//<xml>
?>
<?php

echo "<div class=\"linea\"
    ><input type=\"hidden\" name=\"attr[]\" value=\"{$id}\"
   /><input type=\"hidden\" name=\"superior[{$id}]\" value=\"{$superior}\"
   /><span class=\"celda1\"><img src=\"/img/".(($fila['nodo_tipo'] != 1) ? 'e" onclick="listaColapsar(this)' : 'trans')."\" alt=\"\" /></span
    ><span class=\"celda2\" onmousedown=\"return arrastrar(this, event)\">{$identificador}</span
    ><span class=\"celda10\"><a href=\"".APU."campo?id={$id}&amp;seccion={$_POST['seccion']}\">Editar</a></span
    ><span class=\"celda9\"><input type=\"checkbox\" name=\"salida[{$id}]\" value=\"1\"".($fila['op_oculto'] ? $sel[$salida] : ' disabled="disabled" title="Este atributo no se puede ocultar."')." /></span
    ><span class=\"celda8\"><input type=\"checkbox\" name=\"en_listado[{$id}]\" value=\"1\"".($fila['op_listado'] ? $sel[$fila['en_listado']] : ' disabled="disabled" title="Este atributo no se puede ocultar."')." /></span
    ><span class=\"celda7\">{$poromision[$id]}&#160;</span
    ><span class=\"celda6\" title=\"{$tipos[$fila['tipo_id']][0]}\">{$tipos[$fila['tipo_id']][0]}&#160;</span
    ><span class=\"celda5\">{$sugerido_ops[$fila['unico']]}&#160;</span
    ><span class=\"celda4\">{$sugerido_ops[$fila['sugerido']]}&#160;</span
    ><span class=\"celda3\">{$fila['atributo']}&#160;</span
  ></div><hr onmouseover=\"resaltarSep(this, true)\" onmouseout=\"resaltarSep(this, false)\"
 />";
//echo "<post>".htmlspecialchars(var_export($_POST, true))."</post>
//<fila>".htmlspecialchars(var_export($fila, true))."</fila>";
//</xml>


echo "<pre>";
print_r($_POST);
echo "</pre>";

?>