<?php

class Ftl_Image {
    //put your code here

    private $_img           = false;
    private $_size          = null;
    private $_orig          = false;
    private $_keepOriginal  = false;
    private $_folderUploads = PATH_UPLOADS;


        public function  __construct()
        {
                if( !extension_loaded( 'gd' ) && !dl( 'gd.so' ) ) {
                        throw new Exception( 'GD not installed' );
                }
        }

        public function setFolderUploads( $fu )
        {
            $this->_folderUploads = $fu;
        }

	public function getFileSize() {
		return $this->_size;
	}

	public function getWidth() {
		return $this->_img->width;
	}

	public function getHeight() {
		return $this->_img->height;
	}


	public function getAspectRatio() {
		return $this->_img->ratio;
	}

	public function keepOriginal() {

		$this->_keepOriginal = true;

		return $this;

	}

	public function restoreOriginal() {

		$this->_img = clone $this->_orig;

		return $this;

	}

	public function fromString( $str, $mime = Ftl_MimeType::IMAGE_JPEG ) {

		$img = @imagecreatefromstring( $str );

		$width = @imagesx( $img );
		$height = @imagesy( $img );

		if( !$width || !$height ) {
			return false;
		}

		$this->setLoadedImageProperties( $img, $mime, $width, $height );

		return $this;

	}

	public function fromUrl( $url ) {

		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5 );

