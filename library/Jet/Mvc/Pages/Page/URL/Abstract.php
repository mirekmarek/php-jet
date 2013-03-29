<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Pages
 */
namespace Jet;

abstract class Mvc_Pages_Page_URL_Abstract extends DataModel_Related_1toN {
	/**
	 * @var string
	 */
	protected static $__factory_class_name = "Jet\\Mvc_Factory";
	/**
	 * @var string
	 */
	protected static $__factory_class_method = "getPageURLInstance";
	/**
	 * @var string
	 */
	protected static $__factory_must_be_instance_of_class_name = "Jet\\Mvc_Pages_Page_URL_Abstract";

	/**
	 * @param string $URL
	 * @param bool $is_default (optional, default: false )
	 * @param bool $is_SSL (optional, default: false )
	 */
	abstract function __construct($URL="", $is_default=false, $is_SSL=false);

	/**
	 * @return string
	 */
	public function  __toString() {
		return $this->toString();
	}

	/**
	 * @return string
	 */
	abstract function  toString();

	/**
	 * @return string
	 */
	abstract public function getURL();

	/**
	 * @param string $URL
	 */
	abstract public function setURL($URL);

	/**
	 * @return bool
	 */
	abstract public function getIsDefault();

	/**
	 * @param bool $is_default
	 */
	abstract public function setIsDefault($is_default);

	/**
	 * @return bool
	 */
	abstract public function getIsSSL();

	/**
	 * @param bool $is_SSL
	 */
	abstract public function setIsSSL($is_SSL);


}