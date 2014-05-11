var paleta;
function paletaDeColores(devMuestra, devCampo)
 {
  if(paleta)
   {
	paleta.antMuestra = paleta.devMuestra;
	paleta.antCampo = paleta.devCampo;
   	paleta.devMuestra = devMuestra;
	paleta.devCampo = devCampo;
	if(paleta.antMuestra != devMuestra)
	 {
	  paleta.posMuestra = obtenerPosAbsoluta(devMuestra);
	  paleta.tabla.style.left = (paleta.posMuestra['x'] + 15) + "px";
	  paleta.tabla.style.top = (paleta.posMuestra['y'] + 11) + "px";
	 }
    return paleta;
   }
  paleta = new function(muestra, campo)
   {
	this.tabla = paletaDeColores.construir();
	this.muestra = document.getElementById('paletaMuestra');
	this.campo = document.getElementById('ingresoRGB');
	this.devMuestra = devMuestra;
	this.devCampo = devCampo;
	this.posMuestra = obtenerPosAbsoluta(devMuestra);
	this.tabla.style.left = (this.posMuestra['x'] + 15) + "px";
	this.tabla.style.top = (this.posMuestra['y'] + 11) + "px";
	this.antMuestra = false;
	this.antCampo = false;
	var self = this;
	this.mostrar = function()
	 {
	  var vis = self.tabla.style.display;
	  self.tabla.style.display = (vis != 'block' || (vis == 'block' && this.devMuestra != this.antMuestra)) ? 'block' : 'none';
	 }
   }
  return paleta;
 }

