<?php
/**
 *
 *
 *
 * Class describes one meta tag
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
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

abstract class Mvc_Sites_Site_LocalizedData_MetaTag_Abstract extends DataModel_Related_1toN {
	/**
	 * @var string
	 */
	protected static $__factory_class_name = 'Jet\\Mvc_Factory';
	/**
	 * @var string
	 */
	protected static $__factory_class_method = 'getLocalizedSiteMetaTagInstance';
	/**
	 * @var string
	 */
	protected static $__factory_must_be_instance_of_class_name = 'Jet\\Mvc_Sites_Site_LocalizedData_MetaTag_Abstract';

	/**
	 * @param string $content (optional)
	 * @param string $attribute (optional)
	 * @param string $attribute_value (optional)
	 */
	abstract public function __construct($content='', $attribute='', $attribute_value='');

	/**
	 * @return string
	 */
	public function  __toString() {
		return $this->toString();
	}

	/**
	 * @return string
	 */
	abstract public function  toString();

	/**
	 * @return string
	 */
	abstract public function getAttribute();

	/**
	 * @param string $attribute
	 */
	abstract public function setAttribute($attribute);

	/**
	 * @return string
	 */
	abstract public function getAttributeValue();

	/**
	 * @param string $attribute_value
	 */
	abstract public function setAttributeValue($attribute_value);

	/**
	 * @return string
	 */
	abstract public function getContent();

	/**
	 * @param string $content
	 */
	abstract public function setContent($content);
}