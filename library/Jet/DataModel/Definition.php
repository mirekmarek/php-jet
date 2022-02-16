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
class DataModel_Definition extends BaseObject
{

	/**
	 *
	 * @var DataModel_Definition_Model_Main[]|DataModel_Definition_Model_Related[]
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
	 * @return DataModel_Definition_Model_Main|DataModel_Definition_Model_Related
	 */
	public static function get( string $class_name ): DataModel_Definition_Model_Main|DataModel_Definition_Model_Related
	{
		if( !isset( static::$__definitions[$class_name] ) ) {
			/**
			 * @var DataModel $class_name
			 */
			$type = $class_name::dataModelDefinitionType();

			static::$__definitions[$class_name] = Factory_DataModel::getModelDefinitionInstance( $type, $class_name );
			static::$__definitions[$class_name]->init();
		}


		return static::$__definitions[$class_name];

	}

}
