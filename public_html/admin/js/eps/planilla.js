var copiar;
var aviso;
var accionFila = 0;
var movPos;
var MOVER = 1;
var AGREGAR = 2;
var ELIMINAR = 3;

function iniciarAccionFila(evento, accion)
 {
  switch(accionFila)
   {
	case AGREGAR:
	  agregarFilaFin();
	  if(accion == AGREGAR)
	    return;
	  break;
    case ELIMINAR:
	  eliminarFilaFin();
	  if(accion == ELIMINAR)
	    return;
	  break;
   }
  switch(accion)
   {
	case AGREGAR:
	  agregarFilaInicio(evento);
	  break;
    case ELIMINAR:
	  eliminarFilaInicio(evento);
	  break;
   }
 }

function moverFilaEl(evento)
 {
  aviso.style.top = evento.pageY+'px';
  aviso.style.left = (evento.pageX+7)+'px';
  evento.preventDefault();
 }

function detenerMovFila(evento)
 {
  accionFila = 0;
  document.body.removeChild(aviso);
  var destino = evento.target;
  var encontrado = false;
  while(destino.parentNode != null)
   {
	if(destino.tagName == 'tr')
	 {
	  encontrado = true;
	  break;
	 }
    destino = destino.parentNode;
   }
  copiar.className = '';
  if(encontrado && destino.rowIndex > 0)
   {
    var copia = copiar.cloneNode(true);
    if(movPos > destino.rowIndex)
     {
	  copiar.parentNode.insertBefore(copia, destino);
     }
    else
     {
	  copiar.parentNode.insertBefore(copia, destino.nextSibling);
     }
	agregarEvento(copia.cells[0].firstChild, 'mousedown', moverFila);
	new resaltarFila(copia, MOVER);
    copiar.parentNode.removeChild(copiar);
   }
  document.removeEventListener("mousemove", moverFilaEl, true);
  document.removeEventListener("mouseup", detenerMovFila, true);
 }

function layerArrastre(accion)
 {
  var avisos = {1: ['aviso_mover', 'Mover fila'], 2: ['aviso_agregar', 'Agregar fila'], 3: ['aviso_eliminar', 'Eliminar fila']};
  l = document.createElement('div');
  l.id = 'layerAccion';
  l.className = avisos[accion][0];
  l.appendChild(document.createTextNode(avisos[accion][1]));
  l.style.position = 'absolute';
  return l;
 }

function moverFila(evento)
 {
  evento.preventDefault();
  if(accionFila != 0)
    return false;
  accionFila = MOVER;
  var elemento = evento.target.parentNode.parentNode;
  movPos = elemento.rowIndex;
  elemento.className = 'mover';
  //document.body.style.cursor = 'move';
  //copia = elemento.cloneNode(true);
  copiar = elemento;
  //copiado = elemento;
  aviso = layerArrastre(MOVER);
  aviso.style.top = evento.pageY+'px';
  aviso.style.left = (evento.pageX+7)+'px';
  document.body.appendChild(aviso);

  agregarEvento(document, 'mousemove', moverFilaEl);
  agregarEvento(document, 'mouseup', detenerMovFila);
 }

function agregarFilaFin()
 {
  document.getElementById('boton_agregar').className = '';
  var tabla = document.getElementById('eps_planilla');
  var i = 1;
  while(tabla.rows.length > i)
   {
	tabla.deleteRow(i);
	i++
   }
  accionFila = 0;
  document.body.removeChild(aviso);
  document.getElementById('eps_planilla').className = 'eps_planilla';
 }

function agregarFilaInicio(evento)
 {
  document.getElementById('boton_agregar').className = 'activo';
  accionFila = AGREGAR;
  aviso = layerArrastre(AGREGAR);
  aviso.style.top = evento.pageY+'px';
  aviso.style.left = (evento.pageX+7)+'px';
  document.body.appendChild(aviso);
  agregarEvento(document, 'mousemove', moverFilaEl);
  var tabla = document.getElementById('eps_planilla');
  var nfila, ncelda;
//alert(tabla.rows.length)
  var tot_filas = (tabla.rows.length * 2);
  for(var i = 1; i < tot_filas; i += 2)
   {
	nfila = tabla.insertRow(i);
    ncelda = nfila.insertCell(0);
    ncelda.colSpan="7";
    ndiv = document.createElement('div');
    nfila.className = 'fila_reservada';
    ncelda.appendChild(ndiv);
	agregarEvento(nfila, 'click', agregarFila);
   }
 }

