<?php
//check that zlib compression is enabled
if(!ini_get('zlib.output_compression')){ die(); }
 
$allowed = array('css','js'); //set array of allowed file types to prevent abuse
 
//check for request variable existence and that file type is allowed
if(isset($_GET['file']) && isset($_GET['type']) && in_array(substr($_GET['file'],strrpos($_GET['file'],'.')+1), $allowed)){
	$data = file_get_contents(dirname(__FILE__).'/'.$_GET['file']); // grab the file contents
 
	$etag = '"'.md5($data).'"'; // generate a file Etag
	header('Etag: '.$etag); // output the Etag in the header
 
	// output the content-type header for each file type
	switch ($_GET['type']) {
		case 'css':
			header ("Content-Type: text/css; charset: UTF-8");
		break;
 
		case 'js':
			header ("Content-Type: text/javascript; charset: UTF-8");
		break;
	}
 
	header('Cache-Control: max-age=300, must-revalidate'); //output the cache-control header
	$offset = 60 * 60;
	$expires = 'Expires: ' . gmdate('D, d M Y H:i:s',time() + $offset) . ' GMT'; // set the expires header to be 1 hour in the future
	header($expires); // output the expires header
 
	// check the Etag the browser already has for the file and only serve the file if it is different
	if ($etag == $_SERVER['HTTP_IF_NONE_MATCH']) {
		header('HTTP/1.1 304 Not Modified');
		header('Content-Length: 0');
	} else {
		echo $data;
	}
}