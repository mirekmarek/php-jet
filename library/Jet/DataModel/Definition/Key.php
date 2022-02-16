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
class DataModel_Definition_Key extends BaseObject
{

	/**
	 * @var string
	 */
	protected string $name = '';

	/**
	 * @see DataModel::KEY_TYPE_*
	 *
	 * @var string
	 */
	protected string $type = '';

	/**
	 * @var array
	 */
	protected array $property_names = [];

	/**
	 * @param string $name
	 * @param string $type
	 * @param array $property_names
	 *
	 * @throws DataModel_Exception
	 */
	public function __construct( string $name, string $type = DataModel::KEY_TYPE_INDEX, array $property_names = [] )
	{
		if( !$property_names ) {
			$property_names[] = $name;
		}

		if( !in_array(
			$type, [
				DataModel::KEY_TYPE_INDEX,
				DataModel::KEY_TYPE_PRIMARY,
				DataModel::KEY_TYPE_UNIQUE,
			]
		)
		) {
			throw new DataModel_Exception(
				'Unknown key type', DataModel_Exception::CODE_DEFINITION_NONSENSE
			);

		}

		$this->name = $name;
		$this->property_names = $property_names;
		$this->type = $type;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return array
	 */
	public function getPropertyNames(): array
	{
		return $this->property_names;
	}

	/**
	 * @return string
	 */
	public function getType(): string
	{
		return $this->type;
	}

}