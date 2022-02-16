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
abstract class Config_Definition_Property extends BaseObject
{

	/**
	 * @var string
	 */
	protected string $_type;


	/**
	 * @var string
	 */
	protected string $_configuration_class;

	/**
	 * @var string
	 */
	protected string $name = '';

	/**
	 * @var bool
	 */
	protected bool $is_required = false;


	/**
	 *
	 * @param string $configuration_class_name
	 * @param string $name
	 * @param ?array $definition_data (optional)
	 *
	 */
	public function __construct( string $configuration_class_name, string $name, ?array $definition_data = null )
	{
		$this->_configuration_class = $configuration_class_name;
		$this->name = $name;

		$this->setUp( $definition_data );
	}

	/**
	 * @param ?array $definition_data
	 *
	 * @throws Config_Exception
	 */
	public function setUp( ?array $definition_data = null ): void
	{
		if( !$definition_data ) {
			return;
		}

		foreach( $definition_data as $key => $val ) {
			if( !$this->objectHasProperty( $key ) ) {
				throw new Config_Exception(
					$this->_configuration_class . '::' . $this->name . ': unknown definition option \'' . $key . '\'  ',
					Config_Exception::CODE_DEFINITION_NONSENSE
				);
			}

			$this->{$key} = $val;
		}


		$this->is_required = (bool)$this->is_required;
	}

	/**
	 *
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}


	/**
	 * @return bool
	 */
	public function getIsRequired(): bool
	{
		return $this->is_required;
	}

	/**
	 * @param bool $is_required
	 */
	public function setIsRequired( bool $is_required ): void
	{
		$this->is_required = $is_required;
	}

	/**
	 * @return string
	 */
	public function getType(): string
	{
		return $this->_type;
	}

	/**
	 *
	 * @param mixed $value
	 * @param Config $config
	 *
	 * @return mixed
	 *
	 * @throws Config_Exception
	 */
	public function prepareValue( mixed $value, Config $config ): mixed
	{

		$this->checkValueType( $value );
		$this->checkValue( $value );

		return $value;

	}

	/**
	 *
	 * @param mixed &$value
	 */
	abstract protected function checkValueType( mixed &$value ): void;

	/**
	 *
	 * @param mixed $value
	 *
	 * @throws Config_Exception
	 */
	abstract protected function checkValue( mixed $value ): void;
	
	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString(): string
	{
		return $this->_configuration_class . '::' . $this->getName();
	}

}