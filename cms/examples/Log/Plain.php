<?php

// $log = new Log_Plain();

$log = new Log();

$log->log('Mensaje');
$log->group('Título del grupo');
$log->log('Mensaje', Log::ERROR);
$log->groupEnd();
$log->count();
$log->table($array, $columnas);



?>
