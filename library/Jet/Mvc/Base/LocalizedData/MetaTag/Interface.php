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
interface Mvc_Base_LocalizedData_MetaTag_Interface
{

	/**
	 * @param Mvc_Base_LocalizedData_Interface $localized_data
	 * @param array $data
	 *
	 * @return static
	 */
	public static function _createByData( Mvc_Base_LocalizedData_Interface $localized_data, array $data ): static;

	/**
	 * @return Mvc_Base_LocalizedData_Interface
	 */
	public function getLocalizedData(): Mvc_Base_LocalizedData_Interface;

	/**
	 * @param Mvc_Base_LocalizedData_Interface $localized_data
	 */
	public function setLocalizedData( Mvc_Base_LocalizedData_Interface $localized_data ): void;

	/**
	 * @return string
	 */
	public function __toString(): string;

	/**
	 * @return string
	 */
	public function toString(): string;

	/**
	 * @return string
	 */
	public function getAttribute(): string;

	/**
	 * @param string $attribute
	 */
	public function setAttribute( string $attribute ): void;

	/**
	 * @return string
	 */
	public function getAttributeValue(): string;

	/**
	 * @param string $attribute_value
	 */
	public function setAttributeValue( string $attribute_value ): void;

	/**
	 * @return string
	 */
	public function getContent(): string;

	/**
	 * @param string $content
	 */
	public function setContent( string $content ): void;

	/**
	 * @return array
	 */
	public function toArray(): array;

}