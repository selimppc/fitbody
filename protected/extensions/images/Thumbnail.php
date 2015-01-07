<?php
class Thumbnail {

	public function imageCreate($input) {
		if (is_file($input)) {
			return $this->imageCreateFromFile($input);
		}
		elseif (is_string($input)) {
			return $this->imageCreateFromString($input);
		}
		else {
			return $input;
		}
	}

	/**
	 * Create a GD image resource from file (JPEG, PNG support).
	 *
	 * @param  string $filename The image filename.
	 *
	 * @return mixed			GD image resource on success, FALSE on failure.
	 * @access public
	 * @static
	 */
	public function imageCreateFromFile($filename) {
		if ( !is_file($filename) || !is_readable($filename) ) {
			user_error('Unable to open file "' . $filename . '"', E_USER_NOTICE);
			return false;
		}
		list( , , $type) = getimagesize($filename);
		switch ($type) {
			case IMAGETYPE_JPEG:
				return imagecreatefromjpeg($filename);
				break;
			case IMAGETYPE_PNG:
				return imagecreatefrompng($filename);
				break;
			case IMAGETYPE_GIF:
				return imagecreatefromgif($filename);
				break;
		}
		user_error('Unsupport image type', E_USER_NOTICE);
		return false;
	}

	/**
	 * Create a GD image resource from a string data.
	 *
	 * @param  string $string The string image data.
	 *
	 * @return mixed		  GD image resource on success, FALSE on failure.
	 * @access public
	 * @static
	 */
	public function imageCreateFromString($string) {
		if ( !is_string($string) || empty($string) ) {
			throw new CException('Invalid image value in string');
		}
		return imagecreatefromstring($string);
	}

	/**
	 * Display rendered image (send it to browser or to file).
	 * This method is a common implementation to render and output an image.
	 * The method calls the render() method automatically and outputs the
	 * image to the browser or to the file.
	 *
	 * @param $input
	 * @param null $output
	 * @param array $options Thumbnail options
	 *		 <pre>
	 *		 width   int	Width of thumbnail
	 *		 height  int	Height of thumbnail
	 *		 percent number Size of thumbnail per size of original image
	 *		 method  int	Method of thumbnail creating
	 *		 halign  int	Horizontal align
	 *		 valign  int	Vertical align
	 * @return bool TRUE on success or FALSE on failure.
	 */
	public function output($input, $output=null, $options=array()) {
		// Load source file and render image
		$renderImage = $this->render($input, $options);
		if ( !$renderImage ) {
			user_error('Error rendering image', E_USER_NOTICE);
			return false;
		}
		// Set output image type
		// By default PNG image
		$type = isset($options['type']) ? $options['type'] : IMAGETYPE_PNG;
		// Before output to browsers send appropriate headers
		if ( empty($output) ) {
			$content_type = image_type_to_mime_type($type);
			if ( !headers_sent() ) {
				header('Content-Type: ' . $content_type);
			}
			else {
				user_error('Headers have already been sent. Could not display image.', E_USER_NOTICE);
				return false;
			}
		}
		// Define outputing function
		switch ($type) {
			case IMAGETYPE_PNG:
				$result = empty($output) ? imagepng($renderImage) : imagepng($renderImage, $output);
				break;
			case IMAGETYPE_JPEG:
				$result = empty($output) ? imagejpeg($renderImage) : imagejpeg($renderImage, $output);
				break;
			case IMAGETYPE_GIF:
				$result = empty($output) ? imagegif($renderImage) : imagegif($renderImage, $output);
				break;
			default:
				user_error('Image type ' . $content_type . ' not supported by PHP', E_USER_NOTICE);
				return false;
		}

		// Output image (to browser or to file)
		if ( !$result ) {
			user_error('Error output image', E_USER_NOTICE);
			return false;
		}
		// Free a memory from the target image
		imagedestroy($renderImage);
		return true;
	}

