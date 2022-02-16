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
use Jet\Form_Definition;
use Jet\Form_Field;
use Jet\Locale;
use Jet\Data_DateTime;
use Jet\MVC;
use Jet\Data_Paginator_DataSource;
use Jet\DataModel_IDController_UniqueString;
use Jet\Form;
use Jet\MVC_Cache;


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
class Content_Article extends DataModel
{


	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_ID,
		is_id: true
	)]
	protected string $id = '';

	/**
	 * @var ?Data_DateTime
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_DATE_TIME,
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_DATE_TIME,
		label: '',
		error_messages: [
			Form_Field::ERROR_CODE_INVALID_FORMAT => 'Invalid date format'
		]
	)]
	protected ?Data_DateTime $date_time = null;

	/**
	 * @var Content_Article_Localized[]
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_DATA_MODEL,
		data_model_class: Content_Article_Localized::class
	)]
	#[Form_Definition(is_sub_forms:true)]
	protected array $localized = [];

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
	public function afterLoad(): void
	{

		foreach( static::getLocales() as $lc_str => $locale ) {

			if( !isset( $this->localized[$lc_str] ) ) {

				$this->localized[$lc_str] = new Content_Article_Localized( $this->getId(), $locale );
			}

			$this->localized[$lc_str]->setArticle( $this );
		}

	}

	/**
	 * @return Locale[]
	 */
	public static function getLocales() : array
	{
		return Application_Web::getBase()->getLocales();
	}

	/**
	 *
	 * @param string $id
	 *
	 * @return static|null
	 */
	public static function get( string $id ): static|null
	{
		return static::load( $id );
	}

	/**
	 *
	 * @param string $search
	 *
	 * @return Content_Article[]
	 */
	public static function getList( string $search = '' ): iterable

	{

		$where = [];

		if( $search ) {
			$search = '%' . $search . '%';

			$where[] = [
				'article_localized.title *'      => $search,
				'OR',
				'article_localized.text *'       => $search,
				'OR',
				'article_localized.annotation *' => $search,
			];
		}

		return static::fetchInstances(
			$where,
			[
				'article.id',
				'article.date_time',
				'article_localized.title',
			]
		);

	}

	/**
	 * @return string
	 */
	public function getId(): string
	{
		return $this->id;
	}


	/**
	 * @return Data_DateTime
	 */
	public function getDateTime(): Data_DateTime
	{
		return $this->date_time;
	}

	/**
	 * @param Locale|null $locale
	 *
	 * @return Content_Article_Localized
	 */
	public function getLocalized( Locale $locale = null ): Content_Article_Localized
	{
		if( !$locale ) {
			$locale = MVC::getLocale();
		}
		return $this->localized[$locale->toString()];
	}

	/**
	 * @param Data_DateTime|string $date_time
	 */
	public function setDateTime( Data_DateTime|string $date_time ): void
	{
		if( !($date_time instanceof Data_DateTime) ) {
			$date_time = new Data_DateTime( $date_time );
		}
		$this->date_time = $date_time;
	}

	/**
	 * @return Content_Article[]|Data_Paginator_DataSource
	 */
	public static function getListForCurrentLocale(): array|Data_Paginator_DataSource
	{
		$list = static::fetchInstances(
			[
				'article_localized.locale' => MVC::getLocale(),
			]
		);
		$list->getQuery()->setOrderBy( '-date_time' );

		return $list;
	}

	/**
	 * @param string $path
	 * @param Locale $locale
	 *
	 * @return static|null
	 */
	public static function resolveArticleByURL( string $path, Locale $locale ): static|null
	{
		$current_article = null;
		if( str_ends_with( $path, '.html' ) ) {
			$current_article = static::load(
				[
					'article_localized.URI_fragment' => $path,
					'AND',
					'article_localized.locale'       => $locale
				]
			);
		}

		/**
		 * @var Content_Article $current_article
		 */
		return $current_article;
	}

	/**
	 * @return string
	 */
	public function getUrl(): string
	{
		return $this->getLocalized()->getURL();
	}

	/**
	 * @return string
	 */
	public function getTitle(): string
	{
		return $this->getLocalized()->getTitle();
	}


	/**
	 * @return string
	 */
	public function getAnnotation(): string
	{
		return $this->getLocalized()->getAnnotation();
	}


	/**
	 * @return string
	 */
	public function getText(): string
	{
		return $this->getLocalized()->getText();
	}

	/**
	 * @return Form
	 */
	public function getEditForm(): Form
	{
		if( $this->_form_edit === null ) {
			$this->_form_edit = $this->createForm('article_edit');
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
			$this->_form_add = $this->createForm('article_add');
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
	 *
	 */
	public function afterUpdate(): void
	{
		MVC_Cache::reset();
	}

	/**
	 *
	 */
	public function afterDelete(): void
	{
		MVC_Cache::reset();
	}

	/**
	 *
	 */
	public function afterAdd(): void
	{
		MVC_Cache::reset();
	}
}