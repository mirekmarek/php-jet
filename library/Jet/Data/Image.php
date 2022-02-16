<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class Data_Image extends BaseObject
{
	const TYPE_GIF = 1;
	const TYPE_JPG = 2;
	const TYPE_PNG = 3;

	/**
	 * @var string
	 */
	protected string $path = '';

	/**
	 *
	 * IMG_* constant
	 *
	 * @var int
	 */
	protected int $img_type = 0;

	/**
	 * @var int
	 */
	protected int $width = 0;

	/**
	 * @var int
	 */
	protected int $height = 0;

	/**
	 * @var string
	 */
	protected string $mime_type = '';

	/**
	 * @see imagejpeg
	 *
	 * @var int
	 */
	protected int $image_quality = 85;

	/**
	 * @param string $path
	 *
	 * @throws Data_Image_Exception
	 */
	public function __construct( string $path )
	{
		$this->path = $path;

		if( !IO_File::exists( $path ) ) {
			throw new Data_Image_Exception(
				'File \'' . $path . '\' does not exist!', Data_Image_Exception::CODE_IMAGE_FILE_DOES_NOT_EXIST
			);
		}

		if( !IO_File::isReadable( $path ) ) {
			throw new Data_Image_Exception(
				'File \'' . $path . '\' is not readable!', Data_Image_Exception::CODE_IMAGE_FILE_IS_NOT_READABLE
			);
		}

		$image_dat = Debug_ErrorHandler::doItSilent(function() use ($path) {
			return getimagesize( $path );
		});

		if( !$image_dat ) {
			throw new Data_Image_Exception(
				'File: \'' . $path . '\' Unsupported type! Unable to get image size!',
				Data_Image_Exception::CODE_UNSUPPORTED_IMAGE_TYPE
			);
		}

		$this->path = $path;

		[
			$this->width,
			$this->height,
			$this->img_type
		] = $image_dat;

		$this->mime_type = $image_dat['mime'];
	}

	/**
	 * @return string
	 */
	public function getPath(): string
	{
		return $this->path;
	}

	/**
	 * @return string
	 */
	public function getDirectory(): string
	{
		return dirname( $this->path ) . '/';
	}

	/**
	 * @return string
	 */
	public function getFileName(): string
	{
		return basename( $this->path );
	}

	/**
	 * @return int
	 */
	public function getWidth(): int
	{
		return $this->width;
	}

	/**
	 * @return int
	 */
	public function getHeight(): int
	{
		return $this->height;
	}

	/**
	 * @return int
	 */
	public function getImgType(): int
	{
		return $this->img_type;
	}

	/**
	 * @return string
	 */
	public function getMimeType(): string
	{
		return $this->mime_type;
	}

	/**
	 * @return int
	 */
	public function getImageQuality(): int
	{
		return $this->image_quality;
	}

	/**
	 * @param int $image_quality
	 */
	public function setImageQuality( int $image_quality ): void
	{
		$this->image_quality = $image_quality;
	}

	/**
	 * @param string $target_path
	 * @param int $maximal_width
	 * @param int $maximal_height
	 * @param int|null $target_img_type
	 *
	 * @return static
	 *
	 * @throws Data_Image_Exception
	 */
	public function createThumbnail( string $target_path, int $maximal_width, int $maximal_height, ?int $target_img_type = null ): static
	{

		if( $this->width >= $this->height ) {
			$new_width = $maximal_width;
			$new_height = (int)round( ($new_width / $this->width) * $this->height );


			if( $new_height > $maximal_height ) {
				$_height = $new_height;
				$new_height = $maximal_height;
				$new_width = (int)round( ($new_height / $_height) * $new_width );
			}

		} else {
			$new_height = $maximal_height;
			$new_width = (int)round( ($new_height / $this->height) * $this->width );

			if( $new_width > $maximal_width ) {
				$_width = $new_width;
				$new_width = $maximal_width;
				$new_height = (int)round( ($new_width / $_width) * $new_height );
			}
		}

		return $this->saveAs( $target_path, $new_width, $new_height, $target_img_type );
	}


	/**
	 * @param string $target_path
	 * @param int|null $new_width (optional)
	 * @param int|null $new_height (optional)
	 * @param int|null $target_img_type (optional)
	 *
	 * @return Data_Image
	 *
	 * @throws Data_Image_Exception
	 * @throws static
	 */
	public function saveAs( string $target_path, ?int $new_width = null, ?int $new_height = null, ?int $target_img_type = null ): static
	{
		if( !$target_img_type ) {
			$target_img_type = $this->img_type;
		}

		if( !$new_width ) {
			$new_width = $this->width;
		}

		if( !$new_height ) {
			$new_height = $this->height;
		}

		$image = match ($this->img_type) {
			self::TYPE_JPG => imagecreatefromjpeg( $this->path ),
			self::TYPE_GIF => imagecreatefromgif( $this->path ),
			self::TYPE_PNG => imagecreatefrompng( $this->path ),
		};

		if( !$image ) {
			throw new Data_Image_Exception(
				'File: \'' . $this->path . '\' Unsupported type! Unable to get image size!',
				Data_Image_Exception::CODE_UNSUPPORTED_IMAGE_TYPE
			);
		}

		$new_image = imagecreatetruecolor( $new_width, $new_height );

		if(
			$target_img_type == self::TYPE_PNG ||
			$target_img_type == self::TYPE_GIF
		) {
			imagealphablending( $new_image, false );
			imagesavealpha( $new_image, true );

			$transparent = imagecolorallocatealpha( $new_image, 255, 255, 255, 127 );

			imagefilledrectangle( $new_image, 0, 0, $new_width, $new_height, $transparent );
		}

		imagecopyresampled( $new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $this->width, $this->height );

		switch( $target_img_type ) {
			case self::TYPE_JPG:
				imagejpeg( $new_image, $target_path, $this->image_quality );
				break;
			case self::TYPE_GIF:
				imagegif( $new_image, $target_path );
				break;
			case self::TYPE_PNG:
				imagepng( $new_image, $target_path );
				break;
		}

		imagedestroy( $new_image );

		IO_File::chmod( $target_path );

		return new static( $target_path );
	}


}