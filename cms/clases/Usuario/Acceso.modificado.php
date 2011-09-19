<?php

/*
  acceso(usuario, clave)
 *
  iniciar_sesion(recordarme)
 *
  registro(usuario, clave, email)
 *
  recuperar(pase)
 *
  finalizar_sesion([session])
 */

class Usuario_Acceso extends Usuario {

    static $respuesta, $valores = array();

    //$campos_arr += array('usuario' => false, 'clave' => false);

    public static function registro($campos, & $respuesta) {
//	try
//	 {
        $params = self::validar($campos, array('reg_usuario' => false, 'reg_clave' => false, 'reg_email' => false), $respuesta);
        //    return false;
//	 }
//	catch(Exception $e)
//	 {
//	  throw new Exception();
//	 }
        $aut = self::generarAut();
        $mysqli = BaseDatos::Conectar();
        if (!$mysqli->query("INSERT INTO `usuarios` (`usuario`, `clave`, `email`, `aut`) VALUES ('" . $mysqli->real_escape_string($params['reg_usuario']) . "', '" . self::encriptarClave($params['reg_clave'], $params['reg_usuario']) . "', '" . $mysqli->real_escape_string($params['reg_email']) . "', '{$aut}')")) {
            //if($mysqli->errno == 1062) Duplicate entry -- self::validar($params); <-- podría resultar en un bucle infinito
            //self::$respuesta->setMensaje(Respuesta::ERR_INTERNO);//$sucesos['errores']['servidor'] = array('cod' => 1, 'desc' => $sucesos_desc[1]);
            throw new Exception($mysqli->error, $mysqli->errno);
        }
        if ($id = $mysqli->insert_id) {
            $inst = new parent;
            $inst->id = $id;
            $inst->usuario = $campos['reg_usuario'];
            $inst->estado = 0;
            $inst->clave = $campos['reg_clave'];
            return $inst;
        }
        else
            return false;
    }

    public static function acceso($campos, & $respuesta) {
        //$campos += array('usuario' => false, 'clave' => false);
        if ($params = self::validar($campos, array('usuario' => false, 'clave' => false), $respuesta)) {
            $mysqli = BaseDatos::Conectar();
            if (!$consulta = $mysqli->query("SELECT `id`, `username`, `estado_id`, clave, su, leng_id, created_date FROM `usuarios` WHERE `username` = '" . $mysqli->real_escape_string($params['usuario']) . "' LIMIT 1"))
                throw new Exception($mysqli->error, Respuesta::ERR_INTERNO); //$respuesta->agExcepcion(Respuesta::ERR_INTERNO, $mysqli->error);
                if ($fila = $consulta->fetch_assoc()) {
                    if($fila['clave'] == parent::encriptarClave($params['clave'], $params['usuario'], $fila['created_date'], $fila['clave'])) {
                        $inst = new parent;
                        $inst->id = $fila['id'];
                        $inst->usuario = $fila['username'];
                        $inst->estado = $fila['estado_id'];
                        $inst->clave = $fila['clave'];
                        $inst->su = $fila['su'];
                        $inst->leng_id = $fila['leng_id'];
                        return $inst;
                    }
                    else
                        $respuesta->agExcepcion(Respuesta::CAMPOS_VALORES_INCORRECTOS);
                }
                else
                    $respuesta->campoError('usuario', 3, 'No existe el usuario \'' . $params['usuario'] . '\''); // ('', Respuesta::CAMPOS_VALORES_INCORRECTOS);
                return false;
        }
        else
            return false;
    }

