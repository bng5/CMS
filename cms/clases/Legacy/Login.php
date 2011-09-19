<?php

class Legacy_Login {

    public $respuesta;
    private $usuario, $clave;

    function __construct($accion, $usuario = false, $clave = false) {
        $this->respuesta->exito = false;
        $this->mensajes = array();
        $this->usuario = $usuario;
        $this->clave = $clave;
        if ($accion == "acceder" || $accion == "salir" || $accion == "recuperar")
            $this->$accion();
        else {
            $this->respuesta->errores->cuenta = 3; //array('cod' => 3, 'desc' => $this->sucesoDesc('errores', 3));
            $this->respuesta->mensajes[3] = 3;
        }
    }

    function cerrar_sesion() {
        if (!$_SESSION['usuario'])
            return false;
        $mysqli = BaseDatos::Conectar();
        $mysqli->query("UPDATE usuarios SET `pase` = NULL WHERE usuario = '{$_SESSION['usuario']}'");
        session_unset();
        setcookie("pase", "", 0, '/');
        setcookie("sesion", "", 0, '/');
        if (session_destroy ())
            return true;
    }

    function salir() {
        $this->respuesta->exito = $this->cerrar_sesion();
        return;
        //$this->cerrar_sesion();
        //header("Location: ".substr($_SERVER['PHP_SELF'], 0, -4));
        //exit;
    }

    function acceder() {
        //if($this->cerrar_sesion()) { }//session_start();
        $this->cerrar_sesion();
        if (!$this->usuario) {
            $this->respuesta->errores->usuario = 1; //array('cod' => 1, 'desc' => $this->sucesoDesc('errores', 1));
            $this->respuesta->mensajes[5] = 5; //] = $this->sucesoDesc('mensajes', 1);
            $error = true;
        }
        if (!$this->clave) {
            $this->respuesta->errores->clave = 1; //array('cod' => 1, 'desc' => $this->sucesoDesc('errores', 1));// ? $clave : $_POST['clave'];
            $this->respuesta->mensajes[5] = 5; //] = "Campo requerido";
            $error = true;
        }
        if ($error)
            return;
        //if(empty($this->usuario) || empty($this->clave))
        //  return 1;
        $mysqli = BaseDatos::Conectar();
        $usuario = $mysqli->real_escape_string($this->usuario);
        //if(!
        $result = $mysqli->query("SELECT `id`, `usuario`, `estado_id`, leng_id, nombre_mostrar, clave FROM `usuarios` WHERE `usuario` = '{$usuario}' LIMIT 1"); //)
        if ($fila = $result->fetch_assoc()) {
            $result->close();
            if ($fila['clave'] != sha1($this->clave)) {
                $this->respuesta->mensajes[7] = 7;
                return;
            }
            if ($fila['estado_id'] != 1) {
                $this->respuesta->mensajes[8] = 8;
                return;
            }
            $mysqli->query("INSERT INTO usuarios_accesos (`usuario_id`, `sesion_id`, `ip`, `uri`) VALUES ({$fila['id']}, '" . session_id() . "', '{$_SERVER['REMOTE_ADDR']}', '{$_POST['ref']}')");
            $consultaperm = $mysqli->query("SELECT up.`area_id`, up.`item_id`, up.`permiso_id` FROM `usuarios_permisos` up JOIN admin_secciones ads ON up.item_id = ads.id WHERE up.`usuario_id` = {$fila["id"]} ORDER BY ads.sistema, up.`item_id`");
            if ($fila_perm = $consultaperm->fetch_row()) {
                session_start();
                $_SESSION['usuario'] = $fila['usuario'];
                $_SESSION['usuario_id'] = $fila['id'];
                $_SESSION['leng_id'] = $fila['leng_id'];
                $_SESSION['nombre_mostrar'] = $fila['nombre_mostrar'];
                $pase = md5(rand() . time());
                $mysqli->query("UPDATE usuarios SET `pase` = '{$pase}' WHERE id = {$fila['id']}");
                $expira = $_POST['recordarme'] ? time() + 2592000 : 0; //60*60*24*30
                setcookie("usuario", $fila['usuario'], $expira, "/"); //, ".".DOMINIO);
                setcookie("pase", $pase, $expira, "/", false, false, true); //, ".".DOMINIO);
                $areas = array(2 => 'admin_seccion', 3 => 'admin_seccion_c');
                do {
                    $_SESSION['permisos'][$areas[$fila_perm[0]]][$fila_perm[1]] = $fila_perm[2];
                } while ($fila_perm = $consultaperm->fetch_row());
                $consultaperm->close();
                //$this->respuesta->mensajes[] = 1;
                $this->respuesta->exito = true;
                $this->respuesta->mensajes[1] = 1;
                $this->respuesta->respuesta['sesion'] = session_id();
                return;
            } else {
                $this->respuesta->mensajes[8] = 8;
                return;
            }
        } else {
            $this->respuesta->mensajes[7] = 7;
            return;
        }
    }

    function recuperar() {
        $this->pase = $_COOKIE['pase'];
        $mysqli = BaseDatos::Conectar();
        if (!$result = $mysqli->query("SELECT `id`, `usuario`, `estado_id`, leng_id, nombre_mostrar FROM `usuarios` WHERE `usuario` = '" . $this->usuario . "' AND `pase` = '" . $this->pase . "' LIMIT 1"))
            return 0; //) login_xml(0);
        if ($fila = $result->fetch_assoc()) {
            $result->close();
            if ($fila['estado_id'] != 1) {
                $this->respuesta->mensajes[8] = 8;
                return;
            }
            $consultaperm = $mysqli->query("SELECT upa.`area`, up.`item_id`, up.`permiso_id` FROM `usuarios_permisos` up JOIN `usuarios_permisos_areas` upa ON up.`area_id` = upa.`id` WHERE `usuario_id` = '" . $fila["id"] . "' ORDER BY up.`area_id`, `item_id`");
            if ($fila_perm = $consultaperm->fetch_row()) {
                session_start();
                $_SESSION['usuario'] = $fila['usuario'];
                $_SESSION['usuario_id'] = $fila['id'];
                $_SESSION['leng_id'] = $fila['leng_id'];
                $_SESSION['nombre_mostrar'] = $fila['nombre_mostrar'];
                $pase = md5(rand() . time());
                $mysqli->query("UPDATE usuarios SET `pase` = '{$pase}' WHERE id = {$fila['id']}");
                setcookie("pase", $pase, time() + 2592000, "/", false, false, true); //, ".".DOMINIO);
                do {
                    $_SESSION['permisos'][$fila_perm[0]][$fila_perm[1]] = $fila_perm[2];
                } while ($fila_perm = $consultaperm->fetch_row());
                $consultaperm->close();
            } else {
                $this->respuesta->mensajes[8] = 8;
                return;
            }
            //$this->respuesta->mensajes[] = 1;
            $this->respuesta->exito = true;
            return;
        }
        else {
            $this->respuesta->mensajes[7] = 7;
            return;
        }
    }

    /*
      function sucesoTxt()
      {
      $this->sucesos_desc = array("No fue posible conectar con la base de datos.",
      1 => "Debe completar ambos campos para ingresar.",
      2 => "Los datos proporcionados no son correctos.",
      3 => "Acceso aceptado.",
      4 => "Su sesión ha sido cerrada satisfactoriamente.",
      5 => "Sesión activa.",
      6 => "Su sesión ha expirado o no ha iniciado una.",
      7 => "El usuario no tiene permisos para acceder a este documento.",
      8 => "El usuario no está habilitado.");
      return $this->sucesos_desc[$this->suceso];
      }
     */
}
