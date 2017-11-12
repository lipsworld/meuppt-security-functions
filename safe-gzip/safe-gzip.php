<?php
// Verifica existência da extensão zlib
if(!ini_get('zlib.output_compression')){ die(); }
 
$allowed = array('css','js'); // Define ficheiros a processar
 
if(isset($_GET['file']) && isset($_GET['type']) && in_array(substr($_GET['file'],strrpos($_GET['file'],'.')+1), $allowed)){
	$data = file_get_contents(dirname(__FILE__).'/'.$_GET['file']); // Processa o conteúdo
 
	$etag = '"'.md5($data).'"'; // Gera o Etag
	header('Etag: '.$etag); 
 
	// Gera Conten-type do cabeçalho para cada ficheiro
	switch ($_GET['type']) {
		case 'css':
			header ("Content-Type: text/css; charset: UTF-8");
		break;
 
		case 'js':
			header ("Content-Type: text/javascript; charset: UTF-8");
		break;
	}
 
	header('Cache-Control: max-age=300, must-revalidate'); 
	$offset = 60 * 60;
	$expires = 'Expires: ' . gmdate('D, d M Y H:i:s',time() + $offset) . ' GMT'; 
	header($expires); 
 
	
	if ($etag == $_SERVER['HTTP_IF_NONE_MATCH']) {
		header('HTTP/1.1 304 Not Modified');
		header('Content-Length: 0');
	} else {
		echo $data;
	}
}