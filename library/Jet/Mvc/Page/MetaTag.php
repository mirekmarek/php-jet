<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

require_once 'MetaTag/Interface.php';

/**
 *
 */
class Mvc_Page_MetaTag extends BaseObject implements Mvc_Page_MetaTag_Interface
{

	/**
	 * @var ?Mvc_Page_Interface
	 */
	protected ?Mvc_Page_Interface $__page = null;

	/**
	 *
	 * @var string
	 */
	protected string $attribute = '';

	/**
	 *
	 * @var string
	 */
	protected string $attribute_value = '';

	/**
	 *
	 * @var string
	 */
	protected string $content = '';

	/**
	 * @param Mvc_Page_Interface $page
	 * @param array $data
	 *
	 * @return static
	 */
	public static function createByData( Mvc_Page_Interface $page, array $data ): static
	{
		/**
		 * @var Mvc_Page_MetaTag $meta_tag
		 */
		$meta_tag = Mvc_Factory::getPageMetaTagInstance();
		$meta_tag->setPage( $page );

		$meta_tag->setData( $data );

		return $meta_tag;
	}


	/**
	 * @param array $data
	 */
	protected function setData( array $data ): void
	{
		foreach( $data as $key => $val ) {
			$this->{$key} = $val;
		}
	}

	/**
	 * @return Mvc_Page_Interface
	 */
	public function getPage(): Mvc_Page_Interface
	{
		if( !$this->__page ) {
			return Mvc::getCurrentPage();
		}

		return $this->__page;
	}

	/**
	 * @param Mvc_Page_Interface $page
	 */
	public function setPage( Mvc_Page_Interface $page ): void
	{
		$this->__page = $page;
	}

	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString(): string
	{
		if( $this->attribute ) {
			return '<meta ' . $this->attribute . '="' . Data_Text::htmlSpecialChars(
					$this->attribute_value
				) . '" content="' . Data_Text::htmlSpecialChars( $this->content ) . '" />';
		} else {
			return '<meta content="' . Data_Text::htmlSpecialChars( $this->content ) . '" />';
		}
	}

	/**
	 * @return string
	 */
	public function getAttribute(): string
	{
		return $this->attribute;
	}

	/**
	 * @param string $attribute
	 */
	public function setAttribute( string $attribute ): void
	{
		$this->attribute = $attribute;
	}

	/**
	 * @return string
	 */
	public function getAttributeValue(): string
	{
		return $this->attribute_value;
	}

	/**
	 * @param string $attribute_value
	 */
	public function setAttributeValue( string $attribute_value ): void
	{
		$this->attribute_value = $attribute_value;
	}

	/**
	 * @return string
	 */
	public function getContent(): string
	{
		return $this->content;
	}

	/**
	 * @param string $content
	 */
	public function setContent( string $content ): void
	{
		$this->content = $content;
	}


	/**
	 * @return array
	 */
	public function toArray(): array
	{
		$data = get_object_vars( $this );
		foreach( $data as $k => $v ) {
			if( $k[0] == '_' ) {
				unset( $data[$k] );
			}
		}

		return $data;
	}
}