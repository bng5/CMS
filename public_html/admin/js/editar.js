var TEXTOS = {eliminar : 'Eliminar', errorArchivo : ['No fue posible subir el archivo.', 'El tamaño del archivo excede el límite.'], guardar : 'Guardar', guardando : 'Guardando...'};

function agAdjunto(boton, atributoId, dest, indice, retorno)
 {
  var divSubir = document.getElementById('subirArchivos');
  galeriaDiv = boton.parentNode.childNodes[1];
  divSubir.style.top = (window.innerHeight/2 - 50)+'px';
  divSubir.style.left = (window.innerWidth/2 - 180)+'px';
  divSubir.style.display = 'inline';
  document.forms['en'].reset();
  document.forms['en'].action = dest;
  document.forms['en']['atributo'].value = atributoId;
  document.forms['en']['indice'].value = indice;
  if(document.forms['en']['retorno'])
	document.forms['en']['retorno'].value = retorno ? retorno : '';
  var subirArchivosaviso = document.getElementById('subirArchivosaviso');
  subirArchivosaviso.innerHTML = '';
  subirArchivosaviso.style.visibility = 'hidden';
  /*
  var div = document.createElement('div');
  var seccion = document.createElement('input');
  seccion.setAttribute('type', 'file');
  seccion.setAttribute('name', 'seccion['+id+'][adjuntos][]');
  div.appendChild(seccion);
  boton.parentNode.parentNode.appendChild(div);
  */
 }

function docNCargado(errorno, archivoId, archivo, archivo_nombre, indice)
 {
  var lista = document.getElementById('lista_'+indice);
  borrar = new Image();
  // ruta
  borrar.src = 'img/b_drop_ch.png';
  if(errorno == false)
   {
   	var li = document.createElement('li');
   	var span = document.createElement('span');
   	//while(span.firstChild) span.removeChild(span.firstChild);
	span.appendChild(document.createTextNode(archivo_nombre));
    //document.getElementById('dato'+indice).value = archivoId;
	var campo = document.createElement('input');
	campo.setAttribute('type', 'hidden');
	var atributo = document.forms['en']['atributo'].value;
	campo.setAttribute('name', 'dato[n]['+atributo+'][]');
	campo.setAttribute('value', archivoId);
	borrar.setAttribute("alt", TEXTOS['eliminar']);
	borrar.setAttribute("title", TEXTOS['eliminar']);
	borrar['onclick'] = function()
	 {borrarOpArch(this);}
	li.appendChild(span);
	li.appendChild(borrar);
	li.appendChild(campo);
	lista.appendChild(li);
	document.getElementById('subirArchivos').style.display = 'none';
   }
  else
   {
   	document.getElementById('subirArchivosaviso').innerHTML = 'Error: '+errorUpload[errorno];
   }
 }

function crearIframe()
 {
  var date = new Date();
  var itiempo = date.getTime();
  var iframe = document.createElement('iframe');
  iframe.setAttribute("name", "fr"+itiempo);
  iframe.setAttribute("id", "fr"+itiempo);
  iframe.style.display = 'none';
  document.body.appendChild(iframe);
  return iframe;
 }

function iframeLoad(event) {
	try {
		params = eval('('+this.contentDocument.activeElement.textContent+')');
	}
	catch(e) {
		var r = confirm("La respuesta del servidor no pudo ser interpretada correctamente.\nTipo de error: "+e.name+"\n¿Desea ver la respuesta?");
		if(r == true)
			alert(this.contentDocument.activeElement.textContent);
		return;
	}
	params.funcion(params);
}

function subirImg(campo) {
	if(campo.value.length > 1) {
		var nframe = crearIframe();
		var formularioen = campo.form;
		formularioen.action += '?frame='+nframe.id;
		formularioen.target = nframe.id;
		formularioen.submit();
		nframe.addEventListener('load', iframeLoad , false);

		var subirArchivosaviso = document.getElementById('subirArchivosaviso');
		subirArchivosaviso.innerHTML = 'Enviando archivo, aguarde...';
		subirArchivosaviso.style.visibility = 'visible';
	}
}

var errorUpload = {};
errorUpload[1] = 'El archivo es más grande de lo permitido por la instalación';
errorUpload[2] = 'El archivo es más grande de lo permitido por el formulario';
errorUpload[3] = 'Solo una parte del archivo ha sido subida';
errorUpload[4] = 'No se subió ningún archivo';
errorUpload[5] = 'La extensión del archivo no es la esperada por el campo';
errorUpload[11] = 'No se encontró el archivo.';
errorUpload[12] = 'El archivo no es una imagen.';
errorUpload[13] = 'La imagen debe ser del tipo jpeg (jpg), gif o png.';
errorUpload[14] = 'Existen errores en la imagen.';
errorUpload[15] = 'Ocurrió un error inesperado.';
errorUpload[16] = 'Debe ser indicado un atributo válido.';
errorUpload[17] = 'No se recibió ningún archivo o se excedió el límite de memoria.';

