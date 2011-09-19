var idPred;

function resetearForm(formulario)
 {
  //var filaaviso = document.getElementById('avisoguardar');
  //filaaviso.style.display = 'none';
  document.getElementById('avisoguardar').style.display = 'none';

  document.forms[formulario].reset();
 }

function validarForm(boton, formulario)
 {
  var filaaviso = document.getElementById('avisoguardar');
  filaaviso.style.display = 'none';
  filaaviso.firstChild.firstChild.style.color = '#134679';
  if(formulario.titulo.value.length == 0)
   {
    filaaviso.firstChild.firstChild.innerHTML = 'Debe llenar el campo T\xED­tulo.';
    filaaviso.style.display = '';
    filaaviso.firstChild.firstChild.style.color = '#800000';
    return false;
   }
  else
   {
    boton.value = 'Guardando...';
    aceptarForm(formulario);
   }
 }

function pred_hand()
 {
  if(req.readyState == 4)
   {
    var mensaje = document.getElementById('div_mensaje');
    var cRadio = document.getElementById('poromision'+idPred);
    var nRadio = document.getElementById('poromision'+req.valor);
    if(req.status == 200 && req.responseText == 'ok')
     {
      mensaje.innerHTML = 'El idioma predeterminado es ahora: <b>'+document.getElementById('idiomaLabel'+req.valor).innerHTML+'<\/b>';
      mensaje.className = '';
      var prevCheck = document.getElementById('lista_item'+idPred);
      if(prevCheck != null) prevCheck.disabled = false;
	  idPred = req.valor;
	  check = document.getElementById('lista_item'+idPred);
	  check.checked = false;
	  check.disabled = true;
     }
	else if(req.status == 401)
	 {
	  mensaje.innerHTML = 'Su sesi&oacute;n ha expirado.<br \/>Haga click en <a href="./login?id=salir&amp;">Finalizar sesi&oacute;n<\/a> para identificarse nuevamente.';
	  mensaje.className = 'div_error';
	  if(cRadio != null) cRadio.checked = true;
	  nRadio.checked = false;
     }
    else
	 {
	  mensaje.innerHTML = 'Ha ocurrido un error y <b>no<\/b> fue posible completar su solicitud.<br \/>Si el problema persiste pongase en contacto con el administrador del sitio.<br \/>(HTTP/1.1 '+req.status+')';
	  mensaje.className = 'div_error';
	  if(cRadio != null) cRadio.checked = true;
	  nRadio.checked = false;
	 }
    mensaje.style.display = '';
   }
 }

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
