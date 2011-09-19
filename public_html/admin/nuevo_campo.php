<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <title>jaser18</title>
 <script type="text/javascript" src="/js/configuracion.js" charset="utf-8"></script>
</head>
<body>
<noscript><div id="no_js_alerta"><img src="/img/warning" alt="" /> Para visualizar correctamente este sitio su navegador debe contar con <b>JavaScript</b> habilitado.</div></noscript>
<div>
<!-- onsubmit="return enviarPost(this, '/conf_nuevo_campo', 'nodosAttr', this.previousSibling)" -->
<form action="/conf_nuevo_campo" method="post">
   <input type="hidden" name="accion" value="ag_atributo" />
   <input type="hidden" name="seccion" value="11" />

<button type="submit">Enviar</button>

<fieldset>
 <legend>Agregar atributo</legend>
 <ul>
  <li><label for="identificador">Identificador:</label> <input type="text" name="identificador" id="identificador" size="15" maxlength="15" /></li>
  <li><label for="tipo">Tipo:</label> <select name="tipo" id="tipo"><!-- onchange="mostrarExtra(this.options[this.selectedIndex].value)" -->
	  	<option value=""> -- Seleccione -- </option>
	    <option value="1">Texto</option>
		<option value="2">Color</option>
	  	<option value=""> </option>
		<option value="15">Área de texto</option>
		<option value="6">Número</option>
		<option value="16">Precio</option>
		<option value="11">Lista de opciones</option>
	    <option value="4">Fecha y hora</option>
		<option>Duración</option>
	    <option value="22">Enlace externo (dato)</option>
		<option value="7">Dato externo</option>
	   <optgroup label="Objetos">
	    <option value="9">Archivo</option>
	    <option value="8">Imagen</option>
	    <option value="10">Galería de imágenes</option>
		<option value="26">Enlace</option>
	   </optgroup>
	   <optgroup label="Grupos">
	    <option value="19">Área</option>
		<option value="23">Formulario</option>
	   </optgroup>
	   <optgroup label="Ingreso de datos">
		<option>Campo de texto</option>
		<option>Contraseña</option>
		<option>Área de texto</option>
		<option>Selector</option>
		<option>Selector múltiple</option>
	   </optgroup>
	   <optgroup label="Servicios">
	    <option>Fuente web</option>
	    <option>YouTube Video</option>
	   </optgroup>
	   <optgroup label="Obsoletos">
	    <option value="3">Contraseña</option>
	   </optgroup>
	  </select></li>
  <li><label>Obligatorio:</label> <input type="radio" name="sugerido" id="sugerido2" value="2" /><label for="sugerido2">Si</label> <input type="radio" name="sugerido" id="sugerido1" value="1" checked="checked" /><label for="sugerido1">No</label></li>
  <li><label>Único:</label> <input type="radio" name="unico" id="unico1" value="1" checked="checked" /><label for="unico1">Si</label> <input type="radio" name="unico" id="unico0" value="0" /><label for="unico0">No</label> <input type="radio" name="unico" id="unico2" value="2" /><label for="unico2">Multilingüe</label></li>
  <li><label>Etiqueta/s:</label>
   <ul class="campo_lista">
	<li><label for="leng9"><tt>(am)</tt></label> <input type="text" name="etiqueta[9]" id="leng9" /></li>
	<li><label for="leng107"><tt>(en-us)</tt></label> <input type="text" name="etiqueta[107]" id="leng107" /></li>
	<li><label for="leng76"><tt>(es-uy)</tt></label> <input type="text" name="etiqueta[76]" id="leng76" /></li>
   </ul>
  </li>
 </ul>
</fieldset>


<fieldset>
 <legend>Valor por omisión</legend>
</fieldset>

<fieldset>
 <legend>Texto</legend>
 <ul>
  <li><label for="conf_1_maxl">Largo máximo</label> <input type="text" name="extra[1][maxl]" id="conf_1_maxl" value="200" size="3" /></li>
  <li>Con formato <input type="radio" name="extra[1][cformato]" id="conf_1_cformato1" value="1" /><label for="conf_1_cformato1">Si</label> <input type="radio" name="extra[1][cformato]" id="conf_1_cformato0" value="0" checked="checked" /><label for="conf_1_cformato0">No</label></li>
  <li>Multilínea <input type="radio" name="extra[1][mlinea]" id="conf_1_mlinea" value="1" /><label for="conf_1_mlinea">Si</label> <input type="radio" name="extra[1][mlinea]" id="conf_1_mlinea0" value="0" checked="checked" /><label for="conf_1_mlinea0">No</label></li>
 </ul> 
</fieldset>