function imgAreaTexto(params)
 {
  var errorno = params.errorno;
  var imagenArchivo = params.imagenArchivo;
  var indice = params.indice;
  var atributo = params.atributo;
  if(errorno == false)
   {
       // FIXME zooko??!!
	insertTags('dato'+indice+'_1', '\n{{', '}}\n', 'http://zooko.com.uy/img/0/'+atributo+'/'+imagenArchivo);
	document.getElementById('subirArchivos').style.display = 'none';
   }
  else
   {
   	document.getElementById('subirArchivosaviso').innerHTML = 'Error: '+errorUpload[errorno];
   }
  return true;
 }

function imgCargada(params) {
	var errorno = params.errorno;
	var imagenId = params.imagenId;
	var imagenArchivo = params.imagenArchivo;
	//var frame = params.frame;
	var indice = params.indice;
	var atributo = params.atributo;
	if(errorno == false) {
		var imagen = document.getElementById('img'+indice);
		var imagencont = imagen.parentNode;
		imagencont.removeChild(imagen);
		imagen = new Image();
		imagencont.insertBefore(imagen, imagencont.firstChild);
        // ruta
		imagen.src = 'icono/0/'+atributo+'/'+imagenArchivo; //+'&max=120';
		imagen.id = 'img'+indice;
		document.getElementById('dato'+indice).value = imagenId;
		document.getElementById('crop'+indice).style.display = 'inline';
		imagen.addEventListener('load', function() {
			document.getElementById('subirArchivos').style.display = 'none';
		}, false);
	}
	else {
		document.getElementById('subirArchivosaviso').innerHTML = 'Error: '+errorUpload[errorno];
	}
	return true;
}

function modalCrop(respuesta) {
	//document.getElementById('subirArchivos').style.display = 'none';
	/*
	var confirmacion = confirm("Las proporciones de la imagen podrían no ser correctas.\n¿Desea cortarla manualmente?");
	if(confirmacion) {
	*/
        // ruta
		abrirModal('crop?imagenId='+respuesta.imagenId+'&atributo='+respuesta.atributo+'&indice='+respuesta.indice, 700, 500);
	/*
	}
	else {
		imgCargada(respuesta);
	*/
	//}
}

function mostrarCrop(params) {
	var imagen = document.getElementById('img'+params.indice);
	var imagencont = imagen.parentNode;
   	imagencont.removeChild(imagen);
    imagen = new Image();
	imagencont.insertBefore(imagen, imagencont.firstChild);
	var d = new Date();
    // ruta
	imagen.src = 'icono/0/'+params.atributo+'/'+params.imagenArchivo+'?timestamp='+d.getTime(); //+'&max=120';

	imagen.id = 'img'+params.indice;
    document.getElementById('dato'+params.indice).value = params.imagenId;
	ventanaModal.close();
	ventanaModal = null;
}

function docCargado(params)
 {
  var errorno = params.errorno;
  var archivoId = params.archivoId;
  var archivo = params.archivo;
  var archivo_nombre = params.archivo_nombre;
  var indice = params.indice;

  //borrar = new Image();
  //borrar.src = '/img/b_drop_ch.png';
  if(errorno == false)
   {
   	var span = document.getElementById('archivo'+indice);
   	while(span.firstChild) span.removeChild(span.firstChild);
	span.appendChild(document.createTextNode(archivo_nombre));
    document.getElementById('dato'+indice).value = archivoId;
	borrar = span.nextSibling;
    // ruta
	borrar.src = 'img/b_drop_ch.png';
	borrar.setAttribute("alt", TEXTOS['eliminar']);
	borrar.setAttribute("title", TEXTOS['eliminar']);
	borrar['onclick'] = function()
	 {
	  borrarOpArch(this);
	  //span.parentNode.lastChild.value = '';
	  //while(span.firstChild) span.removeChild(span.firstChild);
	 }
	span.parentNode.lastChild.innerHTML = '<span>Cambiar</span>';
	document.getElementById('subirArchivos').style.display = 'none';
   }
  else
   {
   	document.getElementById('subirArchivosaviso').innerHTML = 'Error: '+errorUpload[errorno];
   }
 }

