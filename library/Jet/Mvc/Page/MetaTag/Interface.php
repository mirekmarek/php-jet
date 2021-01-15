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
interface Mvc_Page_MetaTag_Interface
{

	/**
	 * @param Mvc_Page_Interface $page
	 * @param array $data
	 *
	 * @return static
	 */
	public static function createByData( Mvc_Page_Interface $page, array $data ): static;

	/**
	 * @param Mvc_Page_Interface $page
	 */
	public function setPage( Mvc_Page_Interface $page ): void;

	/**
	 * @return Mvc_Page_Interface
	 */
	public function getPage(): Mvc_Page_Interface;


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