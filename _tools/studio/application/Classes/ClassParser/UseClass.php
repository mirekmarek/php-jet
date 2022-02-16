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
class ClassParser_UseClass extends ClassParser_Element
{
	/**
	 * @var string
	 */
	public string $class = '';

	/**
	 * @var string
	 */
	public string $as = '';

	/**
	 * @param ClassParser $parser
	 */
	public static function parse( ClassParser $parser ): void
	{
		$use = new static( $parser );

		$token = $parser->tokens[$parser->index];
		$use->start_token = $token;

		$getting_as = false;

		do {

			if( !($token = $use->nextToken()) ) {
				break;
			}

			if( $token->ignore() ) {
				continue;
			}

			switch( $token->id ) {
				case T_STRING:
				case T_NS_SEPARATOR:
				case T_NAME_QUALIFIED:
					if( $getting_as ) {
						$use->as .= $token->text;
					} else {
						$use->class .= $token->text;
					}
					break;
				case T_AS:
					$getting_as = true;
					break;
				case ';':
					$use->end_token = $parser->tokens[$parser->index];

					if( !$use->as ) {
						$class_parts = explode( '\\', $use->class );

						$use->as = end( $class_parts );
					}

					$parser->use_classes[] = $use;
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

		echo 'Use: ' . $this->class;
		if( $this->as ) {
			echo ' as ' . $this->as;
		}

		echo PHP_EOL . ' Code: ' . $parser->getTokenText( $this->start_token, $this->end_token );
		echo PHP_EOL . ' Tokens: ' . $this->start_token->index . ' - ' . $this->end_token->index;

		echo PHP_EOL . PHP_EOL;
	}


}
