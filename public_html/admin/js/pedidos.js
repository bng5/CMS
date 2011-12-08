// funcion duplicada de 'editar.js'

var browser_es_ie = document.all ? true : false;
var ventanaModal = null;
function abrirModal(url, ancho, alto)
 {
  if(browser_es_ie)
   {
    var nAncho = ancho + 6;
    var nAlto = alto + 25;
    ventanaModal = showModalDialog(url,'FFFFFF','center:yes;resizable:yes;scroll:no;help:no;dialogHeight:'+nAlto+'px;dialogWidth:'+nAncho+'px;status:no;');
    if(ventanaModal) // [1] != null && formCont[1].length > 1
     {
      imgCargada(false, formCont[0], formCont[1]);
     }
   }
  else
   {
    var nombre = url.split('?');
    var posicion = '';
    posicion = "left="+((screen.width/2)-(ancho/2))+",";
    posicion += "top="+((screen.height/2)-(alto/2))+",";
    ventanaModal = window.open(url, nombre[0], "width="+ancho+"px,height="+alto+"px,,"+posicion);
   }
  return false;
 }

function abrirPop(itemId)
 {
  var ancho = 550;
  var alto = 700;
  // ruta
  ventanaModal = window.open('ver_item?id='+itemId, 'item', "toolbar=no, location=no, directories=no, status=yes, menubar=no, scrollbars=yes, resizable=yes, width="+ancho+",height="+alto+",left="+((screen.width/2)-(ancho/2))+",top="+((screen.height/2)-(alto/2)));
  return ventanaModal ? false : true;
 }
