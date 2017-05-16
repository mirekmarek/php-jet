<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class DataModel_Definition_Property_Locale
 * @package Jet
 */
class DataModel_Definition_Property_Locale extends DataModel_Definition_Property
{
	/**
	 * @var string
	 */
	protected $_type = DataModel::TYPE_LOCALE;

	/**
	 * @var null
	 */
	protected $default_value = null;

	/**
	 * @var string
	 */
	protected $form_field_type = Form::TYPE_SELECT;

	/**
	 * @param mixed $value
	 */
	public function checkValueType( &$value )
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
	 * Converts property form jsonSerialize
	 *
	 * Example: Locale to string
	 *
	 * @param DataModel_Interface $data_model_instance
	 * @param mixed               &$property
	 *
	 * @return mixed
	 */
	public function getJsonSerializeValue( DataModel_Interface $data_model_instance, &$property )
	{
		if( !$property ) {
			return $property;
		}

		return (string)$property;
	}
}