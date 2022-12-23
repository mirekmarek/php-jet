<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\BaseObject;
use Jet\MVC;
use Jet\Locale;
use Jet\Data_Text;
use Jet\Form_Field;


/**
 */
class Project extends BaseObject implements Application_Part
{


	/**
	 * @var string
	 */
	protected static string $application_namespace = 'JetApplication';

	/**
	 * @param bool $as_string
	 *
	 * @return string[]|Locale[]
	 */
	public static function getDefaultLocales( bool $as_string = false ): array
	{
		$locales = [];

		foreach( MVC::getBases() as $base ) {
			foreach( $base->getLocales() as $locale ) {
				$locale_str = (string)$locale;

				$locales[$locale_str] = $as_string ? $locale_str : $locale;
			}
		}

		return $locales;
	}

	/**
	 * @param string $application_namespace
	 */
	public static function setApplicationNamespace( string $application_namespace ): void
	{
		self::$application_namespace = $application_namespace;
	}


	/**
	 * @return string
	 */
	public static function getApplicationNamespace(): string
	{
		return static::$application_namespace;
	}


	/**
	 * @param string $name
	 * @param callable $check_exists_callback
	 *
	 * @return string
	 */
	public static function generateIdentifier( string $name, callable $check_exists_callback ): string
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

			$id = $base_id . $i;
		}

		return $id;
	}


	/**
	 * @param Form_Field $field
	 *
	 * @return bool
	 */
	public static function validateClassName( Form_Field $field ): bool
	{
		if( !$field->getIsRequired() ) {
			return true;
		}


		$class_name = $field->getValue();

		if( !$class_name ) {
			$field->setError( Form_Field::ERROR_CODE_EMPTY );
			return false;
		}

		if(
			!preg_match( '/^([a-zA-Z1-9\\\_]{3,})$/', $class_name ) ||
			str_contains( $class_name, '\\\\' ) ||
			str_contains( $class_name, '__' ) ||
			str_ends_with( $class_name, '\\' )
		) {
			$field->setError( Form_Field::ERROR_CODE_INVALID_FORMAT );

			return false;
		}

		return true;

	}


	/**
	 * @param Form_Field $field
	 *
	 * @return bool
	 */
	public static function validateMethodName( Form_Field $field ): bool
	{
		$method_name = $field->getValue();

		if($method_name) {
			if(
				!preg_match( '/^([a-zA-Z1-9_]{3,})$/', $method_name ) ||
				str_contains( $method_name, '__' )
			) {
				$field->setError( Form_Field::ERROR_CODE_INVALID_FORMAT );

				return false;
			}

		}

		return true;

	}


	/**
	 * @param Form_Field $field
	 *
	 * @return bool
	 */
	public static function validateControllerName( Form_Field $field ): bool
	{
		if( !$field->getIsRequired() ) {
			return true;
		}

		$controller_name = $field->getValue();

		if( !$controller_name ) {
			$field->setError( Form_Field::ERROR_CODE_EMPTY );
			return false;
		}

		if(
			!preg_match( '/^([a-zA-Z1-9_]{3,})$/', $controller_name ) ||
			str_contains( $controller_name, '__' )
		) {
			$field->setError( Form_Field::ERROR_CODE_INVALID_FORMAT );

			return false;
		}

		return true;

	}


}