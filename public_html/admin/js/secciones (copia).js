var despImg = {c : "http://"+HTTP_HOST+"/img/e", e : "http://"+HTTP_HOST+"/img/c"};

function cargarSeccion()
 {
  if(req.readyState == 4)
   {
    if(req.status == 200)
     {
	  var secciones = req.responseXML.firstChild.childNodes;
	  if(secciones.length >= 1)
	   {
		while(req['valor'][0].childNodes[1])
		 { req['valor'][0].removeChild(req['valor'][0].childNodes[1]); }

		//var div = document.createElement('div');
		//req['valor'][0].appendChild(div);
		for (var i = 0; i < secciones.length; i++)
		 {
		  //alert(secciones[i].attributes.getNamedItem("id").value+'\n'+secciones[i].attributes.getNamedItem("superior").value+'\n'+secciones[i].attributes.getNamedItem("estado").value+'\n'+secciones[i].firstChild.nodeValue+'\n'+req['valor'][0]);
		  agregarSubsecciones(secciones[i].attributes.getNamedItem("id").value, secciones[i].attributes.getNamedItem("superior").value, secciones[i].attributes.getNamedItem("estado").value, secciones[i].firstChild.nodeValue, req['valor'][0], secciones[i].attributes.getNamedItem("subniveles").value);
		 }
	   }
	  else
	   {
		//alert(req['valor'].childNodes[1].nodeName);
		while(req['valor'][0].childNodes[1].firstChild)
		 { req['valor'][0].childNodes[1].removeChild(req['valor'][0].childNodes[1].firstChild); }
		var vacio = document.createTextNode('(Vacio) ');
		req['valor'][0].childNodes[1].appendChild(vacio);
	   }
/*
		var agr_label = document.createTextNode('Agregar Subsecci\xF3n');
		var a = document.createElement('a');
		var sup = req['valor'][1];
		a.onclick = function()
		 { nuevaSubseccion(sup); }
		a.appendChild(agr_label);
		req['valor'][0].childNodes[1].appendChild(a);
*/
   
/*
	  document.forms["formedicion"]['titulo[]'][req.valor].lang = novedad[0].firstChild.nodeValue;
	  document.forms["formedicion"]['texto[]'][req.valor].lang = novedad[0].firstChild.nodeValue;

	  document.forms["formedicion"]['titulo[]'][req.valor].dir = novedad[1].firstChild.nodeValue;
	  document.forms["formedicion"]['subtitulo[]'][req.valor].dir = novedad[1].firstChild.nodeValue;
	  document.forms["formedicion"]['texto[]'][req.valor].dir = novedad[1].firstChild.nodeValue;

	  if(novedad[2].childNodes.length >= 1) document.forms["formedicion"]['titulo[]'][req.valor].value = novedad[2].firstChild.nodeValue;
	  else document.forms["formedicion"]['titulo[]'][req.valor].value = '';
	  if(novedad[3].childNodes.length >= 1) document.forms["formedicion"]['subtitulo[]'][req.valor].value = novedad[3].firstChild.nodeValue;
	  else document.forms["formedicion"]['subtitulo[]'][req.valor].value = '';
	  if(novedad[4].childNodes.length >= 1) document.forms["formedicion"]['texto[]'][req.valor].value = novedad[4].firstChild.nodeValue;
	  else document.forms["formedicion"]['texto[]'][req.valor].value = '';
*/
     }
	else if(req.status == 401)
	 {
	  alert('Su sesi\xF3n ha caducado!');
     }
    else
	 {
	  alert('Error!\nHTTP status: '+req.status);
	 }
   }
  return;
 }

function cargarPEdicion(seccion, superior)
 {
  document.forms["formedicion"]['superior'].value = superior;
  document.forms["formedicion"]['id'].value = seccion;
  document.getElementById('avisoguardar').style.display = 'none';
  var idioma;
  if(document.forms["formedicion"]['leng[]'].length > 1) idioma = document.forms["formedicion"]['leng[]'][0].options[document.forms["formedicion"]['leng[]'][0].selectedIndex].value+'&leng[]='+document.forms["formedicion"]['leng[]'][1].options[document.forms["formedicion"]['leng[]'][1].selectedIndex].value;
  else idioma = document.forms["formedicion"]['leng[]'].value;
  loadXMLDoc('./seccion.xml?seccion='+seccion+'&leng[]='+idioma, cargarEdicion, null);
  return false;
 }

