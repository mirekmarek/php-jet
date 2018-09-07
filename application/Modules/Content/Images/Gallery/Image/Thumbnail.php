<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\Content\Images;

use Jet\BaseObject;
use Jet\BaseObject_Interface_Serializable_JSON;
use Jet\Http_Request;
use Jet\Data_Image;
use Jet\IO_File;
use Jet\IO_Dir;

/**
 *
 */
class Gallery_Image_Thumbnail extends BaseObject implements BaseObject_Interface_Serializable_JSON
{
	/**
	 * @var Gallery_Image
	 */
	protected $image;

	/**
	 * @var int
	 */
	protected $maximal_size_w = 0;
	/**
	 * @var int
	 */
	protected $maximal_size_h = 0;

	/**
	 * @var string
	 */
	protected $dir_path = '';

	/**
	 * @var string
	 */
	protected $path = '';

	/**
	 * @var string
	 */
	protected $URI;

	/**
	 * @var bool
	 */
	protected $generated = false;

	/**
	 * @var Data_Image
	 */
	protected $real_image;

	/**
	 * Gallery_Image_Thumbnail constructor.
	 *
	 * @param $image
	 * @param int $maximal_size_w
	 * @param int $maximal_size_h
	 */
	public function __construct( Gallery_Image $image, $maximal_size_w, $maximal_size_h ) {

		$this->image = $image;
		$this->maximal_size_w = (int)$maximal_size_w;
		$this->maximal_size_h = (int)$maximal_size_h;


		$key = $maximal_size_w.'x'.$maximal_size_h;

		$file_name = $key.pathinfo($image->getFileName())['extension'];

		$this->dir_path = $image->getDirPath().'_thb_/';
		$this->path = $this->dir_path.$file_name;


		$this->URI = $image->getBaseURI().'_thb_/'.$file_name;

	}

	/**
	 * @param bool $regenerate
	 */
	public function generate( $regenerate=false ) {
		if($this->generated) {
			return;
		}


		if(
			$regenerate ||
			!IO_File::exists($this->path)
		) {
			if( !IO_File::isReadable($this->image->getFilePath()) ) {
				return;
			}

			if(!IO_Dir::exists($this->dir_path)) {
				IO_Dir::create($this->dir_path);
			}

			$image_file = new Data_Image( $this->image->getFilePath() );
			$image_file->createThumbnail(
				$this->path,
				$this->maximal_size_w,
				$this->maximal_size_h
			);

			$this->real_image = null;

		}

		$this->generated = true;

	}

	/**
	 * @return string
	 */
	public function getURI() {
		$this->generate();

		if(!$this->generated) {
			return '';
		}

		return $this->URI;
	}

	/**
	 * @return string
	 */
	public function getPath()
	{
		$this->generate();

		return $this->path;
	}

	/**
	 * @return string
	 */
	public function getDirPath()
	{
		$this->generate();

		return $this->dir_path;
	}

	/**
	 * @return int
	 */
	public function getMaximalSizeW()
	{
		return $this->maximal_size_w;
	}

	/**
	 * @return int
	 */
	public function getMaximalSizeH()
	{
		return $this->maximal_size_h;
	}

	/**
	 *
	 */
	public function delete()
	{
		if(IO_File::exists($this->path)) {
			IO_File::delete($this->path);
		}

	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		try {
			$URI = $this->getURI();
		} catch( \Exception $e ) {
			$URI = '';
		}

		return $URI;
	}

	/**
	 * @return Data_Image
	 */
	public function getRealImage()
	{
		if(!$this->real_image) {
			$this->generate();
			$this->real_image = new Data_Image($this->path);
		}

		return $this->real_image;
	}

	/**
	 * @return int
	 */
	public function getWidth()
	{
		return $this->getRealImage()->getWidth();
	}

	/**
	 * @return int
	 */
	public function getHeight()
	{
		return $this->getRealImage()->getHeight();
	}

	/**
	 * @return string
	 */
	public function getMimeType()
	{
		return $this->getRealImage()->getMimeType();
	}


	/**
	 *
	 */
	public function jsonSerialize()
	{

		$data = [
			'maximal_size_w' => $this->maximal_size_w,
			'maximal_size_h' => $this->maximal_size_h,
			'real_size_w' => $this->getRealImage()->getWidth(),
			'real_size_h' => $this->getRealImage()->getHeight(),
			'file_size' => IO_File::getSize( $this->getPath() ),
			'URL' => Http_Request::baseURL().$this->getURI()
		];

		return $data;
	}

	/**
	 * @return string
	 */
	public function toJSON()
	{
		return json_encode($this->jsonSerialize());
	}
}