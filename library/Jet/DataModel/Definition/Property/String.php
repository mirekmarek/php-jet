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
class DataModel_Definition_Property_String extends DataModel_Definition_Property
{
	/**
	 * @var string
	 */
	protected string $type = DataModel::TYPE_STRING;


	/**
	 * @var int
	 */
	protected int $max_len = 255;

	/**
	 * @var string|bool
	 */
	protected string|bool $form_field_type = Form_Field::TYPE_INPUT;

	/**
	 * @param array $definition_data
	 *
	 * @throws DataModel_Exception
	 */
	public function setUp( array $definition_data ): void
	{
		if( $definition_data ) {
			parent::setUp( $definition_data );

			$this->max_len = (int)$this->max_len;
		}

	}

	/**
	 * @param mixed &$value
	 *
	 */
	public function checkValueType( mixed &$value ): void
	{
		$value = (string)$value;
	}

	/**
	 * @return int|null
	 */
	public function getMaxLen(): int|null
	{
		return $this->max_len;
	}

	/**
	 * @return string
	 */
	public function getFormFieldType(): string
	{

		if( $this->form_field_type != Form_Field::TYPE_INPUT ) {
			return $this->form_field_type;
		}

		if( $this->max_len <= 255 ) {
			return Form_Field::TYPE_INPUT;
		} else {
			return Form_Field::TYPE_TEXTAREA;
		}
	}

}