function cargarEdicion(seccion)
 {
  if(req.readyState == 4)
   {
    if(req.status == 200)
     {
      document.getElementById('formulario').style.display = '';
      var secciones = req.responseXML.firstChild.childNodes;
      var xmlEstado = req.responseXML.firstChild.attributes.getNamedItem("estado").value;
      document.forms["formedicion"]['estado'].value = xmlEstado;
	  if(document.forms["en"]['estado2']) document.forms["en"]['estado2'][xmlEstado].click();
      
      //document.forms["formedicion"]['id'].value = req.responseXML.firstChild.attributes.getNamedItem("id").value;
      for(var t = 0; t < secciones[0].childNodes.length; t++)
       {
	    //alert('leng_id '+secciones[0].childNodes[c].attributes.getNamedItem("leng_id").value);
		var leng_id = secciones[0].childNodes[t].attributes.getNamedItem("leng_id").value;
		if(document.forms["formedicion"]['leng[]'].length > 1)
		 {
	      for(var s = 0; s < document.forms["formedicion"]['leng[]'].length; s++)
	       {
		    if(leng_id != document.forms["formedicion"]['leng[]'][s].options[document.forms["formedicion"]['leng[]'][s].selectedIndex].value) continue;
			else 
	         {
	          //alert('texto '+secciones[0].childNodes[c].childNodes[1].firstChild.nodeValue);
	          //secciones[0].childNodes[c].firstChild.firstChild.nodeValue
			  //secciones[0].childNodes[c].childNodes[1].firstChild.nodeValue
	          document.forms["formedicion"]['titulo[]'][s].lang = secciones[0].childNodes[t].attributes.getNamedItem("lang").value;
			  document.forms["formedicion"]['titulo[]'][s].dir = secciones[0].childNodes[t].attributes.getNamedItem("dir").value;
			  document.forms["formedicion"]['titulo[]'][s].value = secciones[0].childNodes[t].firstChild.firstChild ? secciones[0].childNodes[t].firstChild.firstChild.nodeValue : '';
			  if(req.responseXML.firstChild.attributes.getNamedItem("texto").value == 1)
			   {
			    document.forms["formedicion"]['texto[]'][s].style.display = '';
			    document.getElementById('labtexto'+s).style.display = '';
			    document.getElementById('filaTtitulo'+s).style.display = '';
			   }
			  else
			   {
			    document.forms["formedicion"]['texto[]'][s].style.display = 'none';
			    document.getElementById('labtexto'+s).style.display = 'none';
			    document.getElementById('filaTtitulo'+s).style.display = 'none';
			   }
/*
			  document.forms["formedicion"]['titulo[]'][req.valor].dir = novedad[1].firstChild.nodeValue;
			  document.forms["formedicion"]['subtitulo[]'][req.valor].dir = novedad[1].firstChild.nodeValue;
			  document.forms["formedicion"]['texto[]'][req.valor].dir = novedad[1].firstChild.nodeValue;

			  if(novedad[2].childNodes.length >= 1) document.forms["formedicion"]['titulo[]'][req.valor].value = novedad[2].firstChild.nodeValue;
			  else document.forms["formedicion"]['titulo[]'][req.valor].value = '';
			  if(novedad[3].childNodes.length >= 1) document.forms["formedicion"]['subtitulo[]'][req.valor].value = novedad[3].firstChild.nodeValue;
			  else document.forms["formedicion"]['subtitulo[]'][req.valor].value = '';
			  if(novedad[4].childNodes.length >= 1) document.forms["formedicion"]['texto[]'][req.valor].value = novedad[4].firstChild.nodeValue;
			  else document.forms["formedicion"]['texto[]'][req.valor].value = '';
*/
	          break;
	         }
	       }
	     }
	    else
	     {
	      if(req.responseXML.firstChild.attributes.getNamedItem("texto").value == 1)
	       {
	        document.forms["formedicion"]['ttitulo[]'].value = secciones[0].childNodes[t].childNodes[1] ? secciones[0].childNodes[t].childNodes[1].attributes.getNamedItem("titulo").value : '';
	        document.forms["formedicion"]['texto[]'].value = secciones[0].childNodes[t].childNodes[1].firstChild ? secciones[0].childNodes[t].childNodes[1].firstChild.nodeValue : '';
	        document.forms["formedicion"]['texto[]'].style.display = '';
	        document.getElementById('labtexto0').style.display = '';
	        document.getElementById('filaTtitulo0').style.display = '';
	       }
		  else
		   {
		    document.forms["formedicion"]['texto[]'].style.display = 'none';
		    document.getElementById('labtexto0').style.display = 'none';
		    document.getElementById('filaTtitulo0').style.display = 'none';
		    document.forms["formedicion"]['ttitulo[]'].value = '';
		    document.forms["formedicion"]['texto[]'].value = '';
		   }
		  if(req.responseXML.firstChild.attributes.getNamedItem("imgs").value == 0)
		   {
			document.getElementById('trimagenes').style.display = 'none';
			//document.getElementById('maximagenes').style.display = 'none';
		   }
		  else
		   {
			document.getElementById('trimagenes').style.display = '';
			//document.getElementById('maximagenes').style.display = '';
		   }
		  document.forms["formedicion"]['titulo[]'].value = secciones[0].childNodes[t].firstChild.firstChild ? secciones[0].childNodes[t].firstChild.firstChild.nodeValue : '';
	     }
	   }
     }
	else if(req.status == 401)
	 {
	  alert('Su sesi\xF3n ha caducado!');
     }
    else
	 {
	  alert(req.status);
	 }
   }
  return;
 }

