<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class DataModel_Definition_Property_Int
 * @package Jet
 */
class DataModel_Definition_Property_Int extends DataModel_Definition_Property
{
	/**
	 * @var string
	 */
	protected $_type = DataModel::TYPE_INT;

	/**
	 * @var int
	 */
	protected $default_value = 0;

	/**
	 * @var string
	 */
	protected $form_field_type = Form::TYPE_INT;

	/**
	 * @param array $definition_data
	 *
	 */
	public function setUp( $definition_data )
	{

		if( !$definition_data ) {
			return;
		}

		parent::setUp( $definition_data );

		if( $this->form_field_min_value!==null ) {
			$this->form_field_min_value = (int)$this->form_field_min_value;
		}
		if( $this->form_field_max_value!==null ) {
			$this->form_field_max_value = (int)$this->form_field_max_value;
		}

	}

	/**
	 * @param mixed $value
	 */
	public function checkValueType( &$value )
	{
		$value = (int)$value;
	}

	/**
	 * @return string
	 */
	public function getTechnicalDescription()
	{
		$res = 'Type: '.$this->getType().' ';

		$res .= ', required: '.( $this->form_field_is_required ? 'yes' : 'no' );

		if( $this->is_id ) {
			$res .= ', is id';
		}

		if( $this->default_value ) {
			$res .= ', default value: '.$this->default_value;
		}

		if( $this->form_field_min_value ) {
			$res .= ', min. value: '.$this->form_field_min_value;
		}

		if( $this->form_field_max_value ) {
			$res .= ', max. value: '.$this->form_field_max_value;
		}

		if( $this->description ) {
			$res .= JET_EOL.JET_EOL.$this->description;
		}

		return $res;
	}

}