function agregarFila(evento)
 {
  var destino = evento.target;
  var encontrado = false;
  if(destino.tagName == 'button')
	return;
  while(destino.parentNode != null)
   {
	if(destino.tagName == 'tr')
	 {
	  encontrado = true;
	  break;
	 }
    destino = destino.parentNode;
   }

  var i = destino.rowIndex;
  if(encontrado)
   {
	var tabla = destino.parentNode;

	nfila = tabla.insertRow(i);
	ncelda = nfila.insertCell(0);
	ncelda.className = "marca";
	var span = document.createElement('span');
	span.className = "arrastre";
	agregarEvento(span, 'mousedown', moverFila);
	span.appendChild(document.createTextNode(' '));
	ncelda.appendChild(span);
	ncelda.appendChild(document.createTextNode(' '));

    var input = document.createElement('input');
	input.setAttribute('type', 'hidden');
	input.setAttribute('name', 'id[]');
	ncelda.appendChild(input);

	var input1 = document.createElement('input');
	input1.setAttribute('type', 'text');
	input1.setAttribute('name', 'marca[]');
	ncelda.appendChild(input1);

	ncelda = nfila.insertCell(1);
	input = document.createElement('input');
	input.setAttribute('type', 'text');
	input.setAttribute('name', 'modelo[]');
	ncelda.appendChild(input);

    ncelda = nfila.insertCell(2);
	input = document.createElement('input');
	input.setAttribute('type', 'text');
	input.setAttribute('name', 'insumo[]');
    ncelda.appendChild(input);

	ncelda = nfila.insertCell(3);
	input = document.createElement('select');
	input.setAttribute('name', 'tipo[]');

	var opcion = document.createElement('option');
	opcion.text=' ';
	opcion.value='';
	input.add(opcion,null);
	opcion = document.createElement('option');
	opcion.text='Tóner';
	opcion.value='2';
	input.add(opcion,null);
	opcion = document.createElement('option');
	opcion.text='Tinta';
	opcion.value='1';
	input.add(opcion,null);
	agregarEvento(input, 'change', cambioTipo);
	ncelda.appendChild(input);

	ncelda = nfila.insertCell(4);
	input = document.createElement('input');
	input.setAttribute('type', 'text');
	input.setAttribute('name', 'rendimiento[]');
	input.className = "precio";
	ncelda.appendChild(input);
	span = document.createElement('span');
	span.appendChild(document.createTextNode('\u00a0'));
	ncelda.appendChild(span);

	ncelda = nfila.insertCell(5);
	ncelda.appendChild(document.createTextNode('\u00a0\u20ac\u00a0'));
	input = document.createElement('input');
	input.setAttribute('type', 'text');
	input.setAttribute('name', 'precio_reman[]');
	input.className = "precio";
	ncelda.appendChild(input);

	ncelda = nfila.insertCell(6);
	ncelda.appendChild(document.createTextNode('\u00a0\u20ac\u00a0'));
	input = document.createElement('input');
	input.setAttribute('type', 'text');
	input.setAttribute('name', 'precio_nuevo[]');
	input.className = "precio";
	ncelda.appendChild(input);
	input1.focus();
	new resaltarFila(nfila, AGREGAR);
	nfila = tabla.insertRow(i);
	ncelda = nfila.insertCell(0);
	ncelda.colSpan="7";
	ndiv = document.createElement('div');
	nfila.className = 'fila_reservada';
	ncelda.appendChild(ndiv);
   }
  agregarFilaFin();
 }

function eliminarFilaFin()
 {
  document.getElementById('boton_eliminar').className = '';
  accionFila = 0;
  document.body.removeChild(aviso);
  document.getElementById('eps_planilla').className = 'eps_planilla';
  document.removeEventListener("mousemove", moverFilaEl, true);
  document.removeEventListener("click", eliminarFila, true);
 }

function eliminarFilaInicio(evento)
 {
  document.getElementById('boton_eliminar').className = 'activo';
  accionFila = ELIMINAR;
  aviso = layerArrastre(ELIMINAR);
  aviso.style.top = evento.pageY+'px';
  aviso.style.left = (evento.pageX+7)+'px';
  document.body.appendChild(aviso);
  document.getElementById('eps_planilla').className += ' p_borrar';
  agregarEvento(document, 'mousemove', moverFilaEl);
  agregarEvento(document, 'click', eliminarFila);
 }

function eliminarFila(evento)
 {
  var destino = evento.target;
  var encontrado = false;
  if(destino.tagName == 'button')
	return;
  while(destino.parentNode != null)
   {
	if(destino.tagName == 'tr')
	 {
	  encontrado = true;
	  break;
	 }
    destino = destino.parentNode;
   }
  if(encontrado && destino.rowIndex > 0)
   {
	var inputId = destino.cells[0].childNodes[2];
	if(inputId.value != '')
	 {
	  var form = inputId.form;
	  var inputBorrar = inputId.cloneNode(false);
	  inputBorrar.name = 'borrar[]';
	  form.appendChild(inputBorrar);
	 }
	destino.parentNode.removeChild(destino);
   }
   
//  var formulario;
//  while(destino.parentNode != null)
//   {
//	if(destino.tagName == 'form')
//	 {
//	  encontrado = true;
//	  break;
//	 }
//    destino = destino.parentNode;
//   }
   

  eliminarFilaFin();
 }

function cambioTipo(event)
 {
  var selector = event.target;
  var indice = selector.selectedIndex;
  var selectorFila = selector.parentNode.parentNode;
  var tipos = [' ', 'págs.', 'ml'];
  selectorFila.cells[4].lastChild.innerHTML = tipos[indice];
 }

function resaltarFila(nfila, accion)
 {
  var trans = {};
  trans[AGREGAR] = ['28C728', '28C728', '28C728', '38d738', '48e748', '68f768', '88ff88','a8ffa8','ffffff'];
  trans[MOVER]   = ['dfefef', 'cfdfdf', '80cccc', '80cccc', '80cccc', 'cfdfdf', 'dfefef','efffff','ffffff'];
  this.fila = nfila;
  this.transicion = trans[accion];
  this.i = 0;
  var self = this;
  function intervalo()
   {
	self.fila.style.backgroundColor = '#'+self.transicion[self.i];
	self.i++
	if(self.i >= self.transicion.length)
	 {
	  window.clearInterval(self.t);
	  //self = null;
	 }
   }
  this.fila.style.backgroundColor = '#'+this.transicion[this.i];
  this.i++
  this.t = setInterval(intervalo, 70);
  //this.t = setTimeout("alertar(i)", 5000);
 }
