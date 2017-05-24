<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\Articles;

use Jet\DataModel;
use Jet\Locale;
use Jet\Data_DateTime;
use Jet\Mvc;
use Jet\Mvc_Site;
use Jet\Mvc_Router_Interface;
use Jet\Data_Text;
use Jet\DataModel_Fetch_Object_Assoc;
use Jet\Data_Paginator_DataSource;
use Jet\DataModel_Id_UniqueString;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Form_Field_Select;
use Jet\Form_Field_DateTime;

/**
 *
 * @JetDataModel:name = 'Article'
 * @JetDataModel:database_table_name = 'articles'
 * @JetDataModel:id_class_name = 'DataModel_Id_UniqueString'
 */
class Article extends DataModel
{

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
	 * @JetDataModel:type = DataModel::TYPE_LOCALE
	 * @JetDataModel:form_field_is_required = true
	 * @JetDataModel:form_field_label = 'Locale'
	 * @JetDataModel:form_field_get_select_options_callback = ['Mvc_Site','getAllLocalesList']
	 * @JetDataModel:form_field_error_messages = [Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select locale', Form_Field_Select::ERROR_CODE_EMPTY => 'Please select locale']
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
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 65536
	 * @JetDataModel:form_field_label = 'Annotation'
	 *
	 * @var string
	 */
	protected $annotation = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 655360
	 * @JetDataModel:form_field_label = 'Text'
	 * @JetDataModel:form_field_type = Form::TYPE_WYSIWYG
	 *
	 * @var string
	 */
	protected $text = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATE_TIME
	 * @JetDataModel:form_field_label = 'Date and time'
	 * @JetDataModel:form_field_error_messages = [Form_Field_DateTime::ERROR_CODE_INVALID_FORMAT => 'Invalid date and time format']
	 *
	 * @var Data_DateTime
	 */
	protected $date_time;

	/**
	 * @return Article
	 */
	public static function getNew()
	{
		return new self();
	}

	/**
	 *
	 * @param string $id
	 *
	 * @return Article
	 */
	public static function get( $id )
	{

		/** @noinspection PhpIncompatibleReturnTypeInspection */
		return static::load( $id );
	}

	/**
	 *
	 * @param array $query (optional)
	 *
	 * @return Article[]|DataModel_Fetch_Object_Assoc
	 */
	public static function getList( $query = [] )
	{

		/**
		 * @var DataModel_Fetch_Object_Assoc $list
		 */
		$list = ( new self() )->fetchObjects(
			$query, [
				      'ID', 'locale', 'title', 'date_time',
			      ]
		);

		return $list;
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
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
	 * @return string
	 */
	public function getURL()
	{
		return Mvc::getCurrentPage()->getURL( [ $this->getURIFragment() ], [] );
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

		$this->URI_fragment = $this->generateUrlFragment( $this->title, $check_callback, '.html' );
	}

	/**
	 *
	 * @param string $URI_fragment
	 *
	 * @return bool
	 */
	public function getUriFragmentExists( $URI_fragment )
	{
		if( $this->getIsNew() ) {
			$q = [
				'this.URI_fragment' => $URI_fragment,
			];
		} else {
			$q = [
				'this.URI_fragment' => $URI_fragment, 'AND', 'this.id!=' => $this->id,
			];
		}

		return (bool)static::getBackendInstance()->getCount( $this->createQuery( $q ) );
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

	/**
	 * @return string
	 */
	public function getAnnotation()
	{
		return $this->annotation;
	}

	/**
	 * @param string $annotation
	 */
	public function setAnnotation( $annotation )
	{
		$this->annotation = $annotation;
	}

	/**
	 * @return string
	 */
	public function getText()
	{
		return $this->text;
	}

	/**
	 * @param string $text
	 */
	public function setText( $text )
	{
		$this->text = $text;
	}

	/**
	 * @return Data_DateTime
	 */
	public function getDateTime()
	{
		return $this->date_time;
	}

	/**
	 * @param Data_DateTime|string $date_time
	 */
	public function setDateTime( $date_time )
	{
		if( !( $date_time instanceof Data_DateTime ) ) {
			$date_time = new Data_DateTime( $date_time );
		}
		$this->date_time = $date_time;
	}

	/**
	 * @return Article[]|Data_Paginator_DataSource
	 */
	public function getListForCurrentLocale()
	{
		$list = $this->fetchObjects(
			[
				'this.locale' => Mvc::getCurrentLocale(),
			]
		);
		$list->getQuery()->setOrderBy( '-date_time' );

		return $list;
	}

	/**
	 * @param string $path
	 *
	 * @return Article|null
	 */
	public function resolveArticleByURL( $path )
	{
		$current_article = null;
		if( substr( $path, -5 )=='.html' ) {

			$current_article = $this->fetchOneObject(
				[
					'this.URI_fragment' => $path,
				]
			);

		}

		/**
		 * @var Article $current_article
		 */
		return $current_article;
	}
}