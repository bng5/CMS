
if(!window.console || !console.log) {
    console = {log: function() {}};
}


// clase Ajast
function Ajast(url, parametros) {
    this.loadImg = false;
    var params = [];
    for(var p in parametros) {
        params.push(p+'='+escape(parametros[p]));
    }
    var consulta = params.length ? '?'+params.join('&') : '';
    var head = document.getElementsByTagName("head").item(0);
    var script = document.createElement("script");
    script.setAttribute("type", "text/javascript");
    script.setAttribute("src", url+consulta);
    head.appendChild(script);
    this.tag = script;
    var self = this;
    script.addEventListener('error', function() { self.Error(); }, true);
    script.addEventListener('load', function() { self.Load(); }, true);
}

Ajast.prototype.Error = function() {
    var mensaje = 'Error:\nNo fue posible obtener la información requerida.';
    alert(mensaje);
    this.RemoverTag();
}

Ajast.prototype.Load = function() {
    this.RemoverTag();
}

Ajast.prototype.RemoverTag = function() {
    if(this.loadImg != false)
        this.loadImg.parentNode.removeChild(this.loadImg);
    this.tag.parentNode.removeChild(this.tag);
}
// /clase Ajast




// Última función para envío de POST vía AJAX
function enviarXHR(url, hand, datos, contenidoTipo, params) {
	var envio = new XMLHttpRequest();
	envio.onreadystatechange = function() { // hand();
		if(envio.readyState == 4)
			hand(envio, params);//eval(hand+"(pet, elAviso)");
    };
	envio.open("POST", url, true);
	if(!contenidoTipo)
		contenidoTipo = 'application/x-www-form-urlencoded';
	envio.setRequestHeader('Content-Type', contenidoTipo);
	envio.send(datos);
}

function loadXMLDoc(url, hand, params)
 {
  if(window.XMLHttpRequest)
   {
    var req = new XMLHttpRequest();
    req.onreadystatechange = function() // hand();
	 {
	  if(req.readyState == 4)
	   {
		hand(req, params);
		//eval(hand+"(pet, elAviso)");
	   }
	 };
	//if(valor != null) req.valor = valor;
    req.open("GET", url, true);
    req.send(null);
   }
  else
   { alert('Su navegador no cuenta con, al menos, uno de los m\xE9todos necesarios para el funcionamiento del formulario.'); }
 }

function peticionAjax(url, metodo, handler, espera, parametros) {
	if(window.XMLHttpRequest) {
		this.req = new XMLHttpRequest();
		this.req.onreadystatechange = function() {// hand();
			if(this.req.readyState == 4) {
				handler(this.req, parametros);
				espera();
				//eval(hand+"(pet, elAviso)");
			}
		}
		this.req.open(metodo, url, true);
		this.req.send(null);
	}
}

function peticionXML(url, hand, params)
 {
  if(window.XMLHttpRequest)
   {
	var pet = new XMLHttpRequest();
    pet.onreadystatechange = function() // hand();
	 {
	  if(pet.readyState == 4)
	   {
		hand(pet, params);
		//eval(hand+"(pet, elAviso)");
	   }
	 }
    pet.open("GET", url, true);
    pet.send(null);
   }
  else
   { alert('Su navegador no cuenta con, al menos, uno de los m\xE9todos necesarios para el funcionamiento del formulario.'); }
 }

function enviarPostXML(accion, datos, espera, suceso, objAdjunto)
 {
  if(window.XMLHttpRequest)
   {
	espera();
  	var reqTiempo = new Date();
   	accion += '?reqTiempo='+reqTiempo.getTime();
	//var els = formulario.elements;
    objAdjunto['__req'] = new XMLHttpRequest();
    objAdjunto['__req'].onreadystatechange = function()
	 {
	  if(objAdjunto['__req'].readyState == 4)
	   {
		   suceso(objAdjunto);
		//eval(hand+"(req, elAviso)");
	   }
	 }
	objAdjunto['__req'].open("POST", accion, true);
    objAdjunto['__req'].setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	/*var datosStr = '';
	for(var e in datos)
	 {
	  //if(els[e].name == '' || els[e].name == undefined) continue;
	  datosStr += e+'='+datos[e]+'&';
	 }*/
    objAdjunto['__req'].send(datos);
    return false;
   }
  else
    return true;
 }

