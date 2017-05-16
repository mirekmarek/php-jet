<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Images;

use Jet\DataModel;
use Jet\DataModel_Fetch_Data_Assoc;
use Jet\DataModel_Fetch_Object_Assoc;
use Jet\DataModel_Query;
use Jet\DataModel_Id_UniqueString;

use Jet\Form;
use Jet\Form_Field_Checkbox;
use Jet\Form_Field_FileImage;
use Jet\Form_Field_Input;

use Jet\Tr;
use Jet\Data_Tree;
use Jet\IO_Dir;

/**
 *
 * @JetDataModel:name = 'ImageGallery'
 * @JetDataModel:database_table_name = 'image_galleries'
 * @JetDataModel:id_class_name = 'DataModel_Id_UniqueString'
 *
 * @JetDataModel:relation = ['Gallery_Image', ['id'=>'gallery_id'], DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN ]
 */
class Gallery extends DataModel
{

	const ROOT_ID = '_root_';
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
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 100
	 * @JetDataModel:form_field_is_required = true
	 * @JetDataModel:form_field_label = 'Title'
	 * @JetDataModel:form_field_error_messages = [Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter title']
	 *
	 * @var string
	 */
	protected $title = '';
	/**
	 * @var Gallery_Image
	 */
	protected $__images;

	/**
	 * @return Gallery
	 */
	public static function getNew()
	{
		return new self();
	}

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
	 * @return DataModel_Fetch_Object_Assoc|Gallery[]
	 */
	public static function getList()
	{
		return ( new self() )->fetchObjects();
	}

	/**
	 *
	 * @return DataModel_Fetch_Data_Assoc
	 */
	public static function getListAsData()
	{
		/**
		 * @var DataModel $i ;
		 */
		$i = new self();
		$props = $i->getDataModelDefinition()->getProperties();

		return $i->fetchDataAssoc( $props, [] );
	}

	/**
	 * @param string $parent_id
	 *
	 * @return DataModel_Fetch_Object_Assoc|Gallery[]
	 */
	public static function getChildren( $parent_id )
	{
		return ( new self() )->fetchObjects( [ [ 'this.parent_id' => $parent_id ] ] );
	}

	/**
	 * @param string $title
	 * @param string $parent_id
	 *
	 * @return Gallery|null
	 */
	public static function getByTitle( $title, $parent_id )
	{
		/** @noinspection PhpIncompatibleReturnTypeInspection */
		return ( new self() )->fetchOneObject( [ [ 'this.title' => $title, 'AND', 'this.parent_id' => $parent_id ] ] );
	}

	/**
	 * @return Data_Tree
	 */
	public static function getTree()
	{
		if( !static::$_tree ) {
			$data = ( new self() )->fetchObjects( [] );

			static::$_tree = new Data_Tree();
			static::$_tree->setLabelGetterMethodName( 'getTitle' );
			static::$_tree->getRootNode()->setId( Gallery::ROOT_ID );
			static::$_tree->getRootNode()->setLabel( Tr::_( 'Galleries' ) );
			static::$_tree->setDataSource( $data );
		}


		return static::$_tree;
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
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
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle( $title )
	{
		$this->title = (string)$title;
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
	 * @return Form
	 */
	public function getUploadForm()
	{
		$image_field = new Form_Field_FileImage( 'file', 'Upload image' );
		$image_field->setIsRequired( true );
		$image_field->setErrorMessages(
			[
				Form_Field_FileImage::ERROR_CODE_EMPTY                => 'Please select image',
				Form_Field_FileImage::ERROR_CODE_DISALLOWED_FILE_TYPE => 'Uploaded file is not supported image',
			]
		);

		$form = new Form(
			'gallery_image_upload', [
				                      $image_field,
				                      new Form_Field_Checkbox( 'overwrite_if_exists', 'Overwrite image if exists' ),
			                      ]
		);

		return $form;
	}

	/**
	 * @param Form $form
	 * @param bool $force_catch
	 *
	 * @return bool|Gallery_Image
	 */
	public function catchUploadForm( Form $form, $force_catch = false )
	{

		if( !$form->catchInput( null, $force_catch )||!$form->validate() ) {
			return false;
		}

		$overwrite_if_exists = $form->getField( 'overwrite_if_exists' )->getValue();


		/**
		 * @var Form_Field_FileImage $img_field
		 */
		$img_field = $form->getField( 'file' );

		$tmp_file_path = $img_field->getTmpFilePath();
		$file_name = $img_field->getFileName();

		if( !$overwrite_if_exists&&$this->getImageExists( $file_name ) ) {
			$form->setCommonMessage(
				Tr::_(
					'Image is already uploaded. Use \'Overwrite image if exists\' option if you want to overwrite it. '
				)
			);

			return false;
		}

		try {
			$image = $this->addImage(
				$tmp_file_path, $file_name, $overwrite_if_exists
			);
		} catch( Exception $e ) {
			$form->setCommonMessage( Tr::_( $e->getMessage() ) );

			return false;
		}

		return $image;

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
		if( $this->__images===null ) {
			$this->__images = Gallery_Image::getList( $this->id );
		}

		return $this->__images;
	}

	/**
	 * @param             $source_file_path
	 * @param null|string $source_file_name
	 * @param bool        $overwrite_if_exists (optional)
	 *
	 * @throws Exception
	 * @return Gallery_Image
	 */
	public function addImage( $source_file_path, $source_file_name = null, $overwrite_if_exists = false )
	{

		if( !$source_file_name ) {
			$source_file_name = basename( $source_file_path );
		}

		$existing_image = $this->getImageExists( $source_file_name );

		if( $existing_image ) {
			if( $overwrite_if_exists ) {
				$existing_image->overwrite( $source_file_path );

				$existing_image->save();

				return $existing_image;

			} else {
				throw new Exception(
					'Image \''.$source_file_name.'\' already exists in the gallery!',
					Exception::CODE_IMAGE_ALREADY_EXIST
				);

			}
		}

		$image = Gallery_Image::getNewImage( $this, $source_file_path, $source_file_name );

		$this->__images[] = $image;

		return $image;

	}

	/**
	 * @return string
	 */
	public function getBaseURI()
	{
		return JET_URI_PUBLIC.'imagegallery/';
	}


}