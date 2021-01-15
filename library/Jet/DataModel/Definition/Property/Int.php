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
class DataModel_Definition_Property_Int extends DataModel_Definition_Property
{
	/**
	 * @var string
	 */
	protected string $type = DataModel::TYPE_INT;

	/**
	 * @var int
	 */
	protected $default_value = 0;

	/**
	 * @var string|bool
	 */
	protected string|bool $form_field_type = Form::TYPE_INT;

	/**
	 * @param array $definition_data
	 *
	 */
	public function setUp( array $definition_data ): void
	{

		if( !$definition_data ) {
			return;
		}

		parent::setUp( $definition_data );

		if( $this->form_field_min_value !== null ) {
			$this->form_field_min_value = (int)$this->form_field_min_value;
		}
		if( $this->form_field_max_value !== null ) {
			$this->form_field_max_value = (int)$this->form_field_max_value;
		}

	}

	/**
	 * @param mixed &$value
	 */
	public function checkValueType( mixed &$value ): void
	{
		$value = (int)$value;
	}
}