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
class ClassParser_Namespace extends ClassParser_Element
{
	/**
	 * @var string
	 */
	public string $namespace = '';

	/**
	 * @param ClassParser $parser
	 */
	public static function parse( ClassParser $parser ): void
	{
		$namespace = new static( $parser );

		$token = $parser->tokens[$parser->index];
		$namespace->start_token = $token;

		do {

			if( !($token = $namespace->nextToken()) ) {
				break;
			}

			if( $token->ignore() ) {
				continue;
			}

			switch( $token->id ) {
				case T_STRING:
				case T_NS_SEPARATOR:
				case T_NAME_QUALIFIED:
					$namespace->namespace .= $token->text;
					break;
				case ';':
					$namespace->end_token = $parser->tokens[$parser->index];

					$parser->namespace = $namespace;
					return;
				default:
					static::parse( $parser );
					return;

			}

		} while( true );

	}

	/**
	 *
	 */
	public function debug_showResult(): void
	{
		$parser = $this->parser;

		echo 'Namespace: ' . $this->namespace;

		echo PHP_EOL . ' Code: ' . $parser->getTokenText( $this->start_token, $this->end_token );
		echo PHP_EOL . ' Tokens: ' . $this->start_token->index . ' - ' . $this->end_token->index;
		echo PHP_EOL . PHP_EOL;
	}

}
