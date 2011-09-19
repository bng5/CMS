<?php

class jsCampo
 {
  public $id, $nombre, $unico, $tipo, $subtipo, $poromision;
  /*public function __construct($pref = '', $area = false)
   {

   }
  */


  private function label()
   {
   	return "
		fila = document.createElement('tr');
		tbody.appendChild(fila);
		celda = document.createElement('td');
		fila.appendChild(celda);
		etiqueta = document.createElement('label');
		etiqueta.appendChild(document.createTextNode('{$this->nombre}:'));
		celda.appendChild(etiqueta);
		celda = document.createElement('td');
		fila.appendChild(celda);";
   }

  public function imprimir()
   {
	$campo_tipo = "campo".$this->tipo;
	//$this->v = $valor_id ? "[m][{$this->tipo}][{$this->valor_id}]" : "[n][{$this->id}][]";
	return $this->label().$this->$campo_tipo();
   }

  private function campo()
   {
	return "";//<tr><td colspan=\"2\">No se ha especificado el tipo de campo.</td></tr>";
   }

  private function campostring()
   {
/*
 *	    +---------------------------------------------------------------------------+
 *  	|  1 | string |    NULL | Campo de texto        | items_valores             |
 *  	|  2 | string |       1 | Color                 | items_valores             |
 *  	|  3 | string |       2 | Contraseña            | NO                        |
 *  	| 13 | string |       3 | Selector múltiple     | campos_opciones           |
 *  	| 12 | string |       4 | Checkbox              | campos_opciones           |
 *	    +---------------------------------------------------------------------------+
 */
    if($this->subtipo == 1)
     {
     }
	elseif($this->subtipo == 2)
     {
     }
	elseif($this->subtipo == 4)
     {
     }
    elseif($this->subtipo == 5)
     {
     }
    elseif($this->subtipo == 6)
     {
     }
	else
	 {
	  return "
		lista = document.createElement('ul');
		lista.className = 'campo_lista';
		celda.appendChild(lista);
		this.i = 1;
		for(lengId in lenguajes)
		 {
		  litem = document.createElement('li');
		  lista.appendChild(litem);
		  etiqueta = document.createElement('label');
		  etiqueta.setAttribute('for', 'galimgdato_'+this.id+'_'+this.h+'_'+this.i);
		  etiqueta.className = 'etiqueta_idioma';
		  litem.appendChild(etiqueta);
		  tt = document.createElement('tt');
		  etiqueta.appendChild(tt);
		  tt.appendChild(document.createTextNode('('+lenguajes[lengId]+')'));
		  litem.appendChild(document.createTextNode(' '));
		  campo = document.createElement('input');
		  campo.setAttribute('type', 'text');
		  campo.setAttribute('name', 'galimgdato['+this.id+'][n][{$this->id}]['+lengId+'][]')
		  campo.setAttribute('id', 'galimgdato_'+this.id+'_'+this.h+'_'+this.i)
		  campo.setAttribute('size', '45');
		  campo.setAttribute('maxlength', '100');
		  litem.appendChild(campo);
		  this.i++
		 }
		this.h++";
	 }
   }

  private function campodate()
   {
	// fecha
    if($this->subtipo == 1)
     {
     }
    // fecha y hora
    else
     {
     }
   }

  private function campoint()
   {
    if($this->subtipo == 1)
     {
     }
    // imagen
    elseif($this->subtipo == 2)
     {
     }
    // archivo
    elseif($this->subtipo == 3)
     {
     }
	// selector
	elseif($this->subtipo == 5)
     {
     }
    // rango
    elseif($this->subtipo == 6)
     {
     }
    // radio
    elseif($this->subtipo == 8)
     {
     }
	 // int no definido
	else
	 {
	 }
   }

  private function campotext()
   {
   	return "
		lista = document.createElement('ul');
		lista.className = 'lista_idiomas';
		celda.appendChild(lista);
		seleccionado = ' seleccionado';
		this.i = 1;
		for(lengId in lenguajes)
		 {
		  litem = document.createElement('li');
		  lista.appendChild(litem);
		  etiqueta = new EtiquetaTexto(this.id, this.h, this.i);
		  etiqueta.el.className = 'etiqueta_idioma'+seleccionado;
		  /************************************************************
		  agregarEvento(etiqueta, 'click', function()
		   {
		   	mostrarTxt('galimgdato_'+self.id+'_'+selfC.h, selfC.i);
		   });
		  ************************************************************/
		  litem.appendChild(etiqueta.el);
		  etiqueta.el.appendChild(document.createTextNode(lenguajes[lengId]));
		  campo = document.createElement('textarea');
		  campo.setAttribute('name', 'galimgdato['+this.id+'][n][{$this->id}]['+lengId+'][]');
		  campo.setAttribute('id', 'galimgdato_'+this.id+'_'+this.h+'_'+this.i);
		  campo.className = 'areadetexto';
		  campo.setAttribute('rows', '20');
		  campo.setAttribute('cols', '55');
		  campo.style.display = (seleccionado == '') ? 'none' : 'block';
		  litem.appendChild(campo);
		  this.i++
		  seleccionado = '';
		 }
		var actualK = 'galimgdato_'+this.id+'_'+this.h+'_';
		actual[actualK] = actualK+'1';
		this.h++\n";
   }
   
  private function camponum()
   {
   }
 }

?>