function ImgGalObj(id)
 {
  this.id = id;
  this.h = 1;
  this.i = 1;
  if(typeof this.ImgGalTablaInfo == 'function') this.ImgGalTablaInfo();
 }

function galimgCargada(params) {
	/*
{
	funcion : parent.galimgCargada,
	errorno : false,
	imagenId : 35,
	imagenArchivo : 'Summer-Night-WallpaperLY78wU.jpg',
	frame : 'fr1274920941285',
	indice : 5,
	atributo : 6
}
	*/
	var errorno = params.errorno;
	var imagenId = params.imagenId;
	var imagenArchivo = params.imagenArchivo;

	if(errorno == false) {
		var laImagen = new ImgGalObj(imagenId);
		var sep = document.createElement('span');
		var imagen = document.createElement('input');
		imagen.setAttribute("type", "image");
		imagen.src = 'icono/4/'+imagenArchivo; //+'&max=120';
		imagen.setAttribute("value", imagenId);
		imagen.setAttribute("alt", imagenArchivo);
		imagen.setAttribute("title", imagenArchivo);
		imagen.setAttribute("name", "imagen[]");
		galeriaDiv.appendChild(imagen);
		agregarEvento(imagen, 'mousedown', mover);
		imagen.onmouseup = function() {
			desplegarImg(imagen);
			return false;
		}
		imagen.onclick = function() {
			return false;
		}
		galeriaDiv.appendChild(sep);
		galeriaDiv.parentNode.parentNode.style.display = '';
		if(laImagen.tabla)
			galeriaDiv.parentNode.lastChild.appendChild(laImagen.tabla);
		if(document.getElementById('celdaIcono') != null && document.getElementById('celdaIcono').firstChild == null)
			cargarIcono(imagenArchivo);
		document.getElementById('subirArchivos').style.display = 'none';
	}
	else {
		document.getElementById('subirArchivosaviso').innerHTML = 'Error: '+errorUpload[errorno];
	}
 }

var actual = {};
function mostrarTxt(prefijo, id)
 {
  var elId = prefijo+id;
  if(actual[prefijo] == elId) return false;
  var el = document.getElementById(elId);
  el.style.display = 'block';
  el.focus();
  document.getElementById('p'+elId).className = 'etiqueta_idioma seleccionado';
  document.getElementById('p'+actual[prefijo]).className = 'etiqueta_idioma';
  document.getElementById(actual[prefijo]).style.display = 'none';
  actual[prefijo] = elId;
  return true;
 }

/* Galería */
var galeriaDiv;
function mover(evento) {
	elemento = evento.target;
	evento.preventDefault();
	galeriaDiv = elemento.parentNode;
	copia = elemento.cloneNode(true);
	copiado = elemento;
	copia.style.position = 'absolute';
	copia.style.top = (evento.pageY+3)+'px';
	copia.style.left = evento.pageX+'px';
	copia.style.opacity = 0.5;
	galeriaDiv.parentNode.appendChild(copia);
	copiaImg = new Image();
	copiaImg.src = copia.src;

	agregarEvento(document, 'mousemove', moverEl);
	agregarEvento(document, 'mouseup', detenerMov);

	var n = 1;
	while(elemento.previousSibling.previousSibling) {
		n++
		elemento = elemento.previousSibling.previousSibling;
	}
	var separadores = galeriaDiv.getElementsByTagName('span');
	for(i = 0; i < separadores.length; i++) {
		if(i == n-1 || i == n)
			continue;
		separadores[i].addEventListener('mouseover', resaltarSep, true);
		separadores[i].addEventListener('mouseout', resaltarSep, true);
	}
}

function moverEl(evento)
 {
  copia.style.top = (evento.pageY+2)+'px';
  copia.style.left = evento.pageX+'px';
  return false;
 }

function detenerMov(evento)
 {
  document.removeEventListener("mousemove", moverEl, true);
  document.removeEventListener("mouseup", detenerMov, true);
  var separadores = galeriaDiv.getElementsByTagName('span');
  for(i = 0; i < separadores.length; i++) {
	  separadores[i].removeEventListener('mouseover', resaltarSep, true);
	  separadores[i].removeEventListener('mouseout', resaltarSep, true);
   }

  if(copia)
	  galeriaDiv.parentNode.removeChild(copia);
  if(evento.target.tagName == 'span' && evento.target.parentNode.id == 'galeria' && evento.target.className == 'resaltado')
   {
    evento.target.style.border = '';
	//evento.target.style.margin = '0';
	evento.target.style.padding = '4px';
	evento.target.style.width = '2px';
	evento.target.style.height = '13px';
	evento.target.className = '';
	galeriaDiv.removeChild(copiado.previousSibling);
	galeriaDiv.removeChild(copiado);
    var copiaSep = evento.target.cloneNode(true);
	galeriaDiv.insertBefore(copiaSep, evento.target);
	galeriaDiv.insertBefore(copiado, evento.target);
   }
  else if(evento.target.name == 'eliminarImg')
	  borrarImg(copia.value);
  else if(evento.target.id == 'icono')
	  cargarIcono(copia.alt);
  copia = null;
 }

