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
class Factory_InputCatcher
{
	/**
	 * @var array<string,string>
	 */
	protected static array $input_catcher_class_names = [
		InputCatcher::TYPE_STRING     => InputCatcher_String::class,
		InputCatcher::TYPE_STRINGS    => InputCatcher_Strings::class,
		InputCatcher::TYPE_STRING_RAW => InputCatcher_StringRaw::class,
		InputCatcher::TYPE_DATE       => InputCatcher_Date::class,
		InputCatcher::TYPE_DATE_TIME  => InputCatcher_DateTime::class,
		InputCatcher::TYPE_FLOAT      => InputCatcher_Float::class,
		InputCatcher::TYPE_FLOATS     => InputCatcher_Floats::class,
		InputCatcher::TYPE_INT        => InputCatcher_Int::class,
		InputCatcher::TYPE_INTS       => InputCatcher_Ints::class,
		InputCatcher::TYPE_BOOL       => InputCatcher_Bool::class,
		InputCatcher::TYPE_FILE       => InputCatcher_File::class,
	];
	
	public static function getInputCatcherClassName( string $type ): string|InputCatcher
	{
		if( !isset( static::$input_catcher_class_names[$type] ) ) {
			throw new InputCatcher_Definition_Exception(
				'Unknown input catcher type \'' . $type . '\''
			);
		}
		
		return static::$input_catcher_class_names[$type];
	}
	
	public static function setFieldClassName( string $type, string $class_name ): string
	{
		return static::$input_catcher_class_names[$type] = $class_name;
	}
	
	public static function getInputCatcherInstance( string $type, string $name, mixed $default_value ): InputCatcher
	{
		$class_name = static::getInputCatcherClassName( $type );
		
		return new $class_name( $name, $default_value );
	}
	
	/**
	 * @return array<string,string>
	 */
	public static function getInputCatcherClassNames(): array
	{
		return static::$input_catcher_class_names;
	}
	
	/**
	 * @param array<string,string> $validator_class_names
	 */
	public static function setInputCatcherClassNames( array $validator_class_names ): void
	{
		static::$input_catcher_class_names = $validator_class_names;
	}
	
	
	/**
	 * @param string $validator_type
	 * @param string $validator_class_name
	 */
	public static function registerNewInputCatcherType( string $validator_type, string $validator_class_name ) : void
	{
		static::$input_catcher_class_names[$validator_type] = $validator_class_name;
	}
	
	/**
	 * @return array<string>
	 */
	public static function getRegisteredInputCatcherTypes() : array
	{
		return array_keys( static::$input_catcher_class_names );
	}
	
}