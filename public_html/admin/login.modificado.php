<?php
/*
// Mantenimiento
header("Location: /login_mantenimiento", true, 307);
exit;
*/
require_once('inc/iniciar.php');
$suceso = null;

if($_REQUEST['cuenta'] != "salir" && !empty($_SESSION['usuario'])) {
    $ref = $_GET["ref"] ? $_GET["ref"] : APU;
    header("Location: ".$ref, TRUE, 303);
    //echo " ";
    exit;
}

if(!empty($_REQUEST['cuenta'])) {
	if($_REQUEST['cuenta'] == 'acceder') {
		$auth = new Autenticacion_Sesion;
		$auth->autenticar();
	}
	else {
		$login = new Login($_REQUEST['cuenta'], $_POST['usuario'], $_POST['clave']);
		//$suceso = $login->sucesoId();
		//$http_status = $login->respuesta->exito ? 200 : 401;
		$suceso = $login->respuesta;
		if($suceso->exito) {
			if($_REQUEST['cuenta'] == 'acceder') {
				$_SESSION['admin_secciones'] = "-".implode("-", array_keys($_SESSION['permisos']['admin_seccion']))."-";
				header("Location: /login?compcookie&ref=".$_POST['ref']);//Location: ".urldecode($_POST["ref"]), TRUE, 303);
				exit;
			}
			else {
				$suceso->mensajes[] = 1;
				$respuesta = 1;//provisorio
			}
		}
	}
}

if(isset($_GET['compcookie'])) {
    if(!$_COOKIE['sesion'])
        $suceso->mensajes[] = 50;
}

$ref = $_REQUEST['ref'] ? urldecode($_REQUEST['ref']) : APU;
header("Cache-Control: no-cache, must-revalidate", true, 401);
$usuario = $_POST['usuario'] ? $_POST['usuario'] : $_COOKIE['usuario'];

$sucesos = array(
 1 => "Su sesión ha sido cerrada satisfactoriamente.",
	"Error interno del servidor.",
	"La petición no pudo ser interpretada.",
	"Sin cambios.",
	"Debe completar ambos campos para ingresar.",
	"Existen datos con tipos incorrectos",
	"Los datos proporcionados no son correctos.",
	"El usuario no se encuentra habilitado.",
 50 => "Su navegador debe aceptar cookies para este dominio.");

include('./vistas/login.mg.php');

?>