		// Getting binary data
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_BINARYTRANSFER, 1 );

		$image = curl_exec( $ch );
		curl_close( $ch );

		return $this->fromString( $image );

	}

	public function fromFile( $file ) {

		$info = getimagesize( $file );
                
		if( $info[ 0 ] == 0 ) {
			return false;
		}


		$this->_size = filesize( $file );

		$mimeType = $info[ 'mime' ];

		switch( $mimeType ) {

		 	case Ftl_MimeType::IMAGE_GIF:
		 		$img = imagecreatefromgif( $file );
				break;

		 	case Ftl_MimeType::IMAGE_JPG:
		 		$img = imagecreatefromjpeg( $file );
				break;

		 	case Ftl_MimeType::IMAGE_PNG:
		 		$img = imagecreatefrompng( $file );
				break;

		 	default:
		 		$img = false;
		 		break;

		}

                //var_dump($img);

		if( $img === false ) {

			$img	= imagecreatetruecolor( 200, 30 );
			$bg = imagecolorallocate( $img, 0, 0, 0 );
			$tc = imagecolorallocate( $img, 255, 255, 255 );

			imagefilledrectangle( $img, 0, 0, 150, 30, $bg );

			imagestring( $img, 1, 5, 5, 'File not Found: ' . $file, $tc );

			return false;

		}

		$this->setLoadedImageProperties( $img, $info[ 'mime' ], $info[ 0 ], $info[ 1 ] );

		return $this;

	}

	public function fromUploadedFile( $field, $path = null ) {

		$tmpFile = md5( uniqid() ) . $_FILES[ $field ][ 'name' ];
                
                if (isset ($path))
                    $this->_folderUploads = $path;

		move_uploaded_file( $_FILES[ $field ][ 'tmp_name' ], $this->_folderUploads . $tmpFile );

                //echo $path . $tmpFile;

		$status = $this->fromFile( $this->_folderUploads . $tmpFile );

		@unlink( $this->_folderUploads . $tmpFile );

		return $status; // true || false

	}

	public function isImage( $mime = null ) {

		if( $mime == null ) {
			return $this->_img !== false;
		} else if( $this->isImage() && $this->_img->mime == $mime ) {
			return true;
		}

		return false;

	}

	public function resize( $width = 80, $height = 80, $keepAspectRatio = true ) {

		$ratio = array( $this->_img->width / $width, $this->_img->height / $height );

		if( $keepAspectRatio ) {

			if( $ratio[ 0 ] > $ratio[ 1 ] ) {
				$width = $height * $this->_img->ratio;
			} else if( $ratio[ 0 ] < $ratio[ 1 ] ) {
				$height = $width / $this->_img->ratio;
			}

		}


		$img = imagecreatetruecolor( $width, $height );

		imagecopyresampled( $img, $this->_img->img, 0, 0, 0, 0, $width, $height, $this->_img->width, $this->_img->height );

		$this->_img->img	= $img;
		$this->_img->width	= $width;
		$this->_img->height	= $height;

		return $this;

	}

	public function crop( $width = 80, $height = 50, $x = -1, $y = -1, $dw = -1, $dh = -1, $keepRatio = true) {

		$cropWidth = $this->_img->width / $width;
		$cropHeight = $this->_img->height / $height;

                
                if ($keepRatio === TRUE){
                    $ratio = $width / $height;

                    if( $cropWidth > $cropHeight ) {

                            $width = $this->_img->height * $ratio;
                            $height = $this->_img->height;

                    } else if( $cropWidth < $cropHeight ) {

                            $height = $this->_img->width / $ratio;
                            $width = $this->_img->width;

                    } else {

                            $width = $this->_img->width;
                            $height = $this->_img->height;

                    }
                }

		$srcX = ( $x == -1 ) ? ( $this->_img->width - $width ) / 2 : $x;
		$srcY = ( $y == -1 ) ? ( $this->_img->height - $height ) / 2 : $y;

                $destWidth = ( $dw == -1 ) ? $width : $dw;
                $destHeight = ( $dh == -1 ) ? $height : $dh;
                
		$img = imagecreatetruecolor( $destWidth, $destHeight );

		imagecopyresampled( $img, $this->_img->img, 0, 0, $srcX, $srcY, $destWidth, $destHeight, $width, $height );

		$this->_img->img	= $img;
		$this->_img->width	= $destWidth;
		$this->_img->height	= $destHeight;
                if ($keepRatio === TRUE){
                    $this->_img->ratio	= $ratio;
                }
		return $this;

	}

	public function convert( $to ) {

		$this->_img->mime = $to;

		return $this;

	}

	public function save( $name = null, $quality = 80, &$ext = '' ) {
                $response = new Ftl_Response();
                $response->state = 0;
                
                $ext = '';
                $img = $this->_img->img;
		$mimeType = $this->_img->mime;

                if (!$name)
                {
                    $name = md5(time());
                }

		$path = $this->_folderUploads . $name;
                
                try{

                    switch( $mimeType ) {
                            case Ftl_MimeType::IMAGE_GIF:
                                    $ext = 'gif';
                                    $transColor = imagecolortransparent ( $img );
                                    $transIndex = imagecolorallocate ( $img, $transColor[ 'red' ], $transColor[ 'green' ], $transColor[ 'blue' ] );
                                    imagecolortransparent ( $img, $transIndex );
                                    imagegif( $img, $path . '.' . $ext );
                                    break;

                            case Ftl_MimeType::IMAGE_JPG:
                                    $ext = 'jpg';
                                    imagejpeg( $img, $path . '.' . $ext, $quality );
                                    break;

                            case Ftl_MimeType::IMAGE_PNG:
                                    $ext = 'png';
                                    imagealphablending( $img, false );
                                    imagesavealpha( $img, true );
                                    imagepng( $img, $path . '.' . $ext );
                                    break;

                    }

                    $response->state = 1;
                    $response->data = $name . '.' . $ext;

                }catch (Exception $e)
                {
                    $response->message = $e->getMessage();
                    return $response;
                }

                return $response;

	}

	public function getRawImageData() {

		ob_start();

		$img = $this->_img->img;
		$mimeType = $this->_img->mime;

		switch( $mimeType ) {

			case Ftl_MimeType::IMAGE_GIF:
				$transColor = imagecolortransparent ( $img );
				$transIndex = imagecolorallocate ( $img, $transColor[ 'red' ], $transColor[ 'green' ], $transColor[ 'blue' ] );
				imagecolortransparent ( $img, $transIndex );
				imagegif( $img);
				break;

			case Ftl_MimeType::IMAGE_JPG:
				imagejpeg( $img, null, $quality );
				break;

			case Ftl_MimeType::IMAGE_PNG:
				imagealphablending( $img, false );
				imagesavealpha( $img, true );
				imagepng( $img );
				break;

		}

		$raw = ob_get_contents();
		ob_end_clean();

		return $raw;
	}

        public function rotate($rotang=0){
		$img = $this->_img->img;
		$mimeType = $this->_img->mime;
                //$newImg = imagerotate($img, $rotang, 0);
                $this->_img->img = imagerotate($img, $rotang, 0);
                return true;
        }
        
	public function show() {

		header( 'Content-Type: ' . $this->_img->mime );

		$img = $this->_img->img;
		$mimeType = $this->_img->mime;

		switch( $mimeType ) {

			case Ftl_MimeType::IMAGE_GIF:
				$transColor = imagecolortransparent ( $img );
				$transIndex = imagecolorallocate ( $img, $transColor[ 'red' ], $transColor[ 'green' ], $transColor[ 'blue' ] );
				imagecolortransparent ( $img, $transIndex );
				imagegif( $img );
				break;

			case Ftl_MimeType::IMAGE_JPG:
				imagejpeg( $img );
				break;

			case Ftl_MimeType::IMAGE_PNG:
				imagealphablending( $img, false );
				imagesavealpha( $img, true );
				imagepng( $img );
				break;

		}

		return $this;

	}

	public function destroy() {

		imagedestroy( $this->_img->img );

		unset( $this->_img->img );

		if( $this->_keepOriginal ) {
			imagedestroy( $this->_orig->img );
			unset( $this->_orig );
		}

		return $this;

	}


	private function setLoadedImageProperties( $img, $mime, $width, $height ) {

		$imgInfo = new Ftl_ImageInfo();
		$imgInfo->img		= $img;
		$imgInfo->mime		= $mime;
		$imgInfo->width		= $width;
		$imgInfo->height	= $height;
		$imgInfo->ratio		= $width / $height;

		$this->_img = $imgInfo;

		if( $this->_keepOriginal ) {
			$this->_orig = clone $imgInfo;
		}

	}


}
?>
