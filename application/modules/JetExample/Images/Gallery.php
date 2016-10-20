<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\Images;

use Jet\DataModel;
use Jet\DataModel_Fetch_Data_Assoc;
use Jet\DataModel_Fetch_Object_Assoc;
use Jet\Form;
use Jet\Form_Field_Checkbox;
use Jet\Form_Field_FileImage;
use Jet\Tr;
use Jet\Data_Tree;
use Jet\IO_Dir;

/**
 * Class Gallery
 *
 * @JetDataModel:name = 'ImageGallery'
 * @JetDataModel:database_table_name = 'Jet_ImageGalleries'
 * @JetDataModel:ID_class_name = 'DataModel_ID_UniqueString'
 *
 * @JetDataModel:relation = ['module:JetExample.Images\Gallery_Image', ['ID'=>'gallery_ID'], DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN ]
 */
class Gallery extends DataModel {

	const ROOT_ID = '_root_';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID
	 *
	 * @var string
	 */
	protected $parent_ID = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID
	 * @JetDataModel:is_ID = true
	 *
	 * @var string
	 */
	protected $ID = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 100
	 * @JetDataModel:form_field_is_required = true
	 * @JetDataModel:form_field_label = 'Title'
     * @JetDataModel:form_field_error_messages = [Form_Field_Input::ERROR_CODE_EMPTY => 'Please type title']
	 *
	 * @var string
	 */
	protected $title = '';


	/**
	 * @var Gallery_Image
	 */
	protected $__images;


	/**
	 * @var Gallery
	 */
	protected static $__galleries = [];

	/**
	 * @param string $parent_ID
	 */
	public function setParentID($parent_ID) {
		$this->parent_ID = (string)$parent_ID;
	}

