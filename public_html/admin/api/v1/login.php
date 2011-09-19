<?php

require_once('inc/iniciar.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $respuesta = new Respuesta();

    /*
      if($usuario = DB_Usuarios::obtenerPorUsuario($_POST['usuario'])) {
      //var_dump($_POST['usuario'], $usuario);
      if(Autenticacion::comprobarClave($usuario, $_POST['clave']))
      var_dump(Sesion::iniciar($usuario, $_POST['recordarme']));
      else {
      //$respuesta->campoError('usuario', Respuesta::CAMPO_ERR_VALOR_INCORRECTO);
      $respuesta->agExcepcion(Respuesta::CAMPOS_VALORES_INCORRECTOS);
      }
      }
      else {
      $respuesta->campoError('usuario', Respuesta::CAMPO_ERR_VALOR_INCORRECTO);
      }

      var_dump($respuesta->obtenerRespuesta());

      exit();
     */
//  Autenticacion::autenticar()
//$auth = new CMSAutenticacion;
//var_dump($auth->autenticar());
//exit;
    try {
        //$usuario = Usuario_Acceso::registro(array('reg_usuario' => 'pablo3', 'reg_email' => 'dss3@bng5.net'), $respuesta = new Respuesta());
        $accion = $_POST['accion'];
        unset($_POST['accion']);
        $recordarme = $_POST['recordarme'];
        unset($_POST['recordarme']);
        if ($usuario = Usuario_Acceso::acceso($_POST, $respuesta))
            $usuario->iniciar_sesion(!empty($recordarme));
    } catch (Exception $e) {
        $respuesta->agExcepcion(Respuesta::CAMPOS_VALORES_INCORRECTOS, "Existen errores en campos.");
    }
    ////print_r($_SESSION);
    $resp_respuesta = $respuesta->respuesta();
    $http_status = $resp_respuesta->exito ? 202 : ($respuesta->status ? $respuesta->status : 401);
    header("Content-Type: application/json; charset=UTF-8", true, $http_status);
    $cuerpo = json_encode($resp_respuesta);
    header("Content-Length: " . strlen($cuerpo));
    echo $cuerpo;
} else {
    header("Allow: POST", true, 405);
    exit(" ");
}

exit;

?>