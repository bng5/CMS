<?php

if($seccion)
 {
  $sel_pos[$seccion->orden] = " checked=\"checked\"";
  $sel_info[$seccion->info] = " checked=\"checked\"";
  $sel_items[$seccion->items] = " checked=\"checked\"";
  $sel_categorias[$seccion->categorias] = " checked=\"checked\"";
  $sel_pub[$seccion->publicacion] = " selected=\"selected\"";
  $sel_menu = $seccion->menu ? 'checked="checked"' : '';
  $nombres = $seccion->nombres;

  $seccionesJS = Secciones::Listado($_SESSION['leng_id'], array('sistema' => 0));

?>
    <form name="formedicion" id="formedicion" action="/secciones" method="post">
	 <input type="hidden" name="id" value="<?php echo $seccion->id ?>" />
	 <input type="hidden" name="rev" value="<?php echo $seccion->revision ?>" />
	 <input type="hidden" name="pos_actual" value="<?php echo $seccion->orden ?>" />
	 <!-- input type="hidden" name="superior" value="< ? php echo $seccion->superior ? >" / -->
	 <table class="tabla">
	  <thead>
	   <tr>
	    <th colspan="2"><?php echo $transaccion_txt ?></th></tr>
	  </thead>
	  <tfoot>
	   <tr>
	    <td align="center" colspan="2"><input type="button" value="Cancelar" onclick="document.location.href='/secciones'" />&nbsp;&nbsp;<input type="submit" name="btGuardar" id="guardar" value="Guardar" /></td></tr>
	  </tfoot>
	  <tbody>
	   <tr>
	    <td><label>Sección</label>:</td>
	    <td>
<?php

  if($idiomas = Idiomas::Listado())
   {

	$iterador = $idiomas->getIterator();
	echo '<ul class="campo_lista">';
	foreach($iterador AS $item)
	  echo '<li><label for="nombre'.$item->codigo.'" class="etiqueta_idioma" title="'.$item->nombre.'"><tt>('.$item->codigo.')</tt></label>&nbsp;<input type="text" name="nombre['.$item->id.']" id="nombre'.$item->codigo.'" value="'.$nombres[$item->id].'" lang="'.$item->codigo.'" dir="'.$item->dir.'" size="32" maxlength="32" onblur="completarIdent(this)" /></li>';
	echo '</ul>';
   }

?>
</td></tr>
	   <tr>
	    <td><label for="identificador">Identificador</label>:</td>
	    <td><input type="text" name="identificador" id="identificador" value="<?php echo $seccion->identificador ?>" maxlength="32" /></td></tr>
	   <tr>
	    <td><label>Información</label>:</td>
	    <td><input type="radio" name="info" id="info1" value="1" <?php echo $sel_info[1] ?>/><label for="info1">si</label> <input type="radio" name="info" id="info0" value="0" <?php echo $sel_info[0] ?>/><label for="info0">no</label></td></tr>
       <tr>
	    <td><label>Items</label>:</td>
	    <td><input type="radio" name="items" id="items1" value="1" <?php echo $sel_items[1] ?>/><label for="items1">si</label> <input type="radio" name="items" id="items0" value="0" <?php echo $sel_items[0] ?>/><label for="items0">no</label></td></tr>
       <tr>
	    <td><label>Categorías</label>:</td>
	    <td><input type="radio" name="categorias" id="categorias1" value="1" <?php echo $sel_categorias[1] ?> onchange="habProfCats(this)" /><label for="categorias1">si</label> <input type="radio" name="categorias" id="categorias0" value="0" <?php echo $sel_categorias[0] ?> onchange="habProfCats(this)" /><label for="categorias0">no</label> / profundidad: <input type="text" name="prof_categorias" value="<?php echo $seccion->categorias_prof ?>" size="2" <?php echo $seccion->categorias ? '' : 'disabled="disabled"' ?> /></td></tr>

<?php

  if($seccionesJS)
   {
?>
	   <tr>
	    <td>Ubicación:</td><td><select name="superior" onchange="actOrdenSel(this.options[this.selectedIndex].value)"><option value="0">(Inicio)</option></select></td></tr>
	   <tr>
	    <td><label>Orden</label>:</td>
	    <td><ul style="list-style-type:none;">
		  <li><input type="radio" name="pos" value="1" id="pos1" /> <label for="pos1">Último</label></li>
		  <li><input type="radio" name="pos" value="2" id="pos2" /> <label for="pos2">Antes de </label><select name="antesde"><option value="6" disabled="disabled">Agencia</option><option value="7">Medios</option><option value="8">Clientes</option><option value="9">Trabajos</option></select></li>
		 </ul></td></tr>

<?php
   }
?>

	   <!-- tr>
	    <td><label for="permiso_min">Permiso mínimo</label>:</td>
	    <td><select name="permiso_min" id="permiso_min"><option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option></select></td></tr -->

	   <tr>
	    <td><label for="salida">Publicación</label>:</td>
	    <td><select name="salida" id="salida" onchange="habMenuCheck(this, document.getElementById('enmenu'))"><option value="0" <?php echo $sel_pub[0] ?>>Inaccesible</option><option value="1" <?php echo $sel_pub[1] ?>>Público</option><option value="2" <?php echo $sel_pub[2] ?>>Requiere autenticación</option></select> <input type="checkbox" name="enmenu" id="enmenu" <?php echo $sel_menu ?> /><label for="enmenu"> En menú</label> </td></tr>

	  </tbody>
	 </table>
	</form>
<?php

  if($seccionesJS)
   {
?>
<script type="text/javascript">
//<![CDATA[
var CATEGORIASEL = <?php echo $seccion->superior ?>;
var CATEGORIAS = {};
var CATEGORIAS_N = {};
<?php


    $constructores = array();
    foreach($seccionesJS AS $v)
     {
	  if(!$constructores[$v->superior_id])
	   {
	    $constructores[$v->superior_id] = true;
	    echo '
CATEGORIAS['.$v->superior_id.'] = {};';
	   }
	  echo '
CATEGORIAS['.$v->superior_id.']['.$v->id.'] = '.$v->id.';
CATEGORIAS_N['.$v->id.'] = \''.$v->titulo.'\';';
     }

?>


function actOrdenSel(id)
 {
  CATEGORIASEL = id;
  actOrden();
 }

function actOrden()
 {
  var j_orden = document.forms[0]['antesde'];
  while(j_orden[0])
    j_orden[0] = null;
  var i = 0;
  for(var x in CATEGORIAS[CATEGORIASEL])
   {
	j_orden[i++] = new Option(CATEGORIAS_N[x], CATEGORIAS[CATEGORIASEL][x]);
   }
  if(i == 0)
   {
   	document.forms[0]['pos'][0].disabled = true;
   	document.forms[0]['pos'][1].disabled = true;
   	document.forms[0]['antesde'].disabled = true;
   }
  else
   {
   	document.forms[0]['pos'][0].disabled = false;
   	document.forms[0]['pos'][1].disabled = false;
   	document.forms[0]['antesde'].disabled = false;
   }
 }

agregarEvento(window, 'load', actOrden);

//]]>

</script>
<br />
<?php
   }
 }
