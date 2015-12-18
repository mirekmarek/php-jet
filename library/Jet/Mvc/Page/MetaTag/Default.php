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
 * @subpackage Mvc_Pages
 */
namespace Jet;

/**
 * Class Mvc_Page_MetaTag_Default
 *
 * @JetDataModel:database_table_name = 'Jet_Mvc_Pages_MetaTags'
 * @JetDataModel:parent_model_class_name = 'Mvc_Page_Default'
 */
class Mvc_Page_MetaTag_Default extends Mvc_Page_MetaTag_Abstract {

	/**
	 * @JetDataModel:related_to = 'main.site_ID'
	 * @JetDataModel:is_ID = true
	 * @JetDataModel:form_field_type = false
	 */
	protected $site_ID;

	/**
	 * @JetDataModel:related_to = 'main.ID'
	 * @JetDataModel:is_ID = true
	 * @JetDataModel:form_field_type = false
	 */
	protected $page_ID;

	/**
	 * @JetDataModel:related_to = 'main.locale'
	 * @JetDataModel:is_ID = true
	 * @JetDataModel:form_field_type = false
	 */
	protected $locale;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID
	 * @JetDataModel:is_ID = true
	 * @JetDataModel:form_field_type = false
	 *
	 * @var string
	 */
	protected $ID = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 *
	 * @var string
	 */
	protected $attribute = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 *
	 * @var string
	 */
	protected $attribute_value = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 *
	 * @var string
	 */
	protected $content = '';


	/**
	 * @return string
	 */
	public function  toString() {
		if($this->attribute) {
			return '<meta '.$this->attribute.'="'.Data_Text::htmlSpecialChars($this->attribute_value).'" content="'.Data_Text::htmlSpecialChars($this->content).'" />';
		} else {
			return '<meta content="'.Data_Text::htmlSpecialChars($this->content).'" />';
		}
	}

    /**
     * @param array $data
     * @return void
     */
    public function setData( array $data ) {
        foreach( $data as $key=>$val ) {
            $this->{$key} = $val;
        }
    }


	/**
	 * @return mixed|null|string
	 */
	public function getArrayKeyValue() {
		return $this->ID;
	}

	/**
	 * @param string $ID
	 */
	public function setIdentifier( $ID ) {
		$this->ID = $ID;
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