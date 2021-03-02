<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use Attribute;

/**
 *
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class DataModel_Definition extends BaseObject
{

	/**
	 *
	 * @var DataModel_Definition_Model_Main[]|DataModel_Definition_Model_Related_1to1[]|DataModel_Definition_Model_Related_1toN[]|DataModel_Definition_Model_Related[]|DataModel_Definition_Model_Related_MtoN[]
	 */
	protected static array $__definitions = [];

	/**
	 * @param mixed ...$attributes
	 */
	public function __construct( ...$attributes )
	{
	}

	/**
	 * Returns model definition
	 *
	 * @param string $class_name
	 *
	 * @return DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN|DataModel_Definition_Model_Related|DataModel_Definition_Model_Related_MtoN
	 */
	public static function get( string $class_name ): DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN|DataModel_Definition_Model_Related|DataModel_Definition_Model_Related_MtoN
	{


		if( isset( static::$__definitions[$class_name] ) ) {
			return static::$__definitions[$class_name];
		}

		/**
		 * @var DataModel $class_name
		 */
		$definition = $class_name::dataModelDefinitionFactory( $class_name );

		static::$__definitions[$class_name] = $definition;
		$definition->init();

		return $definition;

	}

}