	/**
	 * Draw thumbnail result to resource.
	 *
	 * @param  mixed   $input   Destination image, a filename or an image string data or a GD image resource
	 * @param  array   $options Thumbnail options
	 *
	 * @return boolean TRUE on success or FALSE on failure.
	 * @access public
	 * @see	Thumbnail::output()
	 */
	public function render($input, $options=array()) {
		// Create the source image
		$sourceImage = $this->imageCreate($input);
		if ( !is_resource($sourceImage) ) {
			user_error('Invalid image resource', E_USER_NOTICE);
			return false;
		}
		$sourceWidth  = imagesx($sourceImage);
		$sourceHeight = imagesy($sourceImage);

		// Set default options
		static $defOptions = array(
			'width'   => 150,
			'height'  => 150,
			'method'  => 0,
			'percent' => 0,
			'halign'  => 0,
			'valign'  => 0,
		);
		foreach ($defOptions as $k => $v) {
			if ( !isset($options[$k]) ) {
				$options[$k] = $v;
			}
		}

		// Estimate a rectangular portion of the source image and a size of the target image
		if ( $options['method'] == 2 ) {
			if ( $options['percent'] ) {
				$W = floor($options['percent'] * $sourceWidth);
				$H = floor($options['percent'] * $sourceHeight);
			} else {
				$W = $options['width'];
				$H = $options['height'];
			}

			$width  = $W;
			$height = $H;

			$Y = $this->_coord($options['valign'], $sourceHeight, $H);
			$X = $this->_coord($options['halign'], $sourceWidth,  $W);
		}
		else {
			$X = 0;
			$Y = 0;

			$W = $sourceWidth;
			$H = $sourceHeight;

			if ( $options['percent'] ) {
				$width  = floor($options['percent'] * $W);
				$height = floor($options['percent'] * $H);
			}
			else {
				$width  = $options['width'];
				$height = $options['height'];

				if ( $options['method'] == 1 ) {
					$Ww = $W / $width;
					$Hh = $H / $height;
					if ( $Ww > $Hh ) {
						$W = floor($width * $Hh);
						$X = $this->_coord($options['halign'], $sourceWidth,  $W);
					}
					else {
						$H = floor($height * $Ww);
						$Y = $this->_coord($options['valign'], $sourceHeight, $H);
					}
				}
				elseif ( $options['method'] == 0 ) {
					if ( $H > $W ) {
						$width  = floor($height / $H * $W);
					}
					else {
						$height = floor($width / $W * $H);
					}
				}
				else {
					if ($height < $width) {
						$width = $W * $height / $H;
					}
					else {
						$height = $H * $width / $W;
					}
				}
			}
		}

		// Create the target image
		if ( function_exists('imagecreatetruecolor') ) {
//			$targetImage = imagecreatetruecolor($width, $height);
			$targetImage = imagecreatetruecolor($width, $height);
			imageSaveAlpha($targetImage, true);
			ImageAlphaBlending($targetImage, false);
			// Create color ID using our colours
			$color=imagecolorallocatealpha($targetImage, 200, 200, 200, 127);
			imagefill($targetImage, 0, 0, $color);
		}
		else {
			$targetImage = imagecreate($width, $height);
		}
		if ( !is_resource($targetImage) ) {
			user_error('Cannot initialize new GD image stream', E_USER_NOTICE);
			return false;
		}

		// Copy the source image to the target image
		if ( $options['method'] == 2 ) {
			$result = imagecopy($targetImage, $sourceImage, 0, 0, $X, $Y, $W, $H);
		}
		elseif ( function_exists('imagecopyresampled') ) {
			$result = imagecopyresampled($targetImage, $sourceImage, 0, 0, $X, $Y, $width, $height, $W, $H);
		}
		else {
			$result = imagecopyresized($targetImage, $sourceImage, 0, 0, $X, $Y, $width, $height, $W, $H);
		}
		if ( !$result ) {
			user_error('Cannot resize image', E_USER_NOTICE);
			return false;
		}

		// Free a memory from the source image
		imagedestroy($sourceImage);

		// Save the resulting thumbnail
		return $targetImage;
	}

	public function _coord($align, $param, $src) {
		if ( $align < 0 ) {
			$result = 0;
		}
		elseif ( $align > 0 ) {
			$result = $param - $src;
		}
		else {
			$result = ($param - $src) >> 1;
		}
		return $result;
	}
}