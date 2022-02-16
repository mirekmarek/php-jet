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
class ClassParser_Attribute extends ClassParser_Element
{

	/**
	 * @var string
	 */
	public string $name = '';

	/**
	 * @var string
	 */
	public string $arguments = '';

	/**
	 * @param ClassParser $parser
	 */
	public static function parse( ClassParser $parser ): void
	{
		$attribute = new static( $parser );

		$token = $parser->tokens[$parser->index];

		$parser->__attributes[] = $attribute;

		$attribute->start_token = $token;
		$attribute->parser = $parser;

		$got_name = false;
		$searching_for_arguments = false;
		$got_argument_value = false;
		$array_counter = 0;

		do {
			if( !($token = $attribute->nextToken()) ) {
				break;
			}

			if( $token->ignore() ) {
				continue;
			}

			switch( $token->id ) {
				case T_STRING:
					if( !$got_name ) {
						$attribute->name = $token->text;
						$got_name = true;
						continue 2;
					}
					if( $searching_for_arguments ) {
						$attribute->arguments .= $token->text;
						$got_argument_value = true;
						continue 2;
					}

					$parser->parseError();
					break;
				case T_DOUBLE_COLON:
				case T_CONSTANT_ENCAPSED_STRING:
				case T_CLASS:
				case ':':
				case T_DOUBLE_ARROW:
					if( $searching_for_arguments ) {
						$attribute->arguments .= $token->text;
						continue 2;
					}
					$parser->parseError();
					break;
				case '[':
					if( $searching_for_arguments ) {
						$attribute->arguments .= $token->text;
						$array_counter++;
						continue 2;
					}
					$parser->parseError();
					break;

				case '(':
					if(
						!$got_name ||
						$searching_for_arguments
					) {
						$parser->parseError();
					}
					$searching_for_arguments = true;
					$searching_for_argument_name = true;
					continue 2;

				case ')';
					if( $got_argument_value ) {
						$searching_for_arguments = false;
						$searching_for_argument_name = false;
						$searching_for_argument_value = false;
						continue 2;
					}

					$parser->parseError();
					break;
				case ']':
					if( $array_counter > 0 ) {
						$array_counter--;
						$attribute->arguments .= $token->text;
						continue 2;
					}

					if( $got_name && !$searching_for_arguments ) {
						$attribute->end_token = $token;

						return;
					}
					$parser->parseError();

					break;
			}

		} while( true );

	}

	/**
	 *
	 */
	public function debug_showResult(): void
	{
	}
}