<!-- fieldset>
 <legend>Texto</legend>
 <ul>
  <li><label for="conf_1_maxl">Largo máximo</label> <input type="text" name="conf[1][extra][maxl]" id="conf_1_maxl" value="200" size="3" /></li>
  <li>Con formato <input type="radio" name="conf[1][cformato]" id="conf_1_cformato1" value="1" /><label for="conf_1_cformato1">Si</label> <input type="radio" name="conf[1][cformato]" id="conf_1_cformato0" value="0" checked="checked" /><label for="conf_1_cformato0">No</label></li>
  <li> </li>
  <li> </li>
 </ul>
</fieldset -->

 </form>

<table>
<tbody id="extra6" class="conf_extra">
<tr><th colspan="2">
 <p>Número</p>
 <p>6 , 17 , 25</p>
 </th></tr>

  <tr>
	<td><label>Único:</label></td>
	<td><input type="radio" name="extra[6][unico]" value="1" checked="checked" /><label>Único</label> <input type="radio" name="extra[6][unico]" value="0" /><label>Lista</label></td></tr>
  <tr>
   <td>Tipo</td>
   <td><input type="radio" name="extra[6][numtipo]" />Natural <input type="radio" name="extra[6][numtipo]" />Entero <input type="radio" name="extra[6][numtipo]" />Decimal </td>

  </tr>
 </tbody>


<tbody id="extra2" class="conf_extra">
<tr><th colspan="2"> <p>Color</p>
 <p>2</p>
</th></tr>
  <tr>
	<td><label>Único:</label></td>

	<td><input type="radio" name="extra[2][unico]" value="1" checked="checked" /><label>Único</label> <input type="radio" name="extra[2][unico]" value="0" /><label>Lista</label></td></tr>
 </tbody>


<tbody id="extra9" class="conf_extra">
<tr><th colspan="2">Archivo</th></tr>
   <tr>
   <td>Multilíngüe</td>

   <td><input type="radio" name="extra[9][mlingue]" checked="checked" />Si <input type="radio" name="extra[9][mlingue]" />No </td>
  </tr>
   <tr>
	<td><label>Único:</label></td>
	<td><input type="radio" name="extra[9][unico]" value="1" checked="checked" /><label>Único</label> <input type="radio" name="extra[9][unico]" value="0" /><label>Lista</label></td></tr>
	  <tr>

	   <td><label>Extensiones <select name="extra[9][tipo]"><option value="permitidos">permitidas</option><option value="negados">denegadas</option></select>:</label></td>
	   <td><input type="text" name="extra[9][extensiones]" value="" size="30" maxlength="30" title="Ingrese extensiones separadas por comas (,)" /></td></tr>
 </tbody>

<tbody id="extra8" class="conf_extra">
<tr><th colspan="2">
 <p>Imagen / Galería de imágenes</p>
 <p>8 , 10</p>

</th></tr>
   <tr>
	<td><label>Único:</label></td>
	<td><input type="radio" name="unico" value="1" checked="checked" /><label>Único</label> <input type="radio" name="unico" value="0" /><label>Lista</label></td></tr>
	  <tr>
	   <td>Imagen</td>
	   <td>

	    <ul>
	     <li><label for="metodo_img">Método</label> <select name="metodo_img" id="metodo_img" onchange="imgHabMinimo(this)"><option value="escalar">Escalar</option><option value="recortar" selected="selected">Recortar</option></select></li>
	     <li><label for="ancho_img">Ancho</label> <input type="text" name="ancho_img" id="ancho_img" value="300" size="4" maxlength="4" /> <label for="minancho_img">mínimo</label> <input type="text" name="minancho_img" id="minancho_img" value="" size="4" maxlength="4" disabled="disabled" /></li>
	     <li><label for="alto_img">Alto</label> <input type="text" name="alto_img" id="alto_img" value="200" size="4" maxlength="4" /> <label for="minalto_img">mínimo</label> <input type="text" name="minalto_img" id="minalto_img" value="" size="4" maxlength="4" disabled="disabled" /></li>

	     <li><label for="marca">Usar marca de agua</label> <input type="checkbox" name="marca" id="marca" value="1"   onclick="mostrarMarcas(this)" /><input type="hidden" name="marca_arch" value="" /> <img src="img/trans" id="img_marca" alt="gnome-gmush.png" onclick="document.getElementById('img_mustraOp').style.display='block'" /><ul>
		 <li>Posición horizontal: <select name="posX"><option value="1">desde la izquierda</option><option value="0" selected="selected">al centro</option><option value="3">desde la derecha</option></select> <input type="text" name="pxX" size="3" /></li>
		 <li>Posición vertical: <select name="posY"><option value="1">desde arriba</option><option value="0" selected="selected">al centro</option><option value="3">desde abajo</option></select> <input type="text" name="pxY" size="3" /></li></ul></li>

	    </ul></td></tr>
	  <tr>
	   <td>Miniatura</td>
	   <td>
	    <ul>
	     <li><label for="metodo_imgch">Método</label> <select name="metodo_imgch" id="metodo_imgch"><option value="escalar">Escalar</option><option value="recortar" selected="selected">Recortar</option></select></li>

	     <li><label for="ancho_imgch">Ancho</label> <input type="text" name="ancho_imgch" id="ancho_imgch" value="40" size="4" maxlength="4" /></li>
	     <li><label for="alto_imgch">Alto</label> <input type="text" name="alto_imgch" id="alto_imgch" value="40" size="4" maxlength="4" /></li>
	    </ul></td></tr>
	  <tr>
	   <td colspan="2" id="regImagenes"><a onclick="iniciarRegImgs(9)">Regenerar imágenes</a> <span>&#8203;</span></td></tr>
 </tbody>


