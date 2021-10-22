<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
trait Mvc_Page_Trait_Content
{
	/**
	 *
	 * @var string|callable
	 */
	protected $output;

	/**
	 *
	 * @var Mvc_Page_Content_Interface[]
	 */
	protected array $content = [];


	/**
	 * @param string|callable $output
	 */
	public function setOutput( string|callable $output ): void
	{
		$this->output = $output;
		$this->content = [];
	}

	/**
	 * @return string|callable|null
	 */
	public function getOutput(): string|callable|null
	{
		return $this->output;
	}




	/**
	 *
	 * @return Mvc_Page_Content_Interface[]
	 */
	public function getContent(): array
	{
		return $this->content;
	}

	/**
	 * @param Mvc_Page_Content_Interface[] $contents
	 */
	public function setContent( array $contents ): void
	{
		$this->content = [];

		foreach( $contents as $c ) {
			$this->addContent( $c );
		}
	}

	/**
	 * @param Mvc_Page_Content_Interface $content
	 */
	public function addContent( Mvc_Page_Content_Interface $content ): void
	{
		$this->output = '';

		$content->setPage( $this );

		$this->content[] = $content;
	}

	/**
	 * @param int $index
	 */
	public function removeContent( int $index ): void
	{
		unset( $this->content[$index] );

		$this->content = array_values( $this->content );
	}

}