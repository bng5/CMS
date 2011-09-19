<?php

/**
 * @deprecated
 */

?>
<!-- encabezado -->
<?php

//$seccion_est[$seccion] = " class=\"activo\"";
//$sel_seccion[$seccion] = " class=\"activo\"";

?>
 <link rel="stylesheet" type="text/css" href="/css/ia.css" />
 <script type="text/javascript" src="/js/ia.js"></script>
 <?php

if($seccion) echo "<script type=\"text/javascript\" src=\"/js/${seccion}.js\" charset=\"utf-8\"></script>";

?>

 <link rel="stylesheet" type="text/css" media="all" href="/css/calendario.css" />
 <link rel="stylesheet" type="text/css" media="all" href="/css/paleta.css" />
 <script type="text/javascript" src="/js/calendar.js" charset="utf-8"></script>
 <script type="text/javascript" src="/js/calendar_es-uy.js" charset="utf-8"></script>
 <script type="text/javascript" src="/js/calendar-setup.js" charset="utf-8"></script>
 <script type="text/javascript" src="/js/paleta.js" charset="utf-8"></script>
</head>
<body>
<noscript><div id="no_js_alerta"><img src="/img/warning" alt="" /> Para visualizar correctamente este sitio su navegador debe contar con <b>JavaScript</b> habilitado.</div></noscript>
<div id="cabezal_der">
 <div id="cabezal_izq">
  <div id="cabezal">
   <h2><?php echo SITIO_TITULO; ?></h2>
   <div id="finalizarses"><?php echo "<b>".$_SESSION['usuario']."</b>&nbsp;[<a href=\"".APU."micuenta\">Mi cuenta</a>&nbsp;-&nbsp;<a href=\"".APU."login?cuenta=salir\">Finalizar sesi&oacute;n</a>]"; /* <a href=\"cuenta?sesion=".$sesion."\">Cuenta</a> */ ?></div>
  </div>
 </div>
</div>
<div id="contenedor">
 <div id="menu"
<?php

//echo ">".$_SESSION['admin_secciones']."</div><div";
$incluir = "iacache/menu".md5($_SESSION['admin_secciones']).".php";
if(!@include($incluir)) include("templates/iamenu.php");

//$secciones->imprimir();
?>
 ></div>
 <div id="contenido">
  <h3><?php

if($titulo_nav)
 {
  for($nav = 0; $nav < count($titulo_nav); $nav++)
   {
    if(!$link_nav)
     { $mst_link_nav = $seccion; }
    elseif(is_array($link_nav))
     { $mst_link_nav = $link_nav[$nav]; }
    elseif(is_string($link_nav))
     { $mst_link_nav = $link_nav; }
    echo "<a href=\"/".$mst_link_nav."?sesion=".$sesion.$extra_nav."\" target=\"_top\">".$titulo_nav[$nav]."</a>&nbsp;&gt;&nbsp;";
   }
 }
echo $seccion_id ? $secciones_nombres[$seccion_id] : $titulo;
echo "</h3>
<div id=\"div_mensaje\"";
if(!$div_mensaje) echo " style=\"display:none;\"";
echo ">${div_mensaje}&nbsp;</div>";
?>

<!-- /encabezado -->
