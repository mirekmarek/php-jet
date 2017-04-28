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
 */
class Mvc_Page_MetaTag extends BaseObject implements Mvc_Page_MetaTag_Interface {

	/**
	 * @var Mvc_Page
	 */
	protected $page;

	/**
	 *
	 * @var string
	 */
	protected $attribute = '';

	/**
	 *
	 * @var string
	 */
	protected $attribute_value = '';

	/**
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
	 * @return Mvc_Page_Interface
	 */
	public function getPage()
	{
		if(!$this->page) {
			return Mvc::getCurrentPage();
		}

		return $this->page;
	}

	/**
	 * @param Mvc_Page_Interface $page
	 */
	public function setPage(Mvc_Page_Interface $page)
	{
		$this->page = $page;
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