<?php
namespace Jet;

//define('JET_APPLICATION_MODULE_MANIFEST_CLASS_NAME', __NAMESPACE__.'\Application_Modules_Module_Manifest');
define('JET_APPLICATION_MODULE_MANIFEST_CLASS_NAME', 'JetExampleApp\Application_Modules_Module_Manifest');

define('JET_MVC_ROUTER_CLASS',          __NAMESPACE__.'\Mvc_Router');

//define('JET_MVC_PAGE_CLASS',          __NAMESPACE__.'\Mvc_Page');
define('JET_MVC_PAGE_CLASS',          'JetExampleApp\Mvc_Page');
define('JET_MVC_PAGE_META_TAG_CLASS', __NAMESPACE__.'\Mvc_Page_MetaTag');
define('JET_MVC_PAGE_CONTENT_CLASS',  __NAMESPACE__.'\Mvc_Page_Content');

define('JET_MVC_SITE_CLASS',                    __NAMESPACE__.'\Mvc_Site');
define('JET_MVC_SITE_LOCALIZED_CLASS',          __NAMESPACE__.'\Mvc_Site_LocalizedData');
define('JET_MVC_SITE_LOCALIZED_META_TAG_CLASS', __NAMESPACE__.'\Mvc_Site_LocalizedData_MetaTag');
define('JET_MVC_SITE_LOCALIZED_URL_CLASS',      __NAMESPACE__.'\Mvc_Site_LocalizedData_URL');

define('JET_MVC_NAVIGATION_DATA_BREADCRUMB_CLASS', __NAMESPACE__.'\Mvc_NavigationData_Breadcrumb');

define('JET_MVC_LAYOUT_CSS_PACKAGE_CREATOR_CLASS',        __NAMESPACE__.'\Mvc_Layout_PackageCreator_CSS');
define('JET_MVC_LAYOUT_JAVASCRIPT_PACKAGE_CREATOR_CLASS', __NAMESPACE__.'\Mvc_Layout_PackageCreator_JavaScript');


define('JET_DATA_MODEL_BACKEND_CLASS_NAME_PREFIX',             __NAMESPACE__.'\DataModel_Backend_');
define('JET_DATA_MODEL_PROPERTY_DEFINITION_CLASS_NAME_PREFIX', __NAMESPACE__.'\DataModel_Definition_Property_');


define('JET_DB_CONNECTION_CLASS_PREFIX', __NAMESPACE__.'\Db_Connection_');
define('JET_DB_CONNECTION_ADAPTER', 'PDO');

define('JET_FORM_FIELD_CLASS_NAME_PREFIX', __NAMESPACE__.'\Form_Field_');

define('JET_TRANSLATOR_BACKEND_CLASS_NAME_PREFIX', __NAMESPACE__.'\Translator_Backend_');

