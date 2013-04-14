<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule_Articles
 * @subpackage JetApplicationModule\Jet\Articles
 */
namespace JetApplicationModule\Jet\Articles;
use Jet;

class Article extends Jet\DataModel {

	protected static $__data_model_model_name = "JetApplicationModule_Articles_Article";

	protected static $__data_model_properties_definition = array(
		"ID" => array(
			"type" => self::TYPE_ID,
			"is_ID" => true
		),
		"locale" => array(
			"type" => self::TYPE_LOCALE,
			"is_required" => true,
			"form_field_label" => "Locale:",
			"form_field_get_select_options_callback" => array("Jet\Mvc", "getAllSitesLocalesList")
		),
		"URI_fragment" => array(
			"type" => self::TYPE_STRING,
			"max_len" => 255,
			"is_required" => true,
			"form_field_type" => false,
			"backend_options" => array(
				"key" => true,
				"key_type" => self::KEY_TYPE_INDEX
			)
		),
		"title" => array(
			"type" => self::TYPE_STRING,
			"max_len" => 100,
			"is_required" => true,
			"form_field_label" => "Title: ",
		),
		"annotation" => array(
			"type" => self::TYPE_STRING,
			"max_len" => 65536,
			"form_field_label" => "Annotation:"
		),
		"text" => array(
			"type" => self::TYPE_STRING,
			"max_len" => 655360,
			"form_field_label" => "Text:",
			"form_field_type" => "WYSIWYG"
		),
		"date_time" => array(
			"type" => self::TYPE_DATE_TIME,
			"form_field_label" => "Date and time:"
		),
		"tags" => array(
			"type" => self::TYPE_STRING,
			"form_field_label" => "Tags: ",
			"max_len" => 65536,
		)
	);

	/**
	 * @var string
	 */
	protected $tags = "";

	/**
	 * @var string
	 */
	protected $ID = "";

	/**
	 * @var Jet\Locale
	 */
	protected $locale;

	/**
	 * @var string
	 */
	protected $URI_fragment = "";

	/**
	 * @var string
	 */
	protected $title = "";

	/**
	 * @var string
	 */
	protected $annotation = "";

	/**
	 * @var string
	 */
	protected $text = "";

	/**
	 * @var Jet\DateTime
	 */
	protected $date_time;


	/**
	 * @return Jet\Locale
	 */
	public function getLocale() {
		return $this->locale;
	}

	/**
	 * @param Jet\Locale $locale
	 */
	public function setLocale(Jet\Locale $locale) {
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
		}, ".html");
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
				"this.URI_fragment" => $URI_fragment
			);
		} else {
			$q = array(
				"this.URI_fragment" => $URI_fragment,
				"AND",
				"this.ID!=" => $this->ID
			);
		}
		return (bool)$this->getBackendInstance()->getCount( Jet\DataModel_Query::createQuery( $this, $q) );
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
	 * @param Jet\DateTime $date_time
	 */
	public function setDateTime(Jet\DateTime $date_time) {
		$this->date_time = $date_time;
	}


	/**
	 * @static
	 * @return Article
	 */
	public static function getNew() {
		$instance = new self();
		$instance->initNewObject();

		return $instance;
	}

	/**
	 * @static
	 *
	 * @param string $ID
	 *
	 * @return Article
	 */
	public static function get( $ID ) {
		$instance = new self();
		$ID = $instance->getEmptyIDInstance()->createID($ID);

		return $instance->load($ID);
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
			"this.locale" => Jet\Mvc::getCurrentLocale()
		));
		$list->getQuery()->setOrderBy("-date_time");

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

		if(isset($param[0]) && substr($param[0], -5)==".html" ) {
			//$ID = substr($param[0], 0, -5);
			$current_article = $this->fetchOneObject( array(
				"this.URI_fragment" => 	$param[0]
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