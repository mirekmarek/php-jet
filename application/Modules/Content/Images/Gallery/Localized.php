<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\Content\Images;

use Jet\DataModel;
use Jet\DataModel_Related_1toN;
use Jet\DataModel_IDController_Passive;

use Jet\Locale;
use Jet\Mvc;
use Jet\Mvc_Page_Interface;
use Jet\Data_Text;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Form_Field_Select;
use Jet\Form_Field_DateTime;

/**
 *
 * @JetDataModel:name = 'gallery_localized'
 * @JetDataModel:database_table_name = 'image_galleries_localized'
 * @JetDataModel:id_controller_class_name = 'DataModel_IDController_Passive'
 * @JetDataModel:parent_model_class_name = 'Gallery'
 */
class Gallery_Localized extends DataModel_Related_1toN
{
	/**
	 * @var Gallery
	 */
	protected $_gallery;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID
	 * @JetDataModel:is_id = true
	 * @JetDataModel:form_field_type = false
	 * @JetDataModel:related_to = 'main.id'
	 *
	 * @var string
	 */
	protected $gallery_id = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_LOCALE
	 * @JetDataModel:is_id = true
	 * @JetDataModel:form_field_type = false
	 * @JetDataModel:do_not_export = true
	 *
	 * @var Locale
	 */
	protected $locale;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_is_required = true
	 * @JetDataModel:form_field_type = false
	 * @JetDataModel:is_key = true
	 *
	 * @var string
	 */
	protected $URI_fragment = '';

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
	 *
	 * @param string|null $gallery_id
	 * @param Locale|null $locale
	 */
	public function __construct( $gallery_id=null, Locale $locale=null )
	{
		parent::__construct();
		$this->gallery_id = $gallery_id;
		$this->locale = $locale;
	}

	/**
	 * @return Gallery
	 */
	public function getGallery()
	{
		return $this->_gallery;
	}

	/**
	 * @param Gallery $gallery
	 */
	public function setArticle( Gallery $gallery )
	{
		$this->_gallery = $gallery;
	}


	/**
	 * @return string
	 */
	public function getArrayKeyValue()
	{
		return $this->locale->toString();
	}

	/**
	 * @return string
	 */
	public function getGalleryId()
	{
		return $this->gallery_id;
	}

	/**
	 * @param string $gallery_id
	 */
	public function setGalleryId( $gallery_id )
	{
		$this->gallery_id = $gallery_id;
	}


	/**
	 * @return Locale
	 */
	public function getLocale()
	{
		return $this->locale;
	}

	/**
	 * @param Locale|string $locale
	 */
	public function setLocale( $locale )
	{
		if( !( $locale instanceof Locale ) ) {
			$locale = new Locale( $locale );
		}

		$this->locale = $locale;
	}

	/**
	 * @param Mvc_Page_Interface|null $base_page
	 *
	 * @return string
	 */
	public function getURL( Mvc_Page_Interface $base_page=null )
	{
		if(!$base_page) {
			$base_page = Mvc::getCurrentPage();

		}
		return $base_page->getURL( [ $this->getURIFragment() ], [] );
	}

	/**
	 * @return string
	 */
	public function getURIFragment()
	{
		return $this->URI_fragment;
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
		$this->title = $title;

		$article_i = $this;

		$check_callback = function( $URI_fragment ) use ( $article_i ) {
			return $article_i->getUriFragmentExists( $URI_fragment );
		};

		$this->URI_fragment = $this->generateUrlFragment( $this->title, $check_callback );
	}

	/**
	 *
	 * @param string $URI_fragment
	 *
	 * @return bool
	 */
	public function getUriFragmentExists( $URI_fragment )
	{
		$q = [
			'URI_fragment' => $URI_fragment,
			'AND',
			'locale' => $this->locale,
		];

		if( !$this->getIsNew() ) {
			$q[] = 'AND';
			$q['gallery_id!='] = $this->gallery_id;
		}

		return (bool)static::getBackendInstance()->getCount( static::createQuery( $q ) );
	}

	/**
	 * Generates URI fragment:
	 *
	 *
	 * @param string   $URI_fragment
	 *
	 * @param callable $exists_check
	 * @param string   $suffix (optional) example: .html
	 * @param bool     $remove_accents (optional, default: true)
	 *
	 * @return string
	 */
	public function generateUrlFragment( $URI_fragment, callable $exists_check, $suffix = '', $remove_accents = true )
	{

		if( $remove_accents ) {
			$URI_fragment = Data_Text::removeAccents( $URI_fragment );
		}

		$URI_fragment = str_replace( ' ', '-', $URI_fragment );
		$URI_fragment = preg_replace( '~([-]{2,})~', '-', $URI_fragment );

		$replace = [
			'!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '.', '\'', '"', '/', '<', '>', ';', '?', '{',
			'}', '[', ']', '|',
		];
		$URI_fragment = str_replace( $replace, '', $URI_fragment );

		$URI_fragment = rawurlencode( $URI_fragment );

		$max_suffix_no = 9999;

		if( $exists_check( $URI_fragment.$suffix ) ) {
			$_id = substr( $URI_fragment, 0, 255-strlen( (string)$max_suffix_no ) );

			for( $c = 1; $c<=$max_suffix_no; $c++ ) {
				$URI_fragment = $_id.$c;

				if( !$exists_check( $URI_fragment.$suffix ) ) {
					break;
				}
			}
		}


		return $URI_fragment.$suffix;
	}


}