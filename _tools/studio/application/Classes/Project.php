<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\BaseObject;
use Jet\Mvc_Site;
use Jet\Locale;
use Jet\Http_Request;
use Jet\Form_Field_Input;
use Jet\Data_Text;
use Jet\Form_Field;
use Jet\SysConf_URI;


/**
 */
class Project extends BaseObject implements Application_Part {


	/**
	 * @var string
	 */
	protected static $application_namespace = 'JetApplication';

	/**
	 * @param bool $as_string
	 *
	 * @return string[]|Locale[]
	 */
	public static function getDefaultLocales( $as_string=false )
	{
		$locales = [];

		foreach(Mvc_Site::loadSites() as $site) {
			foreach($site->getLocales() as $locale) {
				$locale_str = (string)$locale;

				$locales[$locale_str] = $as_string ? $locale_str : $locale;
			}
		}

		return $locales;
	}

	/**
	 * @param string $application_namespace
	 */
	public static function setApplicationNamespace( $application_namespace )
	{
		self::$application_namespace = $application_namespace;
	}



	/**
	 * @return string
	 */
	public static function getApplicationNamespace()
	{
		return static::$application_namespace;
	}


	/**
	 * @param string $name
	 * @param callable $check_exists_callback
	 *
	 * @return string
	 */
	public static function generateIdentifier( $name, callable $check_exists_callback)
	{

		$id = Data_Text::removeAccents( $name );
		$id = str_replace( ' ', '-', $id );
		$id = preg_replace( '/[^a-z0-9-]/i', '', $id );
		$id = strtolower( $id );
		$id = preg_replace( '~([-]{2,})~', '-', $id );

		$id = trim( $id, '-' );

		$base_id = $id;
		$i = 0;
		while( $check_exists_callback( $id ) ) {
			$i++;

			$id = $base_id.$i;
		}

		return $id;
	}


	/**
	 * @param Form_Field $field
	 *
	 * @return bool
	 */
	public static function validateClassName( Form_Field $field )
	{
		if(!$field->getIsRequired()) {
			return true;
		}


		$class_name = $field->getValue();

		if(
			$field->getIsRequired() &&
			!$class_name
		) {
			$field->setError( Form_Field::ERROR_CODE_EMPTY );
			return false;
		}

		if(
			!preg_match('/^([a-zA-Z1-9\\\_]{3,})$/', $class_name) ||
			strpos( $class_name, '\\\\' )!==false ||
			strpos( $class_name, '__' )!==false ||
			substr($class_name, -1)=='\\'
		) {
			$field->setError( Form_Field::ERROR_CODE_INVALID_FORMAT );

			return false;
		}

		return true;

	}


	/**
	 * @param Form_Field $field
	 * @param Form_Field_Input|null $class_name_field
	 *
	 * @return bool
	 */
	public static function validateMethodName( Form_Field $field, Form_Field_Input $class_name_field=null )
	{
		if(!$field->getIsRequired()) {
			return true;
		}


		$method_name = $field->getValue();

		if(
			!$method_name
		) {
			$field->setError( Form_Field::ERROR_CODE_EMPTY );
			if($class_name_field) {
				$class_name_field->setCustomError( $field->getErrorMessage( Form_Field::ERROR_CODE_EMPTY ) );
			}
			return false;
		}

		if(
			!preg_match('/^([a-zA-Z1-9_]{3,})$/', $method_name) ||
			strpos( $method_name, '__' )!==false
		) {
			$field->setError( Form_Field::ERROR_CODE_INVALID_FORMAT );
			if($class_name_field) {
				$class_name_field->setCustomError( $field->getErrorMessage( Form_Field::ERROR_CODE_INVALID_FORMAT ) );
			}

			return false;
		}

		return true;

	}


	/**
	 * @param Form_Field $field
	 *
	 * @return bool
	 */
	public static function validateControllerName( Form_Field $field )
	{
		if(!$field->getIsRequired()) {
			return true;
		}

		$controller_name = $field->getValue();

		if(
			$field->getIsRequired() &&
			!$controller_name
		) {
			$field->setError( Form_Field::ERROR_CODE_EMPTY );
			return false;
		}

		if(
			!preg_match('/^([a-zA-Z1-9_]{3,})$/', $controller_name) ||
			strpos( $controller_name, '__' )!==false
		) {
			$field->setError( Form_Field::ERROR_CODE_INVALID_FORMAT );

			return false;
		}

		return true;

	}


}