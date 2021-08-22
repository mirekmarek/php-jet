<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Content\Images;

use Jet\DataModel;
use Jet\DataModel_Definition;
use Jet\DataModel_Fetch_Instances;
use Jet\DataModel_Query;
use Jet\DataModel_IDController_UniqueString;
use Jet\DataModel_Related_1toN;
use Jet\DataModel_Related_1toN_Iterator;

use Jet\Form;
use Jet\Form_Field_FileImage;
use Jet\Form_Field_Hidden;

use Jet\SysConf_Path;
use Jet\Tr;
use Jet\Data_Tree;
use Jet\IO_Dir;

use Jet\Locale;

use Jet\Mvc;

use Jet\Mvc_Page_Interface;
use Jet\SysConf_URI;
use JetApplication\Application_Web;

/**
 *
 */
#[DataModel_Definition(
	name: 'gallery',
	database_table_name: 'image_galleries',
	id_controller_class: DataModel_IDController_UniqueString::class,
	relation: [
		'related_to_class_name' => Gallery_Image::class,
		'join_by_properties'    => ['id' => 'gallery_id'],
		'join_type'             => DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN
	]
)]
class Gallery extends DataModel
{

	/**
	 * @var ?Data_Tree;
	 */
	protected static ?Data_Tree $_tree = null;

