<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\Content\Articles;

use Jet\DataModel;
use Jet\DataModel_Related_1toN;
use Jet\DataModel_IDController_Passive;

use Jet\Locale;
use Jet\Mvc;
use Jet\Data_Text;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Form_Field_Select;
use Jet\Form_Field_DateTime;

/**
 *
 * @JetDataModel:name = 'article_localized'
 * @JetDataModel:database_table_name = 'articles_localized'
 * @JetDataModel:id_controller_class_name = 'DataModel_IDController_Passive'
 * @JetDataModel:parent_model_class_name = 'Article'
 */
class Article_Localized extends DataModel_Related_1toN
{
	/**
	 * @var Article
	 */
	protected $_article;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID
	 * @JetDataModel:is_id = true
	 * @JetDataModel:form_field_type = false
	 * @JetDataModel:related_to = 'main.id'
	 * @JetDataModel:do_not_export = true
	 *
	 * @var string
	 */
	protected $article_id = '';

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
	 * @param string $article_id
	 * @param Locale $locale
	 */
	public function __construct( $article_id=null, Locale $locale=null )
	{
		parent::__construct();
		$this->article_id = $article_id;
		$this->locale = $locale;
	}

	/**
	 * @return Article
	 */
	public function getArticle()
	{
		return $this->_article;
	}

	/**
	 * @param Article $article
	 */
	public function setArticle( Article $article )
	{
		$this->_article = $article;
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
	public function getArticleId()
	{
		return $this->article_id;
	}

	/**
	 * @param string $article_id
	 */
	public function setArticleId( $article_id )
	{
		$this->article_id = $article_id;
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
		$q = [
			'URI_fragment' => $URI_fragment,
			'AND',
			'locale' => $this->locale,
		];

		if( !$this->getIsNew() ) {
			$q[] = 'AND';
			$q['article_id!='] = $this->article_id;
		}

		return (bool)static::getBackendInstance()->getCount( static::createQuery( $q ) );
	}

	/**
	 * Generates URI fragment:
	 *
	 *
	 * @param string   $URI_fragment
	 *
	 * @param callable $exists_checker
	 * @param string   $suffix (optional) example: .html
	 * @param bool     $remove_accents (optional, default: true)
	 *
	 * @return string
	 */
	public function generateUrlFragment( $URI_fragment, callable $exists_checker, $suffix = '', $remove_accents = true )
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

		if( $exists_checker( $URI_fragment.$suffix ) ) {
			$_id = substr( $URI_fragment, 0, 255-strlen( (string)$max_suffix_no ) );

			for( $c = 1; $c<=$max_suffix_no; $c++ ) {
				$URI_fragment = $_id.$c;

				if( !$exists_checker( $URI_fragment.$suffix ) ) {
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

}