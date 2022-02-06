<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class DataModel_Definition_Property_Float extends DataModel_Definition_Property
{
	/***
	 * @var string
	 */
	protected string $type = DataModel::TYPE_FLOAT;

	/**
	 * @var string|bool
	 */
	protected string|bool $form_field_type = Form_Field::TYPE_FLOAT;

	/**
	 * @param array $definition_data
	 *
	 * @throws DataModel_Exception
	 */
	public function setUp( array $definition_data ): void
	{
		if( !$definition_data ) {
			return;
		}

		parent::setUp( $definition_data );

		if( $this->form_field_min_value !== null ) {
			$this->form_field_min_value = (float)$this->form_field_min_value;
		}
		if( $this->form_field_max_value !== null ) {
			$this->form_field_max_value = (float)$this->form_field_max_value;
		}

	}

	/**
	 * @param mixed &$value
	 */
	public function checkValueType( mixed &$value ): void
	{
		$value = (float)$value;
	}

}