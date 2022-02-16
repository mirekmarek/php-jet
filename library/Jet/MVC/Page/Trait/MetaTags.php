<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
trait MVC_Page_Trait_MetaTags
{
	/**
	 *
	 * @var MVC_Page_MetaTag[]
	 */
	protected array $meta_tags = [];

	/**
	 *
	 * @return MVC_Page_MetaTag_Interface[]
	 */
	public function getMetaTags(): array
	{
		$meta_tags = [];

		foreach( $this->getBase()->getLocalizedData( $this->getLocale() )->getDefaultMetaTags() as $mt ) {
			$key = $mt->getAttribute() . ':' . $mt->getAttributeValue();
			if( $key == ':' ) {
				$key = $mt->getContent();
			}
			$meta_tags[$key] = $mt;
		}

		foreach( $this->meta_tags as $mt ) {
			$key = $mt->getAttribute() . ':' . $mt->getAttributeValue();
			if( $key == ':' ) {
				$key = $mt->getContent();
			}
			$meta_tags[$key] = $mt;
		}

		return $meta_tags;
	}

	/**
	 * @param string $attribute
	 * @param string $attribute_value
	 * @param string $content
	 */
	public function setMetaTag( string $attribute, string $attribute_value, string $content ) : void
	{
		foreach($this->getMetaTags() as $meta_tag) {

			if(
				$meta_tag->getAttribute()==$attribute &&
				$meta_tag->getAttributeValue()==$attribute_value
			) {
				$meta_tag->setContent( $content );

				return;
			}

		}

		$meta_tag = Factory_MVC::getPageMetaTagInstance();
		$meta_tag->setAttribute($attribute);
		$meta_tag->setAttributeValue($attribute_value);
		$meta_tag->setContent($content);
		$this->addMetaTag($meta_tag);
	}

	/**
	 * @param MVC_Page_MetaTag_Interface[] $meta_tags
	 */
	public function setMetaTags( array $meta_tags ): void
	{
		$this->meta_tags = [];

		foreach( $meta_tags as $meta_tag ) {
			$this->addMetaTag( $meta_tag );
		}
	}

	/**
	 * @param MVC_Page_MetaTag_Interface $meta_tag
	 */
	public function addMetaTag( MVC_Page_MetaTag_Interface $meta_tag ): void
	{

		$meta_tag->setPage( $this );
		$this->meta_tags[] = $meta_tag;
	}

}