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
class Factory_Config
{
	protected static array $property_definition_class_names = [
		Config::TYPE_STRING   => Config_Definition_Property_String::class,
		Config::TYPE_BOOL     => Config_Definition_Property_Bool::class,
		Config::TYPE_INT      => Config_Definition_Property_Int::class,
		Config::TYPE_FLOAT    => Config_Definition_Property_Float::class,
		Config::TYPE_ARRAY    => Config_Definition_Property_Array::class,
		Config::TYPE_SECTION  => Config_Definition_Property_Section::class,
		Config::TYPE_SECTIONS => Config_Definition_Property_Sections::class,
	];

	protected static string $main_config_definition_class_name = Config_Definition_Config::class;
	protected static string $config_section_definition_class_name = Config_Definition_Config_Section::class;

	/**
	 * @param string $type
	 * @return string
	 * @throws Config_Exception
	 */
	public static function getPropertyDefinitionClassName( string $type ) : string
	{
		if(!isset(static::$property_definition_class_names[$type])) {
			throw new Config_Exception(
				'Unknown property type \''.$type.'\'',
				Config_Exception::CODE_DEFINITION_NONSENSE
			);
		}

		return static::$property_definition_class_names[$type];
	}

	/**
	 * @param string $type
	 * @param string $class_name
	 */
	public static function setPropertyDefinitionClassName( string $type, string $class_name ) : void
	{
		static::$property_definition_class_names[$type] = $class_name;
	}

	/**
	 * @return string
	 */
	public static function getMainConfigDefinitionClassName(): string
	{
		return static::$main_config_definition_class_name;
	}

	/**
	 * @param string $class_name
	 */
	public static function setMainConfigDefinitionClassName( string $class_name ): void
	{
		static::$main_config_definition_class_name = $class_name;
	}

	/**
	 * @return string
	 */
	public static function getConfigSectionDefinitionClassName(): string
	{
		return static::$config_section_definition_class_name;
	}

	/**
	 * @param string $class_name
	 */
	public static function setConfigSectionDefinitionClassName( string $class_name ): void
	{
		static::$config_section_definition_class_name = $class_name;
	}


}