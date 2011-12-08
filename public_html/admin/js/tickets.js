
var TEXTOS = {};
TEXTOS['boolean'] = {};
TEXTOS['boolean']['true'] = 'Si';
TEXTOS['boolean']['false'] = 'No';
TEXTOS['navigator'] = {
	platform : 'Plataforma',
	appVersion : 'Versión',
	language : 'Lenguaje',
	appCodeName : 'Nombre clave',
	appName : 'Nombre',
	oscpu : 'Sistema operativo',
	vendor : 'Distribución',
	vendorSub : 'Versión de distribución',
	product : 'Motor',
	productSub : 'Versión de producto',
	securityPolicy : 'Política de seguridad ',
	userAgent : 'Identificación',
	cookieEnabled : 'Cookies habilitadas',
	onLine : 'En línea',
	javaEnabled : 'Java habilitado',
	buildID : 'Compilación',
	cpuClass : 'CPU',
	userLanguage : 'Lenguaje de usuario',
	browserLanguage : 'Lenguaje del navegador',
	appMinorVersion : 'Menor versión',
	plugins : 'Plugin Flash'
   };

function mostrar_subLista(el)
 {
  var siguienteEl = el.nextSibling;
  //alert(el.parentNode);
  if(siguienteEl.style.display == 'none')
   {
	siguienteEl.style.display = '';
	//el.parentNode.style.listStyleImage = "url('/img/e')";
    // ruta
	el.style.backgroundImage = "url('img/e')";
   }
  else
   {
	siguienteEl.style.display = 'none';
	//el.parentNode.style.listStyleImage = "url('/img/c')";
    // ruta
	el.style.backgroundImage = "url('img/c')";
   }
  return true;
 }





function detectarFlash()
 {
  //navigator.plugins.namedItem('Shockwave Flash') ? navigator.plugins.namedItem('Shockwave Flash').description : 'No'
  var flashDesc = 'No';
  if(navigator.plugins && navigator.plugins.length)
   {
    for(var x = 0; x < navigator.plugins.length; x++)
     {
	  if(navigator.plugins[x].name.indexOf('Shockwave Flash') != -1)
	   {
	    //flash_version = navigator.plugins[x].description.split('Shockwave Flash ')[1];
	    //flash_installed = 1;
	    flashDesc = navigator.plugins[x].description;
	    break;
	   }
     }
   }
  return flashDesc;
 }

function agregarCampoNav(x, valor)
 {
  attr = TEXTOS['navigator'][x] ? TEXTOS['navigator'][x] : x;
  label = document.createElement('label');
  label.appendChild(document.createTextNode(attr));
  li = document.createElement('li');
  li.appendChild(label);
  li.appendChild(document.createTextNode(' '));
  input = document.createElement('input');
  input.setAttribute('type', 'text');
  input.setAttribute('name', 'javascript['+x+']');
  input.setAttribute('readonly', 'readonly');
  input.value = valor;

  li.appendChild(input);
  salida.appendChild(li);
 }

var salida;
function datosNavegador()
 {
  salida = document.getElementById('navegadorInfo');
  salida.style.display = 'none';
  salida.lastChild.lastChild.value = 'Si';
  for(x in navigator)
   {
	valor = null;
	switch(typeof navigator[x])
	 {
	  case 'function':
		if(x == 'javaEnabled')
		  valor = TEXTOS['boolean'][navigator[x]()];
		else
		  continue;
		break;
	  case 'object':
		if(x != 'plugins')
		  continue;
		else
		  valor = detectarFlash();
		break;
	  case 'boolean':
		valor = TEXTOS['boolean'][navigator[x]];
		break;
	  case 'string':
		valor = navigator[x];
		break;
	 }
	agregarCampoNav(x, valor);
   }

 }

function validarFormTicket(form)
 {
  var retorno = true;

  var ul = document.createElement('ul');
  var li;
  if(form['tipo'].options[form['tipo'].selectedIndex].value == '0')
   {
   	li = document.createElement('li');
   	li.appendChild(document.createTextNode('Seleccione una \'Categoría\''));
	ul.appendChild(li);
   	retorno = false;
   }
  if(form['resumen'].value.length == 0)
   {
   	li = document.createElement('li');
   	li.appendChild(document.createTextNode('Complete el campo \'Resumen\''));
	ul.appendChild(li);
   	retorno = false;
   }
  if(form['descripcion'].value.length == 0)
   {
	li = document.createElement('li');
   	li.appendChild(document.createTextNode('Complete el campo \'Descripción\''));
	ul.appendChild(li);
   	retorno = false;
   }
  if(retorno == false)
   {
	var divMensaje = document.getElementById('div_mensaje');
	while(divMensaje.firstChild)
	  divMensaje.removeChild(divMensaje.firstChild);
	divMensaje.appendChild(ul);
	divMensaje.style.display = 'block';
	window.scrollTo(0, 0);
   }
  return retorno;
 }

agregarEvento(window, 'load', datosNavegador);

