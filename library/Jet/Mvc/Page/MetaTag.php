<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

require_once 'MetaTag/Interface.php';

/**
 *
 */
class Mvc_Page_MetaTag extends BaseObject implements Mvc_Page_MetaTag_Interface
{

	/**
	 * @var Mvc_Page
	 */
	protected $__page;

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
	 * @param Mvc_Page_Interface $page
	 * @param array              $data
	 *
	 * @return Mvc_Page_MetaTag_Interface
	 */
	public static function createByData( Mvc_Page_Interface $page, array $data )
	{
		$meta_tag = Mvc_Factory::getPageMetaTagInstance();
		$meta_tag->setPage( $page );

		foreach( $data as $key => $val ) {
			$meta_tag->{$key} = $val;
		}

		return $meta_tag;
	}

	/**
	 * @return Mvc_Page_Interface
	 */
	public function getPage()
	{
		if( !$this->__page ) {
			return Mvc::getCurrentPage();
		}

		return $this->__page;
	}

	/**
	 * @param Mvc_Page_Interface $__page
	 */
	public function setPage( Mvc_Page_Interface $__page )
	{
		$this->__page = $__page;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString()
	{
		if( $this->attribute ) {
			return '<meta '.$this->attribute.'="'.Data_Text::htmlSpecialChars(
				$this->attribute_value
			).'" content="'.Data_Text::htmlSpecialChars( $this->content ).'" />';
		} else {
			return '<meta content="'.Data_Text::htmlSpecialChars( $this->content ).'" />';
		}
	}

	/**
	 * @return string
	 */
	public function getAttribute()
	{
		return $this->attribute;
	}

	/**
	 * @param string $attribute
	 */
	public function setAttribute( $attribute )
	{
		$this->attribute = $attribute;
	}

	/**
	 * @return string
	 */
	public function getAttributeValue()
	{
		return $this->attribute_value;
	}

	/**
	 * @param string $attribute_value
	 */
	public function setAttributeValue( $attribute_value )
	{
		$this->attribute_value = $attribute_value;
	}

	/**
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * @param string $content
	 */
	public function setContent( $content )
	{
		$this->content = $content;
	}
}