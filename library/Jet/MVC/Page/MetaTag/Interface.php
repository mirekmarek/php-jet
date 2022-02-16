<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
interface MVC_Page_MetaTag_Interface
{

	/**
	 * @param MVC_Page_Interface $page
	 * @param array $data
	 *
	 * @return static
	 */
	public static function _createByData( MVC_Page_Interface $page, array $data ): static;

	/**
	 * @param MVC_Page_Interface $page
	 */
	public function setPage( MVC_Page_Interface $page ): void;

	/**
	 * @return MVC_Page_Interface
	 */
	public function getPage(): MVC_Page_Interface;


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
}