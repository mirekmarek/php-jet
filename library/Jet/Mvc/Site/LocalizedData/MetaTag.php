<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 * @JetDataModel:name = 'site_localized_data_meta_tag'
 * @JetDataModel:database_table_name = 'Jet_Mvc_Sites_LocalizedData_MetaTags'
 * @JetDataModel:parent_model_class_name = JET_MVC_SITE_LOCALIZED_CLASS
 * @JetDataModel:id_class_name = 'DataModel_Id_UniqueString'
 */
class Mvc_Site_LocalizedData_MetaTag extends BaseObject implements Mvc_Site_LocalizedData_MetaTag_Interface {

	/**
	 * @JetDataModel:related_to = 'main.id'
	 * @JetDataModel:is_id = true
	 *
	 * @var string
	 */
	protected $site_id = '';

	/**
	 *
	 * @JetDataModel:related_to = 'parent.locale'
	 * @JetDataModel:is_id = true
	 *
	 * @var Locale
	 */
	protected $locale = '';


	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID
	 * @JetDataModel:is_id = true
	 *
	 * @var string
	 */
	protected $id = '';

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
	 * @return mixed|null|string
	 */
	public function getArrayKeyValue() {
		return $this->id;
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
	 * @param string $id
	 */
	public function setIdentifier( $id ) {
		$this->id = $id;
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

	/**
	 * @return array
	 */
	public function toArray() {
		return get_object_vars($this);
	}
}