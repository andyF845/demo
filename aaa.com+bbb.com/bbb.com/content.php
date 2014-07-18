<?php
/**
 * ContentProvider Demo script
 * This script should be placed at bbb.com
 * This script takes ?offset=<int> as parameter
 * Returns json-encoded array with keys "text" and "next"
 * Client should store "next" value for next reference 
  */

echo new ContentProvider ( $_GET ['offset'] );

/**
 * ContentProvider
 *
 * @example echo new ContentProvider($offset);
 */
class ContentProvider {
	private $data;
	private $cache;
	private $offset;
	/**
	 * ContentProvider constructor
	 * Initializes data
	 * 
	 * @param $offset Current client offset
	 */
	function __construct($offset) {
		// Set offset
		$this->offset = ( int ) $offset;
		
		// Set ContentProvider data
		$this->data = array (
				"First demo message",
				"Second demo message",
				"Third demo message",
				"Fourth and last server message" 
		);
	}
	/**
	 * Returns text for current offset
	 * 
	 * @return string
	 */
	function __toString() {
		// Get data at current offset
		$result = array (
				"text" => $this->data [$this->offset],
				"next" => $this->getNext () 
		);
		return json_encode ( $result );
	}
	/**
	 * Returns index for next item in data array
	 * 
	 * @return integer
	 */
	function getNext() {
		return ( ++$this->offset >= count ( $this->data ) ) ? 0 : $this->offset;
	}
}

?>