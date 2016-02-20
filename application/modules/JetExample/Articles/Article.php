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
 */
namespace JetApplicationModule\JetExample\Articles;
use Jet;
use Jet\DataModel;
use Jet\Locale;
use Jet\Data_DateTime;
use Jet\Mvc;
use Jet\Mvc_Router_Abstract;
use Jet\Data_Text;
use Jet\DataModel_Fetch_Object_Abstract;
use Jet\DataModel_Fetch_Data_Assoc;
use Jet\Data_Paginator_DataSource_Interface;

/**
 * Class Article
 *
 * @JetDataModel:name = 'Article'
 * @JetDataModel:database_table_name = 'Jet_Articles'
 * @JetDataModel:ID_class_name = 'DataModel_ID_UniqueString'
 */
class Article extends DataModel {

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID
	 * @JetDataModel:is_ID = true
	 *
	 * @var string
	 */
	protected $ID = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_LOCALE
	 * @JetDataModel:form_field_is_required = true
	 * @JetDataModel:form_field_label = 'Locale'
	 * @JetDataModel:form_field_get_select_options_callback = ['Jet\Mvc_Site','getAllLocalesList']
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
	 *
	 * @var Data_DateTime
	 */
	protected $date_time;

	/**
	 * @return Locale
	 */
	public function getLocale() {
		return $this->locale;
	}

	/**
	 * @param Locale|string $locale
	 */
	public function setLocale( $locale) {
		if(!($locale instanceof Locale) ) {
			$locale = new Locale($locale);
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
     * @return string
     */
    public function getURL() {
        return Mvc::getCurrentPage()->getURL().$this->getURIFragment();
    }

	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;

		$article_i = $this;

		$this->URI_fragment = $this->generateUrlFragment($this->title, function( $URI_fragment ) use ( $article_i ) {
			return $article_i->getURIfragmentExists( $URI_fragment );
		}, '.html');
	}


    /**
     * Generates URI fragment:
     *
     * - replace ' ' by '-'
     * - remove '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '.', ''','/','<','>',';','?','{','}','[',']','|'
     * - apply rawurlencode()
     *
     * @param string $URI_fragment
     *
     * @param callable $exists_check
     * @param string $suffix (optional) example: .html
     * @param bool $remove_accents (optional, default: false)
     *
     * @return string
     */
    public function generateUrlFragment( $URI_fragment, callable $exists_check, $suffix='', $remove_accents=false ) {

        if($remove_accents) {
            $URI_fragment = Data_Text::removeAccents($URI_fragment);
        }

        $URI_fragment = str_replace(' ', '-', $URI_fragment);
        $URI_fragment = preg_replace( '~([-]{2,})~', '-' , $URI_fragment );

        $replace = ['!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '.', '\'','"' ,'/','<','>',';','?','{','}','[',']','|'];
        $URI_fragment = str_replace($replace, '', $URI_fragment);

        $URI_fragment = rawurlencode($URI_fragment);

        $max_suffix_no = 9999;

        if( $exists_check( $URI_fragment.$suffix ) ) {
            $_ID = substr($URI_fragment, 0, 255 - strlen( (string)$max_suffix_no )  );

            for($c=1; $c<=$max_suffix_no; $c++) {
                $URI_fragment = $_ID.$c;

                if( !$exists_check( $URI_fragment.$suffix ) ) {
                    break;
                }
            }
        }


        return $URI_fragment.$suffix;
    }


	/**
	 *
	 * @param string $URI_fragment
	 *
	 * @return bool
	 */
	public function getURIfragmentExists( $URI_fragment ) {
		if($this->getIsNew()) {
			$q = [
				'this.URI_fragment' => $URI_fragment
			];
		} else {
			$q = [
				'this.URI_fragment' => $URI_fragment,
				'AND',
				'this.ID!=' => $this->ID
			];
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
	 * @return Data_DateTime
	 */
	public function getDateTime() {
		return $this->date_time;
	}

	/**
	 * @param Data_DateTime|string $date_time
	 */
	public function setDateTime( $date_time) {
		if(!($date_time instanceof Data_DateTime)) {
			$date_time = new Data_DateTime($date_time);
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
	 * @return Article[]|DataModel_Fetch_Object_Abstract
	 */
	public static function getList( $query= []) {
		return (new self())->fetchObjects($query);
	}

	/**
	 * @static
	 *
	 * @return DataModel_Fetch_Data_Assoc
	 */
	public static function getListAsData() {
		/**
		 * @var DataModel $i;
		 */
		$i = new self();
		$props = $i->getDataModelDefinition()->getProperties();
		return $i->fetchDataAssoc($props, []);
	}

	/**
	 * @return Article[]|Data_Paginator_DataSource_Interface
	 */
	public function getListForCurrentLocale() {
		$list = $this->fetchObjects([
			'this.locale' => Mvc::getCurrentLocale()
		]);
		$list->getQuery()->setOrderBy('-date_time');

		return $list;
	}

	/**
	 * @param Mvc_Router_Abstract $router
	 *
	 * @return Article|null
	 */
	public function resolveArticleByURL( Mvc_Router_Abstract $router ) {
		$current_article = null;
		$param = $router->getPathFragments();

		if(isset($param[0]) && substr($param[0], -5)=='.html' ) {

			$current_article = $this->fetchOneObject( [
				'this.URI_fragment' => 	$param[0]
			]);

		}

		return $current_article;
	}
}