paletaDeColores.construir = function()
 {
  colores = ['FFD5D5','FFDFD5','FFEAD5','FFF4D5','FFFFD5','F4FFD5','EAFFD5','DFFFD5','D5FFD5','D5FFDF','D5FFEA','D5FFF4','D5FFFF','D5F4FF','D5EAFF','D5DFFF','D5D5FF','DFD5FF','EAD5FF','F4D5FF','FFD5FF','FFD5F4','FFD5EA','FFD5DF','FFAAAA','FFBFAA','FFD5AA','FFEAAA','FFFFAA','EAFFAA','D5FFAA','BFFFAA','AAFFAA','AAFFBF','AAFFD5','AAFFEA','AAFFFF','AAEAFF','AAD5FF','AABFFF','AAAAFF','BFAAFF','D5AAFF','EAAAFF','FFAAFF','FFAAEA','FFAAD5','FFAABF','FF8080','FF9F80','FFBF80','FFDF80','FFFF80','DFFF80','BFFF80','9FFF80','80FF80','80FF9F','80FFBF','80FFDF','80FFFF','80DFFF','80BFFF','809FFF','8080FF','9F80FF','BF80FF','DF80FF','FF80FF','FF80DF','FF80BF','FF809F','FF5555','FF8055','FFAA55','FFD555','FFFF55','D5FF55','AAFF55','80FF55','55FF55','55FF80','55FFAA','55FFD5','55FFFF','55D5FF','55AAFF','5580FF','5555FF','8055FF','AA55FF','D555FF','FF55FF','FF55D5','FF55AA','FF5580','FF2A2A','FF602A','FF952A','FFCA2A','FFFF2A','CAFF2A','95FF2A','60FF2A','2AFF2A','2AFF60','2AFF95','2AFFCA','2AFFFF','2ACAFF','2A95FF','2A60FF','2A2AFF','602AFF','952AFF','CA2AFF','FF2AFF','FF2ACA','FF2A95','FF2A60','FF0000','FF4000','FF8000','FFBF00','FFFF00','BFFF00','80FF00','40FF00','00FF00','00FF40','00FF80','00FFBF','00FFFF','00BFFF','0080FF','0040FF','0000FF','4000FF','8000FF','BF00FF','FF00FF','FF00BF','FF0080','FF0040','D50000','D53500','D56A00','D59F00','D5D500','9FD500','6AD500','35D500','00D500','00D535','00D56A','00D59F','00D5D5','009FD5','006AD5','0035D5','0000D5','3500D5','6A00D5','9F00D5','D500D5','D5009F','D5006A','D50035','AA0000','AA2B00','AA5500','AA8000','AAAA00','80AA00','55AA00','2BAA00','00AA00','00AA2B','00AA55','00AA80','00AAAA','0080AA','0055AA','002BAA','0000AA','2B00AA','5500AA','8000AA','AA00AA','AA0080','AA0055','AA002B','800000','802000','804000','806000','808000','608000','408000','208000','008000','008020','008040','008060','008080','006080','004080','002080','000080','200080','400080','600080','800080','800060','800040','800020','550000','551500','552B00','554000','555500','405500','2B5500','155500','005500','005515','00552B','005540','005555','004055','002B55','001555','000055','150055','2B0055','400055','550055','550040','55002B','550015','2B0000','2B0B00','2B1500','2B2000','2B2B00','202B00','152B00','0B2B00','002B00','002B0B','002B15','002B20','002B2B','00202B','00152B','000B2B','00002B','0B002B','15002B','20002B','2B002B','2B0020','2B0015','2B000B','000000','050505','0B0B0B','101010','151515','1B1B1B','202020','252525','2B2B2B','303030','353535','3A3A3A','404040','454545','4A4A4A','505050','555555','5A5A5A','606060','656565','6A6A6A','707070','757575','7A7A7A','858585','8A8A8A','8F8F8F','959595','9A9A9A','9F9F9F','A5A5A5','AAAAAA','AFAFAF','B5B5B5','BABABA','BFBFBF','C5C5C5','CACACA','CFCFCF','D5D5D5','DADADA','DFDFDF','E4E4E4','EAEAEA','EFEFEF','F4F4F4','FAFAFA', 'FFFFFF'];
  var tabla = document.createElement('table');
  var tbody = document.createElement('tbody');
  tabla.appendChild(tbody);
  var i, j, k = 0;
  for(i = 1; i <= 13; i++)
   {
	var fila = document.createElement('tr');
	for(j = 0; j < 24; j++)
	 {
	  var td = celda(colores[k]);
	  fila.appendChild(td);
	   k++;
	 }
	tbody.appendChild(fila);
   }
  var cont = document.createElement('div');
  cont.setAttribute("id", "paletaDeColores");
  var cerrar = document.createElement('span');
  cerrar.setAttribute("id", "paletaCerrar");
  cerrar.appendChild(document.createTextNode('X'));
  cerrar['onclick'] = function()
   { paleta.tabla.style.display = 'none'; }
  cont.appendChild(cerrar);
  cont.appendChild(tabla);
  var muestra = document.createElement('div');
  muestra.setAttribute("id", "paletaMuestra");
  cont.appendChild(muestra);
  var span = document.createElement('span');
  span.setAttribute("id", "paletaIngresoRGB");
  span.appendChild(document.createTextNode('rgb: #'));
  var campo = document.createElement('input');
  campo.setAttribute("id", "ingresoRGB");
  campo.setAttribute("size", "6");
  campo.setAttribute("maxlength", "6");
  campo.setAttribute("readonly", "readonly");
  /*campo['onkeyup'] = function()
   { contar6rgb(this); }
  var btn = new Image();
  btn.src = '/img/flecha_bt';
  btn['onclick'] = function()
   {
    var valor = contar6rgb(this.previousSibling);
    if(valor)
     {
	  if(valor == -1)
	   {
	    alert('Debe ingresar un valor hexadecimal de 6 caracteres');
	    return false;
	   }
      selColor(valor);
     }
    else return false;
   }
  */
  span.appendChild(campo);
  //span.appendChild(btn);
  cont.appendChild(span);
  document.body.appendChild(cont);
  return cont;
 }

function celda(color)
 {
  var td = document.createElement('td');
  td.style.backgroundColor = '#'+color;
  td['onmouseover'] = function()
   {
	paleta.muestra.style.backgroundColor = '#'+color;
	paleta.campo.value = color;
   }
  td['onclick'] = function()
   { selColor(color); }
  return td;
 }

obtenerPosAbsoluta = function(el) {
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

function selColor(color)
 {
  paleta.devMuestra.style.backgroundColor = '#'+color;
  paleta.devCampo.value =color;
  paleta.tabla.style.display = 'none';
 }

function MakeNum(str)
 {
  if((str >= 0) && (str <= 9)) return;
  switch(str.toUpperCase())
   {
    case "A": return;
    case "B": return;
    case "C": return;
    case "D": return;
    case "E": return;
    case "F": return;
    default:  alert('Debe ingresar un valor hexadecimal (caracteres permitidos: entre 0 y F).');
    return 'X';
   }
 }

function contar6rgb(campo)
 {
  if (campo.value.length != 6) return -1;
  valoresHex = campo.value;
  for (i = 0; i <= 5; i++)
   {
	soloHex = MakeNum(valoresHex.substring(i,i+1));
	if(soloHex == 'X')
	 { return false; }
   }
  //paleta.muestra.style.backgroundColor = '#'+campo.value;
  campo.nextSibling.style.backgroundColor = '#'+campo.value;
  return campo.value;
 }
