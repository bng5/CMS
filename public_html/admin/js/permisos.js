var filaEstados = ['inactivo', '', 'enproceso'];

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


function esperaEnvioPost(elAviso)
 {
  elAviso.style.display = '';
  if(elAviso.firstChild.firstChild.firstChild != null) elAviso.firstChild.firstChild.removeChild(elAviso.firstChild.firstChild.firstChild);
  elAviso.firstChild.firstChild.appendChild(document.createTextNode('Espere un momento'));
 }

function enviarPost(formulario, ruta, hand, elAviso)
 {
  var msj = '';
  var datos;
  if(window.XMLHttpRequest)
   {
	esperaEnvioPost(elAviso);
  	var reqTiempo = new Date();
   	datos = 'reqTiempo='+reqTiempo.getTime();

	var els = formulario.elements;
	for(var e = 0; e < els.length; e++)
	 {
	  if(els[e].name == '') continue;
	  datos += '&'+els[e].name+'='+els[e].value;
	 }
    var req = new XMLHttpRequest();
    req.onreadystatechange = function()
	 {
	  if(req.readyState == 4)
	   {
		eval(hand+"(req, elAviso)");
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

function unHandler(req, filaaviso)
 {
  var modifs;
  if(req.status == 200)
	modifs = parseInt(req.responseXML.firstChild.firstChild.firstChild.nodeValue);
  else if(req.status == 401)
   {
   	alert('Su sesión ha expirado.');
   	modifs = -1;
   }
  else
	modifs = -1;

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

  filaaviso.firstChild.firstChild.innerHTML = str;
  var guardar = document.getElementById('guardar');
  guardar.value = 'Guardar';
  guardar.blur();
 }


function acondTablaPerm()
 {
  var tBody = document.getElementById('tabla_permisos').childNodes[2];
  for(var f = 0; f < tBody.childNodes.length; f++)
   {
   	changeEvento = 0;
	valorSel = tBody.childNodes[f].childNodes[1].firstChild.value;


	if(tBody.childNodes[f].childNodes[2].firstChild != null)
	 {
	  changeEvento += 1;
	  if(valorSel == '0')
		habilitarPermCat('0', tBody.childNodes[f].childNodes[2].firstChild);
	 }
	ident = tBody.childNodes[f].childNodes[1].firstChild.id.substring(7);
	if(subSecciones[ident] != null)
	 {
	  changeEvento += 2;
	  if(valorSel != '0')
		habilitarSubSeccs(ident, tBody.childNodes[f], '0');
	 }
	if(valorSel == '0')
	  tBody.childNodes[f].className = 'inactivo';

	if(changeEvento == 1)
	 {
	  tBody.childNodes[f].childNodes[1].firstChild['onchange'] = function()
	   {
		habilitarPermCat(this.value, this.parentNode.nextSibling.firstChild);
		this.parentNode.parentNode.className = this.value == '0' ? 'inactivo' : '';
	   }
	 }
	else if(changeEvento == 2)
	 {
	  tBody.childNodes[f].childNodes[1].firstChild['onchange'] = function()
	   {
		habilitarSubSeccs(this.id.substring(7), this.parentNode.parentNode, this.value);
		this.parentNode.parentNode.className = this.value == '0' ? 'inactivo' : '';
	   }
	 }
	else if(changeEvento == 3)
	 {
	  tBody.childNodes[f].childNodes[1].firstChild['onchange'] = function()
	   {
		habilitarSubSeccs(this.id.substring(7), this.parentNode.parentNode, this.value);
		habilitarPermCat(this.value, this.parentNode.nextSibling.firstChild);
		this.parentNode.parentNode.className = this.value == '0' ? 'inactivo' : '';
	   }
	 }
	else
	 {
	  tBody.childNodes[f].childNodes[1].firstChild['onchange'] = function()
	   {
		this.parentNode.parentNode.className = this.value == '0' ? 'inactivo' : '';
	   }
	 }

   }//for
 }

function habilitarPermCat(valor, selector)
 {
  if(valor == '0') selector.options[0].selected = true;
  selector.disabled = (valor == '0') ? true : false;
 }

function habilitarSubSeccs(id, fila, valor)
 {
  if(subSeccionesDib[id] == null)
   {
	var tabla = fila.parentNode;
	subSeccionesDib[id] = new Array();
	imgFondoOrd = '_ultimo';
	for(var x in subSecciones[id])
	 {
	  subSeccionesDib[id].push(x);
	  nfila = document.createElement('tr');
	  nfila.setAttribute('id', 'fila'+x);
	  ncelda0 = document.createElement('td');
	  ncelda0.style.backgroundImage = 'url(/img/nodo'+imgFondoOrd+')';
	  imgFondoOrd = '';
	  ncelda0.style.backgroundRepeat = "no-repeat";
	  nlabel = document.createElement('label');
	  nlabel.appendChild(document.createTextNode(subSecciones[id][x][0]));
	  ncelda0.appendChild(nlabel);
	  nlabel.style.marginLeft = "16px";
	  nlabel.setAttribute('for', 'seccion'+x);
	  ncelda0 = nfila.appendChild(ncelda0);
	  nceldasel = document.createElement('td');
	  if(subSecciones[id][x][3] >= subSecciones[id][x][1])
	   {
	   	nsel = document.createElement('span');
	   	nsel.appendChild(document.createTextNode(subSecciones[id][x][3]+'_ '+permisosTipos[subSecciones[id][x][3]]));
	   	nceldasel.appendChild(nsel);
	   }
	  else
	   {
		nsel = document.createElement('select');
		nsel.setAttribute('name', 'seccion['+x+'][2]');
		nsel.setAttribute('id', 'seccion'+x);
		nsel = nceldasel.appendChild(nsel);
	    for(o = 0; o <= subSecciones[id][x][1]; o++)
	     {
		  nsel.add(new Option(o+'_ '+permisosTipos[o], o),null);
	     }
	    nfila.className = subSecciones[id][x][3] ? '' : 'inactivo';
	    nsel.options[subSecciones[id][x][3]].selected = true;
	   }
	  nfila.appendChild(nceldasel);
	  ncelda = document.createElement('td');
	  if(subSecciones[id][x][2])
	   {

		if(subSecciones[id][x][4] > subSecciones[id][x][1])
		 {

		 }
		else
		 {

		  nsel['onchange'] = function()
		   {
			habilitarPermCat(this.value, this.parentNode.nextSibling.firstChild);
			this.parentNode.parentNode.className = this.value == '0' ? 'inactivo' : '';
		   }
		  nsel2 = document.createElement('select');
		  nsel2.setAttribute('name', 'seccion['+x+'][3]');
		  nsel2.setAttribute('id', 'seccionc'+x);
		  nsel2 = ncelda.appendChild(nsel2);
		  for(o = 0; o <= subSecciones[id][x][5]; o++)
		   {
		    nsel2.add(new Option(o+'_ '+permisosTipos[o], o),null);
		    if(o == 5) break;
	       }
	      if(subSecciones[id][x][3] == 0)
	        nsel2.disabled = true;
	      else
	        nsel2.options[subSecciones[id][x][4]].selected = true;
		 }
	   }
	  nfila.appendChild(ncelda);
	  tabla.insertBefore(nfila, fila.nextSibling);
	 }
   }
  else
   {
	for(o = 0; o < subSeccionesDib[id].length; o++)
	 {
	  fila = document.getElementById('fila'+subSeccionesDib[id][o]);
	  fila.style.display =  (valor == '0') ? 'none' : '';
	  if(valor != '0') continue;
	  fila.className =  'inactivo';
	  if(fila.childNodes[1].firstChild != null && fila.childNodes[1].firstChild.nodeName == 'select') fila.childNodes[1].firstChild.options[0].selected = true;
	  if(fila.childNodes[2].firstChild != null && fila.childNodes[2].firstChild.nodeName == 'select')
	   {
		fila.childNodes[2].firstChild.options[0].selected = true;
		fila.childNodes[2].firstChild.disabled = true;
	   }
	 }
   }
 }

var subSeccionesDib = {};

