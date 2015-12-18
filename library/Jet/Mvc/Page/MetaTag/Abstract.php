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
 * @subpackage Mvc_Pages
 */
namespace Jet;

/**
 * Class Mvc_Page_MetaTag_Abstract
 *
 * @JetFactory:class = 'Mvc_Factory'
 * @JetFactory:method = 'getPageMetaTagInstance'
 * @JetFactory:mandatory_parent_class = 'Mvc_Page_MetaTag_Abstract'
 *
 * @JetDataModel:name = 'page_meta_tag'
 * @JetDataModel:parent_model_class_name = 'Mvc_Page_Abstract'
 * @JetDataModel:ID_class_name = 'DataModel_ID_UniqueString'
 */
abstract class Mvc_Page_MetaTag_Abstract extends DataModel_Related_1toN {

	/**
	 * @param string $content (optional)
	 * @param string $attribute (optional)
	 * @param string $attribute_value (optional)
	 */
	public function __construct($content='', $attribute='', $attribute_value='') {

		if($content) {
			$this->setContent( $content );
			$this->setAttribute( $attribute );
			$this->setAttributeValue( $attribute_value );
		}

		parent::__construct();
	}

	/**
	 * @return string
	 */
	public function  __toString() {
		return $this->toString();
	}

    /**
     * @param array $data
     * @return void
     */
    abstract public function setData( array $data );

	/**
	 * @param string $ID
	 */
	abstract public function setIdentifier( $ID );


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