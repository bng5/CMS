<?php

class Usuario {
	
	const REALM = "Acceso de usuarios";
	public $usuario, $id, $estado, $clave;

	function __construct() {
		//$this->datos = array(''=> false, '' => false);
	}

  static function Id($id)
   {
	$inst = new self;
	$inst->usuario_id = $id;
   }

  static function NombreUsuario($usuario)
   {
	self::validar_usuario($usuario);
	$mysqli = BaseDatos::Conectar();
	$consulta = $mysqli->query("SELECT `id`, `usuario`, `estado_id`, clave FROM `usuarios` WHERE `usuario` = '".$mysqli->real_escape_string($usuario)."' LIMIT 1");
	if($fila = $consulta->fetch_assoc())
	 {
	  $inst = new self;
	  $inst->id = $fila['id'];
	  $inst->usuario = $fila['usuario'];
	  $inst->estado = $fila['estado_id'];
	  $inst->clave = $fila['clave'];
	  return $inst;
	 }
	else
	  return false;//throw new Exception('No existe el usuario '.$usuario);
   }

/*  function __set($nombre, $valor)
   {
	if($this->datos[$nombre])
	 {
	  $validador = "validar_${nombre}";
	  $validados++;
	  try
	   {
		$this->datos[$nombre] = self::$validador($valor);
	   }catch(Exception $e)
	   {
		// TODO
	   }
	 }
	else
	  return false;
   }
*/
/*
  function __get($nombre)
   {
	if($this->datos[$nombre])
	  return $this->datos[$nombre];
	else
	  return false;
   }
*/

//  function acceso($clave)//$params = false)
//   {
//	return ($this->clave == self::encriptarClave($clave, $this->usuario));

	/*
	 *

	if($fila = $result->fetch_assoc())
	 {
	  $clave = self::encriptarClave($params['clave'], $params['usuario']);
	  $result->close();
	  if($fila['clave'] == $clave)
		return $fila;
	 }
	else
	 {
	  $result->close();
	  throw new Exception('', Respuesta::CAMPOS_VALORES_INCORRECTOS);//return false;//self::$respuesta->setMensaje(Respuesta::ERR_VALORES);
	 }*/
 //  }

  function iniciar_sesion($recordarme = false)//$params
   {
	//if($fila = self::acceso($params))
	// {
	  session_start();
	  $_SESSION['usuario'] = $this->usuario;//$fila['usuario'];
	  $_SESSION['usuario_id'] = $this->id;//$fila['id'];
	  $_SESSION['su'] = $this->su;
	  $_SESSION['leng_id'] = $this->leng_id;
	  //$_SESSION['nombre_mostrar'] = $fila['nombre_mostrar'];
	  $pase = md5(rand().time());
	  $mysqli = BaseDatos::Conectar();
	  $mysqli->query("UPDATE usuarios SET `pase` = '{$pase}' WHERE id = {$this->id}");
	  $expira = $recordarme ? time()+2592000 : 0;//60*60*24*30
	  setcookie("usuario", $this->usuario, $expira, "/");//, ".".DOMINIO);
	  setcookie("pase", $pase, $expira, "/", false, false, true);//, ".".DOMINIO);
	// }


	  $consultaperm = $mysqli->query("SELECT up.`area_id`, up.`item_id`, up.`permiso_id` FROM `usuarios_permisos` up JOIN secciones s ON up.item_id = s.id WHERE up.`usuario_id` = ".$this->id." ORDER BY s.sistema, up.`item_id`");
	  if($fila_perm = $consultaperm->fetch_row())
	   {
		$areas = array(2 => 'admin_seccion', 3 => 'admin_seccion_c');
	    do
	     {
		  $_SESSION['permisos'][$areas[$fila_perm[0]]][$fila_perm[1]] = $fila_perm[2];
	     }while($fila_perm = $consultaperm->fetch_row());
		$_SESSION['admin_secciones'] = md5(implode("-", $_SESSION['permisos']['admin_seccion']));
	    $consultaperm->close();
	    //$this->respuesta->exito = true;
		//$this->respuesta->mensajes[1] = 1;
		//$this->respuesta->respuesta['sesion'] = session_id();
	    //return;
	   }
	  /*else
	   {
	    //$this->respuesta->mensajes[8] = 8;
	    //return;
	   }
	  */


   }





  protected function validar_usuario($valor)
   {
	$valor = trim($valor);
	if(empty($valor))
	  throw new Exception('Debe indicar el nombre de usuario', 1);
	if((mb_strlen($valor) < 4 || mb_strlen($valor) > 60))
	  throw new Exception('El nombre de usuario debe contener entre 4 y 60 caracteres', ((mb_strlen($valor) < 4) ? 4 : 5));
	if(!preg_match('/^[a-zA-ZáéíóúüñÁÉÍÓÚÜÑ]{1}[-a-zA-Z0-9@._áéíóúüñÁÉÍÓÚÜÑ]+$/', $valor))
	  throw new Exception('El nombre de usuario contiene caracteres no válidos', 2);
	return $valor;
   }

  protected function validar_clave($valor)
   {
	if(empty($valor))
	  throw new Exception('Ingrese la contraseña', 1);
	if(mb_strlen($valor) < 4)
	  throw new Exception('La contraseña debe contener al menos 4 caracteres', 4);
	return $valor;
   }

  protected function validar_email($valor)
   {
	$valor = trim($valor);
	if(empty($valor))
	  throw new Exception('', 1);
	if(!eregi('^[[:alnum:]._-ñçáéíóú]+@[a-zA-Z0-9._-ñçáéíóú]+\.([a-zA-Z]{2,4})$', $valor))
	  throw new Exception('', 2);
	return $valor;
   }

  function encriptarClave($clave, $usuario)
   {
	return sha1($clave);//md5($usuario.':'.self::REALM.':'.$clave);//sha1($cadena);
   }

  function generarAut($largo = 16)
   {
	$clave_caract = "-.0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz";
	$cod_aut = "";
	$max = (strlen($clave_caract)-1);
	for($i=0; $i < $largo; $i++)
	  $cod_aut .= $clave_caract[rand(0, $max)];
	return $cod_aut;
   }
 }

?>