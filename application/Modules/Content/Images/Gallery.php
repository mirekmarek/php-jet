<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\Content\Images;

use Jet\DataModel;
use Jet\DataModel_Fetch_Instances;
use Jet\DataModel_Query;
use Jet\DataModel_IDController_UniqueString;
use Jet\DataModel_Related_1toN;
use Jet\DataModel_Related_1toN_Iterator;

use Jet\Form;
use Jet\Form_Field_FileImage;
use Jet\Form_Field_Hidden;

use Jet\Tr;
use Jet\Data_Tree;
use Jet\IO_Dir;

use Jet\Locale;

use Jet\Mvc;

use Jet\Mvc_Page_Interface;
use JetApplication\Application_Web;

/**
 *
 * @JetDataModel:name = 'gallery'
 * @JetDataModel:database_table_name = 'image_galleries'
 * @JetDataModel:id_controller_class_name = 'DataModel_IDController_UniqueString'
 *
 * @JetDataModel:relation = ['Gallery_Image', ['id'=>'gallery_id'], DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN ]
 */
class Gallery extends DataModel
{

	/**
	 * @var Data_Tree;
	 */
	protected static $_tree;
	/**
	 * @var Gallery
	 */
	protected static $__galleries = [];

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID
	 *
	 * @var string
	 */
	protected $parent_id = '';
	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID
	 * @JetDataModel:is_id = true
	 *
	 * @var string
	 */
	protected $id = '';

	/**
	 * @JetDataModel:type = DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Gallery_Localized'
	 *
	 * @var Gallery_Localized[]|DataModel_Related_1toN|DataModel_Related_1toN_Iterator
	 */
	protected $localized;


	/**
	 * @var Gallery_Image[]
	 */
	protected $_images;

	/**
	 * @var Gallery[]
	 */
	protected $_children;


	/**
	 * @var Form
	 */
	protected $_form_add;
	/**
	 * @var Form
	 */
	protected $_form_edit;
	/**
	 * @var Form
	 */
	protected $_form_image_upload;
	/**
	 * @var Form
	 */
	protected $_form_image_delete;

	/**
	 *
	 * @param string $id
	 *
	 * @return Gallery
	 */
	public static function get( $id )
	{
		if( !isset( static::$__galleries[$id] ) ) {
			$s_id = $id;


			/**
			 * @var Gallery $instance
			 */
			if( !( $instance = static::load( $id ) ) ) {
				return null;
			}

			static::$__galleries[$s_id] = $instance;

			return $instance;
		}

		return static::$__galleries[$id];
	}

	/**
	 *
	 * @return DataModel_Fetch_Instances|Gallery[]
	 */
	public static function getList()
	{
		return static::fetchInstances();
	}

	/**
	 *
	 * @return DataModel_Fetch_Instances|Gallery[]
	 */
	public static function getRootGalleries()
	{
		return static::fetchInstances(['parent_id'=>'']);
	}



	/**
	 * @param string        $path
	 * @param string|Locale $locale
	 *
	 * @return Gallery|null
	 */
	public static function resolveGalleryByURL( $path, $locale )
	{

		$gallery = static::load(
				[
					'gallery_localized.URI_fragment' => $path,
					'AND',
					'gallery_localized.locale' => $locale
				]
			);



		/**
		 * @var Gallery $gallery
		 */
		return $gallery;
	}

	/**
	 * @param string $search
	 *
	 * @return Gallery[]
	 */
	public static function search( $search )
	{

		$search = '%'.$search.'%';

		$result = static::fetchInstances(
			[
				'gallery_localized.title *' => $search,
			    'OR',
				'image.file_name *' => $search
			]
		);

		/**
		 * @var Gallery[] $result
		 */
		return $result;
	}


