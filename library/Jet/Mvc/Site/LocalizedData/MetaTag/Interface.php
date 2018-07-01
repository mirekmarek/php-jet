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
interface Mvc_Site_LocalizedData_MetaTag_Interface
{

	/**
	 * @param Mvc_Site_LocalizedData_Interface $localized_data
	 * @param array                            $data
	 *
	 * @return Mvc_Site_LocalizedData_MetaTag_Interface
	 */
	public static function createByData( Mvc_Site_LocalizedData_Interface $localized_data, array $data );

	/**
	 * @return Mvc_Site_LocalizedData_Interface
	 */
	public function getLocalizedData();

	/**
	 * @param Mvc_Site_LocalizedData_Interface $localized_data
	 */
	public function setLocalizedData( Mvc_Site_LocalizedData_Interface $localized_data );

	/**
	 * @return string
	 */
	public function __toString();

	/**
	 * @return string
	 */
	public function toString();

	/**
	 * @return string
	 */
	public function getAttribute();

	/**
	 * @param string $attribute
	 */
	public function setAttribute( $attribute );

	/**
	 * @return string
	 */
	public function getAttributeValue();

	/**
	 * @param string $attribute_value
	 */
	public function setAttributeValue( $attribute_value );

	/**
	 * @return string
	 */
	public function getContent();

	/**
	 * @param string $content
	 */
	public function setContent( $content );


	/**
	 * @return array
	 */
	public function toArray();

}