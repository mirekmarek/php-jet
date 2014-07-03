<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Pages
 */
namespace Jet;

/**
 * Class Mvc_Pages_Page_Default
 *
 * @JetDataModel:database_table_name = 'Jet_Mvc_Pages'
 * @JetDataModel:ID_class_name = 'Jet\Mvc_Pages_Page_ID_Default'
 */
class Mvc_Pages_Page_Default extends Mvc_Pages_Page_Abstract{

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
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 100
	 * @JetDataModel:is_ID = true
	 *
	 * @var string
	 */
	protected $site_ID = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_LOCALE
	 * @JetDataModel:is_ID = true
	 * @JetDataModel:form_field_type = false
	 *
	 * @var Locale
	 */
	protected $locale;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 100
	 * @JetDataModel:is_key = true
	 * @JetDataModel:form_field_label = 'Parent page'
	 * @JetDataModel:form_field_type = false
	 *
	 * @var string
	 */
	protected $parent_ID = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:is_required = true
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:error_messages = array (  'required' => 'Name was not specified',)
	 * @JetDataModel:form_field_label = 'Name'
	 *
	 * @var string
	 */
	protected $name = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_BOOL
	 * @JetDataModel:form_field_label = 'Is admin UI'
	 *
	 * @var bool
	 */
	protected $is_admin_UI = false;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 *
	 * @var string
	 */
	protected $force_front_controller_module_name = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_label = 'Title'
	 *
	 * @var string
	 */
	protected $title = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_label = 'Title for menu item'
	 *
	 * @var string
	 */
	protected $menu_title = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_label = 'Title for breadcrumb navigation'
	 *
	 * @var string
	 */
	protected $breadcrumb_title = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_label = 'URL fragment'
	 *
	 * @var string
	 */
	protected $URL_fragment = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:is_required = true
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_label = 'Layout'
	 *
	 * @var string
	 */
	protected $layout = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 65535
	 * @JetDataModel:form_field_label = 'Headers suffix'
	 *
	 * @var string
	 */
	protected $headers_suffix = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 65535
	 * @JetDataModel:form_field_label = 'Body prefix'
	 *
	 * @var string
	 */
	protected $body_prefix = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 65535
	 * @JetDataModel:form_field_label = 'Body suffix'
	 *
	 * @var string
	 */
	protected $body_suffix = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Jet\Mvc_Pages_Page_MetaTag_Default'
	 * @JetDataModel:form_field_type = false
	 *
	 * @var Mvc_Pages_Page_MetaTag_Abstract[]
	 */
	protected $meta_tags;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_BOOL
	 * @JetDataModel:form_field_label = 'Authentication required'
	 *
	 * @var bool
	 */
	protected $authentication_required = false;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_BOOL
	 * @JetDataModel:form_field_label = 'Secure connection required'
	 *
	 * @var bool
	 */
	protected $SSL_required = false;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Jet\Mvc_Pages_Page_Content_Default'
	 * @JetDataModel:form_field_type = false
	 *
	 * @var Mvc_Pages_Page_Content_Abstract[]
	 */
	protected $contents;

	/**
	 * @param string $ID
	 */
	public function setID( $ID ) {
		$this->ID = $ID;
		$this->setIsNew();
	}

	/**
	 * @param string $site_ID
	 */
	public function setSiteID( $site_ID ) {
		$this->site_ID = $site_ID;
		$this->setIsNew();
	}

	/**
	 * @param Locale $locale
	 *
	 */
	public function setLocale( Locale $locale ) {
		$this->locale = $locale;
		$this->setIsNew();
	}

	/**
	 * @return Mvc_Sites_Site_ID_Abstract
	 */
	public function getSiteID() {
		return Mvc_Factory::getSiteIDInstance()->createID( $this->site_ID );
	}

