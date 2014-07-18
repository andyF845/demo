<?php
/**
 * Remote ContentProvider usage demo
 * This script should be placed at aaa.com
 * Gets data from remote host
 */

define ( BBB_SERVER, "http://bbb.com/content.php?offset=%s" );
define ( BBB_ERROR, "Couldn't get info from remote server" );

try {
	// Get data from bbb.com
	if (@! $data = file_get_contents ( sprintf ( BBB_SERVER, $_COOKIE ["offset"] ) ))
		throw new Exception ();
	
	// Decode server data
	if (! $data = json_decode ( $data, true ))
		throw new Exception ();
	
	// Store next offset
	// If client disabled cookies, first record will be always displayed
	if (! headers_sent ())
		setcookie ( "offset", $data ['next'] );
		
		// Show text from bbb.com
	echo $data ['text'];
} catch ( Exception $e ) {
	// Something went wrong
	echo BBB_ERROR;
}
?>