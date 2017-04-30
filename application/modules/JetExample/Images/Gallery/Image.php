<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Images;

use Jet\Data_Image;
use Jet\IO_File;
use Jet\IO_Dir;
use Jet\DataModel;
use Jet\DataModel_Fetch_Data_Assoc;
use Jet\DataModel_Id_UniqueString;

/**
 *
 * @JetDataModel:name = 'Image'
 * @JetDataModel:database_table_name = 'image_galleries_images'
 * @JetDataModel:id_class_name = 'DataModel_Id_UniqueString'
 */
class Gallery_Image extends DataModel {
	const THUMBNAILS_DIR_NAME = '_t_';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID
	 *
	 * @var string
	 */
	protected $gallery_id = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID
	 * @JetDataModel:is_id = true
	 *
	 * @var string
	 */
	protected $id = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_INT
	 * @JetDataModel:form_field_min_value = 1
	 * @JetDataModel:form_field_type = false
	 *
	 * @var int
	 */
	protected $offset = 0;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 100
	 * @JetDataModel:form_field_is_required = false
	 * @JetDataModel:form_field_label = 'Title: '
	 *
	 * @var string
	 */
	protected $title = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_is_required = true
	 * @JetDataModel:form_field_type = false
	 *
	 * @var string
	 */
	protected $file_name = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_is_required = true
	 * @JetDataModel:form_field_type = false
	 *
	 * @var string
	 */
	protected $file_mime_type = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_INT
	 * @JetDataModel:form_field_min_value = 1
	 * @JetDataModel:form_field_type = false
	 *
	 * @var int
	 */
	protected $file_size = 0;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_INT
	 * @JetDataModel:form_field_min_value = 1
	 * @JetDataModel:form_field_type = false
	 *
	 * @var int
	 */
	protected $image_size_w = 0;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_INT
	 * @JetDataModel:form_field_min_value = 1
	 * @JetDataModel:form_field_type = false
	 *
	 * @var int
	 */
	protected $image_size_h = 0;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Gallery_Image_Thumbnail'
	 *
	 * @var Gallery_Image_Thumbnail[]
	 */
	protected $thumbnails;

	/**
	 * @var Gallery
	 */
	protected $__gallery;

	/**
	 * @return string
	 */
	public function getGalleryId() {
		return $this->gallery_id;
	}

	/**
	 * @return Gallery
	 */
	public function getGallery() {
		if(!$this->__gallery) {
			$this->__gallery = Gallery::get( $this->gallery_id );
		}

		return $this->__gallery;
	}

	/**
	 * @param Gallery $gallery
	 */
	public function setGallery(Gallery $gallery ) {
		$this->gallery_id = $gallery->getIdObject()->toString();

		$this->__gallery = $gallery;
	}


	/**
	 * @return int
	 */
	public function getOffset() {
		return $this->offset;
	}

	/**
	 * @return string
	 */
	public function getFileMimeType() {
		return $this->file_mime_type;
	}

	/**
	 * @return string
	 */
	public function getFileName() {
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
	public function getImageSizeH() {
		return $this->image_size_h;
	}

	/**
	 * @return int
	 */
	public function getImageSizeW() {
		return $this->image_size_w;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}


	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}




	/**
	 * @param string $file_mime_type
	 */
	protected function setFileMimeType($file_mime_type) {
        $this->file_mime_type = $file_mime_type;
	}

	/**
	 * @param string $file_name
	 */
	protected function setFileName($file_name) {
		$this->file_name = $file_name;
	}

	/**
	 * @param int $file_size
	 */
	protected function setFileSize($file_size) {
		$this->file_size = (int)$file_size;
	}

	/**
	 * @param int $image_size_h
	 */
	protected function setImageSizeH($image_size_h) {
		$this->image_size_h = (int)$image_size_h;
	}

	/**
	 * @param int $image_size_w
	 */
	protected function setImageSizeW($image_size_w) {
		$this->image_size_w = (int)$image_size_w;
	}

	/**
	 * @param int $offset
	 */
	protected function setOffset($offset) {
		$this->offset = (int)$offset;
	}

	/**
	 * @return int
	 */
	public function getAllImagesCount() {
		return $this->fetchObjectIds()->getCount();
	}


	/**
	 * @static
	 *
	 * @param string $id
	 *
	 * @return Gallery_Image
	 */
	public static function get( $id ) {
		/** @noinspection PhpIncompatibleReturnTypeInspection */
		return static::load( $id );
	}


	/**
	 * @return string
	 */
	public function getFilePath() {
		return $this->getDirPath().$this->getFileName();
	}

	/**
	 * @return string
	 */
	public function getOffsetDirPath() {
		return $this->getGallery()->getBaseDirPath().$this->getOffset().'/';
	}

