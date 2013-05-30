<?php
/**
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule\Jet\Images
 */
namespace JetApplicationModule\Jet\Images;
use Jet;

class Gallery_Image_Thumbnail extends Jet\DataModel_Related_1toN {

	protected static $__data_model_parent_model_class_name = "JetApplicationModule\\Jet\\Images\\Gallery_Image";
	protected static $__data_model_model_name = "Jet_ImageGallery_Images_Thumbnails";


	protected static $__data_model_properties_definition = array(
		"ID" => array(
			"type" => self::TYPE_ID,
			"is_required" => true,
			"is_ID" => true,
		),
		"maximal_size_w" => array(
			"type" => self::TYPE_INT,
			"is_required" => true,
			"form_field_type" => false
		),
		"maximal_size_h" => array(
			"type" => self::TYPE_INT,
			"is_required" => true,
			"form_field_type" => false
		),
		"real_size_w" => array(
			"type" => self::TYPE_INT,
			"is_required" => true,
			"form_field_type" => false
		),
		"real_size_h" => array(
			"type" => self::TYPE_INT,
			"is_required" => true,
			"form_field_type" => false
		),
		"file_size" => array(
			"type" => self::TYPE_INT,
			"is_required" => true,
			"form_field_type" => false
		),
		"file_mime_type" => array(
			"type" => self::TYPE_STRING,
			"max_len" => 255,
			"form_field_type" => false
		),
		"file_name" => array(
			"type" => self::TYPE_STRING,
			"max_len" => 255,
			"form_field_type" => false
		),
	);

	/**
	 * @var string
	 */
	protected $Jet_ImageGallery_Images_ID = "";

	/**
	 * @var string
	 */
	protected $ID = "";


	/**
	 * @var int
	 */
	protected $maximal_size_w = 0;

	/**
	 * @var int
	 */
	protected $maximal_size_h = 0;

	/**
	 * @var int
	 */
	protected $real_size_w = 0;

	/**
	 * @var int
	 */
	protected $real_size_h = 0;

	/**
	 * @var int
	 */
	protected $file_size = 0;

	/**
	 * @var int
	 */
	protected $file_mime_type = 0;

	/**
	 * @var string
	 */
	protected $file_name = "";

	/**
	 * @var Gallery_Image
	 */
	protected $__image;

	/**
	 * @return string
	 */
	public function getArrayKeyValue() {
		return $this->createKey($this->maximal_size_w, $this->maximal_size_h);
	}

	/**
	 * @param int $maximal_size_w
	 * @param int $maximal_size_h
	 *
	 * @return string
	 * @throws Exception
	 */
	public static function createKey( $maximal_size_w, $maximal_size_h ) {
		$maximal_size_w = (int)$maximal_size_w;
		$maximal_size_h = (int)$maximal_size_h;

		if(
			!$maximal_size_w ||
			!$maximal_size_h
		) {
			throw new Exception(
				"Dimensions of Image thumbnail must be greater then 0! Given values: w:{$maximal_size_w}, h:{$maximal_size_h}",
				Exception::CODE_ILLEGAL_THUMBNAIL_DIMENSION
			);
		}

		return $maximal_size_w."x".$maximal_size_h;
	}


	/**
	 * @return int
	 */
	public function getFileMimeType() {
		return $this->file_mime_type;
	}

	/**
	 * @return string
	 */
	public function getFileName() {
		//TODO: check
		return $this->file_name;
	}

	/**
	 * @return int
	 */
	public function getFileSize() {
		return $this->file_size;
	}

	/**
	 * @return int
	 */
	public function getMaximalSizeH() {
		return $this->maximal_size_h;
	}

	/**
	 * @return int
	 */
	public function getMaximalSizeW() {
		return $this->maximal_size_w;
	}

	/**
	 * @return int
	 */
	public function getRealSizeH() {
		return $this->real_size_h;
	}

	/**
	 * @return int
	 */
	public function getRealSizeW() {
		return $this->real_size_w;
	}

	/**
	 * @return string
	 */
	public function getPath() {
		return $this->__image->getThumbnailsDirPath().$this->getFileName();
	}

	/**
	 * @return string
	 */
	public function getURI() {
		return $this->__image->getThumbnailsBaseURI().rawurlencode($this->getFileName());
	}

	/**
	 * @param Gallery_Image $image
	 */
	public function setImage( Gallery_Image $image) {
		$this->__image = $image;
	}

	/**
	 *
	 */
	public function recreate() {

		$image_file = new Jet\Image( $this->__image->getFilePath() );

		$target_path = $this->getPath();

		$created_image_file = $image_file->createThumbnail($target_path, $this->maximal_size_w, $this->maximal_size_h);

		$this->real_size_w = $created_image_file->getWidth();
		$this->real_size_h = $created_image_file->getHeight();
		$this->file_mime_type = $created_image_file->getMimeType();
		$this->file_size = Jet\IO_File::getSize( $target_path );

		$this->validateProperties();

	}

	/**
	 * @param Gallery_Image $image
	 * @param int $maximal_size_w
	 * @param int $maximal_size_h
	 *
	 * @throws Exception
	 * @return Gallery_Image_Thumbnail
	 */
	public static function getNewThumbnail( Gallery_Image $image, $maximal_size_w, $maximal_size_h ) {
		$maximal_size_w = (int)$maximal_size_w;
		$maximal_size_h = (int)$maximal_size_h;

		$key = static::createKey($maximal_size_w, $maximal_size_h);

		/**
		 * @var Gallery_Image_Thumbnail $thumbnail
		 */
		$thumbnail = new static();
		$thumbnail->generateID();

		$thumbnail->file_name = "{$key}_{$image->getFileName()}";

		$image_file = new Jet\Image( $image->getFilePath() );

		$target_path = $image->getThumbnailsDirPath().$thumbnail->file_name;

		$created_image_file = $image_file->createThumbnail($target_path, $maximal_size_w, $maximal_size_h);

		$thumbnail->real_size_w = $created_image_file->getWidth();
		$thumbnail->real_size_h = $created_image_file->getHeight();

		$thumbnail->maximal_size_w = $maximal_size_w;
		$thumbnail->maximal_size_h = $maximal_size_h;

		$thumbnail->file_name = $created_image_file->getFileName();
		$thumbnail->file_mime_type = $created_image_file->getMimeType();
		$thumbnail->file_size = Jet\IO_File::getSize( $target_path );

		$thumbnail->__image = $image;

		$thumbnail->validateProperties();

		return $thumbnail;
	}

}