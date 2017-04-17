<?php
/**
 *
 *
 *
 * Class describes one meta tag
 *
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Sites
 */
namespace Jet;

interface Mvc_Site_LocalizedData_MetaTag_Interface {


	/**
	 * @return string
	 */
	public function  toString();

	/**
	 * @param string $id
	 */
	public function setIdentifier($id );

	/**
	 * @return string
	 */
	public function getAttribute();

	/**
	 * @param string $attribute
	 */
	public function setAttribute($attribute);

	/**
	 * @return string
	 */
	public function getAttributeValue();

	/**
	 * @param string $attribute_value
	 */
	public function setAttributeValue($attribute_value);

	/**
	 * @return string
	 */
	public function getContent();

	/**
	 * @param string $content
	 */
	public function setContent($content);
}