	/**
	 * @return string
	 */
	public function getParentID() {
		return $this->parent_ID;
	}



	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = (string)$title;
	}


	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}


	/**
	 * @return string
	 */
	public function getBaseDirPath() {
		$base_dir = JET_PUBLIC_PATH.'imagegallery/';
		if(!IO_Dir::exists($base_dir)) {
			IO_Dir::create( $base_dir );
		}

		return $base_dir;
	}

	/**
	 * @return Gallery_Image[]
	 */
	public function getImages() {
		if($this->__images===null) {
			$this->__images=Gallery_Image::getList( $this->ID );
		}
		return $this->__images;
	}

	/**
	 * @param $source_file_path
	 * @param null|string $source_file_name
	 * @param bool $overwrite_if_exists (optional)
	 *
	 * @throws Exception
	 * @return Gallery_Image
	 */
	public function addImage( $source_file_path, $source_file_name=null, $overwrite_if_exists=false ) {

		if(!$source_file_name) {
			$source_file_name = basename( $source_file_path );
		}

		$existing_image = $this->getImageExists( $source_file_name );

		if($existing_image) {
			if($overwrite_if_exists) {
				$existing_image->overwrite( $source_file_path );

				$existing_image->save();

				return $existing_image;

			} else {
				throw new Exception(
					'Image \''.$source_file_name.'\' allready exists in the gallery!',
					Exception::CODE_IMAGE_ALLREADY_EXIST
				);

			}
		}

		$image = Gallery_Image::getNewImage($this, $source_file_path, $source_file_name );

		$this->__images[] = $image;

		return $image;

	}

	/**
	 * @param string $file_name
	 *
	 * @return bool|Gallery_Image
	 */
	public function getImageExists( $file_name ) {

		foreach( $this->getImages() as $existing_image ) {
			if($existing_image->getFileName()==$file_name) {
				return $existing_image;
			}
		}

		return false;
	}


	/**
	 * @return Form
	 */
	public function getUploadForm() {
        $image_field = new Form_Field_FileImage('file', 'Upload image' );
        $image_field->setIsRequired(true);
        $image_field->setErrorMessages([
            Form_Field_FileImage::ERROR_CODE_EMPTY => 'Please select image',
            Form_Field_FileImage::ERROR_CODE_DISALLOWED_FILE_TYPE => 'Uploaded file is not supported image'
        ]);

		$form = new Form('gallery_image_upload', [
            $image_field,
			new Form_Field_Checkbox('overwrite_if_exists', 'Overwrite image if exists' )
		]);

		return $form;
	}

	/**
	 * @param Form $form
	 * @param bool $force_catch
	 *
	 * @return bool|Gallery_Image
	 */
	public function catchUploadForm( Form $form, $force_catch=false ) {

		if(
			!$form->catchValues(null, $force_catch) ||
			!$form->validateValues()
		) {
			return false;
		}

		$overwrite_if_exists = $form->getField('overwrite_if_exists')->getValue();


		/**
		 * @var Form_Field_FileImage $img_field
		 */
		$img_field = $form->getField('file');

		$tmp_file_path = $img_field->getTmpFilePath();
		$file_name = $img_field->getFileName();

		if(
			!$overwrite_if_exists &&
			$this->getImageExists( $file_name )
		) {
			$form->setCommonMessage( Tr::_('Image is already uploaded. Use \'Overwrite image if exists\' option if you want to overwrite it. ') );

			return false;
		}

		try {
			$image = $this->addImage(
				$tmp_file_path,
				$file_name,
				$overwrite_if_exists
			);
		} catch( Exception $e ) {
			$form->setCommonMessage( Tr::_($e->getMessage()) );

			return false;
		}

		return $image;

	}

	/**
	 * @return string
	 */
	public function getBaseURI() {
		return JET_PUBLIC_URI.'imagegallery/';
	}


	/**
	 * @static
	 * @return Gallery
	 */
	public static function getNew() {
		return new self();
	}

	/**
	 * @static
	 *
	 * @param string $ID
	 *
	 * @return Gallery
	 */
	public static function get( $ID ) {
		if(!isset(static::$__galleries[$ID])) {
			$s_ID = $ID;


			/**
			 * @var Gallery $instance
			 */
			if( !($instance = static::load($ID)) ) {
				return null;
			}

			static::$__galleries[$s_ID]=$instance;

			return $instance;
		}

		return static::$__galleries[$ID];
	}

	/**
	 * @static
	 *
	 * @return DataModel_Fetch_Object_Assoc|Gallery[]
	 */
	public static function getList() {
		return (new self())->fetchObjects();
	}

	/**
	 * @static
	 *
	 * @return DataModel_Fetch_Data_Assoc
	 */
	public static function getListAsData() {
		/**
		 * @var DataModel $i;
		 */
		$i = new self();
		$props = $i->getDataModelDefinition()->getProperties();
		return $i->fetchDataAssoc($props, []);
	}

	/**
	 * @param string $parent_ID
	 *
	 * @return DataModel_Fetch_Object_Assoc|Gallery[]
	 */
	public static function getChildren( $parent_ID ) {
		return (new self())->fetchObjects( [[ 'this.parent_ID'=>$parent_ID ]]);
	}

	/**
	 * @param string $title
	 * @param string $parent_ID
	 *
	 * @return Gallery|null
	 */
	public static function getByTitle( $title, $parent_ID ) {
		/** @noinspection PhpIncompatibleReturnTypeInspection */
		return (new self())->fetchOneObject( [[ 'this.title'=>$title, 'AND', 'this.parent_ID'=>$parent_ID ]]);
	}


	/**
	 * @return Data_Tree
	 */
	public static function getTree() {
		$data = (new self())->getListAsData()->toArray();



		$tree = new Data_Tree();
		$tree->getRootNode()->setID(Gallery::ROOT_ID);

		$tree->getRootNode()->setLabel( Tr::_('Galleries') );

		$tree->setData( $data );
		$tree->setLabelKey( 'title' );

		return $tree;
	}


}