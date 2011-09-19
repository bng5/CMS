var browser_es_ie = document.all? true: false

function cargarListado()
 {
  if(req.readyState == 4)
   {
    if(req.status == 200)
     {
	  while(tablaListado.childNodes[1].firstChild)
	   { tablaListado.childNodes[1].removeChild(tablaListado.childNodes[1].firstChild); }
	  //var tablaPie = tablaListado.childNodes[1].firstChild.firstChild;
	  while(listadoResult.firstChild)
	   { listadoResult.removeChild(listadoResult.firstChild); }

	  var galerias = req.responseXML.getElementsByTagName("galeria");
	  var attrsPrimerNodo = req.responseXML.firstChild.attributes;
	  if(attrsPrimerNodo.length > 0)
	   {
	    tablaListado.style.display = '';
	    listadoOpciones.style.display = '';
	   }
	  var rpp = attrsPrimerNodo.getNamedItem("rpp") ? parseInt(attrsPrimerNodo.getNamedItem("rpp").value) : 0;
	  var total = attrsPrimerNodo.getNamedItem("total") ? parseInt(attrsPrimerNodo.getNamedItem("total").value) : 0;
	  var pagina = attrsPrimerNodo.getNamedItem("pagina") ? parseInt(attrsPrimerNodo.getNamedItem("pagina").value) : 0;
	  var paginas = attrsPrimerNodo.getNamedItem("paginas") ? parseInt(attrsPrimerNodo.getNamedItem("paginas").value) : 0;

	  var resultados = document.createTextNode(total+' resultados en '+paginas+' páginas');
	  listadoResult.appendChild(resultados);
	  if(paginas > 1)
	   {
		var br = document.createElement('br');
	    listadoResult.appendChild(br);
	    //var result_paginas = document.createTextNode('Páginas ');
	    //listadoResult.appendChild(result_paginas);
	    
	    if(pagina > 1)
	     {
	      anterior = (pagina - 1);
		  var ant = document.createElement('a');
		  ant.onclick = function()		   { loadXMLDoc('./galeria.xml?leng=1&pagina='+anterior, cargarListado, null); }
		  var antLabel = document.createTextNode('<<');
		  ant.appendChild(antLabel);
		  listadoResult.appendChild(ant);
	     }

		var min_pagina = 1;
		var max_pagina = paginas;
		if((paginas - pagina) > 9) max_pagina = pagina + 9;
		if(pagina > 9) min_pagina = pagina - 9;

		for(var pags = min_pagina; pags <= max_pagina; pags++)
         {
          var numLabel = document.createTextNode(pags);
          if(pags == pagina)
           {
            var num = document.createElement('b');
           }
          else
           {
            var num = document.createElement('a');
            num.ruta = './galeria.xml?leng=1&pagina='+pags;
		    num.onclick = function()		     { loadXMLDoc(this.ruta, cargarListado, null); }
		   }
		  num.appendChild(numLabel);
		  listadoResult.appendChild(num);
         }

	    if(pagina < paginas)
	     {
	      pagina++
		  var sig = document.createElement('a');
		  sig.onclick = function()		   { loadXMLDoc('./galeria.xml?leng=1&pagina='+pagina, cargarListado, null); }
		  var sigLabel = document.createTextNode('>>');
		  sig.appendChild(sigLabel);
		  listadoResult.appendChild(sig);
	     }
	   }  

	  //if(productos.length >= 1) select.parentNode.style.display = "block";
	  for (var i = 0; i < galerias.length; i++)
	   {
	    id = galerias[i].attributes.getNamedItem("id") ? galerias[i].attributes.getNamedItem("id").value : null;
	    estado = galerias[i].attributes.getNamedItem("estado") ? galerias[i].attributes.getNamedItem("estado").value : null;
	    img = galerias[i].attributes.getNamedItem("img") ? galerias[i].attributes.getNamedItem("img").value : null;
	    orden = galerias[i].attributes.getNamedItem("orden") ? galerias[i].attributes.getNamedItem("orden").value : null;
	    titulo = galerias[i].firstChild ? galerias[i].firstChild.nodeValue : null;
		//alert('<galeria id="'+id+'" estado="'+estado+'" img="'+img+'" orden="'+orden+'">'+titulo+'</galeria>');
		agregarAListado(id, estado, img, orden, titulo);
	   }
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

function agregarAListado(id, estado, img, orden, titulo)
 {
  var tr = document.createElement('tr');
  var td1 = document.createElement('td');
  var td2 = document.createElement('td');
  var td3 = document.createElement('td');
  var td4 = document.createElement('td');

  //td1.setAttribute("align", "center");
  //td4.setAttribute("align", "center");
  //var chbox = document.createElement('input');
  //chbox.setAttribute("type", "checkbox");
  //chbox.setAttribute("name", "lista_item[]");
  //chbox.setAttribute("value", id);
  //chbox.onclick = function()
  // { selFila(chbox, ''); }
  //td1.appendChild(chbox);

  var chbox = document.createElement('input');
  chbox.setAttribute("type", "checkbox");
  //chbox.setAttribute("id", "item"+id);
  chbox.setAttribute("name", "lista_item[]");
  chbox.setAttribute("value", id);
  chbox.onclick = function()
   { selFila(chbox, estado); }
  /*
  chbox.setAttribute("type", "checkbox");
  ordeninput.setAttribute("size", "2");
  ordeninput.setAttribute("maxlength", "3");
  */
  td1.setAttribute("align", "center");
  td1.appendChild(chbox);
  
  
  var imagen = document.createElement('img');
  imagen.setAttribute("src", 'icono?archivo=img/galerias/imagenes/'+img);
  //a.setAttribute("href", "<?php echo $_SERVER['PHP_SELF']."?ia=editar&prod_id=5&sesion=".$sesion; ?>");
  //var link = document.createTextNode(nombre);
  //a.appendChild(link);
  td2.setAttribute("align", "center");
  td2.appendChild(imagen);
  
  var a = document.createElement('a');
  a.setAttribute('href', 'galerias?id='+id);
  var nombre = document.createTextNode(titulo);
  a.appendChild(nombre);
  td3.appendChild(a);

  var ordeninput = document.createElement('input');
//  ordeninput.setAttribute("id", "item"+id);
  ordeninput.setAttribute("value", orden);
  ordeninput.setAttribute("type", "text");
  ordeninput.setAttribute("size", "2");
  ordeninput.setAttribute("maxlength", "3");
  //ordeninput.setAttribute("tabindex", "29");
  //ordeninput.setAttribute("dir", "rtl");
  ordeninput.onblur = function()
   { actualizar(ordeninput, id); }
  var ordenbak = document.createElement('input');
  ordenbak.setAttribute("id", "itembak"+id);
  ordenbak.setAttribute("value", orden);
  ordenbak.setAttribute("type", "hidden");
  td4.appendChild(ordeninput);
  td4.appendChild(ordenbak);
  
  tr.appendChild(td1);
  tr.appendChild(td2);
  tr.appendChild(td3);
  tr.appendChild(td4);

  tablaListado.childNodes[1].appendChild(tr);
 }
 
function mostrarGal()
 {
  document.getElementById('fila_galeria').style.display = '';
 }

/*
function imgBorrada()
 {
  if(req.readyState == 4)
   {
    if(req.status == 200)
     {
	  if(req.responseText == "1")
	   {
	    galeriaDiv.removeChild(document.getElementById(copiado));
	    filaCargando.childNodes[3].childNodes[2].src = 'img/trans';
	    //document.forms['formimg_pos']['img_pos'].options.splice(document.forms['formimg_pos']['img_pos'].options.length-1, 1);
	   }
     }
	else if(req.status == 401) alert('Su sesi\xF3n ha caducado!');
    else
	 {
	  alert(req.status);
	 }
   }
 }
*/

function borrarImg(selImg)
 {
  //selImg = document.forms['formimg_pos']['eliminarImg'].value;
  if(selImg > 0)
   {
	if(confirm('\xBFDesea eliminar la imagen '+copia.alt+'?'))
	 {
	  galeriaDiv.removeChild(copiado.previousSibling);
	  galeriaDiv.removeChild(copiado);
	  var borrar = document.createElement('input');
	  borrar.setAttribute("type", "hidden");
	  borrar.setAttribute("name", "borrarImg[]");
	  borrar.setAttribute("value", selImg);
	  document.forms['formedicion'].appendChild(borrar);
	  //loadXMLDoc('./imagenes?id='+selImg+'&seccion=galerias&accion=borrar', imgBorrada, null);
	  if(galeriaDiv.childNodes.length == 1) galeriaDiv.parentNode.parentNode.style.display = 'none';
	 }
   }
  return;
 }

/*
function moverPos(imgId, posOrig, pos)
 {
  loadXMLDoc('./imagenes?id='+imgId+'&posorig='+posOrig+'&pos='+pos+'&sup='+document.forms['formedicion']['id'].value+'&seccion=galerias&accion=mover', null, null);
  var copia = document.getElementById('im'+imgId).cloneNode(true);
  galeriaDiv.removeChild(document.getElementById('im'+imgId));
  galeriaDiv.insertBefore(copia, galeriaDiv.childNodes[pos-1]);
 }
*/

function desplegarImg(imagenId, imagenArchivo, img)
 {
  selImg = imagenId;
  //while(filaCargando.childNodes[3].firstChild)
  // { filaCargando.childNodes[3].removeChild(filaCargando.childNodes[3].firstChild); }
  if(filaCargando.childNodes[3].firstChild.firstChild) filaCargando.childNodes[3].firstChild.removeChild(filaCargando.childNodes[3].firstChild.firstChild);
  filaCargando.childNodes[3].firstChild.appendChild(document.createTextNode(imagenArchivo));

  //document.forms['formimg_pos']['eliminarImg'].addEventListener('click', borrarImg, false);

  filaCargando.childNodes[3].childNodes[2].src = 'imagen?archivo=img/galerias/imagenes/'+imagenArchivo;
 }

function aceptarFormGal(formulario)
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
  formulario.submit();
 }

agregarEvento = function(el, evname, func)
 {
  if(typeof el == "string") el = document.getElementById(el);
  if(el == null) return false;

  if (el.attachEvent) el.attachEvent("on" + evname, func); // IE
  else if (el.addEventListener) el.addEventListener(evname, func, true); // Gecko / W3C
  else el["on" + evname] = func;
 };

quitarEvento = function(el, evname, func)
 {
  if(typeof el == "string") el = document.getElementById(el);
  if(el == null) return false;
  if (el.detachEvent) el.detachEvent("on" + evname, func); // IE
  else if (el.removeEventListener) el.removeEventListener(evname, func, true); // Gecko / W3C
  else el["on" + evname] = null;
 };

function moverEl(evento)
 {
  copia.style.top = (evento.pageY+2)+'px';
  copia.style.left = evento.pageX+'px';
  return false;
 }

function detenerMov(evento)
 {
  document.removeEventListener("mousemove", moverEl, true);
  var separadores = galeriaDiv.getElementsByTagName('span');
  for(i = 0; i < separadores.length; i++)
   {
    separadores[i].removeEventListener('mouseover', resaltarSep, true);
    separadores[i].removeEventListener('mouseout', resaltarSep, true);
   }

  if(copia) galeriaDiv.parentNode.removeChild(copia);

  if(evento.target.tagName == 'SPAN' && evento.target.parentNode.id == 'galeria')
   {
    evento.target.style.border = '';
	evento.target.style.margin = '0';
	evento.target.style.padding = '4px';
	galeriaDiv.removeChild(copiado.previousSibling);
	galeriaDiv.removeChild(copiado);
    var copiaSep = evento.target.cloneNode(true);
	galeriaDiv.insertBefore(copiaSep, evento.target);
	galeriaDiv.insertBefore(copiado, evento.target);
   }
  else if(evento.target.name == 'eliminarImg') borrarImg(copia.value);
  else if(evento.target.id == 'icono') cargarIcono(copia.alt);
  copia = null;
 }

function resaltarSep(event)
 {
  separador = event.target;
  if(event.type == 'mouseover')
   {
    separador.style.border = '2px dotted #bbb';
    separador.style.position = 'relative';
    separador.style.width = copiaImg.width+'px';
    separador.style.height = copiaImg.height+'px';
    separador.style.margin = '0 4px';
   }
  else if(event.type == 'mouseout')
   {
	separador.style.border = '';
	separador.style.margin = '0';
	separador.style.padding = '4px';
   }
 }

function mover(elemento, evento)
 {
  copia = elemento.cloneNode(true);
  copiado = elemento;
  copia.style.position = 'absolute';
  copia.style.top = evento.pageY+'px';
  copia.style.left = evento.pageX+'px';
  copia.style.MozOpacity = 0.5;
  galeriaDiv.parentNode.appendChild(copia);
  copiaImg = new Image();
  copiaImg.src = copia.src;

  agregarEvento(document, 'mousemove', moverEl);
  agregarEvento(document, 'mouseup', detenerMov);

  var n = 1;
  while (elemento = elemento.previousSibling.previousSibling) n++
  var separadores = galeriaDiv.getElementsByTagName('span');
  for(i = 0; i < separadores.length; i++)
   {
    if(i == n-1 || i == n) continue;
    separadores[i].addEventListener('mouseover', resaltarSep, true);
    separadores[i].addEventListener('mouseout', resaltarSep, true);
   }
 }

function cargarIcono(imagenArchivo)
 {
  var icono = document.getElementById('celdaIcono').firstChild;
  if(icono == null)
   {
    var icono = new Image();
    icono.setAttribute("id", "icono");
	var iconoHidd = document.createElement('input');
	iconoHidd.setAttribute("type", "hidden");
	iconoHidd.setAttribute("name", "miniatura");
	document.getElementById('celdaIcono').appendChild(icono);
	document.getElementById('celdaIcono').appendChild(iconoHidd);
   }
  icono.src = 'icono?archivo=img/'+CARPETA+'/imagenes/'+imagenArchivo;
  document.forms['formedicion']['miniatura'].value = imagenArchivo;
 }


/******************************************************/

var flechasOrden = new Array();
function precargaimgs()
 {
  for (i = 0; i < precargaimgs.arguments.length; i++)
   {
    flechasOrden[i] = new Image()
    flechasOrden[i].src = 'img/'+precargaimgs.arguments[i]+'.png';
   }
 }
precargaimgs('fl2_ab', 'fl2_arr');

function abrirModal(url, ancho, alto)
 {
  if (browser_es_ie)
   {
    var nAncho = ancho + 6;
    var nAlto = alto + 25;

    var formCont = showModalDialog(url,'FFFFFF','center:yes;resizable:yes;scroll:no;help:no;dialogHeight:'+nAlto+'px;dialogWidth:'+nAncho+'px;status:no;');
    if(formCont) // [1] != null && formCont[1].length > 1
     { agregarAdj(formCont[0], formCont[1]); }
   }
  else
   {
    var nombre = url.split('?');
    var posicion = '';
    posicion = "left="+((screen.width/2)-(ancho/2))+",";
    posicion += "top="+((screen.height/2)-(alto/2))+",";
    var formCont = window.open(url, nombre[0], "modal=yes,width="+ancho+"px,height="+alto+"px,,"+posicion);
   }
 }

function agregarAdj(id, nombre, ext)
 {
  document.producto.imagen.value = id;
  document.getElementById('lyr_imagen').innerHTML = '<img src="../e_productos/fotos/'+id+'.'+ext+'" alt="" \/><a href="javascript:descartarImg();">Descartar imagen<\/a>';
 }

function descartarImg()
 {
  document.producto.imagen.value = '';
  document.getElementById('lyr_imagen').innerHTML = '<a href="javascript:abrirModal(\'./imgs\', 510, 474);">Agregar imagen<\/a>';
 }

function actualizar(campo, itemId)
 {
  if(campo.value != document.getElementById('itembak'+itemId).value)
   {
    curr_campo = campo;
    curr_campoId = itemId;
    curr_campoVal = campo.value;
    curr_campo.className = 'campo_desactivado';
    loadXMLDoc('./prod_act_orden?id='+itemId+'&val='+campo.value);
   }
 }

var listaArr = new Array();
function construirLista(listado, parametro, nValor)
 {
  if(parametro == 2)
   {
    var ordenDir = nValor%2;
    if(ordenDir == 1)
     { nIndice = nValor; }
    else
     { nIndice = (nValor - 1); }

    if(listaArr[parametro] == nValor)
     {
      if(ordenDir == 1)
       { nValor++; }
      else
       { nValor--; }
     }
    else
     {
      var TcabezalAnterior = document.getElementById('Tcabezal'+indice);
      var TcabezalActual = document.getElementById('Tcabezal'+nIndice);
      document.getElementById('fl'+indice).src = 'img/trans';
      var TcabezalActual = document.getElementById('Tcabezal'+nIndice);
      indice = nIndice;
      TcabezalAnterior.className = '';
      TcabezalActual.className = 'sel';
     }

   }
  listaArr[parametro] = nValor;
  ordenDir = (nValor%2);
  document.getElementById('fl'+nIndice).src = flechasOrden[ordenDir].src;
  loadXMLLista('./catalogo.xml?p_id='+listaArr[1]+'&orden='+listaArr[2]+'&de='+listaArr[0]);
 }

function loadXMLLista(url)
 {
  tiempo = new Date();
  tiempo = tiempo.getTime();
  url += '&tiempo='+tiempo;
  if (window.XMLHttpRequest)
   {
    req = new XMLHttpRequest();
    req.onreadystatechange = estadoPeticion;
    req.open("GET", url, true);
    req.send(null);
   }
  else if (window.ActiveXObject)
   {
    isIE = true;
    req = new ActiveXObject("Microsoft.XMLHTTP");
    if (req)
     {
      req.onreadystatechange = estadoPeticion;
      req.open("GET", url, true);
      req.send();
     }
   }
 }

function estadoPeticion()
 {
  if (req.readyState == 4)
   {
    if (req.status == 200)
     {
      limpiarListado();
      construirListado();
     }
   }
 }

function limpiarListado()
 {
  var lista = document.getElementById('listado').childNodes[3];
  while(lista.firstChild)
   { lista.removeChild(lista.firstChild); }
 }
