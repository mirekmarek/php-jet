<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class Factory_Validator
{
	
	/**
	 * @var array<string,string>
	 */
	protected static array $validator_class_names = [
		Validator::TYPE_COLOR      => Validator_Color::class,
		Validator::TYPE_DATE       => Validator_Date::class,
		Validator::TYPE_DATE_TIME  => Validator_DateTime::class,
		Validator::TYPE_EMAIL      => Validator_Email::class,
		Validator::TYPE_FILE       => Validator_File::class,
		Validator::TYPE_FILE_IMAGE => Validator_FileImage::class,
		Validator::TYPE_FLOAT      => Validator_Float::class,
		Validator::TYPE_INT        => Validator_Int::class,
		Validator::TYPE_MONTH      => Validator_Month::class,
		Validator::TYPE_OPTION     => Validator_Option::class,
		Validator::TYPE_OPTIONS    => Validator_Options::class,
		Validator::TYPE_PASSWORD   => Validator_Password::class,
		Validator::TYPE_REGEXP     => Validator_RegExp::class,
		Validator::TYPE_TEL        => Validator_Tel::class,
		Validator::TYPE_TIME       => Validator_Time::class,
		Validator::TYPE_URL        => Validator_Url::class,
		Validator::TYPE_WEEK       => Validator_Week::class,
		Validator::TYPE_NULL       => Validator_Null::class,
	];
	
	public static function getValidatorClassName( string $type ): string|Validator
	{
		if( !isset( static::$validator_class_names[$type] ) ) {
			throw new Entity_Validator_Definition_Exception(
				'Unknown validator type \'' . $type . '\''
			);
		}
		
		return static::$validator_class_names[$type];
	}

	public static function setFieldClassName( string $type, string $class_name ): string
	{
		return static::$validator_class_names[$type] = $class_name;
	}
	
	public static function getValidatorInstance( string $type ): Validator
	{
		$class_name = static::getValidatorClassName( $type );
		
		return new $class_name();
	}
	
	/**
	 * @return array<string,string>
	 */
	public static function getValidatorClassNames(): array
	{
		return static::$validator_class_names;
	}
	
	/**
	 * @param array<string,string> $validator_class_names
	 */
	public static function setValidatorClassNames( array $validator_class_names ): void
	{
		static::$validator_class_names = $validator_class_names;
	}
	
	
	/**
	 * @param string $validator_type
	 * @param string $validator_class_name
	 */
	public static function registerNewValidatorType( string $validator_type, string $validator_class_name ) : void
	{
		static::$validator_class_names[$validator_type] = $validator_class_name;
	}
	
	/**
	 * @return array<string>
	 */
	public static function getRegisteredValidatorTypes() : array
	{
		return array_keys( static::$validator_class_names );
	}
}