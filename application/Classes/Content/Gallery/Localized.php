<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\DataModel;
use Jet\DataModel_Definition;
use Jet\DataModel_Related_1toN;
use Jet\DataModel_IDController_Passive;

use Jet\Form_Definition;
use Jet\Locale;
use Jet\Form_Field;
use Jet\MVC;
use Jet\MVC_Page_Interface;
use Jet\Data_Text;

/**
 *
 */
#[DataModel_Definition(
	name: 'gallery_localized',
	database_table_name: 'image_galleries_localized',
	id_controller_class: DataModel_IDController_Passive::class,
	parent_model_class: Content_Gallery::class
)]
class Content_Gallery_Localized extends DataModel_Related_1toN
{
	/**
	 * @var ?Content_Gallery
	 */
	protected ?Content_Gallery $_gallery = null;

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_ID,
		is_id: true,
		related_to: 'main.id'
	)]
	protected string $gallery_id = '';

	/**
	 * @var ?Locale
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_LOCALE,
		is_id: true,
		do_not_export: true
	)]
	protected ?Locale $locale = null;

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255,
		is_key: true
	)]
	protected string $URI_fragment = '';

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 100,
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_INPUT,
		is_required: true,
		label: 'Title',
		error_messages: [Form_Field::ERROR_CODE_EMPTY => 'Please enter title']
	)]
	protected string $title = '';


	/**
	 *
	 * @param string|null $gallery_id
	 * @param Locale|null $locale
	 */
	public function __construct( string|null $gallery_id = null, ?Locale $locale = null )
	{
		parent::__construct();
		if( $gallery_id ) {
			$this->gallery_id = $gallery_id;
		}

		if( $locale ) {
			$this->locale = $locale;
		}
	}

	/**
	 * @return Content_Gallery
	 */
	public function getGallery() : Content_Gallery
	{
		return $this->_gallery;
	}

	/**
	 * @param Content_Gallery $gallery
	 */
	public function setGallery( Content_Gallery $gallery ) : void
	{
		$this->_gallery = $gallery;
	}


	/**
	 * @return string
	 */
	public function getArrayKeyValue(): string
	{
		return $this->locale->toString();
	}

	/**
	 * @return string
	 */
	public function getGalleryId(): string
	{
		return $this->gallery_id;
	}

	/**
	 * @param string $gallery_id
	 */
	public function setGalleryId( string $gallery_id ): void
	{
		$this->gallery_id = $gallery_id;
	}


	/**
	 * @return Locale
	 */
	public function getLocale() : Locale
	{
		return $this->locale;
	}

	/**
	 * @param Locale|string $locale
	 */
	public function setLocale( Locale|string $locale ): void
	{
		if( !($locale instanceof Locale) ) {
			$locale = new Locale( $locale );
		}
		$this->locale = $locale;
	}

	/**
	 * @param MVC_Page_Interface|null $base_page
	 *
	 * @return string
	 */
	public function getURL( ?MVC_Page_Interface $base_page = null ) : string
	{
		if( !$base_page ) {
			$base_page = MVC::getPage();

		}
		return $base_page->getURL( [$this->getURIFragment()], [] );
	}

	/**
	 * @return string
	 */
	public function getURIFragment() : string
	{
		return $this->URI_fragment;
	}

	/**
	 * @return string
	 */
	public function getTitle() : string
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle( string $title ): void
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
	public function getUriFragmentExists( string $URI_fragment ): bool
	{
		$q = [
			'URI_fragment' => $URI_fragment,
			'AND',
			'locale'       => $this->locale,
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
	 * @param string $URI_fragment
	 *
	 * @param callable $exists_check
	 * @param string $suffix (optional) example: .html
	 * @param bool $remove_accents (optional, default: true)
	 *
	 * @return string
	 */
	public function generateUrlFragment( string $URI_fragment,
	                                     callable $exists_check,
	                                     string $suffix = '',
	                                     bool $remove_accents = true ) : string
	{

		if( $remove_accents ) {
			$URI_fragment = Data_Text::removeAccents( $URI_fragment );
		}

		$URI_fragment = str_replace( ' ', '-', $URI_fragment );
		$URI_fragment = preg_replace( '~([-]{2,})~', '-', $URI_fragment );

		$replace = [
			'!',
			'@',
			'#',
			'$',
			'%',
			'^',
			'&',
			'*',
			'(',
			')',
			'+',
			'=',
			'.',
			'\'',
			'"',
			'/',
			'<',
			'>',
			';',
			'?',
			'{',
			'}',
			'[',
			']',
			'|',
		];
		$URI_fragment = str_replace( $replace, '', $URI_fragment );

		$URI_fragment = rawurlencode( $URI_fragment );

		$max_suffix_no = 9999;

		if( $exists_check( $URI_fragment . $suffix ) ) {
			$_id = substr( $URI_fragment, 0, 255 - strlen( (string)$max_suffix_no ) );

			for( $c = 1; $c <= $max_suffix_no; $c++ ) {
				$URI_fragment = $_id . $c;

				if( !$exists_check( $URI_fragment . $suffix ) ) {
					break;
				}
			}
		}


		return $URI_fragment . $suffix;
	}


}