<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

/**
 *
 */
abstract class ClassParser_Element
{

	/**
	 * @var ?ClassParser
	 */
	public ?ClassParser $parser = null;

	/**
	 * @var string
	 */
	public string $name = '';

	/**
	 * @var ?ClassParser_Token
	 */
	public ?ClassParser_Token $start_token = null;

	/**
	 * @var ?ClassParser_Token
	 */
	public ?ClassParser_Token $end_token = null;

	/**
	 *
	 * @param ClassParser $parser
	 */
	public function __construct( ClassParser $parser )
	{
		$this->parser = $parser;
	}

	/**
	 *
	 * @return ClassParser_Token|null
	 */
	public function nextToken(): ClassParser_Token|null
	{
		$parser = $this->parser;
		$parser->index++;
		if( $parser->index >= count( $parser->tokens ) ) {
			return null;
		}

		return $parser->tokens[$parser->index];
	}

	/**
	 *
	 */
	public function parseError(): void
	{
		$this->parser->parseError();
	}

	/**
	 *
	 */
	public function remove(): void
	{
		$parser = $this->parser;

		$parser->removeTokens( $this->start_token, $this->end_token );
	}

	/**
	 * @param string $new_text
	 */
	public function replace( string $new_text ): void
	{
		$new_text = trim( $new_text );

		$parser = $this->parser;

		$parser->replaceTokens( $this->start_token, $this->end_token, $new_text );
	}

	/**
	 * @return string
	 */
	public function toString(): string
	{
		return $this->parser->getTokenText( $this->start_token, $this->end_token );
	}

	/**
	 *
	 */
	abstract public function debug_showResult(): void;

}
