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
class Config_Definition_Property_Sections extends Config_Definition_Property
{
	/**
	 * @var string
	 */
	protected $_type = Config::TYPE_SECTIONS;

	/**
	 * @var string
	 */
	protected $section_creator_method_name = '';

	/**
	 * @var array
	 */
	protected $default_value = [];

	/**
	 * @param array $definition_data
	 *
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
	 *
	 * @param array $value
	 * @param Config $config
	 *
	 * @return Config_Definition_Property_Section[]
	 *
	 * @throws Config_Exception
	 */
	public function prepareValue( $value, Config $config )
	{

		$sections = [];
		foreach( $value as $name=>$data ) {
			$sections[$name] = $config->{$this->section_creator_method_name}( $data );
			$sections[$name]->setConfig( $config );
		}

		return $sections;
	}

	/**
	 * Do nothing
	 *
	 * @param mixed $value
	 */
	public function checkValueType( &$value )
	{
	}

	/**
	 *
	 * @param mixed &$value
	 *
	 * @throws Config_Exception
	 */
	protected function checkValue( $value )
	{
	}

	/**
	 *
	 * @param mixed $property_value
	 *
	 * @throws DataModel_Exception
	 * @return Form_Field|null|Form_Field[]
	 */
	public function createFormField( $property_value )
	{
		if( $this->getFormFieldType()===false ) {
			return null;
		}

		/**
		 * @var Config_Section[] $property_value
		 */

		$fields = [];

		foreach( $property_value as $key=>$section ) {

			$form = $section->getCommonForm();

			foreach( $form->getFields() as $field ) {
				$prefix = '/'.$this->getName().'/'.$key;

				$field_name = $field->getName();

				if($field_name[0]=='/') {
					$field_name = $prefix.$field_name;
				} else {
					$field_name = $prefix.'/'.$field_name;
				}

				$field->setName( $field_name );

				$fields[] = $field;
			}

		}

		return $fields;

	}

}