function nuevaSubseccion(superior)
 {
  document.getElementById('formulario').style.display = '';
  
  document.forms["formedicion"]['superior'].value = superior;
  document.forms["formedicion"]['id'].value = '';
  if(document.forms["formedicion"]['usar_texto'] != null)
   {
    document.forms["formedicion"]['usar_texto'].checked = true;
    usarTexto(true);
   }
  if(document.forms["en"]['estado2'][1] != null)
   {
    document.forms["en"]['estado2'][1].checked = true;
    document.forms["formedicion"]['estado'].value = '2';
   }
  if(document.forms["formedicion"]['titulo[]'].length > 1)
   {
	for(var s = 0; s < document.forms["formedicion"]['titulo[]'].length; s++)
	 {
	  document.forms["formedicion"]['titulo[]'][s].value = '';
	  document.forms["formedicion"]['texto[]'][s].value = '';
	 }
   }
  else
   {
	document.forms["formedicion"]['titulo[]'].value = '';
	document.forms["formedicion"]['texto[]'].value = '';
   }
 }

function agregarSubsecciones(id, superior, estado, titulo, divc, subniveles)
 {
  var divd = document.createElement('div');
  if(estado == 0) divd.className = 'inactivo';
  divd.setAttribute('id', 'contenedor'+id);
  divc.appendChild(divd);

  var div = document.createElement('div');
  div.setAttribute('id', 'seccion'+id);
  div.className = 'seccion';
  divd.appendChild(div);

  if(subniveles != 0)
   {
	var desplegarimg = document.createElement('img');
	desplegarimg.src = './img/c';
	desplegarimg.onclick = function()
	 { desplegar(desplegarimg, id); }
	div.appendChild(desplegarimg);
   }
  
  var titulo = document.createTextNode(titulo);
  var a = document.createElement('a');
  a.onclick = function()
   { cargarPEdicion(id, superior); }
  a.ondblclick = function()
   { desplegar(desplegarimg, id); }
  a.href = '#';
  a.appendChild(titulo);
  div.appendChild(a);

  div.appendChild(document.createTextNode(' '));


/***** NO BORRAR *************/
/*
  if(subniveles != 0)
   {
	//var nueva = document.createTextNode('Agregar Subsecci\xF3n');
	var nueva_a = document.createElement('img');
	nueva_a.src = './img/add';
	nueva_a.onclick = function()
	 { nuevaSubseccion(id); }
	nueva_a.alt = 'Agregar Subsecci\xF3n';
	//nueva_a.appendChild(nueva);
	div.appendChild(nueva_a);
   }

  div.appendChild(document.createTextNode(' '));
  //var borrar = document.createTextNode('Borrar');
  var borrar_a = document.createElement('img');
  borrar_a.src = './img/b_drop_ch';
  borrar_a.onclick = function()
   { borrarSeccion(id); }
  borrar_a.alt = 'Borrar';
  //borrar_a.appendChild(borrar);
  div.appendChild(borrar_a);
*/
 
  //div.appendChild(document.createTextNode(']'));
  

/*


  var td1 = document.createElement('td');
  var td2 = document.createElement('td');
  var td3 = document.createElement('td');
  var td4 = document.createElement('td');

  var espanol = document.createTextNode(es);
  var ingles = document.createTextNode(en);

  var a = document.createElement('a');
  a.setAttribute("href", "./seccion?id="+id);
  a.appendChild(espanol);
  td1.appendChild(a);
  td2.appendChild(ingles);

  var borrar = document.createElement('img');
  borrar.src = './img/b_drop';
  borrar.onclick = function()
   { eliminarSubsec(id, es); }
  borrar.setAttribute("alt", "Eliminar");
  td3.setAttribute("align", "center");
  td3.appendChild(borrar);

  var select = document.createElement('select');
  select.setAttribute('name', 'orden'+id);
  select.onchange = function()
   { moverPos(id, h, select); }
  var q = 1;
  for(var p = 0; p < total; p++)
   {
    select.options[p] = new Option(q, q);
    q++
   }
  select.options[h-1].selected = true;
  td4.appendChild(select);

  tr.appendChild(td1);
  tr.appendChild(td2);
  tr.appendChild(td3);
  tr.appendChild(td4);

  lista.appendChild(tr);
 }
*/
  return;
 }