var peticionesSimult = 0;
// Muestra u oculta el cartel 'Cargando'
function mostrarCargando(mostrar)
 {
  var cargando = document.getElementById('cargando');
  if(mostrar)
	peticionesSimult++
  else
    peticionesSimult--
  mostrar = (peticionesSimult >= 1) ? true : false;
  cargando.style.display = mostrar ? 'block' : 'none';
  //var texto = mostrar ? 'Cargando\u2026' : ' ';
  cargando.replaceChild(document.createTextNode((mostrar ? 'Cargando\u2026' : ' ')), cargando.firstChild);
 }

function mostrarObjeto(obj, show)
 {
  obj = document.getElementById(obj);
  if (obj==null) return;

  obj.style.display = show ? 'block' : 'none';
  obj.style.visibility = show ? 'visible' : 'hidden';
 }

function agregarEvento(elemento, evento, funcion) {
	if(typeof elemento == "string")
		elemento = document.getElementById(elemento);
	if(elemento == null)
		return false;
	if(elemento.addEventListener)
		elemento.addEventListener(evento, funcion, true);
	else
		elemento["on" + evento] = funcion;
	return true;
}

var filaEstados = ['inactivo', '', 'enproceso'];
var filasEstilos = {};
function checkearTodo(formulario, selector, campos)
 {
  var e = formulario.elements;
  for(var i in e)
   {
   	if(e[i].name != campos || e[i].type != 'checkbox' || e[i].disabled) continue;
    valor = e[i].value;
	if(filasEstilos[valor] == null)
	  filasEstilos[valor] = e[i].parentNode.parentNode.className;
	e[i].checked = selector.checked;
    selFila(e[i], filasEstilos[valor]);
   }
 }

function selFila(selector, clase)
 {
  var fila = selector.parentNode.parentNode;
  if(fila == null) return;
  if(selector.checked == false)
   { fila.className = clase; }//fila.estilo; }
  else
   {
    mostrarObjeto('error_check_form', false);
    fila.className = 'sel_fila';
   }
 }

function contarCheck(campos)
 {
  var seleccionados = 0;
  var e = document.getElementsByName(campos);
  for(var i = 0; i < e.length; i++)
   {
    if(e[i].checked == true) seleccionados += 1;
   }
  if(seleccionados == 0)
   {
    mostrarObjeto('error_check_form', true);
    return false;
   }
  mostrarObjeto('error_check_form', false);
  return seleccionados;
 }

function confBorrado(campos)
 {
  var total = contarCheck(campos);
  if(total)
   {
    if(!confirm('Est\xE1 a punto de eliminar '+total+' item/s.\n\xBFDesea continuar?'))
      return false;
	return true;
   }
  else
	return false;
 }

function confBorradoCat(nombre, contenido, link)
 {
  if(contenido == 0)
   {
	var confirmacion = confirm('Est\xE1 a punto de eliminar la categoría '+nombre+'.\n\xBFDesea continuar?');
	if(confirmacion == true)
	  link.href += '&conf=1';
	return confirmacion;
   }
  return true;
 }

function obtenerPos(el)
 {
  var SL = 0, ST = 0;
  var is_div = /^div$/i.test(el.tagName);
  if (is_div && el.scrollLeft)
    SL = el.scrollLeft;
  if (is_div && el.scrollTop)
    ST = el.scrollTop;
  var r = { x: el.offsetLeft - SL, y: el.offsetTop - ST };
  if (el.offsetParent)
   {
    var tmp = obtenerPosAbsoluta(el.offsetParent);
    r.x += tmp.x;
    r.y += tmp.y;
   }
  return r;
 };

function selMarca(el)
 {
  var alt = el.alt;
  document.forms[0]['marca_arch'].value = alt;
  marcaMuestra = document.getElementById('img_marca');
  marcaMuestra.alt = alt;
  marcaMuestra.src = 'marcas/'+alt;
  el.parentNode.parentNode.parentNode.style.display = 'none';
 }

function mostrarMarcas(el)
 {
  if(el.checked == true && el.nextSibling.value == '')
	document.getElementById('img_mustraOp').style.display='block';
 }

