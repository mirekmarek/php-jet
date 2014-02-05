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
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Sites
 */
namespace Jet;

/**
 * Class Mvc_Sites_Site_LocalizedData_MetaTag_Default
 *
 * @JetDataModel:database_table_name = 'Jet_Mvc_Sites_LocalizedData_MetaTags'
 * @JetDataModel:parent_model_class_name = 'Jet\\Mvc_Sites_Site_LocalizedData_Default'
 */
class Mvc_Sites_Site_LocalizedData_MetaTag_Default extends Mvc_Sites_Site_LocalizedData_MetaTag_Abstract {

	/**
	 * @JetDataModel:related_to = 'main.ID'
	 *
	 * @var string
	 */
	protected $site_ID = '';

	/**
	 * @JetDataModel:related_to = 'parent.ID'
	 *
	 * @var string
	 */
	protected $localized_data_ID = '';


	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_ID
	 * @JetDataModel:is_ID = true
	 *
	 * @var string
	 */
	protected $ID = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 *
	 * @var string
	 */
	protected $attribute = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 *
	 * @var string
	 */
	protected $attribute_value = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 *
	 * @var string
	 */
	protected $content = '';

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