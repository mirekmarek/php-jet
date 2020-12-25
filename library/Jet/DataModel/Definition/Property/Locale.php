<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class DataModel_Definition_Property_Locale extends DataModel_Definition_Property
{
	/**
	 * @var string
	 */
	protected string $type = DataModel::TYPE_LOCALE;

	/**
	 * @var string|bool
	 */
	protected string|bool $form_field_type = Form::TYPE_SELECT;

	/**
	 * @param mixed $value
	 */
	public function checkValueType( mixed &$value ) : void
	{
		if( !is_object( $value ) ) {
			$value = new Locale( $value );
		} else {
			if( !$value instanceof Locale ) {
				$value = new Locale();
			}
		}
	}

	/**
	 *
	 * @param mixed &$property
	 *
	 * @return mixed
	 */
	public function getJsonSerializeValue( mixed &$property ) : mixed
	{
		if( !$property ) {
			return $property;
		}

		return (string)$property;
	}
}