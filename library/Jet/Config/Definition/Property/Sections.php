<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace Jet;

/**
 *
 */
class Config_Definition_Property_Sections extends Config_Definition_Property
{
	/**
	 * @var string
	 */
	protected string $_type = Config::TYPE_SECTIONS;

	/**
	 * @var string
	 */
	protected string $section_creator_method_name = '';

	/**
	 * @var array
	 */
	protected $default_value = [];

	/**
	 * @param ?array $definition_data
	 *
	 */
	public function setUp( ?array $definition_data = null ): void
	{
		parent::setUp( $definition_data );

		if(
			$definition_data !== null &&
			!$this->section_creator_method_name
		) {
			throw new Config_Exception(
				$this->_configuration_class . '::' . $this->name . ': section_creator_method_name is not defined ',
				Config_Exception::CODE_DEFINITION_NONSENSE
			);

		}

	}

	/**
	 *
	 * @param array $value
	 * @param Config $config
	 *
	 * @return Config_Definition_Property_Section[]
	 *
	 */
	public function prepareValue( mixed $value, Config $config ): array
	{

		$sections = [];
		foreach( $value as $name => $data ) {
			$sections[$name] = $config->{$this->section_creator_method_name}( $data );
			$sections[$name]->setConfig( $config );
		}

		return $sections;
	}

	/**
	 *
	 * @param mixed &$value
	 */
	protected function checkValueType( mixed &$value ): void
	{
	}

	/**
	 *
	 * @param mixed $value
	 */
	protected function checkValue( mixed $value ): void
	{
	}

	/**
	 *
	 * @param mixed $property_value
	 *
	 * @return Form_Field|null|Form_Field[]
	 */
	public function createFormField( mixed $property_value ): Form_Field|null|array
	{
		if( $this->getFormFieldType() === false ) {
			return null;
		}

		/**
		 * @var Config_Section[] $property_value
		 */

		$fields = [];

		foreach( $property_value as $key => $section ) {

			$form = $section->getCommonForm();

			foreach( $form->getFields() as $field ) {
				$prefix = '/' . $this->getName() . '/' . $key;

				$field_name = $field->getName();

				if( $field_name[0] == '/' ) {
					$field_name = $prefix . $field_name;
				} else {
					$field_name = $prefix . '/' . $field_name;
				}

				$field->setName( $field_name );

				$fields[] = $field;
			}

		}

		return $fields;

	}

}