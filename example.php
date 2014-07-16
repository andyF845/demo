<?php
require_once 'GifResizer.php';

try {
	
	//Create object
	$gr = new GifResizer();
	//Load source file
	$gr->loadFile('W:\home\amf\www\test.gif');
	//Send http headers
	header('Content-type: image/gif');
	//Get resized gif file
	echo $gr->getResizedGif(200,100);
	
} catch (GifResizerException $e) {
	echo "Caught resize exception: ".$e->getMessage();
} catch (Exception $e) {
	echo "Caught unknown exception: ".$e->getMessage();
}
?>