function resaltarSep(event) {
	separador = event.target;
	if(event.type == 'mouseover') {
		separador.style.border = '2px dotted #bbb';
		separador.style.position = 'relative';
		separador.style.width = copiaImg.width+'px';
		separador.style.height = copiaImg.height+'px';
		separador.className = 'resaltado';
		//separador.style.margin = '0 4px';
	}
	else if(event.type == 'mouseout') {
		separador.style.border = '';
		separador.style.width = '2px';
		separador.style.height = '13px';
		//separador.style.margin = '0';
		separador.style.padding = '4px';
		separador.className = '';
	}
}

function borrarImg(selImg) {
	if(confirm('\xBFDesea eliminar la imagen '+copia.alt+'?')) {
		galeriaDiv.removeChild(copiado.previousSibling);
		galeriaDiv.removeChild(copiado);
		var borrar = document.createElement('input');
		borrar.setAttribute("type", "hidden");
		borrar.setAttribute("name", "borrarImg[]");
		borrar.setAttribute("value", selImg);
		document.forms['formedicion'].appendChild(borrar);
	}
	return;
}

var ImgGalSel = 0;
function desplegarImg(imagen) {

  if(ImgGalSel != 0 && document.getElementById('muestra_'+ImgGalSel) != null)
	document.getElementById('muestra_'+ImgGalSel).style.display = 'none';
  if(document.getElementById('muestra_'+imagen.value) != null) document.getElementById('muestra_'+imagen.value).style.display = '';
  ImgGalSel = imagen.value;
  var contenedorSup = imagen.parentNode.parentNode.lastChild;

  var imagenId = imagen.value;
  var imagenArchivo = imagen.alt;
  selImg = imagenId;
  //while(filaCargando.childNodes[3].firstChild)
  // { filaCargando.childNodes[3].removeChild(filaCargando.childNodes[3].firstChild); }
  /*
  document.forms['en']['img_editando'].value = imagenId;
  document.forms['en']['img_estado'].checked = (document.forms['formedicion']['img_estado['+imagenId+']'].value == 1);
  document.forms['en']['img_titulo'].value = document.forms['formedicion']['img_titulo['+imagenId+']'].value;
  document.forms['en']['img_texto'].value = document.forms['formedicion']['img_texto['+imagenId+']'].value;

  var fechaImp = '';
  var v = document.forms['formedicion']['img_fecha['+imagenId+']'].value;
  if(v.length > 9)
   {
	var vArr = v.split(" ");
	var dia = vArr[0].split("-");
	var hora = vArr[1].split(":");
	var fecha = new Date(dia[0], dia[1], dia[2], hora[0], hora[1], hora[2]);
	fechaImp = fecha.print("%A, %d de %B de %Y, %H:%m hs.");
   }
  document.getElementById('mostrar_fechaImg').innerHTML = fechaImp;
  Calendar.setup({
    	inputField : 'img_fecha'+imagenId,
		ifFormat       :    "%Y-%m-%d %H:%m:00",
		displayArea    :    "mostrar_fechaImg",
		daFormat       :    "%A, %d de %B de %Y, %H:%m hs.",
		button         :    "tn_calendarioImg",
		showsTime : true});
  //  document.forms['en']['img_fecha'].value = document.forms['formedicion']['img_fecha['+imagenId+']'].value;
  if(filaCargando.childNodes[2].firstChild.firstChild) filaCargando.childNodes[2].firstChild.removeChild(filaCargando.childNodes[2].firstChild.firstChild);
  filaCargando.childNodes[2].firstChild.appendChild(document.createTextNode(imagenArchivo));

  //document.forms['formimg_pos']['eliminarImg'].addEventListener('click', borrarImg, false);
  */

	if(contenedorSup.childNodes[0].firstChild != null) {
		contenedorSup.childNodes[0].removeChild(contenedorSup.childNodes[0].firstChild);
	}

  contenedorSup.childNodes[0].appendChild(document.createTextNode(imagenArchivo));//innerHTML = imagenArchivo;
  contenedorSup.childNodes[1].src = 'icono/3/'+imagenArchivo;
  contenedorSup.style.display = '';
 }

