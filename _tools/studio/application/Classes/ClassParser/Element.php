<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

/**
 *
 */
abstract class ClassParser_Element {

	/**
	 * @var ClassParser
	 */
	public $parser;

	/**
	 * @var string
	 */
	public $name = '';

	/**
	 * @var ClassParser_Token
	 */
	public $start_token;

	/**
	 * @var ClassParser_Token
	 */
	public $end_token;

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
	public function nextToken()
	{
		$parser = $this->parser;
		$parser->index++;
		if($parser->index>=count($parser->tokens)) {
			return null;
		}

		return $parser->tokens[$parser->index];
	}

	/**
	 *
	 */
	public function parseError()
	{
		$this->parser->parseError();
	}

	/**
	 *
	 */
	public function remove()
	{
		$parser = $this->parser;

		$parser->removeTokens( $this->start_token, $this->end_token );
	}

	/**
	 * @param string $new_text
	 */
	public function replace( $new_text )
	{
		$new_text = trim($new_text);

		$parser = $this->parser;

		$parser->replaceTokens( $this->start_token, $this->end_token, $new_text );
	}

	/**
	 * @return string
	 */
	public function toString()
	{
		return $this->parser->getTokenText( $this->start_token, $this->end_token );
	}

	/**
	 *
	 */
	abstract public function debug_showResult();

}
