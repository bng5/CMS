

function aceptarForm(formulario, publicar)
 {
  if(publicar == true) document.forms['formedicion']['publicar'].value = '1';
  formulario.submit();
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
  //var guardar = document.getElementById('guardar');
  if(document.forms['formedicion']['publicar'].value == '1')
   {
	document.forms['formedicion']['publicar'].value = '0';
	document.forms['formedicion']['btPublicar'].value = 'Guardar/Publicar';
   }
  else
   {
    document.forms['formedicion']['btGuardar'].value = 'Guardar';
    //guardar.value = 'Guardar';
    //guardar.blur();
   }
 }
