<?php

define(GIF_RESIZER_MAX_WIDTH,	320);
define(GIF_RESIZER_MIN_WIDTH,	100);
define(GIF_RESIZER_MAX_HEIGHT,	240);
define(GIF_RESIZER_MIN_HEIGHT,	100);

define(GIF_RESIZER_ERR_FILE_NOT_FOUND,	20);
define(GIF_RESIZER_ERR_INVALID_FILE,	21);
define(GIF_RESIZER_ERR_IMAGE_TO_SMALL,	22);
define(GIF_RESIZER_ERR_DEST_SIZE,		30);

class GifResizerException extends Exception {}

final class GifResizer {
	private $source;
	private $sourceWidth;
	private $sourceHeight;
	/**
	* Loads gif image from file
	* @throws GifResizeException on errors
	* @return true on successes
	*/
	public function loadFile($sourceFileName) {
		//File must exists
		if (!file_exists($sourceFileName))
			throw new GifResizerException(GIF_RESIZER_ERR_FILE_NOT_FOUND);
		
		//Get source image properties
		if ($info = getimagesize($sourceFileName)) {
			$this->sourceWidth = $info[0];
			$this->sourceHeight = $info[1];
		} else {
			throw new GifResizerException(GIF_RESIZER_ERR_INVALID_FILE);
		}
		
		//Check source dimensions
		if ( ($this->sourceWidth < GIF_RESIZER_MIN_WIDTH) || ($this->sourceHeight < GIF_RESIZER_MIN_HEIGHT) ) 
			throw new GifResizerException(GIF_RESIZER_ERR_IMAGE_TO_SMALL);
		
		//Load image
		if (!$this->source = new Imagick($sourceFileName))
			throw new GifResizerException(GIF_RESIZER_ERR_INVALID_FILE);
		
		//Successed
		return true;
	}
	/**
	 * Performs image resize and returns image stream on successes
	 * @param int $destWidth
	 * @param int $destHeight
	 * @throws GifResizeException if source file was not loaded
	 * @return image stream if successes, false otherwise
	 */
	public function getResizedGif($destWidth,$destHeight) {
		if (!$this->source) throw new GifResizerException(GIF_RESIZER_ERR_INVALID_FILE);
		if ( !( ($this->sourceWidth > $destWidth) || ($this->sourceHeight > $destHeight) ) ) {
			//Image is already smaller than we need
			return $this->source->getImageBlob();
		} elseif (	($destWidth < GIF_RESIZER_MIN_WIDTH)   || 
					($destWidth > GIF_RESIZER_MAX_WIDTH)   ||
					($destHeight < GIF_RESIZER_MIN_HEIGHT) ||
					($destHeight > GIF_RESIZER_MAX_HEIGHT) ) {
			throw new GifResizerException(GIF_RESIZER_ERR_DEST_SIZE);
		} else {
			//Resize image
			$image = $this->source->coalesceimages();
			
			foreach ($image as $frame) {
				//Resize frame
				$frame->thumbnailImage($destWidth, $destHeight);
				$frame->setImagePage($destWidth, $destHeight, 0, 0);
			}
			
			//Get only difference between frames
			$image = $image->deconstructImages();
			
			return $image->getImagesBlob();
		}		
	}	
}

?>