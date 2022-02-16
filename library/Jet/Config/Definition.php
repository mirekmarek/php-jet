<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use Attribute;

/**
 *
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Config_Definition extends BaseObject
{

	/**
	 * @param mixed ...$attributes
	 */
	public function __construct( ...$attributes )
	{
	}

	/**
	 * @param string $class_name
	 *
	 * @return Config_Definition_Config
	 */
	public static function getMainConfigDefinition( string $class_name ): Config_Definition_Config
	{
		$definition_class_name = Factory_Config::getMainConfigDefinitionClassName();

		return new $definition_class_name( $class_name );
	}


	/**
	 * @param string $class_name
	 *
	 * @return Config_Definition_Config_Section
	 */
	public static function getSectionConfigDefinition( string $class_name ): Config_Definition_Config_Section
	{
		$definition_class_name = Factory_Config::getConfigSectionDefinitionClassName();

		return new $definition_class_name( $class_name );
	}
}