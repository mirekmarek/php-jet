<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Data_Image;
use Jet\Http_Request;
use Jet\IO_File;
use Jet\IO_Dir;
use Jet\DataModel;
use Jet\DataModel_Definition;
use Jet\DataModel_IDController_UniqueString;
use Jet\MVC_Cache;

#[DataModel_Definition(
	name: 'image',
	database_table_name: 'image_galleries_images',
	id_controller_class: DataModel_IDController_UniqueString::class
)]
class Content_Gallery_Image extends DataModel
{

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_ID
	)]
	protected string $gallery_id = '';

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_ID,
		is_id: true
	)]
	protected string $id = '';

	/**
	 * @var int
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_INT,
	)]
	protected int $offset = 0;

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255,
	)]
	protected string $file_name = '';

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255,
	)]
	protected string $file_mime_type = '';

	/**
	 * @var int
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_INT,
	)]
	protected int $file_size = 0;

	/**
	 * @var int
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_INT,
	)]
	protected int $image_size_w = 0;

	/**
	 * @var int
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_INT,
	)]
	protected int $image_size_h = 0;

	/**
	 * @var ?Content_Gallery
	 */
	protected ?Content_Gallery $__gallery = null;

	/**
	 *
	 * @param string $id
	 *
	 * @return static|null
	 */
	public static function get( string $id ): static|null
	{
		return static::load( $id );
	}

	/**
	 * @param Content_Gallery $gallery
	 * @param string $source_file_path
	 * @param string|null $source_file_name
	 * @param bool $do_not_save_immediately
	 *
	 * @return Content_Gallery_Image
	 */
	public static function getNewImage( Content_Gallery $gallery,
	                                    string          $source_file_path,
	                                    ?string         $source_file_name = null,
	                                    bool            $do_not_save_immediately = false ): Content_Gallery_Image
	{

		$image = new static();
		$image->getIDController()->generate();

		$image->setGallery( $gallery );

		$offset = ceil( $image->getAllImagesCount() / 1000 );
		$offset = $offset ? : 1;

		$image->setOffset( $offset );

		$source_image_file = new Data_Image( $source_file_path );


		$image->setImageSizeH( $source_image_file->getHeight() );
		$image->setImageSizeW( $source_image_file->getWidth() );
		$image->setFileName( $source_file_name ? : $source_image_file->getFileName() );
		$image->setFileMimeType( $source_image_file->getMimeType() );
		$image->setFileSize( IO_File::getSize( $source_file_path ) );

		$offset_dir = $image->getOffsetDirPath();


		if( !IO_Dir::exists( $offset_dir ) ) {
			IO_Dir::create( $offset_dir );
		}

		IO_Dir::create( $image->getDirPath() );

		IO_File::copy( $source_file_path, $image->getFilePath() );

		if( !$do_not_save_immediately ) {
			$image->save();
		}

		return $image;
	}

	/**
	 * @return string
	 */
	public function getId(): string
	{
		return $this->id;
	}

	/**
	 * @return int
	 */
	public function getAllImagesCount(): int
	{
		return static::fetchIDs()->getCount();
	}


	/**
	 * @return Content_Gallery
	 */
	public function getGallery(): Content_Gallery
	{
		if( !$this->__gallery ) {
			$this->__gallery = Content_Gallery::get( $this->gallery_id );
		}

		return $this->__gallery;
	}

	/**
	 * @param Content_Gallery $gallery
	 */
	public function setGallery( Content_Gallery $gallery ): void
	{
		$this->gallery_id = $gallery->getId();

		$this->__gallery = $gallery;
	}

	/**
	 * @return int
	 */
	public function getOffset(): int
	{
		return $this->offset;
	}

	/**
	 * @param int $offset
	 */
	protected function setOffset( int $offset ): void
	{
		$this->offset = $offset;
	}

	/**
	 * @return string
	 */
	public function getOffsetDirPath(): string
	{
		return $this->getGallery()->getBaseDirPath() . $this->getOffset() . '/';
	}

	/**
	 * @return string
	 */
	public function getDirPath(): string
	{
		return $this->getOffsetDirPath() . $this->getId() . '/';
	}

	/**
	 * @return string
	 */
	public function getFilePath(): string
	{
		return $this->getDirPath() . $this->getFileName();
	}

	/**
	 * @return string
	 */
	public function getBaseURI(): string
	{
		return $this->getGallery()->getBaseURI() . $this->getOffset() . '/' . $this->getId() . '/';
	}


	/**
	 * @return string
	 */
	public function getURI(): string
	{
		return $this->getBaseURI() . rawurldecode( $this->getFileName() );
	}


	/**
	 * @return string
	 */
	public function getFileName(): string
	{
		return $this->file_name;
	}

	/**
	 * @param string $file_name
	 */
	protected function setFileName( string $file_name ): void
	{
		$this->file_name = $file_name;
	}

	/**
	 *
	 * @param string $gallery_id (optional)
	 *
	 * @return Content_Gallery_Image[]
	 */
	public static function getList( string $gallery_id = '' ): iterable
	{
		$where = [];

		if( $gallery_id ) {
			$where['gallery_id'] = $gallery_id;
		}

		return static::fetchInstances( $where );
	}

	/**
	 * @return string
	 */
	public function getGalleryId(): string
	{
		return $this->gallery_id;
	}

	/**
	 * @return string
	 */
	public function getFileMimeType(): string
	{
		return $this->file_mime_type;
	}

	/**
	 * @param string $file_mime_type
	 */
	protected function setFileMimeType( string $file_mime_type ): void
	{
		$this->file_mime_type = $file_mime_type;
	}

	/**
	 * @return int
	 */
	public function getFileSize(): int
	{
		return $this->file_size;
	}

	/**
	 * @param int $file_size
	 */
	protected function setFileSize( int $file_size ): void
	{
		$this->file_size = $file_size;
	}

	/**
	 * @return int
	 */
	public function getImageSizeH(): int
	{
		return $this->image_size_h;
	}

	/**
	 * @param int $image_size_h
	 */
	protected function setImageSizeH( int $image_size_h ): void
	{
		$this->image_size_h = $image_size_h;
	}

	/**
	 * @return int
	 */
	public function getImageSizeW(): int
	{
		return $this->image_size_w;
	}

	/**
	 * @param int $image_size_w
	 */
	protected function setImageSizeW( int $image_size_w ): void
	{
		$this->image_size_w = $image_size_w;
	}

	/**
	 * @param int $maximal_size_w
	 * @param int $maximal_size_h
	 *
	 * @return Content_Gallery_Image_Thumbnail
	 */
	public function getThumbnail( int $maximal_size_w, int $maximal_size_h ): Content_Gallery_Image_Thumbnail
	{
		return new Content_Gallery_Image_Thumbnail( $this, $maximal_size_w, $maximal_size_h );
	}

	/**
	 *
	 */
	public function afterDelete(): void
	{
		$path = $this->getDirPath();

		if( IO_Dir::exists( $path ) ) {
			IO_Dir::remove( $path );
		}

		MVC_Cache::reset();
	}


	/**
	 *
	 */
	public function jsonSerialize(): array
	{

		$data = parent::jsonSerialize();

		$data['URL'] = Http_Request::baseURL() . $this->getURI();

		return $data;
	}

}