<tbody id="extra11" class="conf_extra">
<tr><th colspan="2">Selector de opciones
 <p>11 , 12 , 13 , 14 , </p>
</th></tr>
   <tr>
   <td>Multilíngüe</td>
   <td><input type="radio" name="mlingue" checked="checked" />Si <input type="radio" name="mlingue" />No </td>
  </tr>

  <tr>
	<td><label>Múltiple:</label></td>
	<td><input type="radio" name="unico" value="1" checked="checked" /><label>Si</label> <input type="radio" name="unico" value="0" /><label>No</label></td></tr>
  <tr>
   <td>Visualización:</td>
   <td><input type="radio" name="vis" />Lista <input type="radio" name="vis" />Compacto</td>

  </tr>
	  <tr>
	   <td>Opciones</td>
	   <td>
	    <ul><li><input type="text" name="extra_v[0]" value="" /></li><li><input type="text" name="extra_v[1]" value="" /></li><li><input type="text" name="extra_v[2]" /></li><li><input type="text" name="extra_v[3]" /></li>
	    </ul></td></tr>
 </tbody>

<tbody id="extra4" class="conf_extra">

<tr><th colspan="2">
 Fecha y hora
 <p>4 , 5</p>
</th></tr>
  <tr>
   <td>Hora</td>
   <td><input type="radio" name="hora" value="1" checked="checked" /><label>Si</label> <input type="radio" name="hora" value="0" /><label>No</label></td>
  </tr>

 	  <tr>
	   <td>Formato</td>
	   <td><select name="extra_v">
	    <optgroup label="Formatos Predeterminados">
	     <option value="1">Corto</option>
	     <option value="2">Largo</option>
	    </optgroup>

	    <!-- optgroup label="Fecha/hora completa">
	     <option value="3">ISO 8601</option>
	     <option value="4">RFC 2822</option>
	     <option value="5">Epoch</option>
	    </optgroup -->
	    </select></td></tr>
</tbody>


<tbody id="extra22" class="conf_extra">
<tr><th colspan="2">
Enlace
</th></tr>
   <tr>
	<td><label>Único:</label></td>
	<td><input type="radio" name="unico" value="1" checked="checked" /><label>Único</label> <input type="radio" name="unico" value="0" /><label>Lista</label></td></tr>

	  <tr>
	   <td>Protocolos</td>
	   <td>
	    <ul>
	     <li><input type="checkbox" name="extra[1]" value="1" id="ex1" /> <label for="ex1">http</label></li>
	     <li><input type="checkbox" name="extra[2]" value="2" id="ex2" /> <label for="ex2">https</label></li>

	     <li><input type="checkbox" name="extra[3]" value="3" id="ex3" /> <label for="ex3">ftp</label></li>
	     <li><input type="checkbox" name="extra[4]" value="4" id="ex4" /> <label for="ex4">gopher</label></li>
	     <li><input type="checkbox" name="extra[5]" value="5" id="ex5" /> <label for="ex5">mailto</label></li>
	    </ul></td></tr>
 </tbody>


<tbody id="extra7" class="conf_extra">
<tr><th colspan="2">Dato externo
 <p>7</p>
</th></tr>
	  <tr>
	   <td>Sección:</td>
	   <td><select name="_a"><option value="10">Productos</option><option value="11">Todos los atributos</option></select>

</td></tr>
	  <tr>
	   <td>Campo:</td>
	   <td><select id="asdfg"><option>&#8203;</option></select></td></tr>
</tbody>


<tbody id="extra23" class="conf_extra">
<tr><th colspan="2">Formulario
 <p>23</p>
</th></tr>
	  <tr>

	   <td>Destino</td>
	   <td><input type="text" /></td>
	  </tr>
	  <tr>
	   <td>Método</td>
	   <td><select><option>post</option><option>multipart-post</option><option>form-data-post</option><option>put</option><option>get</option></select></td>

	  </tr>
</tbody>



<tbody id="extra16" class="conf_extra">
<tr><th colspan="2">
 Precio
16
 </th></tr>
	  <tr>
	   <td>Moneda</td>
	   <td><select name="extra_v"><option value="1">Pesos Uruguayos</option></select></td></tr>

</tbody>
	</table>

</div>
</body>
</html>