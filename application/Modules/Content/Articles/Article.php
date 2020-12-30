<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\Content\Articles;

use Jet\DataModel;
use Jet\DataModel_Definition;
use Jet\DataModel_Related_1toN;
use Jet\DataModel_Related_1toN_Iterator;
use Jet\Form_Field_DateTime;
use Jet\Locale;
use Jet\Data_DateTime;
use Jet\Mvc;
use Jet\Data_Paginator_DataSource;
use Jet\DataModel_IDController_UniqueString;
use Jet\Form;
use JetApplication\Application_Web;

/**
 *
 */




#[DataModel_Definition(
	name: 'article',
	database_table_name: 'articles',
	id_controller_class: DataModel_IDController_UniqueString::class,
	id_controller_options: [
		'id_property_name' => 'id'
	]
)]
class Article extends DataModel
{


	/**
	 * @var string
	 */
	#[DataModel_Definition(type: DataModel::TYPE_ID)]
	#[DataModel_Definition(is_id: true)]
	protected string $id = '';

	/**
	 * @var ?Data_DateTime
	 */ 
	#[DataModel_Definition(
		type: DataModel::TYPE_DATE_TIME,
		form_field_type: Form::TYPE_DATE_TIME,
		form_field_label: '',
		form_field_error_messages: [
			Form_Field_DateTime::ERROR_CODE_INVALID_FORMAT => 'Invalid date format'
		]
	)]
	protected ?Data_DateTime $date_time = null;

	/**
	 * @var Article_Localized[]|DataModel_Related_1toN|DataModel_Related_1toN_Iterator|null
	 */ 
	#[DataModel_Definition(
		type: DataModel::TYPE_DATA_MODEL,
		data_model_class: Article_Localized::class
	)]
	protected $localized = null;

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
	protected ?Form $_form_delete = null;


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
	public function afterLoad() : void
	{

		foreach( Application_Web::getSite()->getLocales() as $lc_str => $locale) {

			if (!isset($this->localized[$lc_str])) {

				$this->localized[$lc_str] = new Article_Localized($this->getId(), $locale);
			}

			$this->localized[$lc_str]->setArticle( $this );
		}

	}


	/**
	 *
	 * @param string $id
	 *
	 * @return static|null
	 */
	public static function get( string $id ) : static|null
	{
		return static::load( $id );
	}

	/**
	 *
	 * @param string $search
	 *
	 * @return Article[]
	 */
	public static function getList( $search = '' ) : iterable

	{

		$where = [];

		if( $search ) {
			$search = '%'.$search.'%';

			$where[] = [
				'article_localized.title *' => $search,
				'OR',
				'article_localized.text *' => $search,
				'OR',
				'article_localized.annotation *' => $search,
			];
		}

		$list = static::fetchInstances(
			$where,
			[
				'article.id',
				'article.date_time',
				'article_localized.title',
			]
		);

		return $list;
	}

	/**
	 * @return string
	 */
	public function getId() : string
	{
		return $this->id;
	}



	/**
	 * @return Data_DateTime
	 */
	public function getDateTime() : Data_DateTime
	{
		return $this->date_time;
	}

	/**
	 * @param Locale|null $locale
	 *
	 * @return Article_Localized
	 */
	public function getLocalized( Locale $locale=null ) : Article_Localized
	{
		if(!$locale) {
			$locale = Mvc::getCurrentLocale();
		}
		return $this->localized[$locale->toString()];
	}

	/**
	 * @param Data_DateTime|string $date_time
	 */
	public function setDateTime( Data_DateTime|string $date_time ) : void
	{
		if( !( $date_time instanceof Data_DateTime ) ) {
			$date_time = new Data_DateTime( $date_time );
		}
		$this->date_time = $date_time;
	}

	/**
	 * @return Article[]|Data_Paginator_DataSource
	 */
	public static function getListForCurrentLocale() : array|Data_Paginator_DataSource
	{
		$list = static::fetchInstances(
			[
				'article_localized.locale' => Mvc::getCurrentLocale(),
			]
		);
		$list->getQuery()->setOrderBy( '-date_time' );

		return $list;
	}

	/**
	 * @param string $path
	 * @param string|Locale $locale
	 *
	 * @return Article|null
	 */
	public static function resolveArticleByURL( string $path, Locale|string $locale ) : Article|null
	{
		$current_article = null;
		if( substr( $path, -5 )=='.html' ) {

			$current_article = static::load(
				[
					'article_localized.URI_fragment' => $path,
					'AND',
					'article_localized.locale' => $locale
				]
			);

		}

		/**
		 * @var Article $current_article
		 */
		return $current_article;
	}

	/**
	 * @return string
	 */
	public function getUrl() : string
	{
		return $this->getLocalized()->getURL();
	}

	/**
	 * @return string
	 */
	public function getTitle() : string
	{
		return $this->getLocalized()->getTitle();
	}



	/**
	 * @return string
	 */
	public function getAnnotation() : string
	{
		return $this->getLocalized()->getAnnotation();
	}


	/**
	 * @return string
	 */
	public function getText() : string
	{
		return $this->getLocalized()->getText();
	}

	/**
	 * @return Form
	 */
	public function getEditForm() : Form
	{
		if($this->_form_edit===null) {
			$this->_form_edit = $this->getCommonForm();
		}

		return $this->_form_edit;
	}

	/**
	 * @return bool
	 */
	public function catchEditForm() : bool
	{
		return $this->catchForm( $this->getEditForm() );
	}


	/**
	 * @return Form
	 */
	public function getAddForm() : Form
	{
		if(!$this->_form_add) {
			$this->_form_add = $this->getCommonForm();
		}

		return $this->_form_add;
	}

	/**
	 * @return bool
	 */
	public function catchAddForm() : bool
	{
		return $this->catchForm( $this->getAddForm() );
	}

}