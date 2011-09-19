<?php

header("Content-Type: application/json; charset=utf-8");

if($_GET['ruta_video']) {
	$dato = parse_url($_GET['ruta_video']);
	$host = strstr($dato['host'], 'youtube.com');
	parse_str($dato['query'], $query_str);
	$video->id = $query_str['v'];

	if($host == 'youtube.com' && $video->id) {
		$xml = simplexml_load_file('http://gdata.youtube.com/feeds/api/videos/'.$video->id);
		$video->titulo = (string) $xml->title;
		$video->descripcion = (string) $xml->content;
		//$url = $xml->xpath('link');
		//$namespaces = $xml->getNamespaces(true);
		$xml->registerXPathNamespace('media', 'http://search.yahoo.com/mrss/');//$namespaces['media']);
		$thumbnails = $xml->xpath('//media:thumbnail');
		$imagen = $thumbnails[0]->attributes();
		$video->imagen = array('url' => (string) $imagen['url'], 'width' => (int) $imagen['width'], 'height' => (int) $imagen['height']);
		echo json_encode($video);
	}
}
else {
	header("HTTP/1.1 400 Bad Request");
	exit(" ");
}

?>