	/**
	 * @var Gallery[]
	 */
	protected static array $__galleries = [];

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_ID
	)]
	protected string $parent_id = '';

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_ID,
		is_id: true
	)]
	protected string $id = '';

	/**
	 * @var Gallery_Localized[]|DataModel_Related_1toN|DataModel_Related_1toN_Iterator
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_DATA_MODEL,
		data_model_class: Gallery_Localized::class
	)]
	protected $localized = null;


	/**
	 * @var iterable|null
	 */
	protected ?iterable $_images = null;

	/**
	 * @var ?DataModel_Fetch_Instances
	 */
	protected ?DataModel_Fetch_Instances $_children = null;


	/**
	 * @var ?Form
	 */
	protected ?Form $_form_add = null;

	/**
	 * @var ?Form
	 */
	protected ?Form $_form_edit = null;

	/**
	 * @var ?Form
	 */
	protected ?Form $_form_image_upload = null;

	/**
	 * @var ?Form
	 */
	protected ?Form $_form_image_delete = null;

	/**
	 *
	 * @param string $id
	 *
	 * @return static|null
	 */
	public static function get( string $id ): static|null
	{
		if( !$id ) {
			return null;
		}

		if( !isset( static::$__galleries[$id] ) ) {
			$s_id = $id;


			/**
			 * @var Gallery $instance
			 */
			if( !($instance = static::load( $id )) ) {
				return null;
			}

			static::$__galleries[$s_id] = $instance;

			return $instance;
		}

		return static::$__galleries[$id];
	}

	/**
	 *
	 * @return Gallery[]
	 */
	public static function getList(): iterable
	{
		return static::fetchInstances();
	}

	/**
	 *
	 * @return Gallery[]
	 */
	public static function getRootGalleries(): iterable
	{
		return static::fetchInstances( ['parent_id' => ''] );
	}


	/**
	 * @param string $path
	 * @param string|Locale $locale
	 *
	 * @return Gallery|null
	 */
	public static function resolveGalleryByURL( string $path, Locale|string $locale ): Gallery|null
	{

		return static::load(
			[
				'gallery_localized.URI_fragment' => $path,
				'AND',
				'gallery_localized.locale'       => $locale
			]
		);

	}

	/**
	 * @param string $search
	 *
	 * @return Gallery[]
	 */
	public static function search( string $search ): iterable
	{

		$search = '%' . $search . '%';

		return static::fetchInstances(
			[
				'gallery_localized.title *' => $search,
				'OR',
				'image.file_name *'         => $search
			]
		);

	}


	/**
	 * @return Data_Tree
	 */
	public static function getTree(): Data_Tree
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
	public function afterLoad(): void
	{

		foreach( Application_Web::getBase()->getLocales() as $lc_str => $locale ) {

			if( !isset( $this->localized[$lc_str] ) ) {

				$this->localized[$lc_str] = new Gallery_Localized( $this->getId(), $locale );
			}

			$this->localized[$lc_str]->setGallery( $this );
		}

	}


	/**
	 * @return string
	 */
	public function getId(): string
	{
		return $this->id;
	}


	/**
	 *
	 * @return Gallery[]
	 */
	public function getChildren(): iterable
	{
		if( $this->_children === null ) {
			$this->_children = static::fetchInstances( ['parent_id' => $this->id] );
		}

		return $this->_children;
	}

	/**
	 * @param Locale|null $locale
	 *
	 * @return Gallery_Localized
	 */
	public function getLocalized( Locale $locale = null ): Gallery_Localized
	{
		if( !$locale ) {
			$locale = Mvc::getCurrentLocale();
		}
		return $this->localized[$locale->toString()];
	}

	/**
	 * @return string
	 */
	public function getTitle(): string
	{
		return $this->getLocalized()->getTitle();
	}

	/**
	 * @param Mvc_Page_Interface|null $base_page
	 *
	 * @return string
	 */
	public function getURL( ?Mvc_Page_Interface $base_page = null ): string
	{
		return $this->getLocalized()->getURL( $base_page );
	}


	/**
	 * @return string
	 */
	public function getParentId(): string
	{
		return $this->parent_id;
	}

	/**
	 * @param string $parent_id
	 */
	public function setParentId( string $parent_id ): void
	{
		$this->parent_id = (string)$parent_id;
	}

	/**
	 * @return Gallery|null
	 */
	public function getParent(): Gallery|null
	{
		if( !$this->parent_id ) {
			return null;
		}

		return Gallery::get( $this->parent_id );
	}

	/**
	 * @return Gallery[]
	 */
	public function getPath(): array
	{
		$parent = $this;

		$path = [];

		do {
			array_unshift( $path, $parent );
		} while( ($parent = $parent->getParent()) );

		return $path;

	}

	/**
	 * @param string $file_name
	 *
	 * @return bool|Gallery_Image
	 */
	public function getImageExists( string $file_name ): bool|Gallery_Image
	{

		foreach( $this->getImages() as $existing_image ) {
			if( $existing_image->getFileName() == $file_name ) {
				return $existing_image;
			}
		}

		return false;
	}

	/**
	 * @return Gallery_Image[]
	 */
	public function getImages(): iterable
	{
		if( $this->_images === null ) {
			$this->_images = Gallery_Image::getList( $this->id );
		}

		return $this->_images;
	}

	/**
	 * @param string $source_file_path
	 * @param null|string $source_file_name
	 *
	 * @return Gallery_Image
	 */
	public function addImage( string $source_file_path, ?string $source_file_name = null ): Gallery_Image
	{

		if( !$source_file_name ) {
			$source_file_name = basename( $source_file_path );
		}

		$pi = pathinfo( $source_file_name );

		$i = 0;
		while( ($existing_image = $this->getImageExists( $source_file_name )) ) {
			$i++;

			$source_file_name = $pi['filename'] . '_' . $i . '.' . $pi['extension'];
		}

		$image = Gallery_Image::getNewImage( $this, $source_file_path, $source_file_name );

		$this->_images[] = $image;

		return $image;

	}


	/**
	 * @return string
	 */
	public function getBaseDirPath(): string
	{
		$base_dir = SysConf_Path::getImages() . 'gallery/';
		if( !IO_Dir::exists( $base_dir ) ) {
			IO_Dir::create( $base_dir );
		}

		return $base_dir;
	}

	/**
	 * @return string
	 */
	public function getBaseURI(): string
	{
		return SysConf_URI::getImages() . 'gallery/';
	}


	/**
	 * @return Form
	 */
	public function getEditForm(): Form
	{
		if( !$this->_form_edit ) {
			$this->_form_edit = $this->getCommonForm();

			$this->_form_edit->getField( 'parent_id' )->setValidator( function( Form_Field_Hidden $field ) {

				$parent_id = $field->getValue();
				if( !$parent_id ) {
					return true;
				}

				if( !Gallery::get( $parent_id ) ) {
					$field->setCustomError( Tr::_( 'Unknown parent' ), 'UNKNOWN_PARENT' );
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
	public function catchEditForm(): bool
	{
		return $this->getEditForm()->catch();
	}


	/**
	 * @return Form
	 */
	public function getAddForm(): Form
	{
		if( !$this->_form_add ) {

			$this->_form_add = $this->getCommonForm();
			$this->_form_add->getField( 'parent_id' )->setValidator( function( Form_Field_Hidden $field ) {

				$parent_id = $field->getValue();
				if( !$parent_id ) {
					return true;
				}

				if( !Gallery::get( $parent_id ) ) {
					$field->setCustomError( Tr::_( 'Unknown parent' ), 'UNKNOWN_PARENT' );
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
	public function catchAddForm(): bool
	{
		return $this->getAddForm()->catch();
	}

	/**
	 * @return Form
	 */
	public function getImageUploadForm(): Form
	{
		if( !$this->_form_image_upload ) {
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
				'gallery_image_upload', [$image_field]
			);

		}


		return $this->_form_image_upload;
	}

	/**
	 * @param bool $force_catch
	 *
	 * @return bool|Gallery_Image[]
	 */
	public function catchImageUploadForm( bool $force_catch = false ): bool|array
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
			if( is_array( $tmp_file_paths ) ) {
				foreach( $tmp_file_paths as $i => $tmp_file_path ) {
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
	public function delete(): void
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