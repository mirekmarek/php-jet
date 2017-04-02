<?php
/**
 *
 *
 *
 * Class describes one meta tag
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
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
 * Class Mvc_Page_MetaTag
 *
 * @JetDataModel:name = 'page_meta_tag'
 * @JetDataModel:parent_model_class_name = 'Mvc_Page'
 * @JetDataModel:id_class_name = 'DataModel_Id_UniqueString'
 * @JetDataModel:database_table_name = 'Jet_Mvc_Pages_MetaTags'
 * @JetDataModel:parent_model_class_name = 'Mvc_Page'
 */
class Mvc_Page_MetaTag extends BaseObject implements Mvc_Page_MetaTag_Interface {

	/**
	 * @JetDataModel:related_to = 'main.site_id'
	 * @JetDataModel:is_id = true
	 * @JetDataModel:form_field_type = false
	 */
	protected $site_id;

	/**
	 * @JetDataModel:related_to = 'main.id'
	 * @JetDataModel:is_id = true
	 * @JetDataModel:form_field_type = false
	 */
	protected $page_id;

	/**
	 * @JetDataModel:related_to = 'main.locale'
	 * @JetDataModel:is_id = true
	 * @JetDataModel:form_field_type = false
	 */
	protected $locale;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID
	 * @JetDataModel:is_id = true
	 * @JetDataModel:form_field_type = false
	 *
	 * @var string
	 */
	protected $meta_tag_id = '';

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

    }

    /**
     * @return string
     */
    public function  __toString() {
        return $this->toString();
    }

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
		return $this->meta_tag_id;
	}

	/**
	 * @param string $id
	 */
	public function setId($id ) {
		$this->meta_tag_id = $id;
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