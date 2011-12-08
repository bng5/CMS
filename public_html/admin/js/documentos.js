
function editarDoc(id, editar)
 {
  var input = document.getElementById('nombredoc'+id);
  document.getElementById('linkdoc'+id).style.display = editar ? 'none' : 'block';
  input.style.display = editar ? 'inline' : 'none';
  if(editar == true)
   {
    input.firstChild.focus();
    archNombre = input.firstChild.value;
   }
  else
   { input.firstChild.value = archNombre; }
  return false;
 }

function renombrarDoc(campo, id, cat, obra)
 {
  editarDoc(id, false);
  cargarXML('renombrar='+id+'&nombre='+campo+'&cat='+cat+'&obra='+obra);
 }

function eliminarDoc(id, archivo, cat, obra)
 {
  if(confirm('\xBFDesea eliminar el archivo \''+archivo+'\'?'))
   { cargarXML('borrar='+id+'&cat='+cat+'&obra='+obra); }
  return false;
 }

var erroresDoc = new Array();
erroresDoc[1] = 'El tamaño del archivo excede el aceptado por el servidor.';
erroresDoc[2] = 'El tamaño del archivo excede el indicado para este formulario.';
erroresDoc[3] = 'Sólo se subió parte del archivo.';
erroresDoc[4] = 'No se subió ningún archivo.';
function DocCargada(errorno, cat, obra)
 {
  var filacargando = document.getElementById('filadocestado');
  document.forms['formdoc']['archivo'].disabled = false;
  document.forms['formdoc'].reset();
  if(errorno == false)
   {
    cargarXML('cat='+cat+'&obra='+obra);
    //cargando.parentNode.style.display = 'none';
   }
  else
   {
    filacargando.style.display = '';
	filacargando.firstChild.firstChild.innerHTML = erroresDoc[errorno];
   }
  document.formdoc.reset();
 }

function construirListado()
 {
  var lista = document.getElementById('subcategorias');
  var categoria = req.responseXML.firstChild;
  var secciones_arr = req.responseXML.getElementsByTagName("subcategoria");
  for (var i = 0; i < secciones_arr.length; i++)
   {
    agregarAListado(lista, secciones_arr[i].attributes.getNamedItem("id").value, secciones_arr[i].childNodes[0].firstChild.nodeValue, secciones_arr[i].childNodes[1].firstChild.nodeValue, categoria.attributes.getNamedItem("cat").value, categoria.attributes.getNamedItem("obra").value);
   }
 }

function cargarXML(parametros)
 {
  var url = '/_archivos.xml?'+parametros;
  var tiempo = new Date();
  tiempo = tiempo.getTime();
  url += '&tiempo='+tiempo;
  if (window.XMLHttpRequest)
   {
    req = new XMLHttpRequest();
    req.onreadystatechange = estadoPeticion;
    req.open("GET", url, true);
    req.send(null);
   }
  else if (window.ActiveXObject)
   {
    isIE = true;
    req = new ActiveXObject("Microsoft.XMLHTTP");
    if (req)
     {
      req.onreadystatechange = estadoPeticion;
      req.open("GET", url, true);
      req.send();
     }
   }
  return false;
 }

function estadoPeticion()
 {
  if (req.readyState == 4)
   {
    if(req.status == 200)
     {
      limpiarListado();
      construirListado();
     }
    else alert('No ha sido posible enviar información al servidor.\nHTTP Status '+req.status);
   }
 }

function limpiarListado()
 {
  var lista = document.getElementById('subcategorias');
  while(lista.firstChild)
   { lista.removeChild(lista.firstChild); }
 }

function agregarAListado(lista, id, titulo, archivo, cat, obra)
 {
  var tr = document.createElement('tr');
  
  tr.setAttribute("id", "filadoc"+id);
  var td1 = document.createElement('td');
  var td3 = document.createElement('td');
  var td4 = document.createElement('td');

  var form = document.createElement('div');
  form.setAttribute("id", "nombredoc"+id);
  form.style.display = 'none';
  
  var inputnombre = document.createElement('input');
  inputnombre.setAttribute("type", "text");
  inputnombre.setAttribute("name", "archivo_nombre");
  inputnombre.setAttribute("value", titulo);
  inputnombre.onblur = function()
   { renombrarDoc(inputnombre.value, id, cat, obra); }
  form.appendChild(inputnombre);
  td1.appendChild(form);

  var div = document.createElement('div');
  div.setAttribute("id", "linkdoc"+id);
  var link = document.createElement('a');
  // ruta
  link.setAttribute("href", "archivo?n="+archivo);
  link.appendChild(document.createTextNode(titulo));
  div.appendChild(link);
  td1.appendChild(div);

  var editarA = document.createElement('a');
  editarA.setAttribute("href", "#");
  var editar = document.createElement('img');
  editar.src = './img/b_edit';
  editar.setAttribute("border", "0");
  editarA.onclick = function()
   { return editarDoc(id, true); }
  editar.setAttribute("alt", "Renombrar");
  td3.setAttribute("align", "center");
  editarA.appendChild(editar);
  td3.appendChild(editarA);

  var borrarA = document.createElement('a');
  borrarA.setAttribute("href", "#");
  var borrar = document.createElement('img');
  borrar.src = './img/b_drop';
  borrar.setAttribute("border", "0");
  borrarA.onclick = function()
   { return eliminarDoc(id, titulo, cat, obra); }
  borrar.setAttribute("alt", "Eliminar");
  td4.setAttribute("align", "center");
  borrarA.appendChild(borrar);
  td4.appendChild(borrarA);

  tr.appendChild(td1);
  tr.appendChild(td3);
  tr.appendChild(td4);
  lista.appendChild(tr);
 }

function subirDoc(campo)
 {
  if(campo.value.length > 1)
   {
	document.getElementById('filadocestado').style.display = 'none';
	campo.form.submit();
	campo.disabled = true;
   }
 }
