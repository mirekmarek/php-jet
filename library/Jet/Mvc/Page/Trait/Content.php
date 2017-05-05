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
trait Mvc_Page_Trait_Content
{

	/**
	 *
	 * @var Mvc_Page_Content_Interface[]
	 */
	protected $content;

	/**
	 *
	 * @return Mvc_Page_Content_Interface[]
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * @param Mvc_Page_Content_Interface[] $content
	 */
	public function setContent( $content )
	{
		$this->content = [];

		foreach( $content as $c ) {
			$this->addContent( $c );
		}
	}

	/**
	 * @param Mvc_Page_Content_Interface $content
	 */
	public function addContent( Mvc_Page_Content_Interface $content )
	{
		/**
		 * @var Mvc_Page|Mvc_Page_Trait_Content $this
		 */
		if( !$content->getId() ) {
			$content->setId( count( $this->content ) );
		}
		$content->setPage( $this );

		$this->content[] = $content;
	}

	/**
	 * @param int $index
	 */
	public function removeContent( $index )
	{
		unset( $this->content[$index] );
	}

}