function borrarSeccion(id)
 {
alert('borrar seccion '+id);
 }

function reDesplegar(seccion, id, modif)
 {
  cambiosGuardados(id, modif);
  if(document.getElementById('seccion'+seccion) != null)
   {
	var imagen = document.getElementById('seccion'+seccion).firstChild;
	imagen.src = despImg['e'];
   }
  else var imagen = false;
  var contenedor = document.getElementById('contenedor'+seccion);
  while(contenedor.childNodes[1])
   { contenedor.removeChild(contenedor.childNodes[1]); }
  desplegar(imagen, seccion);
 }

function desplegar(imagen, seccion)
 {
  if(imagen != false)
   {
	var img = imagen.src.substr(imagen.src.length - 1);
	imagen.src = despImg[img];
   }
  //var contenedor = imagen.parentNode.parentNode;
  var contenedor = document.getElementById('contenedor'+seccion);
  
  if(img == 'e')
   {
    for(var h = 1; h < contenedor.childNodes.length; h++)
     { contenedor.childNodes[h].style.display = 'none'; }
   }
  else
   {     
	if(contenedor.childNodes.length > 1)
     {
      for(var h = 1; h < contenedor.childNodes.length; h++)
       { contenedor.childNodes[h].style.display = 'block'; }
     }
    else
     {
	  var p = document.createElement('p');
	  var cargando = document.createTextNode('Cargando...');
	  p.appendChild(cargando);
	  contenedor.appendChild(p);
      loadXMLDoc('./seccion.xml?superior='+seccion+'&leng=1', cargarSeccion, [contenedor, seccion]);
     }
   }

  return false;
//stringObject.substr(start,length)
//if(imagen.src = 'http://<?php echo $_SERVER['HTTP_HOST']; ?>/img/c') alert('si');
 }

function cargarXMLLeng(selector, indice)
 {
//alert(selector+' '+indice);
  var selectores = new Array();
  selectores[0] = 1;
  selectores[1] = 0;
  var seleccionado = selector.selectedIndex;
  var valor = selector.options[selector.selectedIndex].value;
  if(valor >= 1) loadXMLDoc('./seccion.xml?seccion='+document.forms["formedicion"]['id'].value+'&leng[]='+valor, cargarEdicion, indice);
  if(indice == 1 && document.forms["formedicion"]['leng[]'][1].length > document.forms["formedicion"]['leng[]'][0].length)
   {
    document.forms["formedicion"]['leng[]'][1].options[0] = null;
    seleccionado -= 1;
   }
  else if(indice == 0 && document.forms["formedicion"]['leng[]'][1].length > document.forms["formedicion"]['leng[]'][0].length) seleccionado += 1;
  var selector2 = document.forms["formedicion"]['leng[]'][selectores[indice]];
  for(op in selector2.options)
   { selector2.options[op].disabled = false; }
  selector2.options[seleccionado].disabled = true;
 }

function usarTexto(checked)
 {
  if(document.forms["formedicion"]['texto[]'].length > 1)
   {
    for(var t = 0; t < document.forms["formedicion"]['texto[]'].length; t++)
     {
	  document.getElementById('labtexto'+t).style.display = checked ? '': 'none';
	  document.forms["formedicion"]['texto[]'][t].style.display = checked ? '': 'none';
     }
   }
  else
   {
    document.getElementById('labtexto'+0).style.display = checked ? '': 'none';
	document.forms["formedicion"]['texto[]'].style.display = checked ? '': 'none';
   }
 }

function aceptarForm(formulario)
 {
  /*
  formulario.dia.value = document.forms['en'].fechadia.value;
  formulario.mes.value = document.forms['en'].fechames.options[document.en.fechames.selectedIndex].value;
  formulario.anyo.value = document.forms['en'].fechaanyo.value;
  */
  formulario.submit();
 }

function validarForm(boton, formulario)
 {
  var incompleto = false;
  var filaaviso = document.getElementById('avisoguardar');
  filaaviso.style.display = 'none';
  filaaviso.firstChild.firstChild.style.color = '#134679';

  if(formulario['texto[]'].length > 1)
   {
    for(var t = 0; t < formulario['texto[]'].length; t++)
     {
      if(formulario['leng[]'][t].options[formulario['leng[]'][t].selectedIndex].value == '') continue;
      if(formulario['titulo[]'][t].value.length == 0) incompleto = true;
	 }
   }
  else 
   { if(formulario['titulo[]'].value.length == 0) incompleto = true; }
  if(incompleto == true)
   {
    filaaviso.firstChild.firstChild.innerHTML = 'Debe llenar el campo T\xED­tulo.';
	filaaviso.style.display = '';
	filaaviso.firstChild.firstChild.style.color = '#800000';
	return false;
   }
  boton.value = 'Guardando...';
  aceptarForm(formulario);
 }