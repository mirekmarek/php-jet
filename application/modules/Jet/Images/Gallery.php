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
 * @package JetApplicationModule\Jet\Images
 */
namespace JetApplicationModule\Jet\Images;
use Jet;

class Gallery extends Jet\DataModel {

	protected static $__data_model_model_name = "Jet_ImageGallery";

	protected static $__data_model_properties_definition = array(
		"parent_ID" => array(
			"type" => self::TYPE_ID,
			"is_required" => true,
		),
		"ID" => array(
			"type" => self::TYPE_ID,
			"is_ID" => true
		),
		/*
		"locale" => array(
			"type" => self::TYPE_LOCALE,
			"is_required" => true,
			"form_field_label" => "Locale:",
			"form_field_get_select_options_callback" => array("Jet\Mvc", "getAllSitesLocalesList")
		),
		*/
		"title" => array(
			"type" => self::TYPE_STRING,
			"max_len" => 100,
			"is_required" => true,
			"form_field_label" => "Title: ",
		),
		/*
		"annotation" => array(
			"type" => self::TYPE_STRING,
			"max_len" => 65536,
			"form_field_label" => "Annotation:"
		)
		*/
	);

	/**
	 * @var string
	 */
	protected $parent_ID = "";

	/**
	 * @var string
	 */
	protected $ID = "";

	/**
	 * @var string
	 */
	protected $title = "";

	/**
	 * @var Gallery_Image
	 */
	protected $__images;


	/**
	 * @var Gallery
	 */
	protected static $__galleries = array();

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
		$base_dir = JET_PUBLIC_PATH."imagegallery/";
		if(!Jet\IO_Dir::exists($base_dir)) {
			Jet\IO_Dir::create( $base_dir );
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
		$images = $this->getImages();

		if(!$source_file_name) {
			$source_file_name = basename( $source_file_path );
		}

		foreach( $images as $existing_image ) {
			if($existing_image->getFileName()==$source_file_name) {
				if($overwrite_if_exists) {
					$existing_image->overwrite( $source_file_path );

					$existing_image->validateProperties();
					$existing_image->save();

					return $existing_image;
				} else {
					throw new Exception(
						"Image '{$source_file_name}' allready exists in the gallery!",
						Exception::CODE_IMAGE_ALLREADY_EXIST
					);
				}
			}
		}

		$image = Gallery_Image::getNewImage($this, $source_file_path, $source_file_name );
		$image->validateProperties();

		$this->__images[] = $image;

		return $image;

	}

	/**
	 * @return string
	 */
	public function getBaseURI() {
		return JET_PUBLIC_URI."imagegallery/";
	}


	/**
	 * @static
	 * @return Gallery
	 */
	public static function getNew() {
		$instance = new self();
		$instance->initNewObject();

		return $instance;
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

			$instance = new self();
			$ID = $instance->getEmptyIDInstance()->createID($ID);

			if( !($instance = $instance->load($ID)) ) {
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
	 * @return Gallery[]
	 */
	public static function getList() {
		return (new self())->fetchObjects();
	}

	/**
	 * @static
	 *
	 * @return Jet\DataModel_Fetch_Data_Assoc
	 */
	public static function getListAsData() {
		/**
		 * @var Jet\DataModel $i;
		 */
		$i = new self();
		$props = $i->getDataModelDefinition()->getProperties();
		return $i->fetchDataAssoc($props, array());
	}

	/**
	 * @returnJet\Data_Tree
	 */
	public static function getTree() {
		$data = static::getListAsData()->toArray();

		$root = array(
			array(
				"ID"=>"_root_",
				"parent_ID" => "",
				"title" => "Galleries"
			)
		);


		$tree = new Jet\Data_Tree();
		$tree->setData( array_merge($root, $data) );
		$tree->setLabelKey( "title" );

		return $tree;
	}


}