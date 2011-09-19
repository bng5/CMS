<?php

class Listado
 {
  private $complementos, $listar_arr, $limite_pre, $estilo, $orden_dir, $db_criterios_orden, $db_criterios_indice, $opciones_arr, $opciones;
  function __construct($db_criterios_orden, $consulta)
   {
    $this->consulta = $consulta;
    $this->resultados = 25;
    $this->inicio; // = $inicio;
    $this->orden; // = $orden;
    $this->listar_arr = array();
    $this->complementos = array("checkbox");
    $this->opciones_arr = array("borrar" => "Borrar", "habilitar" => "Habilitar", "deshabilitar" => "Deshabilitar");
    $this->opciones = array();
    $this->estilo = array();
    $this->id = "listado";
    $this->db_criterios_orden = $db_criterios_orden;
    array_unshift($this->db_criterios_orden, FALSE);
    $this->orden_dir = array(array("DESC", "ab", "Orden descendente"), array("ASC", "arr", "Orden ascendente"));

    $this->orden = $orden;
    $this->flechas = "/img/fl2";

    $this->db_criterios_indice = array();
    for($i = 1; $i < count($db_criterios_orden); $i++)
     {
      if(empty($db_criterios_orden[$i][0])) continue;
      $this->db_criterios_indice[$i] = $db_criterios_orden[$i][0];
     }
   }

  function listar($item, $mostrar)
   {
    if(($mostrar == false || $mostrar == 0) && in_array($item, $this->listar_arr))
     {
      $clave = array_search($item, $this->listar_arr);
      if($clave !== FALSE)
       { unset($this->listar_arr[$clave]); }
     }
    elseif(($mostrar == true || $mostrar == 1) && in_array($item, $this->complementos) && !in_array($item, $this->listar_arr))
     { $this->listar_arr[] = $item; }
   }

  function agregar_opcion($opcion, $opcion_texto = false)
   {
    if($this->opciones_arr[$opcion])
     {
      if(!$opcion_texto) $opcion_texto = $this->opciones_arr[$opcion];
      $this->opciones[$opcion] = $opcion_texto;
     }
   }

  function obtenerIndice($orden)
   {
    $this->ordendir = $orden%2;
    if($this->ordendir == 1)
     { $orden += 1; }
    return ($orden/2);
   }

  function style($atributo, $valor)
   { $this->estilo[$atributo] = $valor; }

  function imprimir()
   {
    global $mysqli, $sesion;
    $this->limite_pre = ($this->inicio + $this->resultados);
    $this->rutaimg = substr($this->flechas, 0, strrpos($this->flechas, "/")+1);
    $this->flechas = substr($this->flechas, strrpos($this->flechas, "/")+1);
    if(empty($this->orden) xor $this->orden > count($this->db_criterios_indice)*2)
     { $this->orden = 1; }
    $this->ordendir = $this->orden%2;
    $this->indice = $this->obtenerIndice($this->orden);
    
    $this->db_orden = $this->db_criterios_indice[$this->indice]." ".$this->orden_dir[$this->ordendir][0];
    $this->consulta = str_replace("lstOrden", "ORDER BY ".$this->db_orden, $this->consulta);
    $this->consulta = str_replace("lstLimite", "LIMIT ".intval($this->inicio).", ".$this->resultados, $this->consulta);

    if(!$result = $mysqli->query($this->consulta)) die("<br />\n".__LINE__." - mySql: ".$mysqli->error);
    if($fila = $result->fetch_row())
     {
      echo "
        <form action=\"".php_self()."\" name=\"form".$this->id."\" method=\"post\">
   	<table class=\"tabla\" id=\"".$this->id."\"";
      if(count($this->estilo))
       {
        echo " style=\"";
        foreach($this->estilo as $indice => $valor)
         { echo $indice.":".$valor.";"; }
        echo "\"";
       }
      echo ">
	 <thead>
	 <tr class=\"orden\">";
	 
      if(in_array("checkbox", $this->listar_arr) && count($this->opciones))
       {
        echo "
	  <td style=\"width:20px;text-align:center;\"><input type=\"checkbox\" name=\"checkTodos\" onclick=\"checkearTodo(this.form, this, 'lista_item[]');\" /></td>";
       }
      $j = 1;
      $k = 1;
      for($i = 1; $i < count($this->db_criterios_orden); $i++)
       {
	    $orden = $j;
	    echo "<td";
	    if($this->db_criterios_orden[$i]['ancho']) echo " style=\"width:".$this->db_criterios_orden[$i]['ancho']."px;\"";
	    if(empty($this->db_criterios_orden[$i][0])) echo ">&nbsp;".$this->db_criterios_orden[$i][1]."</td>";
	    else
	     {
	      if($this->indice == $k)
	       {
	        if($this->orden%2)
	         { $orden += 1; }
		    $sel = TRUE;
	        $img = $this->flechas."_".$this->orden_dir[$this->ordendir][1];
	        $alt = $this->orden_dir[$this->ordendir][2];
	       }
	      else
	       {
	        $sel = FALSE;
	        $img = "trans";
	        $alt = FALSE;
	       }

		  if($sel == TRUE) echo " class=\"sel\"";
		  echo " id=\"Tcabezal".$i."\">";
		  echo "<a onclick=\"return construirLista('".$this->id."', 2, ".$orden.")\" href=\"".php_self()."?orden=".$orden."\" title=\"Ordenar por ".$this->db_criterios_orden[$i][1]."\"><img src=\"".$this->rutaimg.$img."\" id=\"fl".$i."\" style=\"border:0;\" width=\"11\" height=\"14\" class=\"fl\" alt=\"".$alt."\" />&nbsp;".$this->db_criterios_orden[$i][1]."</a></td>";
		  $j += 2;
		  $k++;
         }
       }
      echo "
	 </tr>
	 </thead>
	 <tbody>";
      do
       {
	    echo "
	 <tr>";
	    if(in_array("checkbox", $this->listar_arr) && count($this->opciones))
	     {
	      echo "
	  <td style=\"text-align:center;\"><input type=\"checkbox\" name=\"lista_item[]\" value=\"".$fila[0]."\" onclick=\"selFila(this, '".$clase."');\" /></td>";
	     }
	    for($i = 1; $i < count($this->db_criterios_orden); $i++)
	     {
	      echo "
	  <td>";
	      if($this->db_criterios_orden[$i]['link'])
	       {
	        if(empty($this->db_criterios_orden[$i]['link'])) $this->db_criterios_orden[$i]['link'] = php_self();
	        if(empty($this->db_criterios_orden[$i]['arg'])) $this->db_criterios_orden[$i]['arg'] = "id";
	        echo "<a href=\"".$this->db_criterios_orden[$i]['link']."?".$this->db_criterios_orden[$i]['arg']."=".$fila[0]."&amp;sesion=".$sesion."\">";
	       }
	      if($this->db_criterios_orden[$i]['img'])
	       {
	        echo "<img src=\"".$this->db_criterios_orden[$i]["carpeta"].$fila[$i]."\" alt=\"".$fila[$i]."\" />";
	       }
	      else
	       { echo $fila[$i]; }
	      if($this->db_criterios_orden[$i]['link'])
	       { echo "</a>"; }
	      echo "</td>";
	     }
	    echo "</tr>";
       } while ($fila = mysql_fetch_row($result)); 
      echo "
	 </tbody>
	</table>";
      if(count($this->opciones))
       {
	    echo "
	<div id=\"error_check_form\" class=\"div_error\" style=\"display:none;\">No ha seleccionado ninguna novedad.</div>
	<div style=\"padding:4px;\"><img src=\"./img/flecha_arr_der.png\" alt=\"Para los items seleccionados\" style=\"padding:0 5px;\" />";
	    foreach($this->opciones as $opcion => $opcion_texto)
	     {
	      echo "
<input type=\"submit\" name=\"mult_submit\" value=\"".$opcion_texto."\" onclick=\"return confBorrado('lista_item[]');\" />&nbsp;-&nbsp;";
	     }
        // <select name=\"mover_ubicacion\">";
        //subcategoria(0, 0, $row_ct['cat_superior'], null);
		// </select>
//        echo "&nbsp;
//<input type=\"submit\" name=\"mult_submit\" value=\"Mover\" /></div>";
       }
      echo "
	</form>";
     }
   }
 }

?>