/*----------------------------------------------------------------------------*/

if($id !== null)
  echo '  <div id="opciones"><a href="/secciones?ia=agregar&amp;superior='.$id.'">Agregar sección</a></div>';

/*----------------------------------------------------------------------------*/
if($secciones)
 {
?>

	<form action="/secciones?id=<?php echo $id ?>" method="post" onsubmit="return contarCheck('lista_item[]');">
	 <table class="tabla" id="tablaListado">
	  <thead>
	   <tr class="orden">
	    <td style="width:20px;text-align:center;"><input type="checkbox" name="checkTodos" onclick="checkearTodo(this.form, this, 'lista_item[]');" /></td>
	    <td>T&iacute;tulo</td>
	   </tr>
	  </thead>
	  <tbody>

<?php

  $iterador = $secciones->getIterator();
  foreach($iterador AS $item) {
	if(!$item->titulo)
	    $item->titulo = $item->identificador;
	echo '
	   <tr class="">
	    <td style="width:20px;text-align:center;"><input type="checkbox" name="lista_item[]" id="lista_item'.$item->id.'" value="'.$item->id.'" onclick="selFila(this, \'\');" /></td>
	    <td><a href="/secciones?id='.$item->id.'">'.$item->titulo.'</a></td></tr>';
//   <li><a href=\"/secciones?id={$item->id}\">{$item->titulo}</a></li>";
	$js_celdaClases[$item->id] = 1;
   }
?>
	  </tbody>
	 </table>
	 <script type="text/javascript">
	 var celdaClases = {};
<?php
  foreach($js_celdaClases AS $k => $v)
   {
	echo "
	 celdaClases[${k}] = ${v};";
   }
?>

     </script>
     <div id="error_check_form" class="div_error" style="display:none;">No ha seleccionado ningún elemento de la lista.</div>
     <div id="listado_opciones" style="padding:4px;"><img src="./img/flecha_arr_der" alt="Para los items seleccionados" style="padding:0 5px;" /><input type="submit" name="mult_submit[eliminar]" value="Eliminar" onclick="return confBorrado('lista_item[]');" />&nbsp;<input type="submit" name="mult_submit[habilitar]" value="Habilitar" />&nbsp;<input type="submit" name="mult_submit[deshabilitar]" value="Deshabilitar" /></div>
     <div id="listado_result"></div>
    </form>

<?php
 }
?>