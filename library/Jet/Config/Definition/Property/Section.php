<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Config_Definition_Property_Section extends Config_Definition_Property
{
	/**
	 * @var string
	 */
	protected $_type = Config::TYPE_SECTION;

	/**
	 * @var array
	 */
	protected $default_value = null;

	/**
	 * @var string
	 */
	protected $section_creator_method_name = '';

	/**
	 * @param array|null $definition_data
	 *
	 * @throws Config_Exception
	 */
	public function setUp( array $definition_data = null )
	{
		parent::setUp( $definition_data );

		if(
			$definition_data!==null &&
			!$this->section_creator_method_name
		) {
			throw new Config_Exception(
				$this->_configuration_class.'::'.$this->name.': section_creator_method_name is not defined ',
				Config_Exception::CODE_DEFINITION_NONSENSE
			);

		}
	}


	/**
	 * @param Config $config
	 *
	 * @return Config_Section|mixed
	 */
	public function getDefaultValue( Config $config )
	{
		/**
		 * @var Config_Section $section
		 */
		$section = $config->{$this->section_creator_method_name}( [] );
		$section->setConfig( $config );

		return $section;
	}



	/**
	 *
	 * @param array $value
	 * @param Config $config
	 *
	 * @return Config_Section
	 *
	 */
	public function prepareValue( $value, Config $config )
	{
		/**
		 * @var Config_Section $section
		 */
		$section = $config->{$this->section_creator_method_name}( $value );
		$section->setConfig( $config );

		return $section;

	}

	/**
	 *
	 * @param mixed &$value
	 */
	public function checkValueType( &$value )
	{
	}

	/**
	 *
	 * @param mixed &$value
	 *
	 */
	protected function checkValue( $value )
	{
	}


	/**
	 *
	 * @param mixed $property_value
	 *
	 * @return Form_Field|null|Form_Field[]
	 */
	public function createFormField( $property_value )
	{
		if(
			$this->getFormFieldType()===false ||
			!$property_value
		) {
			return null;
		}

		/**
		 * @var Config_Section $property_value
		 */

		$fields = [];


		$form = $property_value->getCommonForm();

		foreach( $form->getFields() as $field ) {
			$prefix = '/'.$this->getName();

			$field_name = $field->getName();

			if($field_name[0]=='/') {
				$field_name = $prefix.$field_name;
			} else {
				$field_name = $prefix.'/'.$field_name;
			}

			$field->setName( $field_name );

			$fields[] = $field;
		}

		return $fields;
	}
}