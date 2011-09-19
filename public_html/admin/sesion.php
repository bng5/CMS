<?php

require('inc/iniciar.php');
require('inc/ad_sesiones.php');

header("Content-Type: text/plain; charset=UTF-8");
if($_SESSION['su'])
 {
  print_r($_SESSION);
 }

?>