function aceptarFormGal(formulario, publicar)
 {
  var img = document.getElementsByName('img[]');
  for(var i = img.length-1; i >= 0 ; i--) formulario.removeChild(img[i]);
  var imagenes = document.getElementsByName('imagen[]');
  for(i = 0; i < imagenes.length; i++)
   {
	var imgHidd = document.createElement('input');
	imgHidd.setAttribute("type", "hidden");
	imgHidd.setAttribute("name", "img[]");
	imgHidd.setAttribute("value", imagenes[i].value);
	formulario.appendChild(imgHidd);
   }
  document.forms['formedicion']['publicar'].value = publicar ? '1' : '0';
  return true;
 }

function publicacionEliminada(req, params)
 {
  if(req.readyState == 4)
   {
    if(req.status == 200 && req.responseText == 1)
     {
	  var filaaviso = document.getElementById('avisoguardar');
	  filaaviso.style.display = '';
	  filaaviso.firstChild.firstChild.innerHTML = 'La publicaci\xF3n ha sido borrada satisfactoriamente.';
	  var btnElPub = document.forms[0]['btElimPublic'];
	  btnElPub.parentNode.removeChild(btnElPub);
     }
	else if(req.status == 401)
	 {
	  alert('Su sesi\xF3n ha caducado!');
     }
    else
	 {
	  alert(req.status);
	 }
   }
 }

function publicacionEliminadaCat()
 {
  if(req.readyState == 4)
   {
    if(req.status == 200)
     {
      if(req.responseText == 1)
       {
		var filaaviso = document.getElementById('avisoguardar');
	    filaaviso.style.display = '';
		filaavisoTxt = (filaaviso.firstChild.firstChild.firstChild != null) ? filaaviso.firstChild.firstChild.firstChild : filaaviso.firstChild.firstChild.appendChild(document.createTextNode(''));
		filaavisoTxt.replaceData(0, filaavisoTxt.length, 'La publicaci\xF3n ha sido borrada satisfactoriamente.');
	    //filaaviso.firstChild.firstChild.innerHTML = 'La publicaci\xF3n ha sido borrada satisfactoriamente.';
       }
     }
	else if(req.status == 401)
	 {
	  alert('Su sesi\xF3n ha caducado!');
     }
    else
	 {
	  alert(req.status+'\n'+req.responseText);
	 }
   }
 }

function eliminarPublicacion(seccion_id, seccion)
 {
  if(document.forms['formedicion']['id'].value.length >= 1)
   {
    loadXMLDoc('./editar_borrar_publicacion.txt?id='+document.forms['formedicion']['id'].value+'&seccion_id='+seccion_id+'&seccion='+seccion, publicacionEliminada, null);
   }
 }

function eliminarPublicacionCat(seccion)
 {
  if(document.forms['formedicion']['id'].value.length >= 1)
   {
    loadXMLDoc('./categoria_borrar_publicacion?id='+document.forms['formedicion']['id'].value+'&seccion_id='+document.forms['formedicion']['seccion'].value+'&seccion='+seccion, publicacionEliminadaCat, null);
   }
 }

function agregarOp(tipo, subtipo, elemento, idAtributo, prefijo, pref)
 {
  var lista = elemento.previousSibling;
  var li = document.createElement('li');
  lista.appendChild(li);
  if(tipo == 'string')
   {
   	if(subtipo = 1)
   	 {
	  var campo = document.createElement('input');
	  campo.setAttribute("type", "text");
	  campo.setAttribute("name", prefijo+"[n]["+idAtributo+"][]");
	  campo.setAttribute("size", "6");
	  campo.setAttribute("maxlength", "6");
	  campo['onkeyup'] = function()
	   {contar6rgb(this);}
	  var muestra = new Image();
      // ruta
	  muestra.src = 'img/trans';
	  muestra['onclick'] = function()
	   {paletaDeColores(this, this.nextSibling).mostrar();}
	  muestra.className = 'muestraColor';
	  muestra.width = '22';
	  muestra.height = '22';

	  var borrar = new Image();
      // ruta
	  borrar.src = 'img/b_drop_ch';
	  borrar.alt = 'Eliminar';
	  borrar['onclick'] = function()
	   {borrarOp(this.parentNode, false, pref);}
   	  li.appendChild(document.createTextNode('#'));
   	  li.appendChild(campo);
   	  li.appendChild(muestra);
   	  li.appendChild(borrar);
   	  paletaDeColores(muestra, campo).mostrar();
//	  alert('tipo: '+tipo+'\nsubtipo: '+subtipo)
   	 }
   }
  else if(tipo == 'int')
   {
   	if(subtipo = 1)
   	 {
alert('estp?');
return;
   	 }
   }
 }

