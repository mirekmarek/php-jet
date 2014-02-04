<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule_Articles
 * @subpackage JetApplicationModule\JetExample\Articles
 */
namespace JetApplicationModule\JetExample\Articles;
use Jet;

/**
 * Class Article
 *
 * @JetDataModel:name = 'Article'
 * @JetDataModel:database_table_name = 'Jet_Articles'
 */
class Article extends Jet\DataModel {

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_ID
	 * @JetDataModel:is_ID = true
	 *
	 * @var string
	 */
	protected $ID = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_LOCALE
	 * @JetDataModel:is_required = true
	 * @JetDataModel:form_field_label = 'Locale:'
	 * @JetDataModel:form_field_get_select_options_callback = array (  0 => 'Jet\\Mvc',  1 => 'getAllSitesLocalesList',)
	 *
	 * @var Jet\Locale
	 */
	protected $locale;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:is_required = true
	 * @JetDataModel:form_field_type = false
	 * @JetDataModel:is_key = true
	 *
	 * @var string
	 */
	protected $URI_fragment = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 100
	 * @JetDataModel:is_required = true
	 * @JetDataModel:form_field_label = 'Title: '
	 *
	 * @var string
	 */
	protected $title = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 65536
	 * @JetDataModel:form_field_label = 'Annotation:'
	 *
	 * @var string
	 */
	protected $annotation = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 655360
	 * @JetDataModel:form_field_label = 'Text:'
	 * @JetDataModel:form_field_type = 'WYSIWYG'
	 *
	 * @var string
	 */
	protected $text = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_DATE_TIME
	 * @JetDataModel:form_field_label = 'Date and time:'
	 *
	 * @var Jet\DateTime
	 */
	protected $date_time;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:form_field_label = 'Tags: '
	 * @JetDataModel:max_len = 65536
	 *
	 * @var string
	 */
	protected $tags = '';

	/**
	 * @return Jet\Locale
	 */
	public function getLocale() {
		return $this->locale;
	}

	/**
	 * @param Jet\Locale|string $locale
	 */
	public function setLocale( $locale) {
		if(!($locale instanceof Jet\Locale) ) {
			$locale = new Jet\Locale($locale);
		}

		$this->locale = $locale;
	}

	/**
	 * @return string
	 */
	public function getURIFragment() {
		return $this->URI_fragment;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;

		$article_i = $this;

		$this->URI_fragment = Jet\Mvc_Factory::getPageInstance()->generateURLfragment($this->title, function( $URI_fragment ) use ( $article_i ) {
			return $article_i->getURIfragmentExists( $URI_fragment );
		}, '.html');
	}

	/**
	 *
	 * @param string $URI_fragment
	 *
	 * @return bool
	 */
	public function getURIfragmentExists( $URI_fragment ) {
		if($this->getIsNew()) {
			$q = array(
				'this.URI_fragment' => $URI_fragment
			);
		} else {
			$q = array(
				'this.URI_fragment' => $URI_fragment,
				'AND',
				'this.ID!=' => $this->ID
			);
		}
		return (bool)$this->getBackendInstance()->getCount( $this->createQuery($q) );
	}


	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $annotation
	 */
	public function setAnnotation($annotation) {
		$this->annotation = $annotation;
	}

	/**
	 * @return string
	 */
	public function getAnnotation() {
		return $this->annotation;
	}

	/**
	 * @param string $text
	 */
	public function setText($text) {
		$this->text = $text;
	}

	/**
	 * @return string
	 */
	public function getText() {
		return $this->text;
	}


	/**
	 * @return Jet\DateTime
	 */
	public function getDateTime() {
		return $this->date_time;
	}

	/**
	 * @param Jet\DateTime|string $date_time
	 */
	public function setDateTime( $date_time) {
		if(!($date_time instanceof Jet\DateTime)) {
			$date_time = new Jet\DateTime($date_time);
		}
		$this->date_time = $date_time;
	}


	/**
	 * @static
	 * @return Article
	 */
	public static function getNew() {
		return new self();
	}

	/**
	 * @static
	 *
	 * @param string $ID
	 *
	 * @return Article
	 */
	public static function get( $ID ) {

		return static::load( static::createID($ID) );
	}

	/**
	 * @static
	 *
	 * @param array $query (optional)
	 *
	 * @return Article[]
	 */
	public static function getList( $query=array() ) {
		return (new self())->fetchObjects($query);
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
	 * @return Article[]
	 */
	public function getListForCurrentLocale() {
		$list = $this->fetchObjects(array(
			'this.locale' => Jet\Mvc::getCurrentLocale()
		));
		$list->getQuery()->setOrderBy('-date_time');

		return $list;
	}

	/**
	 * @param Jet\Mvc_Router_Abstract $router
	 *
	 * @return Article|null
	 */
	public function resolveArticleByURL( Jet\Mvc_Router_Abstract $router ) {
		$current_article = null;
		$param = $router->getPathFragments();

		if(isset($param[0]) && substr($param[0], -5)=='.html' ) {
			//$ID = substr($param[0], 0, -5);
			$current_article = $this->fetchOneObject( array(
				'this.URI_fragment' => 	$param[0]
			) );

			if($current_article) {
				$router->putUsedPathFragment( $param[0] );
			}
		}

		return $current_article;
	}

	/**
	 * @return string
	 */
	public function getTags() {
		return $this->tags;
	}

	/**
	 * @param string $tags
	 */
	public function setTags($tags) {
		$this->tags = $tags;
	}
}