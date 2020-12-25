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
class DataModel_Definition_Property_Date extends DataModel_Definition_Property
{
	/**
	 * @var string
	 */
	protected string $type = DataModel::TYPE_DATE;

	/**
	 * @var null
	 */
	protected $default_value = null;

	/**
	 * @var string|bool
	 */
	protected string|bool $form_field_type = Form::TYPE_DATE;

	/**
	 * @param mixed $value
	 */
	public function checkValueType( mixed &$value ) : void
	{

		if( $value==='' ) {
			$value = null;
		}

		if( $value===null ) {
			return;
		}

		if( !is_object( $value ) ) {
			$value = new Data_DateTime( $value );
			$value->setTime( 0, 0 );
		} else {
			if( !$value instanceof Data_DateTime ) {
				$value = new Data_DateTime();
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
		/**
		 * @var Data_DateTime $property_value
		 */
		if( !$property ) {
			return $property;
		}

		return (string)$property;
	}

}