function borrarOp(li, almacenado, pref)
 {
  li.parentNode.removeChild(li);
  if(almacenado != false)
   {
   	//if(pref == '') pref = '';
   	var campo = document.createElement('input');
   	campo.setAttribute('type', 'hidden');
   	campo.setAttribute('name', 'borrar['+pref+'][]');
   	campo.value = almacenado;
   	document.forms[0].appendChild(campo);
   }
 }

function borrarOpArch(el)
 {
  var span = el.previousSibling;
  var txt = span.innerHTML;
  txt = txt.fontcolor("Gray");
  span.innerHTML = txt.strike();
  var valor = el.nextSibling.value;
  el.nextSibling.value = '';
  el.src = 'img/arrow_undo';
  el.alt = 'Restaurar';
  el.title = 'Restaurar';
  el['onclick'] = function()
   {
	deshacerBorrarOpArch(this, valor);
   }
 }

function deshacerBorrarOpArch(el, valor)
 {
  var span = el.previousSibling;
  var txt = span.firstChild.firstChild.innerHTML;
  span.innerHTML = txt;
  el.nextSibling.value = valor;
  el.src = 'img/b_drop_ch';
  el.alt = 'Eliminar';
  el.title = 'Eliminar';

  el['onclick'] = function()
   {
	borrarOpArch(this);
   }
 }

function actOrdenSel(id)
 {
  CATEGORIASEL = id;
  actOrden();
 }

