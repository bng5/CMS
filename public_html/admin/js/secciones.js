function completarIdent(campo)
 {
  if(campo.form.identificador.value.length > 0 || campo.value.length == 0)
   return;
  var valor = campo.value.replace(/ /g, "_");
  while(valor.lastIndexOf("_") == (valor.length - 1))
	valor = valor.substring(0, (valor.length - 1));
  while(valor.indexOf("_") == 0)
	valor = valor.substring(1);
  valor = valor.toLowerCase();
  var i;
  var nvalor = '';
  for(i = 0; i < valor.length; i++)
   {
	cod = valor.charCodeAt(i);
	nvalor += (cod == 95 || (cod > 47 && cod < 58) || (cod > 96 && cod < 123)) ? valor[i] : '';
   }
  campo.form.identificador.value = nvalor;
 }

function habProfCats(radio)
 {
  radio.form.prof_categorias.disabled = (radio.value == '1') ? false : true;
 }

function habMenuCheck(selector, menuCheck)
 {
  if(selector.selectedIndex == 0)
   {
    menuCheck.disabled = true;
    menuCheck.nextSibling.style.color = '#cccccc';
   }
  else
   {
    menuCheck.disabled = false;
    menuCheck.nextSibling.style.color = '#000000';
   }
 }