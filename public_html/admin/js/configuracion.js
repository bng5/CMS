
function esperaEnvioPost(elAviso, mostrar)
 {
  if(mostrar == true)
   {
	elAviso.style.display = '';
	if(elAviso.firstChild != null) elAviso.removeChild(elAviso.firstChild);
	elAviso.appendChild(document.createTextNode('Espere un momento'));
   }
  else
    elAviso.style.display = 'none';
 }

function enviarPost(formulario, ruta, hand, elAviso)
 {
  if(formulario['tipo'].selectedIndex == 0 || formulario['identificador'].value == '')
   {
	elAviso.innerHTML = '<span style="color:#800000;">Debe completar los campos "Identificador" y "Tipo"</span>';
	return false;
   }
  //var msj = '';
  var datos = '';
  if(window.XMLHttpRequest)
   {
	esperaEnvioPost(elAviso, true);
  	var reqTiempo = new Date();
   	ruta += '?reqTiempo='+reqTiempo.getTime();

	var els = formulario.elements;


 var resp = document.getElementById('resp');
 resp.innerHTML = '';

	for(var e = 0; e < els.length; e++)
	 {
	  if(els[e].name == '' || els[e].name == undefined) continue;
	  if((els[e].type == 'radio' || els[e].type == 'checkbox') && els[e].checked == false) continue;
	  datos += els[e].name+'='+els[e].value+'&';
resp.innerHTML += els[e].type+'\n';
	 }
resp.innerHTML += '\n-------------------------------------------------------------\n';
    var req = new XMLHttpRequest();
    req.onreadystatechange = function()
	 {
	  if(req.readyState == 4)
	   {
		eval(hand+"(req, elAviso)");
		esperaEnvioPost(elAviso, false);
	   }
	 }
	req.open("POST", ruta, true);
    req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    req.send(datos);
    return false;
   }
  else
    return true;
 }

function nodosAttr(req, filaaviso)
 {
  var modifs;
  if(req.status == 200)
   {
	   
	   
 document.getElementById('resp').innerHTML += req.responseText;

    arr_cont = document.getElementById('arrastre_cont');
    arr_cont.innerHTML += req.responseText;
    return;
   }
  else if(req.status == 401)
   {
   	alert('Su sesión ha expirado.');
   	modifs = -1;
   }
  else
   {
    filaaviso.innerHTML = 'Error: HTTP Status '+req.status;
    modifs = -1;
   }
	
 }






function changeIt()
 {
  if(!document.styleSheets) return;
  var theRules = new Array();
  if (document.styleSheets[0].cssRules)
	theRules = document.styleSheets[0].cssRules
  else if (document.styleSheets[0].rules)
	theRules = document.styleSheets[0].rules
  else return;
  theRules[0].style.backgroundColor = '#EEF0F5';
  //alert(theRules);
 }

var arrastre = false;
var arrastreEl;

function arrastrar(el, evento)
 {
  arrastreEl = el.parentNode;
  arrastreEl.previousSibling.style.display = 'none';

  //el.parentNode.parentNode.className = 'act';

  arrastreEl.style.position='absolute';
  arrastreEl.style.top = (evento.pageY+3)+'px';
  arrastreEl.style.left = evento.pageX+'px';
  arrastreEl.style.MozOpacity = 0.5;

  agregarEvento(document, 'mousemove', arrastrarEl);
  agregarEvento(document, 'mouseup', arrastrarDet);



  arrastre = true;
  return false;
 }

function arrastrarEl(evento)
 {
  arrastreEl.style.top = (evento.pageY+2)+'px';
  arrastreEl.style.left = evento.pageX+'px';
  return false;
 }

function arrastrarDet(evento)
 {
  arrastre = false;
  arrastreEl.style.position='';
  arrastreEl.style.MozOpacity = 1;
  arrastreEl.previousSibling.style.display = '';
  document.removeEventListener("mousemove", arrastrarEl, true);
  document.removeEventListener("mouseup", arrastrarDet, true);

  //if(copia) galeriaDiv.parentNode.removeChild(copia);
  var destino = evento.target;
  if(destino.tagName == 'hr' && destino.className == 'act')
   {
   	destino.className = '';

	clonSep = arrastreEl.previousSibling.cloneNode(true);
	clon = arrastreEl.cloneNode(true);
	arrastreEl.parentNode.removeChild(arrastreEl.previousSibling);
	arrastreEl.parentNode.removeChild(arrastreEl);
	destino.parentNode.insertBefore(clonSep, destino);
	var nEl = destino.parentNode.insertBefore(clon, destino);

	padre = nEl.parentNode;
	nEl.childNodes[1].value = (padre.id == 'arrastre_cont') ? '0' : padre.childNodes[0].value;

/*	nSup = 190;
	while(padre.id != 'arrastre_cont')
	 {
	  padre = padre.parentNode;
	  nSup -= 15;
	 }
	nEl.childNodes[3].style.width = nSup+'px';
*/
   }
  else if(destino.tagName == 'img' && destino.id == 'eliminarImg')
   {
   	deshab = document.getElementById('arrastre_cont_desact');
   	clonSep = arrastreEl.previousSibling.cloneNode(true);
	clon = arrastreEl.cloneNode(true);
	arrastreEl.parentNode.removeChild(arrastreEl.previousSibling);
	arrastreEl.parentNode.removeChild(arrastreEl);
	deshab.insertBefore(clon, deshab.firstChild);
	deshab.insertBefore(clonSep, deshab.firstChild);
   }
  //else if(evento.target.id == 'icono') cargarIcono(copia.alt);
  //copia = null;
 }

function resaltarSep(el, vis)
 {
  if(arrastre == false) return false;
  el.className = vis ? 'act' : '';
 }

function listaColapsar(el)
 {
  superior = el.parentNode.parentNode;
  if(superior.className == 'linea')
   {
   	superior.className = 'linea colapsada';
   	el.src = '/img/c';
   }
  else
   {
   	superior.className = 'linea';
   	el.src = '/img/e';
   }
  return false;
 }

var extraEl;
function mostrarExtra(id)
 {
  if(id == 10) id = 8;
  if(extraEl != null)
  	 extraEl.style.display = 'none';
  if(id == '') retrun;
  extraEl = document.getElementById('extra'+id);
  if(extraEl != null)
    extraEl.style.display = 'table-row-group';
  else
    {}//alert(id);
alert(id);
 }

// configuración
/*
var atributosOpUnicos = {1: 1, 2 : 3, 3 : 1, 4 : 3, 5 : 3, 6 : 3, 7 : 1, 8 : 3, 9 : 3, 10 : 1, 11 : 1, 12 : 2,13 : 2, 14 : 1, 15 : 1, 16 : 1, 17 : 3, 18 : 1, 19 : 1, 21 : 3};
function atributosOpciones(attrId)
 {
  if(atributosOpUnicos[attrId])
   {
   	var selattr = atributosOpUnicos[attrId];
//alert(selattr)
document.forms[0][7].disabled = (selattr == 1) ? true : false;
document.forms[0][8].disabled = (selattr == 2) ? true : false;
if(selattr == 2) document.forms[0][7].checked = true;
else document.forms[0][8].checked = true;
	//document.forms[0][8].checked
   	//alert(document.forms[0][7].type+' - '+document.forms[0][7].name);
   	//alert(document.forms[0][8].type+' - '+document.forms[0][8].name);
   }
 }
*/