	/**
	 * @return Data_Tree
	 */
	public static function getTree()
	{
		if( !static::$_tree ) {
			$data = static::fetchInstances();

			static::$_tree = new Data_Tree();
			static::$_tree->setLabelGetterMethodName( 'getTitle' );
			static::$_tree->getRootNode()->setLabel( Tr::_( 'Galleries' ) );
			static::$_tree->setDataSource( $data );
		}


		return static::$_tree;
	}


	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		$this->afterLoad();
	}

	/**
	 *
	 */
	public function afterLoad()
	{

		foreach( Application_Web::getSite()->getLocales() as $lc_str => $locale) {

			if (!isset($this->localized[$lc_str])) {

				$this->localized[$lc_str] = new Gallery_Localized($this->getId(), $locale);
			}

			$this->localized[$lc_str]->setArticle( $this );
		}

	}


	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}


	/**
	 *
	 * @return DataModel_Fetch_Instances|Gallery[]
	 */
	public function getChildren()
	{
		if($this->_children===null) {
			$this->_children = static::fetchInstances( [ 'parent_id' => $this->id ] );
		}

		return $this->_children;
	}

	/**
	 * @param Locale|null $locale
	 *
	 * @return Gallery_Localized
	 */
	public function getLocalized( Locale $locale=null )
	{
		if(!$locale) {
			$locale = Mvc::getCurrentLocale();
		}
		return $this->localized[$locale->toString()];
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->getLocalized()->getTitle();
	}

	/**
	 * @param Mvc_Page_Interface|null $base_page
	 *
	 * @return string
	 */
	public function getURL( Mvc_Page_Interface $base_page=null )
	{
		return $this->getLocalized()->getURL( $base_page );
	}


	/**
	 * @return string
	 */
	public function getParentId()
	{
		return $this->parent_id;
	}

	/**
	 * @param string $parent_id
	 */
	public function setParentId( $parent_id )
	{
		$this->parent_id = (string)$parent_id;
	}

	/**
	 * @return Gallery|null
	 */
	public function getParent()
	{
		if(!$this->parent_id) {
			return null;
		}

		return Gallery::get($this->parent_id);
	}

	/**
	 * @return Gallery[]
	 */
	public function getPath()
	{
		$parent = $this;

		$path = [];

		do {
			array_unshift( $path, $parent );
		} while( ($parent=$parent->getParent()) );

		return $path;

	}

	/**
	 * @param string $file_name
	 *
	 * @return bool|Gallery_Image
	 */
	public function getImageExists( $file_name )
	{

		foreach( $this->getImages() as $existing_image ) {
			if( $existing_image->getFileName()==$file_name ) {
				return $existing_image;
			}
		}

		return false;
	}

	/**
	 * @return Gallery_Image[]
	 */
	public function getImages()
	{
		if( $this->_images===null ) {
			$this->_images = Gallery_Image::getList( $this->id );
		}

		return $this->_images;
	}

	/**
	 * @param             $source_file_path
	 * @param null|string $source_file_name
	 *
	 * @throws Exception
	 * @return Gallery_Image
	 */
	public function addImage( $source_file_path, $source_file_name = null  )
	{

		if( !$source_file_name ) {
			$source_file_name = basename( $source_file_path );
		}

		$pi = pathinfo($source_file_name);

		$i = 0;
		while( ($existing_image = $this->getImageExists( $source_file_name )) ) {
			$i++;

			$source_file_name = $pi['filename'].'_'.$i.'.'.$pi['extension'];
		}

		$image = Gallery_Image::getNewImage( $this, $source_file_path, $source_file_name );

		$this->_images[] = $image;

		return $image;

	}


	/**
	 * @return string
	 */
	public function getBaseDirPath()
	{
		$base_dir = JET_PATH_PUBLIC.'imagegallery/';
		if( !IO_Dir::exists( $base_dir ) ) {
			IO_Dir::create( $base_dir );
		}

		return $base_dir;
	}

	/**
	 * @return string
	 */
	public function getBaseURI()
	{
		return JET_URI_PUBLIC.'imagegallery/';
	}


	/**
	 * @return Form
	 */
	public function getEditForm()
	{
		if(!$this->_form_edit) {
			$this->_form_edit = $this->getCommonForm();

			$this->_form_edit->getField('parent_id')->setValidator( function( Form_Field_Hidden $field ) {

				$parent_id = $field->getValue();
				if(!$parent_id) {
					return true;
				}

				if(!Gallery::get($parent_id)) {
					$field->setCustomError(Tr::_('Unknown parent'), 'UNKNOWN_PARENT');
					return false;
				}
				return true;

			} );

		}

		return $this->_form_edit;
	}

	/**
	 * @return bool
	 */
	public function catchEditForm()
	{
		return $this->catchForm( $this->getEditForm() );
	}


	/**
	 * @return Form
	 */
	public function getAddForm()
	{
		if(!$this->_form_add) {

			$this->_form_add = $this->getCommonForm();
			$this->_form_add->getField('parent_id')->setValidator( function( Form_Field_Hidden $field ) {

				$parent_id = $field->getValue();
				if(!$parent_id) {
					return true;
				}

				if(!Gallery::get($parent_id)) {
					$field->setCustomError(Tr::_('Unknown parent'), 'UNKNOWN_PARENT');
					return false;
				}
				return true;

			} );
		}

		return $this->_form_add;
	}

	/**
	 * @return bool
	 */
	public function catchAddForm()
	{
		return $this->catchForm( $this->getAddForm() );
	}

	/**
	 * @return Form
	 */
	public function getImageUploadForm()
	{
		if(!$this->_form_image_upload) {
			$image_field = new Form_Field_FileImage( 'file', 'Upload image' );
			$image_field->setIsRequired( true );
			$image_field->setErrorMessages(
				[
					Form_Field_FileImage::ERROR_CODE_EMPTY                => 'Please select image',
					Form_Field_FileImage::ERROR_CODE_FILE_IS_TOO_LARGE    => 'File is too large',
					Form_Field_FileImage::ERROR_CODE_DISALLOWED_FILE_TYPE => 'Uploaded file is not supported image',
				]
			);
			$image_field->setMaximalSize(
				Config::getDefaultMaxW(),
				Config::getDefaultMaxH()
			);

			$image_field->setAllowMultipleUpload( true );

			$this->_form_image_upload = new Form(
				'gallery_image_upload', [ $image_field ]
			);

		}


		return $this->_form_image_upload;
	}

	/**
	 * @param bool $force_catch
	 *
	 * @return bool|Gallery_Image[]
	 */
	public function catchImageUploadForm( $force_catch = false )
	{

		$form = $this->getImageUploadForm();

		if(
			!$form->catchInput( null, $force_catch ) ||
			!$form->validate()
		) {
			return false;
		}



		/**
		 * @var Form_Field_FileImage $img_field
		 */
		$img_field = $form->getField( 'file' );

		$tmp_file_paths = $img_field->getTmpFilePath();
		$file_names = $img_field->getFileName();

		$new_images = [];

		try {
			if(is_array($tmp_file_paths)) {
				foreach( $tmp_file_paths as $i=>$tmp_file_path ) {
					$new_images[] = $this->addImage(
						$tmp_file_path,
						$file_names[$i]
					);
				}
			} else {
				$new_images[] = $this->addImage(
					$tmp_file_paths,
					$file_names
				);
			}
		} catch( Exception $e ) {
			$form->setCommonMessage( Tr::_( $e->getMessage() ) );

			return false;
		}

		$this->_images = null;


		return $new_images;

	}


	/**
	 *
	 */
	public function delete()
	{
		foreach( $this->getImages() as $image ) {
			$image->delete();
		}

		foreach( $this->getChildren() as $ch ) {
			$ch->delete();
		}

		parent::delete();
	}
}