function regenerarImagenes(req, params)
 {
  if(req.readyState == 4)
   {
    if(req.status == 200)
     {
	  regImagenes = document.getElementById('regImagenes').childNodes[2].firstChild;

	  try
	   {
	   	eval(req.responseText);
		if(respuestaM['porc'] >= 100)
	     {
		  regImagenes.replaceData(0, regImagenes.length, 'Finalizado');

	     }
	    else
	     {
		  if(respuestaM['errores'] != false)
		   {
			var regImagenesCont = document.getElementById('regImagenes');
		   	if(regImagenesCont.childNodes[3] != null) var regImagenesErrores = regImagenesCont.childNodes[4];
		   	else
		   	 {
		   	  regImagenesErrEnc = document.createElement('h4');
		   	  regImagenesErrEnc.appendChild(document.createTextNode('Las siguientes imagenes contienen errores'));
		   	  regImagenesCont.appendChild(regImagenesErrEnc);
		   	  var regImagenesErrores = document.createElement('ul');
			  regImagenesCont.appendChild(regImagenesErrores);
		   	 }
		   	for(var er = 0; er < respuestaM['errores'].length; er++)
		   	 {
		   	  var li = document.createElement('li');
			  li.appendChild(document.createTextNode(respuestaM['errores'][er]));
			  regImagenesErrores.appendChild(li);
		   	 }
		   }
//alert(req.responseText+'\n'+'modificar_archivos_img?atributo='+respuestaM['atributo']+'&desde='+respuestaM['hechos']+'&total='+respuestaM['total']);
		  regImagenes.replaceData(0, regImagenes.length, 'Progreso: '+respuestaM['porc']+'% ('+respuestaM['hechos']+'/'+respuestaM['total']+')');
		  loadXMLDoc('modificar_archivos_img?atributo='+respuestaM['atributo']+'&desde='+respuestaM['hechos']+'&total='+respuestaM['total'], regenerarImagenes, null)
	     }
	   }catch(e)
	     {
	      alert(req.responseText);
	     }
     }
	else if(req.status == 401)
	 {
	  alert('Su sesi\xF3n ha caducado!');
     }
    else
	 {
	  alert('-- '+req.status);
	 }
   }
 }

function iniciarRegImgs(attr)
 {
  loadXMLDoc('modificar_archivos_img?atributo='+attr+'&desde=0', regenerarImagenes, null);
  document.getElementById('regImagenes').lastChild.firstChild.insertData(0, 'Enviando petición...');
 }

function imgHabMinimo(selector)
 {
  var deshabilitado = (selector.options[selector.selectedIndex].value == 'escalar') ? false : true;
  // alert(selector.selectedIndex+'\n'+selector.options[selector.selectedIndex].value+'\n'+selector.options[selector.selectedIndex].text);
  document.getElementById('minancho_img').disabled = deshabilitado;
  document.getElementById('minalto_img').disabled = deshabilitado;

 }

function menuDesplegar(el) {
    el.parentNode.className = (el.parentNode.className == 'desplegable') ? 'desplegado' : 'desplegable';
}

function desplegarMenuAsc() {
    var menuActivo = document.getElementById('menu_activo');
    if(menuActivo != null) {
        if(menuActivo.className == "activo desplegable")
            menuActivo.className = 'desplegado';
        while(menuActivo.parentNode.tagName != 'div') {
            if(menuActivo.parentNode.tagName == 'li') {
                menuActivo.parentNode.className = 'desplegado';
            }
            menuActivo = menuActivo.parentNode;
        }
    }
}

agregarEvento(window, 'load', desplegarMenuAsc);

function cargaPreset(el)
 {
  if(PRESETS[el.options[el.selectedIndex].value])
   {
   	var preset = PRESETS[el.options[el.selectedIndex].value];
   	var formulario = el.form;
   	if(preset[0])
   	  formulario['info'][0].checked = true;
   	else
   	  formulario['info'][1].checked = true;
   	if(preset[1])
   	  formulario['items'][0].checked = true;
   	else
   	  formulario['items'][1].checked = true;
	if(preset[2])
   	  formulario['categorias'][0].checked = true;
   	else
   	  formulario['categorias'][1].checked = true;
	formulario['prof_categorias'].value = preset[3] ? preset[3] : '';
	formulario['tipo'].value = el.options[el.selectedIndex].text.toLowerCase();
   }
 }

function nuevoPreset(el)
 {
  if(el.selectedIndex == 1)
   {
	el.style.display = 'none';
	el.nextSibling.style.display = 'inline';
	el.nextSibling.focus();
   }
 }

function agNuevoPreset(el)
 {
  el.previousSibling.options[1].text = el.value ? el.value : 'Nueva configuración\u2026';
  el.style.display = 'none';
  el.previousSibling.style.display = 'inline';
  //el.previousSibling.focus();
 }

function capturaEnter(evento, el)
 {
  if(evento['which'] == 13)
   {
   	el.blur();
	return false;
   }
  return true;
 }

function trim(cadena)
 {
  return cadena.replace(/^\s*|\s*$/g,"");
 }
 