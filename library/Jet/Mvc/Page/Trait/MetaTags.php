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
trait Mvc_Page_Trait_MetaTags
{
	/**
	 *
	 * @var Mvc_Page_MetaTag[]
	 */
	protected $meta_tags = [];

	/**
	 * @param bool $get_default (optional)
	 *
	 * @return Mvc_Page_MetaTag_Interface[]
	 */
	public function getMetaTags( $get_default = false )
	{
		/**
		 * @var Mvc_Page_Trait_MetaTags|Mvc_Page $this
		 */

		if( $get_default ) {
			$meta_tags = [];

			foreach( $this->getSiteLocalizedData()->getDefaultMetaTags() as $mt ) {
				$key = $mt->getAttribute().':'.$mt->getAttributeValue();
				if( $key==':' ) {
					$key = $mt->getContent();
				}
				$meta_tags[$key] = $mt;
			}

			foreach( $this->meta_tags as $mt ) {
				$key = $mt->getAttribute().':'.$mt->getAttributeValue();
				if( $key==':' ) {
					$key = $mt->getContent();
				}
				$meta_tags[$key] = $mt;
			}

			return $meta_tags;

		}

		return $this->meta_tags;
	}

	/**
	 * @param Mvc_Page_MetaTag_Interface[] $meta_tags
	 */
	public function setMetaTags( $meta_tags )
	{
		$this->meta_tags = [];

		foreach( $meta_tags as $meta_tag ) {
			$this->addMetaTag( $meta_tag );
		}
	}

	/**
	 * @param Mvc_Page_MetaTag_Interface $meta_tag
	 */
	public function addMetaTag( Mvc_Page_MetaTag_Interface $meta_tag )
	{
		/**
		 * @var Mvc_Page_Trait_MetaTags|Mvc_Page $this
		 */

		$meta_tag->setPage( $this );
		$this->meta_tags[] = $meta_tag;
	}

	/**
	 * @param int $index
	 */
	public function removeMetaTag( $index )
	{
		unset( $this->meta_tags[$index] );
	}

}