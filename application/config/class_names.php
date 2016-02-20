<?php
namespace Jet;

define('JET_MVC_ROUTER_CLASS',          __NAMESPACE__.'\Mvc_Router');
define('JET_MVC_ROUTER_CONFIG_CLASS',   __NAMESPACE__.'\Mvc_Router_Config');
define('JET_MVC_ROUTER_CACHE_BACKEND_CLASS_NAME_PREFIX', __NAMESPACE__.'\Mvc_Router_Cache_Backend_');

define('JET_MVC_PAGE_CLASS',          __NAMESPACE__.'\Mvc_Page');
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
define('JET_DATA_MODEL_HISTORY_BACKEND_CLASS_NAME_PREFIX',     __NAMESPACE__.'\DataModel_History_Backend_');
define('JET_DATA_MODEL_CACHE_BACKEND_CLASS_NAME_PREFIX',       __NAMESPACE__.'\DataModel_Cache_Backend_');
define('JET_DATA_MODEL_PROPERTY_DEFINITION_CLASS_NAME_PREFIX', __NAMESPACE__.'\DataModel_Definition_Property_');

define('JET_AUTH_CONFIG_CLASS',        __NAMESPACE__.'\Auth_Config');
define('JET_AUTH_USER_CLASS',          __NAMESPACE__.'\Auth_User');
define('JET_AUTH_USER_ROLES_CLASS',    __NAMESPACE__.'\Auth_User_Roles');
define('JET_AUTH_ROLE_CLASS',          __NAMESPACE__.'\Auth_Role');
define('JET_AUTH_ROLE_PRIVILEGE_CLASS',__NAMESPACE__.'\Auth_Role_Privilege');

define('JET_DB_CONNECTION_CLASS_PREFIX', __NAMESPACE__.'\Db_Connection_');
define('JET_DB_CONNECTION_ADAPTER', 'PDO');

define('JET_JETML_LAYOUT_POSTPROCESSOR_CLASS', __NAMESPACE__.'\JetML');
define('JET_JETML_WIDGET_CLASS_NAME_PREFIX',   __NAMESPACE__.'\JetML_Widget_');

define('JET_FORM_FIELD_CLASS_NAME_PREFIX', __NAMESPACE__.'\Form_Field_');
define('JET_FORM_DECORATOR_CLASS_NAME_PREFIX', __NAMESPACE__.'\Form_Decorator_');

define('JET_TRANSLATOR_BACKEND_CLASS_NAME_PREFIX', __NAMESPACE__.'\Translator_Backend_');