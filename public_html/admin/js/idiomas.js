var intervIdiomasMostrar = null;

function mostrarListaIdiomas()
 {
  var txtEnlace = document.getElementById('administ_idiomas').firstChild;
  if(IDIOMAS_CARGADOS == true)
   {
	idiomasCont = document.getElementById('idiomas_cont');
	if(intervIdiomasMostrar != null)
	  return false;
	txtEnlaceL = txtEnlace.length;
	if(!idiomasCont)
	 {
	  //idiomasCont = document.getElementById('idiomas_cont');//	 \" style=\"display:none
	  expandirSuperiores(document.forms[0]);
	 }
	if(idiomasCont.style.display == 'none')
	 {
	  txtEnlace.replaceData((txtEnlaceL - 2), txtEnlaceL, '<<');
	  idiomasCont.style.height = '5px';
	  idiomasCont.style.display = 'block';
	  intervIdiomasAlto = 5;
	  intervIdiomasMostrar = setInterval("aperturaIdiomasLista(true, idiomasCont, intervIdiomasAlto)", 90);
	 }
	else
	 {
	  txtEnlace.replaceData((txtEnlaceL - 2), txtEnlaceL, '>>');
	  idiomasCont.style.display = 'block';
	  intervIdiomasAlto = parseInt(idiomasCont.style.height);
	  intervIdiomasMostrar = setInterval("aperturaIdiomasLista(false, idiomasCont, intervIdiomasAlto)", 90);
	 }
   }
  else
   {
	var img = new Image();
	img.src = '/img/silk/ajax-loader';
	txtEnlace.parentNode.parentNode.appendChild(img);
	var ajast = new Ajast('http://bng5.net/cms2/api/v1/recursos/idiomas', {callback: 'crearArbolIdiomas'});
	ajast.loadImg = img;
   }
  return false;
 }

var IDIOMAS_DISP = {};
function idiomasAgLista(lista, contenedor, nivel)
 {
  var seleccionados = 0;
  var ul = document.createElement('ul');
  if(nivel == 0)
	ul.id = 'idiomas';
  contenedor.appendChild(ul);
  var li, label, flecha, etiquetaLabel;
  for(var i = 0; i < lista.length; i++)
   {
	if(IDIOMAS[lista[i]['codigo']])
     {
	  seleccionados++
	  etiquetaLabel = 'b';
	 }
	else
	  etiquetaLabel = 'a';
	li = document.createElement('li');
	label = document.createElement(etiquetaLabel);
	label.id = 'opc_'+lista[i]['codigo'];
	label.appendChild(document.createTextNode(lista[i]['nombre']));
	label.addEventListener('click', habilitarIdioma, false);
	li.appendChild(label);
	ul.appendChild(li);
	IDIOMAS_DISP[lista[i]['codigo']] = {nombre: lista[i]['nombre'], dir: lista[i]['dir']}
	if(lista[i]['regiones'])
	 {
	  flecha = new Image();
	  flecha.src = '/img/c';
	  flecha.addEventListener('click', expandir, false);
	  li.insertBefore(flecha, li.firstChild);

	  //$flecha->setAttribute('alt', '');
	  //$flecha->setAttribute('onclick', 'expandir(this)');
	  //$lista2 = $doc->createElement('ul');
	  //$lista2 = $li->appendChild($lista2);

	  if(idiomasAgLista(lista[i]['regiones'], li, ++nivel) > 0)
		li.className = 'expandido';
	  nivel--
	 }
   }
  return seleccionados;
 }

var IDIOMAS_CARGADOS = false;
function crearArbolIdiomas(idiomas)
 {
  idiomasAgLista(idiomas, document.getElementById('idiomas_cont'), 0);
  IDIOMAS_CARGADOS = true;
  mostrarListaIdiomas();

for(var ii in IDIOMAS)
  document.getElementById('fs_idiomas').childNodes[1].appendChild(document.createTextNode(IDIOMAS[ii]));
  //document.getElementById('fs_idiomas_disp').childNodes[1].appendChild();
 }

 function habilitarIdioma(event)
 {
  var cod = event.target.id.split('_')[1];
  if(IDIOMAS[cod])
	return false;

  if(document.getElementById('fila'+cod) != null)
   {
	var fila = document.getElementById('fila'+cod);
	var imgPub = fila.cells[2].firstChild;
	var radio = fila.cells[3].firstChild;
   }
  else
   {
	disponibTabla = document.getElementById('idiomas_disponibles');
	if(disponibTabla.tBodies[0] == null)
	 {

	 }
	var indice = 0;
	if(disponibBody.rows.length > 0)
	 {
	  while(disponibBody.rows[indice].textContent < cod)
	   {
	    indice++
	    if(indice == disponibBody.rows.length)
		  break;
	   }
	 }
	var fila = disponibBody.insertRow(indice);
	fila.setAttribute('id', 'fila'+cod);
	var td0 = fila.insertCell(0);
	var td1 = fila.insertCell(1);
	var td2 = fila.insertCell(2);
	var td3 = fila.insertCell(3);
	var td4 = fila.insertCell(4);
	var a = document.createElement('a');
	a.appendChild(document.createTextNode(cod));
	//a.setAttribute('href', '/idiomas?id='+cod);
	a.addEventListener('click', function(event)
	 {
	  idiomaEditar(cod);
	  event.preventDefault();
	 }, false);
	td0.appendChild(a);
	td1.appendChild(document.createTextNode(IDIOMAS_DISP[cod].nombre));
	td1.setAttribute('id', 'idiomaLabel'+cod);

	var imgPub = new Image();
	td2.appendChild(imgPub);
	imgPub.addEventListener('click', function() { conmSubEst(cod); }, false);

	var radio = new Image();//document.createElement('input');
	radio.addEventListener('click', function() { predeterminado(cod); }, false);
	//radio.setAttribute('type', 'radio');
	//radio.setAttribute('name', 'poromision');
	radio.setAttribute('id', 'poromision'+cod);
	//radio.setAttribute('value', cod);
	//radio.setAttribute('onchange', 'predeterminado(this.value)');
	//radio.setAttribute('title', 'Establecer idioma predeterminado');
	//if(estado != 1)
	//  radio.src = '/img/silk/bullet_white';
	td3.appendChild(radio);
   }
 }

// Expande idiomas
function expandir(event)
 {
  var img_el = event.target;
  superior = img_el.parentNode;
  if(superior.className == 'expandido')
   {
   	superior.className = '';
   	img_el.src = '/img/c';
   }
  else
   {
   	superior.className = 'expandido';
   	img_el.src = '/img/e';
   }
  return false;
 }

// Animación de apertura o cierre llamado por setInterval (mostrarListaIdiomas)
function aperturaIdiomasLista(abrir, el, alto)
 {
  if(abrir)
	intervIdiomasAlto = (alto * 2);
  else
	intervIdiomasAlto = (alto / 2);

  el.style.height = intervIdiomasAlto+'px';
  if(intervIdiomasAlto > 300 || intervIdiomasAlto < 5)
   {
    intervIdiomasMostrar = window.clearInterval(intervIdiomasMostrar);
	intervIdiomasMostrar = null;
	if(abrir == false)
	  el.style.display = 'none';
   }
 }