    public static function validar($valores, $campos, & $respuesta) {
        $error = false;
        $validados = 0;
        $valores += $campos;
        foreach ($valores AS $k => $v) {
            if (isset($campos[$k])) {//if(method_exists(__CLASS__, $validador))
                $validador = "validar_" . $k;
                $validados++;
                try {
                    $params[$k] = self::$validador($v);
                } catch (Exception $e) {
                    $error = true;
                    $cod = $e->getCode();
                    if ($cod != 3)
                        $respuesta->status = 400;
                    $respuesta->campoError($k, $e->getCode());
                } catch (ErrorException $e) {
                    $error = true;
                    $respuesta->agExcepcion($e->getCode(), $e->getMessage());
                }
            } else {
                $respuesta->agregarIgnorados($k);
            }
        }
        if ($error) {
            //$respuesta->agExcepcion(Respuesta::CAMPOS_VALORES_INCORRECTOS, "Existen errores en campos.");
            //throw new Exception('Existen errores en campos');//return false;
            return false;
        } elseif ($validados == 0) {
            $respuesta->agExcepcion(Respuesta::FALTAN_DATOS, "Debe indicar el/los campos que desea validar.");
            return false;
        }
        return $params;
    }

    /*
      public static function llamada($campos_arr, $accion, & $respuesta)
      {
      //self::$respuesta = $respuesta;//new Respuesta();
      switch($accion)
      {
      case 'registro':
      $campos_arr += array('reg_usuario' => false, 'reg_clave' => false, 'reg_email' => false);
      break;
      case 'iniciar_sesion':
      case 'acceso':
      $campos_arr += array('usuario' => false, 'clave' => false);
      break;
      default:
      unset($accion);
      break;
      }

      $error = false;
      $validados = 0;
      foreach($campos_arr AS $k => $v)
      {
      $validador = "validar_{$k}";
      if(method_exists(__CLASS__, $validador))
      {
      $validados++;
      try
      {
      $params[$k] = self::$validador($v);
      }
      catch(Exception $e)
      {
      $error = true;
      $respuesta->campoError($k, $e->getCode());
      }
      catch(ErrorException $e)
      {
      $error = true;
      $respuesta->agExcepcion($e->getCode(), $e->getMessage());
      }
      }
      else
      {
      $error = true;
      $respuesta->agregarIgnorados($k);
      }
      }

      if(!$error && $accion)
      {
      try
      {
      $ret = self::$accion($params);
      var_dump($ret);
      }
      catch(Exception $e)
      {
      $respuesta->agExcepcion($e->getCode(), $e->getMessage());
      }
      }
      elseif($validados == 0)
      $respuesta->agExcepcion(Respuesta::FALTAN_DATOS, "Debe indicar el/los campos que desea validar.");
      return;// self::$respuesta->respuesta();
      }
     */

    private function validar_accion($valor) {
        $acciones = array('registro', 'acceso', 'validar');
        if (in_array($valor, $acciones))
            return $valor;
        else {
            $error = empty($valor) ? 1 : 3;
            throw new Exception('', $error);
        }
    }

    private function validar_reg_usuario($valor) {
        $valor = self::validar_usuario($valor);
        $mysqli = BaseDatos::Conectar();
        $valor_sql = $mysqli->real_escape_string($valor);
        $result = $mysqli->query("SELECT usuario FROM usuarios WHERE usuario = '{$valor_sql}' LIMIT 1");
        if ($row = $result->fetch_row())
            throw new Exception('', 3);
        return $valor;
    }

    private function validar_reg_clave($valor) {
        if (is_array($valor)) {
            if ($valor[0] !== $valor[1])
                throw new Exception('', 3);
            if (empty($valor[0]) || empty($valor[1]))
                throw new Exception('', 1);
            $valor = $valor[0];
        }
        $valor = trim($valor);
        if (empty($valor))
            throw new Exception('', 1);
        if (mb_strlen($valor) < 4)
            throw new Exception('', 4);
        return $valor;
    }

    private function validar_reg_email($valor) {
        $valor = self::validar_email($valor);
        $mysqli = BaseDatos::Conectar();
        $valor_sql = $mysqli->real_escape_string($valor);
        if (!$result = $mysqli->query("SELECT usuario FROM usuarios WHERE email = '{$valor_sql}' LIMIT 1")) {
            throw new ErrorException(Respuesta::ERR_INTERNO, $mysqli->error);
        } elseif ($row = $result->fetch_row())
            throw new Exception('', 3);
        return $valor;
    }

}

?>