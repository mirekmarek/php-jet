<?php
/**
 *
 *
 *
 * Class describes one meta tag
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Pages
 */
namespace Jet;

class Mvc_Pages_Page_MetaTag_Default extends Mvc_Pages_Page_MetaTag_Abstract {

	/**
	 * @var string
	 */
	protected static $__data_model_model_name = "Jet_Mvc_Pages_Page_MetaTag";
	/**
	 * @var string
	 */
	protected static $__data_model_parent_model_class_name = "Jet\\Mvc_Pages_Page_Default";
	/**
	 * @var array
	 */
	protected static $__data_model_properties_definition = array(
		"ID" => array(
			"type" => self::TYPE_ID,
			"is_ID" => true
		),
		"attribute" => array(
			"type" => self::TYPE_STRING
		),
		"attribute_value" => array(
			"type" => self::TYPE_STRING
		),
		"content" => array(
			"type" => self::TYPE_STRING,
		),
	);

	/**
	 * @var string
	 */
	protected $Jet_Mvc_Pages_Page_ID = "";
	/**
	 * @var string
	 */
	protected $Jet_Mvc_Pages_Page_locale = "";
	/**
	 * @var string
	 */
	protected $Jet_Mvc_Pages_Page_site_ID = "";

	/**
	 * @var string
	 */
	protected $ID = "";

	/**
	 * @var string
	 */
	protected $content = "";
	/**
	 * @var string
	 */
	protected $attribute = "";
	/**
	 * @var string
	 */
	protected $attribute_value = "";

	/**
	 * @param string $content (optional)
	 * @param string $attribute (optional)
	 * @param string $attribute_value (optional)
	 */
	public function __construct($content="", $attribute="", $attribute_value="") {
		if($content) {
			$this->generateID();

			$this->content = $content;
			$this->attribute = $attribute;
			$this->attribute_value = $attribute_value;
		}
	}

	/**
	 * @return string
	 */
	public function  toString() {
		if($this->attribute) {
			return '<meta '.$this->attribute.'="'.htmlspecialchars($this->attribute_value).'" content="'.htmlspecialchars($this->content).'" />';
		} else {
			return '<meta content="'.htmlspecialchars($this->content).'" />';
		}
	}

	/**
	 * @return string
	 */
	public function getAttribute() {
		return $this->attribute;
	}

	/**
	 * @param string $attribute
	 */
	public function setAttribute($attribute) {
		$this->attribute = $attribute;
	}

	/**
	 * @return string
	 */
	public function getAttributeValue() {
		return $this->attribute_value;
	}

	/**
	 * @param string $attribute_value
	 */
	public function setAttributeValue($attribute_value) {
		$this->attribute_value = $attribute_value;
	}

	/**
	 * @return string
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * @param string $content
	 */
	public function setContent($content) {
		$this->content = $content;
	}
}