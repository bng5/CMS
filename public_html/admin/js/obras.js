/* Listado */
var isIE = false;
var req;

function loadXMLDoc(url, hand, valor)
 {
  if(window.XMLHttpRequest)
   {
    req = new XMLHttpRequest();
    req.onreadystatechange = hand;
    if(valor != null) req.valor = valor;
    req.open("GET", url, true);
    req.send(null);
   }
  else if (window.ActiveXObject)
   {
    isIE = true;
    req = new ActiveXObject("Microsoft.XMLHTTP");
    if (req)
     {
      req.onreadystatechange = hand;
      if(valor != null) req.valor = valor;
      req.open("GET", url, true);
      req.send();
     }
   }
  else
   { alert('Su navegador no cuenta con, al menos, uno de los m\xE9todos necesarios para el funcionamiento del formulario.'); }
 }

function mostrarObjeto(obj, show)
 {
  obj = document.getElementById(obj);
  if (obj==null) return;

  obj.style.display = show ? 'block' : 'none';
  obj.style.visibility = show ? 'visible' : 'hidden';
 }

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

	  var rpp = attrsPrimerNodo.getNamedItem("rpp") ? parseInt(attrsPrimerNodo.getNamedItem("rpp").value) : 0;
	  var total = attrsPrimerNodo.getNamedItem("total") ? parseInt(attrsPrimerNodo.getNamedItem("total").value) : 0;
	  var pagina = attrsPrimerNodo.getNamedItem("pagina") ? parseInt(attrsPrimerNodo.getNamedItem("pagina").value) : 0;
	  var paginas = attrsPrimerNodo.getNamedItem("paginas") ? parseInt(attrsPrimerNodo.getNamedItem("paginas").value) : 0;

	  if(total > 0)
	   {
	    tablaListado.style.display = '';
	    //listadoOpciones.style.display = '';
	   }

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
		  ant.onclick = function()
		   { loadXMLDoc('./galeria.xml?leng=1&pagina='+anterior, cargarListado, null); }
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
		    num.onclick = function()
		     { loadXMLDoc(this.ruta, cargarListado, null); }
		   }
		  num.appendChild(numLabel);
		  listadoResult.appendChild(num);
         }

	    if(pagina < paginas)
	     {
	      pagina++
		  var sig = document.createElement('a');
		  sig.onclick = function()
		   { loadXMLDoc('./galeria.xml?leng=1&pagina='+pagina, cargarListado, null); }
		  var sigLabel = document.createTextNode('>>');
		  sig.appendChild(sigLabel);
		  listadoResult.appendChild(sig);
	     }
	   }  

	  //if(productos.length >= 1) select.parentNode.style.display = "block";
	  var contenedor = tablaListado.getElementsByTagName('tbody')[0];
	  for (var i = 0; i < galerias.length; i++)
	   {
		/*
	    clienteId = galerias[i].attributes.getNamedItem("id") ? galerias[i].attributes.getNamedItem("id").value : null;
	    estado = galerias[i].attributes.getNamedItem("estado") ? galerias[i].attributes.getNamedItem("estado").value : null;
	    img = galerias[i].attributes.getNamedItem("img") ? galerias[i].attributes.getNamedItem("img").value : null;
	    orden = galerias[i].attributes.getNamedItem("orden") ? galerias[i].attributes.getNamedItem("orden").value : null;
	    creada = galerias[i].attributes.getNamedItem("creada") ? galerias[i].attributes.getNamedItem("creada").value : null;
	    titulo = galerias[i].firstChild ? galerias[i].firstChild.nodeValue : null;
		*/
		//alert('<galeria id="'+id+'" estado="'+estado+'" img="'+img+'" orden="'+orden+'">'+titulo+'</galeria>');
		agregarAListado(contenedor, galerias[i].childNodes[0].attributes.getNamedItem("id").value, galerias[i].childNodes[0].firstChild.nodeValue, galerias[i].childNodes[1].firstChild.nodeValue);
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

var filaEstados = ['inactivo', '', 'enproceso'];
function agregarAListado(contenedor, obraId, obra, cliente)
 {
//alert('contenedor: '+contenedor+'\nobraId: '+obraId+'\nobra: '+obra+'\ncliente: '+cliente);
  var tr = document.createElement('tr');
  var td1 = document.createElement('td');
  var td2 = document.createElement('td');
  var td3 = document.createElement('td');
  var td4 = document.createElement('td');
  var td5 = document.createElement('td');
  var td6 = document.createElement('td');

  //var a = document.createElement('a');
  //a.setAttribute('href', 'obras?cat='+CAT+'&6='+obraId);
  var img = document.createElement('img');
  img.src = 'icono/imagenesChicas/'+cliente;
  var nombre = document.createTextNode(obra);
  //a.appendChild(nombre);
  td1.appendChild(img);

  //var nombreCliente = document.createTextNode(cliente);
  td2.appendChild(nombre);


  var antes = document.createElement('a');
  antes.setAttribute('href', 'obras?cat=16&6='+obraId);
  //var nombre = document.createTextNode(obra);
  antes.appendChild(document.createTextNode('antes'));
  td3.appendChild(antes);
  var durante = document.createElement('a');
  durante.setAttribute('href', 'obras?cat=17&6='+obraId);
  durante.appendChild(document.createTextNode('obra'));
  td4.appendChild(durante);
  var despues = document.createElement('a');
  despues.setAttribute('href', 'obras?cat=18&6='+obraId);
  despues.appendChild(document.createTextNode('después'));
  td5.appendChild(despues);
  var documentos = document.createElement('a');
  documentos.setAttribute('href', 'documentos?obra='+obraId);
  documentos.appendChild(document.createTextNode('documentos'));
  td6.appendChild(documentos);
  tr.appendChild(td1);
  tr.appendChild(td2);
  tr.appendChild(td3);
  tr.appendChild(td4);
  tr.appendChild(td5);
  tr.appendChild(td6);
  contenedor.appendChild(tr);
 }

function checkearTodo(formulario, selector, campos)
 {
  var e = document.getElementsByName(campos);
  for(var i = 0; i < e.length; i++)
   {
    if(e[i].type == 'checkbox' && e[i].disabled == false)
     {
      e[i].checked = selector.checked;
      selFila(e[i], '');
     }
   }
 }

function selFila(selector, clase)
 {
  var fila = selector.parentNode.parentNode;
  if (fila==null) return;
  if (selector.checked == false)
   { fila.className = fila.estilo; }
  else
   {
//    mostrarObjeto('error_us_form', false);
    fila.className = 'sel_fila';
   }
 }

function contarCheck(campos)
 {
  var seleccion = false;
  var seleccionados = 0;
  var e = document.getElementsByName(campos);
  for(var i = 0; i < e.length; i++)
   {
    if (e[i].checked == true)
     {
      seleccion = true;
      seleccionados += 1;
     }
   }
  if(seleccion == false)
   {
    mostrarObjeto('error_check_form', true);
    return false;
   }
  mostrarObjeto('error_check_form', false);
  return seleccionados;
 }

function confBorrado(campos)
 {
  total = contarCheck(campos);
  if(total)
   {
    if(!confirm('Est\xE1 a punto de eliminar '+total+' item/s.\n\xBFDesea continuar?'))
     { return false; }
   }
  else
   { return false; }
 }

function ordenPublicacion(seccion, criterio, direccion, criterioTexto)
 {
  //alert(seccion+'\n'+criterio+'\n'+direccion);
  //return;
  var ordenDir = direccion ? '1': '';
  loadXMLDoc('./ordenar_publicacion.txt?seccion='+seccion+'&orden_criterio='+criterio+'&orden_dir='+ordenDir, ordenActualizado, [seccion, criterio, direccion, criterioTexto]);
 }

function ordenActualizado()
 {
  if(req.readyState == 4)
   {
    if(req.status == 200)
     {
	  if(req.responseText == "1")
	   {
	    var ordenDir = req.valor[2] ? "descendente": "ascendente";
		var divMensaje = document.getElementById('div_mensaje');
		while(divMensaje.firstChild) divMensaje.removeChild(divMensaje.firstChild);
	    divMensaje.appendChild(document.createTextNode("El orden de publicación ha sido modificado: "+req.valor[3]+" "+ordenDir+"."));
	    divMensaje.style.display = '';
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


/**********************************************/
/* Edición */

var browser_es_ie = document.all ? true : false;
function abrirModal(url, ancho, alto)
 {
  if(browser_es_ie)
   {
    var nAncho = ancho + 6;
    var nAlto = alto + 25;
    var formCont = showModalDialog(url,'FFFFFF','center:yes;resizable:yes;scroll:no;help:no;dialogHeight:'+nAlto+'px;dialogWidth:'+nAncho+'px;status:no;');
    if(formCont) // [1] != null && formCont[1].length > 1
     {
      imgCargada(false, formCont[0], formCont[1]);
     }
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
  if(publicar == true) document.forms['formedicion']['publicar'].value = '1';
  formulario.submit();
 }

function cambiosGuardados(id, modifs)
 {
  document.forms['formedicion']['id'].value = id;
  if(modifs > 0)
   {
    var tiempo = new Date();
    var h = tiempo.getHours();
    var m = tiempo.getMinutes();
    if(m < 10) m = '0'+m;
    var str = 'Los cambios han sido guardados satisfactoriamente ('+h+':'+m+' hs.).';
   }
  else if(modifs == 0)
   { var str = 'No se ha realizado ning\xFAn cambio.'; }
  else if(modifs < 0)
   { var str = '<span style="color:#800000;">Ha ocurrido un error inesperado y NO fue posible guardar los cambios.<\/span>'; }
  var filaaviso = document.getElementById('avisoguardar');
  filaaviso.style.display = '';
  filaaviso.firstChild.firstChild.innerHTML = str;
  //var guardar = document.getElementById('guardar');
  if(document.forms['formedicion']['publicar'].value == '1')
   {
	document.forms['formedicion']['publicar'].value = '0';
	document.forms['en']['btPublicar'].value = 'Guardar/Publicar';
   }
  else
   {
    document.forms['en']['btGuardar'].value = 'Guardar';
    //guardar.value = 'Guardar';
    //guardar.blur();
   }
 }

function mostrarGal()
 {
  document.getElementById('fila_galeria').style.display = '';
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
  return itiempo;
 }

function subirImg(campo, formulario)
 {
  if(campo.value.length > 1)
   {
	filaCargando['subiendoTotal']++;
	//var formularioedicion = document.getElementById(formulario);
	var nframe = crearIframe();
	var formularioen = campo.form;
	formularioen.action = './obras_imgsubir?id='+document.forms[formulario]['id'].value+'&frame='+nframe;
	formularioen.target = "fr"+nframe;
	formularioen.submit();
	formularioen.reset();

	//var galeriaDiv = document.createElement('div');
	if(filaCargando.childNodes[1].firstChild == null)
	 {
	  var cargando = new Image();
	  cargando.src = '../img/cargando';
	  //galeriaDiv.setAttribute("id", "im"+nframe);
	  //imgcontenedor.setAttribute("class", "miniatura");
	  var txtcargando = document.createTextNode('Cargando');
	  filaCargando.childNodes[1].appendChild(txtcargando);
	  filaCargando.childNodes[1].appendChild(cargando);
	 }
	//filaCargando.style.display = '';
	filaCargando.childNodes[1].style.display = '';
	//galeria = document.getElementById('galeria');
	//galeria.appendChild(galeriaDiv);
	// if(galeria.childNodes.length > 0)
	// {
	//galeria.style.display = 'block';
	// }
   }
 }

function imgCargada(errorno, imagenId, imagenArchivo, frame, carpeta)
 {
  filaCargando['subiendoTotal']--;
  
  //cargando.style.display = 'block';
  if(errorno == false)
   {
   	//var galeriaDiv = document.createElement('div');
   	var sep = document.createElement('span');
    var imagen = document.createElement('input');
    imagen.setAttribute("type", "image");
    imagen.src = 'imagenobra/img/'+carpeta+'/'+imagenArchivo; //+'&max=120';
	imagen.setAttribute("value", imagenId);
    imagen.setAttribute("alt", imagenArchivo);
    imagen.setAttribute("title", imagenArchivo);
    imagen.setAttribute("name", "imagen[]");
    var event = 'event';
	imagen.onmousedown = function()
      { mover(imagen, event);return false; }
    imagen.onmouseup = function()
      { desplegarImg(imagenId, imagenArchivo, imagen);return false; }
    imagen.onclick = function()
      { return false; }
    galeriaDiv.appendChild(imagen);
    galeriaDiv.appendChild(sep);
    galeriaDiv.parentNode.parentNode.style.display = '';
    if(document.getElementById('celdaIcono') != null && document.getElementById('celdaIcono').firstChild == null)
	  cargarIcono(imagenArchivo);
	var imgEstado = document.createElement('input');
	imgEstado.setAttribute("type", "hidden");
	imgEstado.setAttribute("name", "img_estado["+imagenId+"]");
	imgEstado.setAttribute("value", "1");
	var imgTitulo = document.createElement('input');
	imgTitulo.setAttribute("type", "hidden");
	imgTitulo.setAttribute("name", "img_titulo["+imagenId+"]");
	var imgTexto = document.createElement('textarea');
	imgTexto.setAttribute("name", "img_texto["+imagenId+"]");
	imgTexto.className = 'oculto';
	var imgFecha = document.createElement('input');
	imgFecha.setAttribute("type", "hidden");
	imgFecha.setAttribute("name", "img_fecha["+imagenId+"]");
	imgFecha.setAttribute("id", "img_fecha"+imagenId);
	document.forms['formedicion'].appendChild(imgEstado);
	document.forms['formedicion'].appendChild(imgTitulo);
	document.forms['formedicion'].appendChild(imgFecha);
	document.forms['formedicion'].appendChild(imgTexto);
   }
  else
   {
	var errordiv = document.createElement('div');
	var errorstr = document.createTextNode('Error '+errorno);
	errordiv.appendChild(errorstr);
	filaCargando.childNodes[3].appendChild(errordiv);
   }
  if(filaCargando['subiendoTotal'] == 0)
   {
	filaCargando.childNodes[1].style.display = 'none';
	//if(filaCargando.firstChild.childNodes[2].firstChild == null) filaCargando.style.display = 'none';
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
  icono.src = 'icono?archivo=../img/galerias/imagenes/'+imagenArchivo;
  document.forms['formedicion']['miniatura'].value = imagenArchivo;
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
  for(i = 0; i < separadores.length; i++)
   {
    separadores[i].removeEventListener('mouseover', resaltarSep, true);
    separadores[i].removeEventListener('mouseout', resaltarSep, true);
   }

  if(copia) galeriaDiv.parentNode.removeChild(copia);
  if(evento.target.tagName == 'SPAN' && evento.target.parentNode.id == 'galeria')
   {
    evento.target.style.border = '';
	//evento.target.style.margin = '0';
	evento.target.style.padding = '4px';
	evento.target.style.width = '2px';
	evento.target.style.height = '64px';
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

agregarEvento = function(el, evname, func)
 {
  if(typeof el == "string") el = document.getElementById(el);
  if(el == null) return false;

  if (el.attachEvent) el.attachEvent("on" + evname, func); // IE
  else if (el.addEventListener) el.addEventListener(evname, func, true); // Gecko / W3C
  else el["on" + evname] = func;
 };

function desplegarImg(imagenId, imagenArchivo)
 {
  selImg = imagenId;
  document.forms['en']['img_editando'].value = imagenId;
  /*
  var fechaImp = '';
  var v = document.forms['formedicion']['img_fecha['+imagenId+']'].value;
  if(v.length > 9)
   {
	var vArr = v.split(" ");
	var dia = vArr[0].split("-");
	var hora = vArr[1].split(":");
	var fecha = new Date(dia[0], dia[1]-1, dia[2], hora[0], hora[1], hora[2]);
	fechaImp = fecha.print("%A, %d de %B de %Y, %H:%m hs.", true);
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
  */
  document.forms['en']['img_titulo'].value = document.forms['formedicion']['img_titulo['+imagenId+']'].value;
  if(filaCargando.childNodes[2].firstChild.firstChild) filaCargando.childNodes[2].firstChild.removeChild(filaCargando.childNodes[2].firstChild.firstChild);
  filaCargando.childNodes[2].firstChild.appendChild(document.createTextNode(imagenArchivo));

  filaCargando.childNodes[2].childNodes[1].src = 'imagenobra/img/galerias/imagenes/'+imagenArchivo;
  filaCargando.childNodes[2].style.display = '';
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
    //separadores[i].addEventListener('mouseup', resaltarSep, true);
   }
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
    //separador.style.margin = '0 4px';
    separador.style.padding = '0';
   }
  else
   {
 	separador.style.border = '';
	separador.style.width = '2px';
    separador.style.height = '64px';
	//separador.style.margin = '0';
	separador.style.padding = '4px';
   }
 }

function borrarImg(selImg)
 {
//alert(selImg)
  //selImg = document.forms['formimg_pos']['eliminarImg'].value;
//  if(selImg > 0)
//   {
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
//   }
  return;
 }

function actualizarImgInfo(campo)
 {
  var id = document.forms['en']['img_editando'].value;
  if(id == '') return false;
  var nombre = campo.name;
  document.forms['formedicion'][nombre+'['+id+']'].value = document.forms['en'][nombre].value;
 }
 
function actualizarImgEstado(campo)
 {
  var id = document.forms['en']['img_editando'].value;
  if(id == '') return false;
  document.forms['formedicion']['img_estado['+id+']'].value = campo.checked ? '1' : '2';
 }
