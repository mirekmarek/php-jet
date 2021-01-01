<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\Content\Articles;

use Jet\DataModel;
use Jet\DataModel_Definition;
use Jet\DataModel_Related_1toN;
use Jet\DataModel_IDController_Passive;

use Jet\Locale;
use Jet\Mvc;
use Jet\Data_Text;
use Jet\Form;
use Jet\Form_Field_Input;

/**
 *
 */
#[DataModel_Definition(name: 'article_localized')]
#[DataModel_Definition(database_table_name: 'articles_localized')]
#[DataModel_Definition(id_controller_class: DataModel_IDController_Passive::class)]
#[DataModel_Definition(parent_model_class: Article::class)]
class Article_Localized extends DataModel_Related_1toN
{
	/**
	 * @var Article
	 */
	protected Article $_article;

	/**
	 *
	 * @var string|null
	 */
	#[DataModel_Definition(type: DataModel::TYPE_ID)]
	#[DataModel_Definition(is_id: true)]
	#[DataModel_Definition(form_field_type: false)]
	#[DataModel_Definition(related_to: 'main.id')]
	#[DataModel_Definition(do_not_export: true)]
	protected string|null $article_id = '';

	/**
	 * @var Locale|null
	 */
	#[DataModel_Definition(type: DataModel::TYPE_LOCALE)]
	#[DataModel_Definition(is_id: true)]
	#[DataModel_Definition(form_field_type: false)]
	#[DataModel_Definition(do_not_export: true)]
	protected Locale|null $locale;

	/**
	 *
	 * @var string
	 */
	#[DataModel_Definition(type: DataModel::TYPE_STRING)]
	#[DataModel_Definition(max_len: 255)]
	#[DataModel_Definition(form_field_is_required: true)]
	#[DataModel_Definition(form_field_type: false)]
	#[DataModel_Definition(is_key: true)]
	protected string $URI_fragment = '';

	/**
	 *
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
	 * @var string
	 */
	#[DataModel_Definition(type: DataModel::TYPE_STRING)]
	#[DataModel_Definition(max_len: 65536)]
	#[DataModel_Definition(form_field_label: 'Annotation')]
	protected string $annotation = '';

	/**
	 *
	 * @var string
	 */
	#[DataModel_Definition(type: DataModel::TYPE_STRING)]
	#[DataModel_Definition(max_len: 655360)]
	#[DataModel_Definition(form_field_label: 'Text')]
	#[DataModel_Definition(form_field_type: Form::TYPE_WYSIWYG)]
	protected string $text = '';

	/**
	 *
	 * @param string|null $article_id
	 * @param Locale|null $locale
	 */
	public function __construct( ?string $article_id=null, ?Locale $locale=null )
	{
		parent::__construct();
		$this->article_id = $article_id;
		$this->locale = $locale;
	}

	/**
	 * @return Article
	 */
	public function getArticle() : Article
	{
		return $this->_article;
	}

	/**
	 * @param Article $article
	 */
	public function setArticle( Article $article ) : void
	{
		$this->_article = $article;
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
	public function getArticleId() : string
	{
		return $this->article_id;
	}

	/**
	 * @param string $article_id
	 */
	public function setArticleId( string $article_id ) : void
	{
		$this->article_id = $article_id;
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
	public function setLocale( Locale|string $locale ) : void
	{
		if( !( $locale instanceof Locale ) ) {
			$locale = new Locale( $locale );
		}

		$this->locale = $locale;
	}

	/**
	 * @return string
	 */
	public function getURL() : string
	{
		return Mvc::getCurrentPage()->getURL( [ $this->getURIFragment() ], [] );
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
	public function setTitle( string $title ) : void
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
	public function getUriFragmentExists( string $URI_fragment ) : bool
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
	 * @param bool     $remove_accents (optional, Default: true)
	 *
	 * @return string
	 */
	public function generateUrlFragment( string $URI_fragment,
	                                     callable $exists_checker,
	                                     string $suffix = '',
	                                     bool $remove_accents = true ) : string
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
	public function getAnnotation() : string
	{
		return $this->annotation;
	}

	/**
	 * @param string $annotation
	 */
	public function setAnnotation( string $annotation ) : void
	{
		$this->annotation = $annotation;
	}

	/**
	 * @return string
	 */
	public function getText() : string
	{
		return $this->text;
	}

	/**
	 * @param string $text
	 */
	public function setText( string $text ) : void
	{
		$this->text = $text;
	}

}