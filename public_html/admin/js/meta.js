function htmlspecialchars(string, quote_style)
 {
  string = string.toString();

  string = string.replace(/&/g, '&amp;');
  string = string.replace(/</g, '&lt;');
  string = string.replace(/>/g, '&gt;');

  if (quote_style == 'ENT_QUOTES')
   {
	string = string.replace(/"/g, '&quot;');
	string = string.replace(/'/g, '&#039;');
   }
  else if (quote_style != 'ENT_NOQUOTES')
   {
	// All other cases (ENT_COMPAT, default, but not ENT_NOQUOTES)
	string = string.replace(/"/g, '&quot;');
   }
  return string;
 }


var estadoPress;
var estadoDown;
var estadoUp;
var etiquetas = {es : {}, en : {}};
var etiquetasCampos = [];


function largo(obj)
 {
  var t = -1;
  for(var x in obj)
{
if(obj[x] == null) continue;
    t += obj[x] + 1;
}
  return t;
 }

function Sup()
 {
  this.el = document.createElement('sup');
  this.el.appendChild(document.createTextNode('X'));
  var self = this.el;
  this.el.onclick = function()
   {
   	borrarEtiqueta(self);
   	//self.parentNode.parentNode.removeChild(self.parentNode);
	//etiquetas[metaActivo][self.parentNode.firstChild.nodeValue] = null;
   	//etiquetasCampos[metaActivo].maxLength = (250 - largo(etiquetas[metaActivo]));
   };
 }

function borrarEtiqueta(el)
 {
  el.parentNode.parentNode.removeChild(el.parentNode);
  etiquetas[metaActivo][el.parentNode.firstChild.nodeValue] = null;
  etiquetasCampos[metaActivo].maxLength = (250 - largo(etiquetas[metaActivo]));
 }

var patron = new RegExp("[;.:,=\"' \t]","g");
function mestadoPress(evento)
 {
  //if(!estadoPress) estadoPress = document.getElementById('estadoPress');
  if(!etiquetasCampos[metaActivo]) etiquetasCampos[metaActivo] = evento.target;

  switch(evento['keyCode'])
   {
    case 37:
    case 39:
    case 46:
	  return true;
	break;
   }

   	//case 32:
  if(evento['which'] == 13 || evento['which'] == 32 || evento['which'] == 44)
   {
	if(evento.target.value.length > 0)
	 {
	  //var cadena = evento.target.value.replace(/\W/g, ',');
	  var cadena = evento.target.value.replace(patron, ',');
	  var ingreso = cadena.split(",");
	  for(var i = 0; i < ingreso.length; i++)
	   {
		if(ingreso[i] == '' || etiquetas[metaActivo][ingreso[i]]) continue;
		cur_ingreso = ingreso[i];
		var span = document.createElement('span');
		span.appendChild(document.createTextNode(cur_ingreso));
		var supEl = evento.target.parentNode;
		supEl.insertBefore(span, evento.target);
		supEl.insertBefore(document.createTextNode(' '), evento.target);
		var input = document.createElement('input');
		input.setAttribute('type', 'hidden');
		input.setAttribute('name', 'etiqueta['+metaActivo+'][]');
		input.value = cur_ingreso;
		span.appendChild(input);
		var supe = new Sup();
		span.appendChild(supe.el);
		etiquetas[metaActivo][cur_ingreso] = cur_ingreso.length;
	   }
	  evento.target.value = '';
	  evento.target.maxLength = (250 - largo(etiquetas[metaActivo]));
	 }
	return false;
   }
  // break;

  keychar = String.fromCharCode(evento['which']);
  numcheck = /\w/;
  return numcheck.test(keychar);
 }

function mostrarMeta(codigo)
 {
  document.getElementById('etiqueta_idioma_'+metaActivo).className = 'etiqueta_idioma';

  document.getElementById('meta-'+metaActivo).style.display = 'none';
  metaActivo = codigo;
  document.getElementById('etiqueta_idioma_'+metaActivo).className = 'etiqueta_idioma seleccionado';
  document.getElementById('meta-'+codigo).style.display = 'block';
 }

function textCounter(field)
 {
  if(field.value.length > 250)
	field.value = field.value.substring(0, 250);
 }
