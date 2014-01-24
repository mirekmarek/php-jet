<?php
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package tools
 */
namespace Jet;
require 'includes/bootstrap_cli.php';

/*
Factory::getClassName( Mvc_Factory::DEFAULT_PAGE_CLASS );
Factory::getClassName( Mvc_Factory::DEFAULT_PAGE_META_TAG_CLASS );
Factory::getClassName( Mvc_Factory::DEFAULT_PAGE_CONTENT_CLASS );
Factory::getClassName( Mvc_Factory::DEFAULT_PAGE_URL_CLASS );

Factory::getClassName( Mvc_Factory::DEFAULT_SITE_CLASS );
Factory::getClassName( Mvc_Factory::DEFAULT_LOCALIZED_SITE_CLASS );
Factory::getClassName( Mvc_Factory::DEFAULT_LOCALIZED_SITE_META_TAG_CLASS );
Factory::getClassName( Mvc_Factory::DEFAULT_LOCALIZED_SITE_URL_CLASS );

Factory::getClassName( Auth_Factory::DEFAULT_ROLE_CLASS );
Factory::getClassName( Auth_Factory::DEFAULT_PRIVILEGE_CLASS );
Factory::getClassName( Auth_Factory::DEFAULT_USER_CLASS );
Factory::getClassName( Auth_Factory::DEFAULT_USER_ROLES_CLASS );
*/

//$class_name = 'JetApplicationModule\JetExample\TestModule\DataModelT1';
//$class_name = 'JetApplicationModule\JetExample\AuthManager\Event';
//$class_name = 'JetApplicationModule\JetExample\Articles\Article';
//$class_name = 'JetApplicationModule\JetExample\Images\Gallery';
//$class_name = 'JetApplicationModule\JetExample\Images\Gallery_Image';
//$class_name = 'JetApplicationModule\JetExample\Images\Gallery_Image_Thumbnail';


require JET_BASE_PATH.'_tests/_mock/Jet/DataModel/Query/DataModelTestMock.php';


$class_name = 'Jet\DataModel_Query_DataModelTestMock';

$data = $class_name::$__data_model_properties_definition;

$result = '';

foreach( $data as $prop_name=>$prop_data ) {
	$default_value = isset($prop_data['default_value']) ? $prop_data['default_value'] : null;
	$type = $prop_data['type'];
	$php_type = 'string';

	switch( $type ) {
		case DataModel::TYPE_ID:
			$type = 'Jet\\DataModel::TYPE_ID';
			$php_type = 'string';
			if($default_value===null) $default_value = '';
		break;
		case DataModel::TYPE_STRING:
			$type = 'Jet\\DataModel::TYPE_STRING';
			$php_type = 'string';
			if($default_value===null) $default_value = '';
		break;
		case DataModel::TYPE_BOOL:
			$type = 'Jet\\DataModel::TYPE_BOOL';
			$php_type = 'bool';
			if($default_value===null) $default_value = false;
		break;
		case DataModel::TYPE_INT:
			$type = 'Jet\\DataModel::TYPE_INT';
			$php_type = 'int';
			if($default_value===null) $default_value = 0;
		break;
		case DataModel::TYPE_FLOAT:
			$type = 'Jet\\DataModel::TYPE_FLOAT';
			$php_type = 'float';
			if($default_value===null) $default_value = 0.0;
		break;
		case DataModel::TYPE_LOCALE:
			$type = 'Jet\\DataModel::TYPE_LOCALE';
			$php_type = 'Locale';
			$default_value=null;
		break;
		case DataModel::TYPE_DATE:
			$type = 'Jet\\DataModel::TYPE_DATE';
			$php_type = 'DateTime';
			$default_value=null;
		break;
		case DataModel::TYPE_DATE_TIME:
			$type = 'Jet\\DataModel::TYPE_DATE_TIME';
			$php_type = 'DateTime';
			$default_value=null;
		break;
		case DataModel::TYPE_ARRAY:
			$type = 'Jet\\DataModel::TYPE_ARRAY';
			$php_type = 'array';
			if($default_value===null) $default_value = array();
		break;
		case DataModel::TYPE_DATA_MODEL:
			$type = 'Jet\\DataModel::TYPE_DATA_MODEL';
			$php_type = $prop_data['data_model_class'].'[]';
			$php_type = str_replace('Jet\\', '', $php_type);
			$php_type = str_replace('Default', 'Abstract', $php_type);
			$default_value=null;
		break;

	}

	$result .= JET_EOL;
	$result .= JET_TAB.'/**'.JET_EOL;
	$result .= JET_TAB.' * '.JET_EOL;
	$result .= JET_TAB.' * @JetDataModel:type = '.$type.JET_EOL;
	foreach( $prop_data as $key=>$val ) {
		if($key=='type') {
			continue;
		}
		$val = var_export($val, true);
		$val = str_replace( JET_EOL, '', $val );

		$result .= JET_TAB.' * @JetDataModel:'.$key.' = '.$val.JET_EOL;

	}
	$result .= JET_TAB.' * '.JET_EOL;
	$result .= JET_TAB.' * @var '.$php_type.JET_EOL;
	$result .= JET_TAB.' */'.JET_EOL;
	if($default_value===null) {
		$result .= JET_TAB.'protected $'.$prop_name.';'.JET_EOL;

	} else {
		$result .= JET_TAB.'protected $'.$prop_name.' = '.var_export($default_value, true).';'.JET_EOL;

	}
}

echo JET_EOL;
echo $class_name.JET_EOL.JET_EOL;
echo $result.JET_EOL.JET_EOL;