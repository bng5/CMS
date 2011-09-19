/****************************************/
/* ia */

function mostrarObjeto(obj, show)
 {
  obj = document.getElementById(obj);
  if (obj==null) return;

  obj.style.display = show ? 'block' : 'none';
  obj.style.visibility = show ? 'visible' : 'hidden';
 }

function selFila(selector, clase)
 {
  var fila = selector.parentNode.parentNode;
  if (fila==null) return;
  if (selector.checked == false)
   { fila.className = clase; }
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

function nuevaVentana(ref, ancho, alto, sesion)
 { window.open('./'+ref+'.php?sesion='+sesion+'','','toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width='+ancho+',height='+alto); }

var claveVisible = false;
function mostrarContrasenya()
 {
  mostrarclave = document.getElementById('mostrarclave');
  if(claveVisible == true)
   {
    claveVisible = false;
    mostrarclave.innerHTML = 'Mostrar contrase&ntilde;a';
    var tipo = 'password';
   }
  else
   {
    claveVisible = true;
    mostrarclave.innerHTML = 'Ocultar contrase&ntilde;a';
    var tipo = 'text';
   }

  for (i = 0; i < mostrarContrasenya.arguments.length; i++)
   {
    var campo = document.getElementById(mostrarContrasenya.arguments[i]);
    if(campo == null) { continue; }
    campo.setAttribute('type', tipo);
   }
 }

var browser_es_ie = document.all? true: false;
function abrirModal(url, ancho, alto)
 {
  if (browser_es_ie)
   {
    var nAncho = ancho + 6;
    var nAlto = alto + 25;
    var formCont = showModalDialog(url,'FFFFFF','center:yes;resizable:yes;scroll:no;help:no;dialogHeight:'+nAlto+'px;dialogWidth:'+nAncho+'px;status:no;');
    if(formCont) // [1] != null && formCont[1].length > 1
     { imgCargada(false, formCont[0], formCont[1]); }
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
  var guardar = document.getElementById('guardar');
  guardar.value = 'Guardar';
  guardar.blur();
 }

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

var animar;
function moverDivisor(dir)
 {
  if(divisorPos == dir) return;
  divisorPos += dir;
  if(divisorPos == 0)
   {
    //div1.className = '';
    //div2.className = '';
    div1.style.display = '';
    div2.style.display = '';
    celda1.firstChild.style.visibility = 'visible';
    celda2.firstChild.style.visibility = 'visible';
   }
  animar = setInterval('animarDivisor(divisorPos)', 35);
 }

function animarDivisor(dir)
 {
  var c1Ancho = parseInt(celda1.style.width);
  var c2Ancho = parseInt(celda2.style.width);
  if(dir == -1)
   {
    c1Ancho += 6;
    c2Ancho -= 6;
    if(c1Ancho == 98)
     {
      clearInterval(animar);
      //div2.className = 'opaco';
      div2.style.display = 'none';
      celda1.firstChild.style.visibility = 'hidden';
     }
    else if(c2Ancho == 44)
     { texto2.style.overflow = 'hidden'; }
   }
  else if(dir == 0)
   {
    if(c1Ancho > c2Ancho)
     {
      c1Ancho -= 6;
      c2Ancho += 6;
     }
    else
     {
      c1Ancho += 6;
      c2Ancho -= 6;
     }
    if(c1Ancho == 50)
     {
      texto1.style.overflow = '';
      texto2.style.overflow = '';
      clearInterval(animar);
     }
   }
  else if(dir == 1)
   {
    c1Ancho -= 6;
    c2Ancho += 6;
    if(c2Ancho == 98)
     {
      clearInterval(animar);
      //div1.className = 'opaco';
      div1.style.display = 'none';
      celda2.firstChild.style.visibility = 'hidden';
     }
    else if(c1Ancho == 44)
     { texto1.style.overflow = 'hidden'; }
   }
  celda1.style.width = c1Ancho+'%';
  celda2.style.width = c2Ancho+'%';
 }


/*
imagenCargando = new Image();
imagenCargando.src = '../img/cargando';
*/

function crearIframe()
 {
  var date = new Date();
  var itiempo = date.getTime();
  var iframe = document.createElement('iframe');
  iframe.setAttribute("name", "fr"+itiempo);
  iframe.setAttribute("id", "fr"+itiempo);
  //iframe.style.display = 'none';
  document.body.appendChild(iframe);
  return itiempo;
 }

function subirImg(campo, formulario)
 {
  if(campo.value.length > 1)
   {
//	filaCargando['subiendoTotal']++;
	//var formularioedicion = document.getElementById(formulario);
	var nframe = crearIframe();
	var formularioen = campo.form;
	formularioen.action = './galerias_imgsubir?id='+document.forms[formulario]['id'].value+'&frame='+nframe;
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

/*
function subirImg(campo, formulariopr)
 {
  if(campo.value.length > 1)
   {
    //var formulariopr = document.getElementById(formulario);
    var formularioen = campo.form;
    formularioen.submit();
    formularioen.reset();
    
	var resultados = document.createTextNode(total+' resultados en '+paginas+' páginas');
	listadoResult.appendChild(resultados);
	if(paginas > 1)
	 {
	  var br = document.createElement('br');
	  listadoResult.appendChild(br);
	    
	    
	    
	    
    var cargando = document.getElementById('cargando'+campo.name);
    cargando.innerHTML = 'Subiendo imagen <img src="./img/cargando.gif" alt="" \/>';
    cargando.style.display = 'block';
   }
 }
*/

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
    imagen.src = 'imagen?archivo=img/'+carpeta+'/'+imagenArchivo; //+'&max=120';
	imagen.setAttribute("value", imagenId);
    imagen.setAttribute("alt", imagenArchivo);
    imagen.setAttribute("title", imagenArchivo);
    var event = 'event';
	imagen.onmousedown = function()
      { mover(imagen, event); }
    imagen.onmouseup = function()
      { desplegarImg(imagenId, imagenArchivo, imagen); }
    galeriaDiv.appendChild(imagen);
    galeriaDiv.appendChild(sep);
    galeriaDiv.parentNode.parentNode.style.display = '';
    if(document.getElementById('celdaIcono') != null && document.getElementById('celdaIcono').firstChild == null)
     {
	  cargarIcono(imagenArchivo);
     }
    //document.getElementById('hd'+campo).value = imagenArchivo;
    //document.forms['en']['borrar'].value = imagenArchivo;
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

/* /ia */
/****************************************************/


function cargarIdioma()
 {
  if(req.readyState == 4)
   {
    if(req.status == 200)
     {
	  var novedad = req.responseXML.firstChild.childNodes;
	  document.forms["formedicion"]['titulo[]'][req.valor].lang = novedad[0].firstChild.nodeValue;
	  document.forms["formedicion"]['subtitulo[]'][req.valor].lang = novedad[0].firstChild.nodeValue;
	  document.forms["formedicion"]['texto[]'][req.valor].lang = novedad[0].firstChild.nodeValue;
	  /*
	  document.forms["formedicion"]['titulo[]'][req.valor].xml:lang = novedad[0].firstChild.nodeValue;
	  document.forms["formedicion"]['subtitulo[]'][req.valor].xml:lang = novedad[0].firstChild.nodeValue;
	  document.forms["formedicion"]['texto[]'][req.valor].xml:lang = novedad[0].firstChild.nodeValue;
	  */
	  document.forms["formedicion"]['titulo[]'][req.valor].dir = novedad[1].firstChild.nodeValue;
	  document.forms["formedicion"]['subtitulo[]'][req.valor].dir = novedad[1].firstChild.nodeValue;
	  document.forms["formedicion"]['texto[]'][req.valor].dir = novedad[1].firstChild.nodeValue;

	  if(novedad[2].childNodes.length >= 1) document.forms["formedicion"]['titulo[]'][req.valor].value = novedad[2].firstChild.nodeValue;
	  else document.forms["formedicion"]['titulo[]'][req.valor].value = '';
	  if(novedad[3].childNodes.length >= 1) document.forms["formedicion"]['subtitulo[]'][req.valor].value = novedad[3].firstChild.nodeValue;
	  else document.forms["formedicion"]['subtitulo[]'][req.valor].value = '';
	  if(novedad[4].childNodes.length >= 1) document.forms["formedicion"]['texto[]'][req.valor].value = novedad[4].firstChild.nodeValue;
	  else document.forms["formedicion"]['texto[]'][req.valor].value = '';
     }
	else if(req.status == 401)
	 {
	  alert('Su sesi\xF3n ha caducado!');
     }
    else
	 {
	 }
   }
 }

function cargarXMLLeng(selector, indice, id)
 {
  var selectores = new Array();
  selectores[0] = 1;
  selectores[1] = 0;
  var valor = selector.options[selector.selectedIndex].value
  if(valor >= 1) loadXMLDoc('./novedad.xml?id='+id+'&leng='+valor, cargarIdioma, indice);
  if(indice == 1 && document.forms["formedicion"]['leng[]'][1].length > document.forms["formedicion"]['leng[]'][0].length) document.forms["formedicion"]['leng[]'][1].options[0] = null;
  //alert(document.forms["formedicion"]['leng[]'][selectores[indice]].options[selector.selectedIndex].value);
  var selector2 = document.forms["formedicion"]['leng[]'][selectores[indice]];
  for(op in selector2.options)
   { selector2.options[op].disabled = false; }
  selector2.options[selector.selectedIndex].disabled = true;
 }

function aceptarForm(formulario)
 {
  formulario.dia.value = document.forms['en'].fechadia.value;
  formulario.mes.value = document.forms['en'].fechames.options[document.forms['en'].fechames.selectedIndex].value;
  formulario.anyo.value = document.forms['en'].fechaanyo.value;
  formulario.submit();
 }

function validarForm(boton, formulario)
 {
  /*
  var filaaviso = document.getElementById('avisoguardar');
  filaaviso.style.display = 'none';
  filaaviso.firstChild.firstChild.style.color = '#134679';
  if(formulario.titulo.value.length == 0)
   {
    filaaviso.firstChild.firstChild.innerHTML = 'Debe llenar el campo T\xEDÂ­tulo.';
    filaaviso.style.display = '';
    filaaviso.firstChild.firstChild.style.color = '#800000';
    return false;
   }
  else
   {
   */
    boton.value = 'Guardando...';
    aceptarForm(formulario);
   //}
 }