function actOrden()
 {
  var j_orden = document.forms[0]['antesde'];
  var cat;
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

function EtiquetaTexto(id, h, i)
 {
  this.id = id;
  this.h = h;
  this.i = i;
  this.el = document.createElement('label');
  this.el.setAttribute('id', 'pgalimgdato_'+this.id+'_'+this.h+'_'+this.i);
  this.el.setAttribute('for', 'galimgdato_'+this.id+'_'+this.h+'_'+this.i);
  var self = this;
  //this.evento = ;
  agregarEvento(this.el, 'click', function()
   {
   	mostrarTxt('galimgdato_'+self.id+'_'+self.h+'_', self.i);
   });
 }

function eliminarArchivo(el)
 {
  el.parentNode.parentNode.removeChild(el.parentNode);
 }

var asCats = {};

function eliminarDeCatSel(valor, deshabilitado)
 {
  var selector = document.forms[0]['cats_sel'];
  for(var i = 0; i < selector.length; i++)
   {
	if(selector.options[i].value == valor)
	 {
	  selector.options[i].disabled = deshabilitado;
	  break;
	 }
   }
  return;
 }

function deAsignarCat(elem)
 {

 }

function asignarCat(id, ordenN)
 {
  if(asCats[id] != null) return false;
  asCats[id] = id;
  var sup = categorias[id][0];
  var ruta = rutas[sup] ? rutas[sup]+' > ' : '';

  var lista = document.getElementById('as_categorias');
  var li = document.createElement('li');
  var span = document.createElement('span');
  span.appendChild(document.createTextNode(ruta+categorias[id][1]));
  li.appendChild(span);

  var borrar = document.createElement('input');//new Image();
  borrar.setAttribute('type', 'image');
  // ruta
  borrar.src = 'img/b_drop_ch';
  borrar.value = id;
  borrar.setAttribute("alt", 'Eliminar');
  borrar.setAttribute("title", 'Eliminar');
  borrar.setAttribute("name", "cat_asoc");
  borrar.onclick = function()
   {
    asCats[this.value] = null;
   	eliminarDeCatSel(this.value, false);
   	this.parentNode.parentNode.removeChild(this.parentNode);
   }
  li.appendChild(borrar);

  li.appendChild(document.createTextNode(' orden:'));

  var orden = document.createElement('input');
  orden.setAttribute('type', 'text');
  orden.setAttribute('name', 'cats_orden['+id+']');
  orden.setAttribute('size', '3');
  orden.value = ordenN ? ordenN : '';
  li.appendChild(orden);
  var campo = document.createElement('input');
  campo.setAttribute('type', 'hidden');
  campo.setAttribute('name', 'cats[]');
  campo.value = id;
  li.appendChild(campo);
  lista.appendChild(li);
  eliminarDeCatSel(id, true);
 }

var browser_es_ie = document.all ? true : false;
var ventanaModal = null;
function abrirModal(url, ancho, alto) {
	/*
	if(document.all == null) { // es IE
		var nAncho = ancho + 6;
		var nAlto = alto + 25;
		ventanaModal = showModalDialog(url,'FFFFFF','center:yes;resizable:yes;scroll:no;help:no;dialogHeight:'+nAlto+'px;dialogWidth:'+nAncho+'px;status:no;');
		if(ventanaModal) { // [1] != null && formCont[1].length > 1
			imgCargada(false, formCont[0], formCont[1]);
		}
	}
	else {
	*/
		var nombre = url.split('?');
		var posicion = '';
		posicion = "left="+((screen.width/2)-(ancho/2))+",";
		posicion += "top="+((screen.height/2)-(alto/2))+",";
		ventanaModal = window.open(url, nombre[0], "modal=yes,width="+ancho+"px,height="+alto+"px,,"+posicion);
	//}
	if(ventanaModal == null)
		alert("Su navegador evitó que se abra una ventana emergente.\nDeberá permitir la apertura de este tipo de ventanas para utilizar esta aplicación.");
}

function abrirEnlace(el) {
  var protocolo = el.parentNode.childNodes[0];
  var prot_str = (protocolo.tagName == 'select') ? protocolo.options[protocolo.selectedIndex].text : protocolo.value;
  var ruta = el.parentNode.childNodes[3].value;
  if(ruta != '')
	  el.href = prot_str+ruta;
  else
	  return false;
 }



function entrarInactividad()
 {
  window.clearInterval(intervaloBloq);
  alert('Entró en inactividad')
 }

function respuestaBloqueo(req, parametros)
 {
  //return parametros['a']+parametros['b']+parametros['c'];
  alert(req.responseText)
 }



/*
function probar2list()
 {
intervaloBloq = ;
//intervaloBloq=clearInterval(intervaloBloq);
alert(typeof self+' - '+self);
 }

*/

function mantenerActividad()
 {
  clearTimeout(timeoutActividad);
  timeoutActividad = setTimeout("entrarInactividad()", 500000);
 }


// metatags
function htmlspecialchars(string, quote_style)
 {
  string = string.toString();

  string = string.replace(/&/g, '&amp;');
  string = string.replace(/</g, '&lt;');
  string = string.replace(/>/g, '&gt;');

  if (quote_style == 'ENT_QUOTES')
   {
	string = string.replace(/"/g, '&quot;');
	string = string.replace(/'/g, '&#039;');
   }
  else if (quote_style != 'ENT_NOQUOTES')
   {
	// All other cases (ENT_COMPAT, default, but not ENT_NOQUOTES)
	string = string.replace(/"/g, '&quot;');
   }
  return string;
 }


var estadoPress;
var estadoDown;
var estadoUp;
var etiquetas = {};
var etiquetasCampos = [];


function largo(obj)
 {
  var t = -1;
  for(var x in obj)
{
if(obj[x] == null) continue;
    t += obj[x] + 1;
}
  return t;
 }

function Sup()
 {
  this.el = document.createElement('sup');
  this.el.appendChild(document.createTextNode('X'));
  var self = this.el;
  this.el.onclick = function()
   {
   	borrarEtiqueta(self);
   	//self.parentNode.parentNode.removeChild(self.parentNode);
	//etiquetas[metaActivo][self.parentNode.firstChild.nodeValue] = null;
   	//etiquetasCampos[metaActivo].maxLength = (250 - largo(etiquetas[metaActivo]));
   };
 }

function borrarEtiqueta(el)
 {
  el.parentNode.parentNode.removeChild(el.parentNode);
  etiquetas[metaActivo][el.parentNode.firstChild.nodeValue] = null;
  etiquetasCampos[metaActivo].maxLength = (250 - largo(etiquetas[metaActivo]));
 }

var patron = new RegExp("[;.:,=\"' \t]","g");
function mestadoPress(evento, campoId)
 {
  //if(!estadoPress) estadoPress = document.getElementById('estadoPress');
  if(!etiquetasCampos[metaActivo])
	etiquetasCampos[metaActivo] = evento.target;

//for(var ee in evento)
//	alert(ee+': '+evento[ee]) 32 13

   	//case 32:
  if(evento['which'] == 13 || evento['which'] == 32 || evento['which'] == 44)
   {
	if(evento.target.value.length > 0)
	 {
	  //var cadena = evento.target.value.replace(/\W/g, ',');
	  var cadena = evento.target.value.replace(patron, ',');
	  var ingreso = cadena.split(",");
	  for(var i = 0; i < ingreso.length; i++)
	   {
		if(ingreso[i] == '' || etiquetas[metaActivo][ingreso[i]]) continue;
		cur_ingreso = ingreso[i];
		var span = document.createElement('span');
		span.appendChild(document.createTextNode(cur_ingreso));
		var supEl = evento.target.parentNode;
		supEl.insertBefore(span, evento.target);
		supEl.insertBefore(document.createTextNode(' '), evento.target);
		var input = document.createElement('input');
		input.setAttribute('type', 'hidden');
		input.setAttribute('name', 'dato[o]['+campoId+'][n][]');
		input.value = cur_ingreso;
		span.appendChild(input);
		var supe = new Sup();
		span.appendChild(supe.el);
		etiquetas[metaActivo][cur_ingreso] = cur_ingreso.length;
	   }
	  evento.target.value = '';
	  evento.target.maxLength = (250 - largo(etiquetas[metaActivo]));
	 }
	return false;
   }
  // break;

  //keychar = String.fromCharCode(evento['which']);
  //numcheck = /\w/;
  return true;//numcheck.test(keychar);
 }

function mostrarMeta(codigo)
 {
  document.getElementById('etiqueta_idioma_'+metaActivo).className = 'etiqueta_idioma';
  document.getElementById('meta-'+metaActivo).style.display = 'none';
  metaActivo = codigo;
  document.getElementById('etiqueta_idioma_'+metaActivo).className = 'etiqueta_idioma seleccionado';
  document.getElementById('meta-'+codigo).style.display = 'block';
 }

function textCounter(field) {
    if(field.value.length > 250)
        field.value = field.value.substring(0, 250);
}

// fin metatags


// apply tagOpen/tagClose to selection in textarea,
// use sampleText instead of selection if there is none
function insertTags(area, tagOpen, tagClose, sampleText) {
    var txtarea = document.getElementById(area);
    var selText, isSample = false;

    if (document.selection  && document.selection.createRange) { // IE/Opera
        //save window scroll position

        var winScroll;
        if (document.documentElement && document.documentElement.scrollTop)
            winScroll = document.documentElement.scrollTop
        else if (document.body)
            winScroll = document.body.scrollTop;
        //get current selection
        txtarea.focus();
        var range = document.selection.createRange();
        selText = range.text;
        //insert tags
        checkSelectedText();
        range.text = tagOpen + selText + tagClose;
        //mark sample text as selected
        if(isSample && range.moveStart) {
            if (window.opera)
                tagClose = tagClose.replace(/\n/g,'');
            range.moveStart('character', - tagClose.length - selText.length);
            range.moveEnd('character', - tagClose.length);
        }
        range.select();
        //restore window scroll position
        if (document.documentElement && document.documentElement.scrollTop)
            document.documentElement.scrollTop = winScroll
        else if (document.body)
            document.body.scrollTop = winScroll;
    }
    else if (txtarea.selectionStart || txtarea.selectionStart == '0') { // Mozilla
        //save textarea scroll position
        var textScroll = txtarea.scrollTop;
        //get current selection
        txtarea.focus();
        var startPos = txtarea.selectionStart;
        var endPos = txtarea.selectionEnd;
        selText = txtarea.value.substring(startPos, endPos);
        //insert tags
        checkSelectedText();
        txtarea.value = txtarea.value.substring(0, startPos) + tagOpen + selText + tagClose + txtarea.value.substring(endPos, txtarea.value.length);
        //set new selection
        if(isSample) {
            txtarea.selectionStart = startPos + tagOpen.length;
            txtarea.selectionEnd = startPos + tagOpen.length + selText.length;
        }
        else {
            txtarea.selectionStart = startPos + tagOpen.length + selText.length + tagClose.length;
            txtarea.selectionEnd = txtarea.selectionStart;
        }
        //restore textarea scroll position
        txtarea.scrollTop = textScroll;
    }
    function checkSelectedText() {
        if (!selText) {
            selText = sampleText;
            isSample = true;
        }
        else if (selText.charAt(selText.length - 1) == ' ') { //exclude ending space char
            selText = selText.substring(0, selText.length - 1);
            tagClose += ' ';
        }
    }
}

function insertarEnlace(areaTexto) {
    var txtarea = document.getElementById(areaTexto);
    txtarea.focus();
    var startPos = txtarea.selectionStart;
    var endPos = txtarea.selectionEnd;
    selText = txtarea.value.substring(startPos, endPos);
    var url = prompt('Dirección de destino', selText);
    if(url) {
        var tit = prompt('Texto del enlace', selText);
        if(tit)
            url = url+'|'+tit;
        txtarea.value = txtarea.value.substring(0, startPos) + url + txtarea.value.substring(endPos, txtarea.value.length);
        txtarea.selectionStart = startPos;
        txtarea.selectionEnd = txtarea.selectionStart + url.length;
        insertTags(areaTexto, '[[', ']]', '')
    }
}
