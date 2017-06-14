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
interface Mvc_Page_MetaTag_Interface
{

	/**
	 * @param Mvc_Page_Interface $page
	 * @param array              $data
	 *
	 * @return Mvc_Page_MetaTag_Interface
	 */
	public static function createByData( Mvc_Page_Interface $page, array $data );

	/**
	 * @param Mvc_Page_Interface $page
	 */
	public function setPage( Mvc_Page_Interface $page );

	/**
	 * @return Mvc_Page_Interface
	 */
	public function getPage();


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
}