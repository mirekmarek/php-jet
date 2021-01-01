<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\Content\Images;

use Jet\DataModel;
use Jet\DataModel_Definition;
use Jet\DataModel_Related_1toN;
use Jet\DataModel_IDController_Passive;

use Jet\Locale;
use Jet\Mvc;
use Jet\Mvc_Page_Interface;
use Jet\Data_Text;
use Jet\Form_Field_Input;

/**
 *
 */
#[DataModel_Definition(name: 'gallery_localized')]
#[DataModel_Definition(database_table_name: 'image_galleries_localized')]
#[DataModel_Definition(id_controller_class: DataModel_IDController_Passive::class)]
#[DataModel_Definition(parent_model_class: Gallery::class)]
class Gallery_Localized extends DataModel_Related_1toN
{
	/**
	 * @var ?Gallery
	 */
	protected ?Gallery $_gallery = null;

	/**
	 * @var string
	 */
	#[DataModel_Definition(type: DataModel::TYPE_ID)]
	#[DataModel_Definition(is_id: true)]
	#[DataModel_Definition(form_field_type: false)]
	#[DataModel_Definition(related_to: 'main.id')]
	protected string $gallery_id = '';

	/**
	 * @var ?Locale
	 */
	#[DataModel_Definition(type: DataModel::TYPE_LOCALE)]
	#[DataModel_Definition(is_id: true)]
	#[DataModel_Definition(form_field_type: false)]
	#[DataModel_Definition(do_not_export: true)]
	protected ?Locale $locale = null;

	/**
	 * @var string
	 */
	#[DataModel_Definition(type: DataModel::TYPE_STRING)]
	#[DataModel_Definition(max_len: 255)]
	#[DataModel_Definition(form_field_is_required: true)]
	#[DataModel_Definition(form_field_type: false)]
	#[DataModel_Definition(is_key: true)]
	protected string $URI_fragment = '';

	/**
	 * @var string
	 */
	#[DataModel_Definition(type: DataModel::TYPE_STRING)]
	#[DataModel_Definition(max_len: 100)]
	#[DataModel_Definition(form_field_is_required: true)]
	#[DataModel_Definition(form_field_label: 'Title')]
	#[DataModel_Definition(form_field_error_messages: [Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter title'])]
	protected string $title = '';


	/**
	 *
	 * @param string|null $gallery_id
	 * @param Locale|null $locale
	 */
	public function __construct( string|null $gallery_id=null, Locale $locale=null )
	{
		parent::__construct();
		if($gallery_id) {
			$this->gallery_id = $gallery_id;
		}

		if($locale) {
			$this->locale = $locale;
		}
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
	 * @return string|int|null
	 */
	public function getArrayKeyValue(): null|string|int
	{
		return $this->locale->toString();
	}

	/**
	 * @return string
	 */
	public function getGalleryId() : string
	{
		return $this->gallery_id;
	}

	/**
	 * @param string $gallery_id
	 */
	public function setGalleryId( string $gallery_id ) : void
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
	public function setLocale( Locale|string $locale ) : void
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
	public function setTitle( string $title ) : void
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
	public function getUriFragmentExists( string $URI_fragment ) : bool
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
	public function generateUrlFragment( string $URI_fragment,
	                                     callable $exists_check,
	                                     string $suffix = '',
	                                     bool $remove_accents = true )
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