<?php
/**
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule\JetExample\Images
 */
namespace JetApplicationModule\JetExample\Images;
use Jet;

/**
 * Class Gallery_Image
 *
 * @JetDataModel:name = 'Image'
 * @JetDataModel:database_table_name = 'Jet_ImageGalleries_Images'
 */
class Gallery_Image extends Jet\DataModel {
	const THUMBNAILS_DIR_NAME = '_t_';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_ID
	 *
	 * @var string
	 */
	protected $gallery_ID = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_ID
	 * @JetDataModel:is_ID = true
	 *
	 * @var string
	 */
	protected $ID = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_INT
	 * @JetDataModel:min_value = 1
	 * @JetDataModel:form_field_type = false
	 *
	 * @var int
	 */
	protected $offset = 0;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 100
	 * @JetDataModel:is_required = false
	 * @JetDataModel:form_field_label = 'Title: '
	 *
	 * @var string
	 */
	protected $title = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:is_required = true
	 * @JetDataModel:form_field_type = false
	 *
	 * @var string
	 */
	protected $file_name = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:is_required = true
	 * @JetDataModel:form_field_type = false
	 *
	 * @var string
	 */
	protected $file_mime_type = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_INT
	 * @JetDataModel:min_value = 1
	 * @JetDataModel:form_field_type = false
	 *
	 * @var int
	 */
	protected $file_size = 0;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_INT
	 * @JetDataModel:min_value = 1
	 * @JetDataModel:form_field_type = false
	 *
	 * @var int
	 */
	protected $image_size_w = 0;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_INT
	 * @JetDataModel:min_value = 1
	 * @JetDataModel:form_field_type = false
	 *
	 * @var int
	 */
	protected $image_size_h = 0;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'JetApplicationModule\JetExample\Images\Gallery_Image_Thumbnail'
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
	public function getGalleryID() {
		return $this->gallery_ID;
	}

	/**
	 * @return Gallery
	 */
	public function getGallery() {
		if(!$this->__gallery) {
			$this->__gallery = Gallery::get( $this->gallery_ID );
		}

		return $this->__gallery;
	}

	/**
	 * @param Gallery $gallery
	 */
	public function setGallery(Gallery $gallery ) {
		$this->gallery_ID = $gallery->getID()->toString();

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
		$this->_setPropertyValue('title', $title);
	}




	/**
	 * @param string $file_mime_type
	 */
	protected function setFileMimeType($file_mime_type) {
		$this->_setPropertyValue('file_mime_type', $file_mime_type);
	}

	/**
	 * @param string $file_name
	 */
	protected function setFileName($file_name) {
		$this->_setPropertyValue('file_name', $file_name);
	}

	/**
	 * @param int $file_size
	 */
	protected function setFileSize($file_size) {
		$this->_setPropertyValue('file_size', $file_size);
	}

	/**
	 * @param int $image_size_h
	 */
	protected function setImageSizeH($image_size_h) {
		$this->_setPropertyValue('image_size_h', $image_size_h);
	}

	/**
	 * @param int $image_size_w
	 */
	protected function setImageSizeW($image_size_w) {
		$this->_setPropertyValue('image_size_w', $image_size_w);
	}

	/**
	 * @param int $offset
	 */
	protected function setOffset($offset) {
		$this->_setPropertyValue('offset', $offset);
	}

	/**
	 * @return int
	 */
	public function getAllImagesCount() {
		return $this->fetchObjectIDs()->getCount();
	}


	/**
	 * @static
	 *
	 * @param string $ID
	 *
	 * @return Gallery_Image
	 */
	public static function get( $ID ) {
		return static::load( static::createID($ID) );
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
		return $this->getOffsetDirPath().$this->getID().'/';
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
		return $this->getGallery()->getBaseURI().$this->getOffset().'/'.$this->getID().'/'.rawurldecode($this->getFileName());
	}


	/**
	 * @return string
	 */
	public function getThumbnailsBaseURI() {
		return $this->getGallery()->getBaseURI().$this->getOffset().'/'.$this->getID().'/'.static::THUMBNAILS_DIR_NAME.'/';
	}

