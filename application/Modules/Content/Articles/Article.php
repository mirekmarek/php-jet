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
use Jet\DataModel_Related_1toN_Iterator;
use Jet\Locale;
use Jet\Data_DateTime;
use Jet\Mvc;
use Jet\DataModel_Fetch_Instances;
use Jet\Data_Paginator_DataSource;
use Jet\DataModel_IDController_UniqueString;
use Jet\Form;
use Jet\Form_Field_DateTime;
use JetApplication\Application_Web;

/**
 *
 * @JetDataModel:name = 'article'
 * @JetDataModel:database_table_name = 'articles'
 * @JetDataModel:id_controller_class_name = 'DataModel_IDController_UniqueString'
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
	 * @JetDataModel:type = DataModel::TYPE_DATE_TIME
	 * @JetDataModel:form_field_label = 'Date and time'
	 * @JetDataModel:form_field_error_messages = [Form_Field_DateTime::ERROR_CODE_INVALID_FORMAT => 'Invalid date and time format']
	 *
	 * @var Data_DateTime
	 */
	protected $date_time;

	/**
	 * @JetDataModel:type = DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Article_Localized'
	 *
	 * @var Article_Localized[]|DataModel_Related_1toN|DataModel_Related_1toN_Iterator
	 */
	protected $localized;

	/**
	 * @var Form
	 */
	protected $_form_add;
	/**
	 * @var Form
	 */
	protected $_form_edit;
	/**
	 * @var Form
	 */
	protected $_form_delete;


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
	public function afterLoad()
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
	 * @return Article
	 */
	public static function get( $id )
	{

		/** @noinspection PhpIncompatibleReturnTypeInspection */
		return static::load( $id );
	}

	/**
	 *
	 * @param string $search
	 *
	 * @return Article[]|DataModel_Fetch_Instances
	 */
	public static function getList( $search = '' )
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

		/**
		 * @var DataModel_Fetch_Instances $list
		 */
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
	public function getId()
	{
		return $this->id;
	}



	/**
	 * @return Data_DateTime
	 */
	public function getDateTime()
	{
		return $this->date_time;
	}

	/**
	 * @param Locale|null $locale
	 *
	 * @return Article_Localized
	 */
	public function getLocalized( Locale $locale=null )
	{
		if(!$locale) {
			$locale = Mvc::getCurrentLocale();
		}
		return $this->localized[$locale->toString()];
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
	public static function getListForCurrentLocale()
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
	 * @param string        $path
	 * @param string|Locale $locale
	 *
	 * @return Article|null
	 */
	public static function resolveArticleByURL( $path, $locale )
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
	public function getUrl()
	{
		return $this->getLocalized()->getURL();
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->getLocalized()->getTitle();
	}



	/**
	 * @return string
	 */
	public function getAnnotation()
	{
		return $this->getLocalized()->getAnnotation();
	}


	/**
	 * @return string
	 */
	public function getText()
	{
		return $this->getLocalized()->getText();
	}

	/**
	 * @return Form
	 */
	public function getEditForm()
	{
		if(!$this->_form_edit) {
			$this->_form_edit = $this->getCommonForm();
		}

		return $this->_form_edit;
	}

	/**
	 * @return bool
	 */
	public function catchEditForm()
	{
		return $this->catchForm( $this->getEditForm() );
	}


	/**
	 * @return Form
	 */
	public function getAddForm()
	{
		if(!$this->_form_add) {
			$this->_form_add = $this->getCommonForm();
		}

		return $this->_form_add;
	}

	/**
	 * @return bool
	 */
	public function catchAddForm()
	{
		return $this->catchForm( $this->getAddForm() );
	}

}