	/**
	 *
	 * @return Locale
	 */
	public function getLocale() {
		return $this->locale;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return bool
	 */
	public function getIsAdminUI() {
		return $this->is_admin_UI;
	}

	/**
	 * @param bool $is_admin_UI
	 */
	public function setIsAdminUI($is_admin_UI) {
		$this->is_admin_UI = (bool)$is_admin_UI;
	}

	/**
	 * @return string
	 */
	public function getForceFrontControllerModuleName() {
		return $this->force_front_controller_module_name;
	}

	/**
	 * @param string $force_front_controller_module_name
	 */
	public function setForceFrontControllerModuleName( $force_front_controller_module_name ) {
		$this->force_front_controller_module_name = $force_front_controller_module_name;
	}

	/**
	 * @return Mvc_Sites_Site_Abstract
	 */
	public function getSite() {
		return Mvc_Sites::getSite( Mvc_Factory::getSiteIDInstance()->createID( $this->site_ID ) );
	}

	/**
	 * @return Mvc_Pages_Page_ID_Abstract
	 */
	public function getParentID() {
		return Mvc_Factory::getPageIDInstance()->createID($this->site_ID, $this->locale, $this->parent_ID);
	}

	/**
	 * @param string $parent_ID
	 */
	public function setParentID( $parent_ID ) {
		$this->parent_ID = $parent_ID;
	}

	/**
	 *
	 * @return Mvc_Pages_Page_Abstract
	 */
	public function getParent() {
		$parent_ID = $this->getParentID();

		if( $this->_page_data_checking_mode ) {
			if( !isset($this->_page_data_checking_map[(string)$parent_ID]) ) {
				return null;
			}

			return $this->_page_data_checking_map[(string)$parent_ID];
		}

		return $this->load( $parent_ID );
		//return Mvc_Pages::getPage( $this->getParentID() );
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle( $title) {
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getMenuTitle() {
		return $this->menu_title;
	}

	/**
	 * @param string $menu_title
	 */
	public function setMenuTitle($menu_title) {
		$this->menu_title = $menu_title;
	}

	/**
	 * @return string
	 */
	public function getBreadcrumbTitle() {
		return $this->breadcrumb_title;
	}

	/**
	 * @param $breadcrumb_title
	 */
	public function setBreadcrumbTitle($breadcrumb_title) {
		$this->breadcrumb_title = $breadcrumb_title;
	}

	/**
	 * Example: http://domain.tld/parent-page/this-is-url-fragment/
	 *
	 * @param string $URL_fragment
	 */
	public function setURLFragment( $URL_fragment ) {

		if($URL_fragment!=$this->URL_fragment) {

			$page_i = $this;
			$this->URL_fragment = $this->generateUrlFragment($URL_fragment, function( $URL_fragment ) use ( $page_i ) {
				/**
				 * @var Mvc_Pages_Page_Abstract $page_i
				 */
				return $page_i->getUrlFragmentExists( $URL_fragment );
			});
		}

		$this->URL_fragment = rawurldecode($this->URL_fragment);
	}

	/**
	 *
	 * @param string $URL_fragment
	 *
	 * @return bool
	 */
	public function getUrlFragmentExists( $URL_fragment ) {
		$q = array(
			'this.site_ID' => $this->site_ID,
			'AND',
			'this.locale' => $this->locale,
			'AND',
			'this.parent_ID' => $this->parent_ID,
			'AND',
			'this.URL_fragment' => $URL_fragment
		);
		if(!$this->getIsNew()) {
			$q[] = 'AND';
			$q['this.ID!='] = $this->ID;
		}
		return (bool)$this->getBackendInstance()->getCount( $this->createQuery( $q ) );
	}


	/**
	 * @return string
	 */
	public function getUrlFragment() {
		return $this->URL_fragment;
	}

	/**
	 * @return string
	 */
	public function getURI() {
		$URLs = $this->getURLs();
		if(!$URLs) {
			return null;
		}

		foreach( $URLs as $URL ) {
			if($URL->getIsMain()) {
				return $URL->getPathPart();
			}
		}

		return null;
	}

	/**
	 * Example: //domain/page/
	 *
	 * @return string
	 */
	public function getNonSchemaURL() {
		$URLs = $this->getURLs();
		if(!$URLs) {
			return null;
		}

		foreach( $URLs as $URL ) {
			if($URL->getIsMain()) {
				return $URL->getAsNonSchemaURL();
			}
		}

		return null;
	}

	/**
	 * Example: http://domain/page/
	 *
	 * @return string
	 */
	public function getNonSslURL() {
		$URLs = $this->getURLs();
		if(!$URLs) {
			return null;
		}

		foreach( $URLs as $URL ) {
			if(
				$URL->getIsDefault() &&
				!$URL->getIsSSL()
			) {
				return $URL->toString();
			}
		}

		return null;
	}

	/**
	 * Example: https://domain/page/
	 *
	 * @return string
	 */
	public function getSslURL() {
		$URLs = $this->getURLs();
		if(!$URLs) {
			return null;
		}

		foreach( $URLs as $URL ) {
			if(
				$URL->getIsDefault() &&
				$URL->getIsSSL()
			) {
				return $URL->toString();
			}
		}

		return null;
	}

	/**
	 * @return string
	 */
	public function getDefaultURL() {

		$URLs = $this->getURLs();
		if(!$URLs) {
			return null;
		}

		foreach( $URLs as $URL ) {
			if($URL->getIsMain()) {
				return $URL->toString();
			}
		}

		return null;
	}

	/**
	 * @param bool $only_default (optional)
	 * @return Mvc_Router_Map_URL_Abstract[]|null
	 */
	protected function getURLs( $only_default=true) {

		return Mvc_Router::getCurrentRouterInstance()->getMap()->findURLs( $this->getID(), $only_default );
	}

	/**
	 * @return string
	 */
	public function getLayout() {
		return $this->layout;
	}

	/**
	 * @param string $layout
	 */
	public function setLayout($layout) {
		$this->layout = $layout;
	}

	/**
	 * @return string
	 */
	public function getHeadersSuffix() {
		return $this->headers_suffix;
	}

	/**
	 * @param string $headers_suffix
	 */
	public function setHeadersSuffix( $headers_suffix ) {
		$this->headers_suffix = $headers_suffix;
	}

	/**
	 * @return string
	 */
	public function getBodyPrefix() {
		return $this->body_suffix;
	}

	/**
	 * @param string $body_prefix
	 */
	public function setBodyPrefix( $body_prefix ) {
		$this->body_prefix = $body_prefix;
	}

	/**
	 * @return string
	 */
	public function getBodySuffix() {
		return $this->body_suffix;
	}

	/**
	 * @param string $body_suffix
	 */
	public function setBodySuffix( $body_suffix ) {
		$this->body_suffix = $body_suffix;
	}

	/**
	 * @return bool
	 */
	public function getAuthenticationRequired() {
		return $this->authentication_required;
	}

	/**
	 * @param bool $authentication_required
	 */
	public function setAuthenticationRequired($authentication_required) {
		$this->authentication_required = (bool)$authentication_required;
	}

	/**
	 * @return bool
	 */
	public function getSSLRequired() {
		return $this->SSL_required;
	}

	/**
	 * @param bool $SSL_required
	 */
	public function setSSLRequired($SSL_required) {
		$this->SSL_required = (bool)$SSL_required;
	}


	/**
	 * @return Mvc_Pages_Page_MetaTag_Abstract[]
	 */
	public function getMetaTags() {
		return $this->meta_tags;
	}

	/**
	 * @param Mvc_Pages_Page_MetaTag_Abstract $meta_tag
	 */
	public function addMetaTag( Mvc_Pages_Page_MetaTag_Abstract $meta_tag) {
		$this->meta_tags[] = $meta_tag;
	}

	/**
	 * @param int $index
	 */
	public function removeMetaTag( $index ) {
		unset( $this->meta_tags[$index] );
	}

	/**
	 * @param Mvc_Pages_Page_MetaTag_Abstract[] $meta_tags
	 */
	public function  setMetaTags( $meta_tags ) {
		/** @noinspection PhpUndefinedMethodInspection */
		$this->meta_tags->clearData();

		foreach( $meta_tags as $meta_tag ) {
			$this->addMetaTag( $meta_tag );
		}
	}

	/**
	 *
	 * @return Mvc_Pages_Page_Content_Abstract[]
	 */
	public function getContents() {
		return $this->contents;
	}

	/**
	 * @param Mvc_Pages_Page_Content_Abstract $content
	 */
	public function addContent( Mvc_Pages_Page_Content_Abstract $content) {
		$this->contents[] = $content;
	}

	/**
	 * @param int $index
	 */
	public function removeContent( $index ) {
		unset( $this->contents[$index] );
	}

	/**
	 * @param Mvc_Pages_Page_Content_Abstract[] $contents
	 */
	public function setContents( $contents ) {

		/** @noinspection PhpUndefinedMethodInspection */
		$this->contents->clearData();

		foreach( $contents as $content ) {
			$this->addContent( $content );
		}
	}

	/**
	 *
	 * @return Mvc_Dispatcher_Queue
	 */
	public function getDispatchQueue() {
		$queue = new Mvc_Dispatcher_Queue();

		foreach( $this->contents as $content ) {
			$queue->addItem( new Mvc_Dispatcher_Queue_Item(
					$content->getModuleName(),
					$content->getControllerAction(),
					$content->getControllerActionParameters(),
					$content
				)
			);
		}

		return $queue;

	}


	/**
	 * @param string $site_ID
	 * @param Locale|string $locale (optional)
	 *
	 * @return DataModel_Fetch_Object_IDs
	 */
	public function getIDs(  $site_ID, $locale=null ) {
		$q = array(
				'this.site_ID' => $site_ID
			);

		if($locale) {
			$q[] = 'AND';
			$q['this.locale'] = $locale;
		}

		return $this->fetchObjectIDs( $q );
	}

	/**
	 * @param string $site_ID
	 * @param Locale|string $locale (optional)
	 *
	 * @return Mvc_Pages_Page_Abstract[]
	 */
	public function getList( $site_ID, $locale=null ) {
		$q = array(
			'this.site_ID' => $site_ID
		);

		if($locale) {
			$q[] = 'AND';
			$q['this.locale'] = $locale;
		}

		return $this->fetchObjects($q);
	}


	/**
	 * @param string|string[] $URL
	 * @return Mvc_Pages_Page_Abstract|null
	 */
	public function getByURL( $URL ) {
		if( is_array($URL) ) {
			$pages = $this->fetchObjects(
				array(
					'page_URL.URL' => $URL,
				)
			);

			$query = $pages->getQuery();

			$query->setOrderBy('-page_URL.URL');
			$query->setLimit(1);

			foreach($pages as $page) {
				return $page;
			}
			return null;


		} else {
			return $this->fetchOneObject(
				array(
					'page_URL.URL' => $URL,
				)
			);

		}

	}

	/**
	 * @param $site_ID
	 * @param Locale $locale
	 * @param bool|null $admin_UI (optional, default: null) null = get all pages, true=only admin UI, false = all but admin UI
	 * @param array $load_properties
	 *
	 * @return Data_Tree
	 */
	public function getTree( $site_ID, Locale $locale, $admin_UI = null,  $load_properties=array('name'=>'this.name')  ) {
		//DO NOT USE PAGE DATA INSTANCE! Why? Because PERFORMANCE!

		$load_properties['ID'] = 'this.ID';
		$load_properties['locale'] = 'this.locale';
		$load_properties['site_ID'] = 'this.site_ID';
		$load_properties['parent_ID'] = 'this.parent_ID';

		$query = array(
			'this.site_ID' => (string)$site_ID,
			'AND',
			'this.locale' => $locale,
		);

		if(is_bool($admin_UI)) {
			$query[] = 'AND';
			$query['this.is_admin_UI'] = $admin_UI;
		}

		$data = $this->fetchDataAll(
			$load_properties,
			$query
		);

		$tree = new Data_Tree();
		$tree->setDataSource($data);

		return $tree;
	}

	/**
	 *
	 * @param bool|null $admin_UI (optional, default: null) null = get all pages, true=only admin UI, false = all but admin UI
	 * @param array $only_properties
	 *
	 * @internal param bool $exclude_admin_UI
	 * @return Data_Tree_Forest
	 */
	public function getAllPagesTree( $admin_UI = null,  $only_properties=array('name') ) {
		//DO NOT USE PAGE DATA INSTANCE! Why? Because PERFORMANCE!

		$only_properties[] = 'site_ID';
		$only_properties[] = 'locale';
		$only_properties[] = 'ID';
		$only_properties[] = 'parent_ID';

		$properties = $this->getDataModelDefinition()->getProperties();

		foreach(array_keys($properties) as $property_name) {
			if(!in_array($property_name, $only_properties)) {
				unset($properties[$property_name]);
			}

		}

		$ID_properties = array( 'ID','site_ID','locale' );

		$forest = new Data_Tree_Forest();
		$forest->setIDKey('ID');
		$forest->setLabelKey('name');

		foreach( Mvc_Sites::getAllSitesList() as $site ) {
			foreach($site->getLocales() as $locale) {
				$query = array(
					'this.site_ID' => (string)$site->getID(),
					'AND',
					'this.locale' => $locale
				);

				if(is_bool($admin_UI)) {
					$query[] = 'AND';
					$query['this.is_admin_UI'] = $admin_UI;
				}

				$this->setSiteID( $site->getID() );
				$this->setLocale( $locale );

				$_data = $this->fetchDataAll( $properties, $query );

				$data = array();
				foreach($_data as $d) {
					$ID = array();
					$parent_ID = array();

					foreach( $ID_properties as $ID_item ) {
						$ID[$ID_item] = (string)$d[$ID_item];
					}

					foreach( $ID_properties as $ID_item ) {
						if($ID_item=='ID') {
							$parent_ID['parent_ID'] = (string)$d['parent_ID'];
						} else {
							$parent_ID[$ID_item] = (string)$d[$ID_item];
						}
					}

					foreach( $ID_properties as $ID_item ) {
						unset($d[$ID_item]);
					}

					$d['ID'] = implode(':', $ID);
					if($d['parent_ID']) {
						$d['parent_ID'] = implode(':', $parent_ID);
					} else {
						$d['name'] = $site->getName().' - '.$locale;
					}

					$data[] = $d;
				}

				$tree = new Data_Tree();
				$tree->setData($data);

				$forest->appendTree($tree);

			}
		}

		return $forest;
	}

	/**
	 * @return DataModel_Fetch_Object_IDs
	 */
	public function getChildrenIDs() {
		return $this->fetchObjectIDs( array(
			'this.parent_ID' => $this->ID,
		) );
	}

	/**
	 * @return Mvc_Pages_Page_Abstract[]
	 */
	public function getChildren() {

		return $this->fetchObjects(
			array(
				'this.parent_ID' => $this->ID,
				'AND',
				'this.site_ID' => (string)$this->site_ID,
				'AND',
				'this.locale' => $this->locale
			)
		);
	}


	/**
	 * @return array
	 */
	public function getLayoutsList() {
		if(!$this->site_ID) {
			return array();
		}

		return  $this->getSite()->getLayoutsList();
	}

	/**
	 * @return array
	 */
	public function jsonSerialize() {
		$data = parent::jsonSerialize();

		$parent = $this->getParent();

		if($parent) {
			$data['parent_URL'] = $parent->getDefaultURL();
		}

		$data['default_URL'] = $this->getDefaultURL();


		$data['layouts_list'] = $this->getLayoutsList();

		$data['URLs'] = $this->getURLs(false);

		return $data;
	}


	/**
	 * @param string $form_name
	 * @param bool $include_related_objects (optional, default=false)
	 * @param bool $skip_hidden_fields (optional, default=false)
	 *
	 * @return Form
	 */
	public function getCommonForm( $form_name='', $include_related_objects=true, $skip_hidden_fields=false ) {
		$form = parent::getCommonForm( $form_name, $include_related_objects, $skip_hidden_fields );

		if( $this->ID!=Mvc_Pages::HOMEPAGE_ID ) {
			$form->getField('URL_fragment')->setIsRequired(true);
		}


		if($this->site_ID) {
			$form->getField('layout')->setSelectOptions( $this->getLayoutsList() );
		}

		return $form;
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

		$replace = array('!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '.', '\'','"' ,'/','<','>',';','?','{','}','[',']','|');
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
	 * @param array|Mvc_Router_Map_URL_Abstract[] $site_base_URLs
	 * @param array|Mvc_Router_Map_URL_Abstract[] $parent_page_URLs
	 *
	 * @return Mvc_Router_Map_URL_Abstract[]
	 *
	 */
	public function generateMapURLs( array $site_base_URLs, array $parent_page_URLs ) {

		/**
		 * @var Mvc_Router_Map_URL_Abstract[] $URLs
		 */
		$URLs = [];

		if($this->ID==Mvc_Pages::HOMEPAGE_ID) {

			$URLs = $site_base_URLs;

		} else {
			foreach( $parent_page_URLs as $parent_URL ) {
				$URL = clone $parent_URL;

				$str_URL = $URL.$this->URL_fragment.'/';

				$URL->setURL( $str_URL );
				$URL->setIsMain( false );

				$URLs[] = $URL;
			}
		}

		$has_main = false;
		foreach( $URLs as $URL ) {
			$URL->takePageData( $this );

			if($has_main) {
				continue;
			}

			if($URL->getIsDefault()) {
				if( $this->SSL_required && $URL->getIsSSL() ) {
					$URL->setIsMain( true );
					$has_main = true;
				}

				if( !$this->SSL_required && !$URL->getIsSSL() ) {
					$URL->setIsMain( true );
					$has_main = true;
				}
			}
		}

		if($has_main) {
			foreach( $URLs as $URL ) {
				$URL->setIsMain(true);
				break;
			}
		}


		return $URLs;
	}

}