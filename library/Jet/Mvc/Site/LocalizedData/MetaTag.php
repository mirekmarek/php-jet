<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

require_once 'MetaTag/Interface.php';

/**
 *
 */
class Mvc_Site_LocalizedData_MetaTag extends BaseObject implements Mvc_Site_LocalizedData_MetaTag_Interface
{

	/**
	 *
	 * @var Mvc_Site_LocalizedData_Interface
	 */
	protected $__localized_data;

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
	 * @param Mvc_Site_LocalizedData_Interface $localized_data
	 * @param array                            $data
	 *
	 * @return Mvc_Site_LocalizedData_MetaTag_Interface
	 */
	public static function createByData( Mvc_Site_LocalizedData_Interface $localized_data, array $data )
	{
		/**
		 * @var Mvc_Site_LocalizedData_MetaTag $meta_tag
		 */
		$meta_tag = Mvc_Factory::getSiteLocalizedMetaTagInstance();
		$meta_tag->setLocalizedData( $localized_data );

		$meta_tag->setData( $data );

		return $meta_tag;
	}

	/**
	 * @param array $data
	 */
	protected function setData( array $data )
	{
		foreach( $data as $key => $val ) {
			$this->{$key} = $val;
		}
	}

	/**
	 * @return Mvc_Site_LocalizedData_Interface
	 */
	public function getLocalizedData()
	{
		return $this->__localized_data;
	}

	/**
	 * @param Mvc_Site_LocalizedData_Interface $localized_data
	 */
	public function setLocalizedData( Mvc_Site_LocalizedData_Interface $localized_data )
	{
		$this->__localized_data = $localized_data;
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

	/**
	 * @return array
	 */
	public function toArray()
	{
		$data = get_object_vars( $this );
		foreach( $data as $k => $v ) {
			if( $k[0]=='_' ) {
				unset( $data[$k] );
			}
		}

		return $data;
	}
}