	/**
	 * @return string
	 */
	public function getDirPath() {
		return $this->getOffsetDirPath().$this->getIdObject().'/';
	}

	/**
	 * @return string
	 */
	public function getThumbnailsDirPath() {
		return $this->getDirPath().static::THUMBNAILS_DIR_NAME.'/';
	}

	/**
	 * @return string
	 */
	public function getURI() {
		return $this->getGallery()->getBaseURI().$this->getOffset().'/'.$this->getIdObject().'/'.rawurldecode($this->getFileName());
	}


	/**
	 * @return string
	 */
	public function getThumbnailsBaseURI() {
		return $this->getGallery()->getBaseURI().$this->getOffset().'/'.$this->getIdObject().'/'.static::THUMBNAILS_DIR_NAME.'/';
	}

	/**
	 * @param $maximal_size_w
	 * @param $maximal_size_h
	 * @param bool $do_not_save_now
	 *
	 * @return Gallery_Image_Thumbnail|null
	 */
	public function getThumbnail( $maximal_size_w, $maximal_size_h, $do_not_save_now=false ) {
		$key = Gallery_Image_Thumbnail::createKey( $maximal_size_w, $maximal_size_h );


		if(!isset($this->thumbnails[$key])) {
			$thb = Gallery_Image_Thumbnail::getNewThumbnail($this, $maximal_size_w, $maximal_size_h);
			if(!$thb) {
				return null;
			}
			$this->thumbnails[$key] = $thb;
			if(!$do_not_save_now) {
				$this->save();
			}
		}

		$this->thumbnails[$key]->setImage( $this );

		return $this->thumbnails[$key];
	}

	/**
	 * @param string $new_source_file_path
	 */
	public function overwrite( $new_source_file_path ) {
		IO_File::copy($new_source_file_path, $this->getFilePath() );

		$source_image_file = new Data_Image( $new_source_file_path );

		$this->setImageSizeH( $source_image_file->getHeight() );
		$this->setImageSizeW( $source_image_file->getWidth() );
		$this->setFileMimeType( $source_image_file->getMimeType() );
		$this->setFileSize( IO_File::getSize( $new_source_file_path ) );

		foreach( $this->thumbnails as $thumbnail ) {
			$thumbnail->setImage($this);
			$thumbnail->recreate();
		}
	}

	/**
	 * @param Gallery $gallery
	 * @param string $source_file_path
	 * @param string|null $source_file_name
	 * @param bool $do_not_save_immediately
	 *
	 * @return Gallery_Image
	 */
	public static function getNewImage(Gallery $gallery, $source_file_path, $source_file_name=null, $do_not_save_immediately=false  ) {

		/**
		 * @var Gallery_Image $image
		 */
		$image = new static();
		$image->getIdObject()->generate();

		$image->setGallery($gallery);

		$offset = ceil($image->getAllImagesCount()/1000);
		$offset = $offset ? $offset : 1;

		$image->setOffset( $offset );

		$source_image_file = new Data_Image( $source_file_path );


		$image->setImageSizeH( $source_image_file->getHeight() );
		$image->setImageSizeW( $source_image_file->getWidth() );
		$image->setFileName( $source_file_name ? $source_file_name : $source_image_file->getFileName() );
		$image->setFileMimeType( $source_image_file->getMimeType() );
		$image->setFileSize( IO_File::getSize( $source_file_path ) );

		$offset_dir = $image->getOffsetDirPath();


		if( !IO_Dir::exists($offset_dir) ) {
			IO_Dir::create( $offset_dir );
		}

		IO_Dir::create( $image->getDirPath() );
		IO_Dir::create( $image->getThumbnailsDirPath() );

		IO_File::copy($source_file_path, $image->getFilePath() );

		if(!$do_not_save_immediately) {
			$image->save();
		}

		return $image;
	}

	/**
	 * @static
	 *
	 * @param string $gallery_id (optional)
	 *
	 * @return Gallery_Image[]
	 */
	public static function getList( $gallery_id='' ) {
		$query = [];

		if($gallery_id) {
			$query['this.gallery_id'] = $gallery_id;
		}

		/** @noinspection PhpIncompatibleReturnTypeInspection */
		return (new self())->fetchObjects($query);
	}

	/**
	 * @static
	 *
	 * @param string $gallery_id (optional)
	 *
	 * @return DataModel_Fetch_Data_Assoc
	 */
	public static function getListAsData( $gallery_id='' ) {
		/**
		 * @var DataModel $i;
		 */
		$i = new self();
		$props = $i->getDataModelDefinition()->getProperties();
		$data = $i->fetchDataAssoc($props, []);

		if($gallery_id) {
			$data->getQuery()->setWhere( ['this.gallery_id'=>$gallery_id]);
		}

		return $data;
	}

}