	/**
	 * @param $maximal_size_w
	 * @param $maximal_size_h
	 * @param bool $do_not_save_imindietly
	 *
	 * @return Gallery_Image_Thumbnail
	 */
	public function getThumbnail( $maximal_size_w, $maximal_size_h, $do_not_save_imindietly=false ) {
		$key = Gallery_Image_Thumbnail::createKey( $maximal_size_w, $maximal_size_h );


		if(!isset($this->thumbnails[$key])) {
			$this->thumbnails[$key] = Gallery_Image_Thumbnail::getNewThumbnail($this, $maximal_size_w, $maximal_size_h);
			if(!$do_not_save_imindietly) {
				$this->validateProperties();
				$this->save();
			}
		}

		$this->thumbnails[$key]->setImage( $this );

		return $this->thumbnails[$key];
	}

	public function overwrite( $new_source_file_path ) {
		Jet\IO_File::copy($new_source_file_path, $this->getFilePath() );

		$source_image_file = new Jet\Image( $new_source_file_path );

		$this->setImageSizeH( $source_image_file->getHeight() );
		$this->setImageSizeW( $source_image_file->getWidth() );
		$this->setFileMimeType( $source_image_file->getMimeType() );
		$this->setFileSize( Jet\IO_File::getSize( $new_source_file_path ) );

		foreach( $this->thumbnails as $thumbnail ) {
			$thumbnail->setImage($this);
			$thumbnail->recreate();
		}
	}

	/**
	 * @param Gallery $gallery
	 * @param string $source_file_path
	 * @param string|null $source_file_name
	 * @param bool $do_not_save_imindietly
	 *
	 * @return Gallery_Image
	 */
	public static function getNewImage( Gallery $gallery, $source_file_path, $source_file_name=null, $do_not_save_imindietly=false  ) {

		/**
		 * @var Gallery_Image $image
		 */
		$image = new static();
		$image->generateID();

		$image->setGallery($gallery);

		$offset = ceil($image->getAllImagesCount()/1000);
		$offset = $offset ? $offset : 1;

		$image->setOffset( $offset );

		$source_image_file = new Jet\Image( $source_file_path );

		$image->setImageSizeH( $source_image_file->getHeight() );
		$image->setImageSizeW( $source_image_file->getWidth() );
		$image->setFileName( $source_file_name ? $source_file_name : $source_image_file->getFileName() );
		$image->setFileMimeType( $source_image_file->getMimeType() );
		$image->setFileSize( Jet\IO_File::getSize( $source_file_path ) );

		$offset_dir = $image->getOffsetDirPath();

		if( !Jet\IO_Dir::exists($offset_dir) ) {
			Jet\IO_Dir::create( $offset_dir );
		}
		Jet\IO_Dir::create( $image->getDirPath() );
		Jet\IO_Dir::create( $image->getThumbnailsDirPath() );

		Jet\IO_File::copy($source_file_path, $image->getFilePath() );

		$image->validateProperties();
		if(!$do_not_save_imindietly) {
			$image->save();
		}

		return $image;
	}

	/**
	 * @static
	 *
	 * @param string $gallery_ID (optional)
	 *
	 * @return Gallery_Image[]
	 */
	public static function getList( $gallery_ID='' ) {
		$query = array();

		if($gallery_ID) {
			$query['this.gallery_ID'] = $gallery_ID;
		}

		return (new self())->fetchObjects($query);
	}

	/**
	 * @static
	 *
	 * @param string $gallery_ID (optional)
	 *
	 * @return Jet\DataModel_Fetch_Data_Assoc
	 */
	public static function getListAsData( $gallery_ID='' ) {
		/**
		 * @var Jet\DataModel $i;
		 */
		$i = new self();
		$props = $i->getDataModelDefinition()->getProperties();
		$data = $i->fetchDataAssoc($props, array());

		if($gallery_ID) {
			$data->getQuery()->setWhere( array('this.gallery_ID'=>$gallery_ID) );
		}

		return $data;
	}

}