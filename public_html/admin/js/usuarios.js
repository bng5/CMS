var filaEstados = ['inactivo', '', 'enproceso'];

// PickList II script (aka Menu Swapper)- By Phil Webb (http://www.philwebb.com)
// Visit JavaScript Kit (http://www.javascriptkit.com) for this JavaScript and 100s more
// Please keep this notice intact

function move(fbox, tbox)
 {
  var arrFbox = new Array();
  var arrTbox = new Array();
  var arrLookup = new Array();
  var i;
  for(i = 0; i < tbox.options.length; i++)
   {
	arrLookup[tbox.options[i].text] = tbox.options[i].value;
	arrTbox[i] = tbox.options[i].text;
   }
  var fLength = 0;
  var tLength = arrTbox.length
  for(i=0; i<fbox.options.length; i++)
   {
	arrLookup[fbox.options[i].text] = fbox.options[i].value;
	if(fbox.options[i].selected && fbox.options[i].value != "")
	 {
	  arrTbox[tLength] = fbox.options[i].text;
	  tLength++;
	 }
	else
	 {
	  arrFbox[fLength] = fbox.options[i].text;
	  fLength++;
	 }
   }
  arrFbox.sort();
  arrTbox.sort();
  fbox.length = 0;
  tbox.length = 0;
  var c;
  for(c=0; c<arrFbox.length; c++)
   {
	var no = new Option();
	no.value = arrLookup[arrFbox[c]];
	no.text = arrFbox[c];
	fbox[c] = no;
   }
  for(c=0; c<arrTbox.length; c++)
   {
	var no = new Option();
	no.value = arrLookup[arrTbox[c]];
	no.text = arrTbox[c];
	tbox[c] = no;
   }
 }

function selectAll(box)
 {
/*	alert(formulario)
	return false
  var box = formulario[selector];
	*/
  if(box != null)
   {
    for(var i = 0; i < box.length; i++)
     {
	  box[i].selected = true;
     }
   }
   return false
 }

function mostrarSelPermisos(campo)
 {
  document.getElementById('permisos_sel').style.display = campo.checked ? 'none' : 'table-row';
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
    var str = 'Los permisos han sido reasignados ('+h+':'+m+' hs.).';
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
/*
function validarForm(boton, formulario)
 {
  var filaaviso = document.getElementById('avisoguardar');
  filaaviso.style.display = 'none';
  filaaviso.firstChild.firstChild.style.color = '#134679';
  if(formulario.titulo.value.length == 0)
   {
    filaaviso.firstChild.firstChild.innerHTML = 'Debe llenar el campo T\xED­tulo.';
    filaaviso.style.display = '';
    filaaviso.firstChild.firstChild.style.color = '#800000';
    return false;
   }
  else
   {
    boton.value = 'Guardando...';
    aceptarForm(formulario);
   }
 }
*/




/**********************************************/


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

function agregarGrupo(selectores, retipear, nombre)
 {
  var aviso = retipear ? retipear+".\n" : "";
  var def = nombre ? nombre : '';
  var grupo = prompt(aviso+"Nombre del grupo:", def);
  if(grupo != null && grupo != "")
   {
	var re = new RegExp(/^[a-zñçáéíóú][-a-z0-9_ ]{0,49}$/i);// /^[a-z][-a-z0-9_ ]{0,49}$/i;
	if(re.test(grupo) == false)
	 {
	  agregarGrupo(selectores, "El nombre debe comenzar con una letra", grupo);
	  return;
	 }
	for(var h = 0; h < selectores.length; h++)
	 {
	  var sel = document.getElementById(selectores[h]);
	  for(var i = 0; i < sel.options.length; i++)
	   {
	    if(grupo.toLowerCase()  == sel.options[i].text.toLowerCase() )
	     {
	      agregarGrupo(selectores, "Ya existe un grupo con ese nombre", grupo);
	      return;
	     }
	   }
	 }
    var option = document.createElementNS('http://www.w3.org/1999/xhtml', 'option');
    option.text = grupo;
    sel.add(option, sel.options[0]);
    sel.options[0].selected = true;
   }
 }

/*
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
****************************** */

/*
imagenCargando = new Image();
imagenCargando.src = '../img/cargando';
*/

/**********************************
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
	filaCargando['subiendoTotal']++;
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

************************ */
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

/***************************************
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
*************************************** */