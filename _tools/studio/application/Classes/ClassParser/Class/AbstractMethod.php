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
class ClassParser_Class_AbstractMethod extends ClassParser_Class_Element
{
	/**
	 * @var ?ClassParser_Token
	 */
	public ?ClassParser_Token $doc_comment = null;

	/**
	 * @var bool
	 */
	public bool $is_static = false;

	/**
	 * @var string
	 */
	public string $name = '';

	/**
	 * @var string
	 */
	public string $param_declaration = '';

	/**
	 * @var string
	 */
	public string $visibility = ClassParser::VISIBILITY_PUBLIC;

	/**
	 * @var ?ClassParser_Token
	 */
	public ?ClassParser_Token $declaration_start = null;

	/**
	 * @var ?ClassParser_Token
	 */
	public ?ClassParser_Token $declaration_end = null;


	/**
	 * @param ClassParser $parser
	 * @param ClassParser_Class $class
	 */
	public static function parse( ClassParser $parser, ClassParser_Class $class )
	{
		$method = new static( $parser, $class );

		$token = $parser->tokens[$parser->index];
		$method->start_token = $token;


		if( $class->_static_token ) {
			$method->is_static = true;
			$method->start_token = $class->_static_token;
		}


		if( $class->_public_token ) {
			$method->start_token = $class->_public_token;
			$method->visibility = ClassParser::VISIBILITY_PUBLIC;
		}

		if( $class->_private_token ) {
			$method->start_token = $class->_private_token;
			$method->visibility = ClassParser::VISIBILITY_PRIVATE;
		}

		if( $class->_protected_token ) {
			$method->start_token = $class->_protected_token;
			$method->visibility = ClassParser::VISIBILITY_PROTECTED;
		}

		if( $class->_abstract_token->index < $method->start_token->index ) {
			$method->start_token = $class->_abstract_token;
		}

		$method->declaration_start = $method->start_token;

		if( $class->_last_doc_comment_token ) {
			$method->doc_comment = $class->_last_doc_comment_token;
			$method->start_token = $class->_last_doc_comment_token;
		}


		$searching_for_param_declaration = false;
		$got_param_declaration = false;


		do {
			if( !($token = $method->nextToken()) ) {
				break;
			}

			if( $token->ignore() ) {
				continue;
			}


			switch( $token->id ) {
				case T_STRING:
					if( !$searching_for_param_declaration ) {
						$method->name = $token->text;
					} else {
						$method->param_declaration .= $token->text;
					}
					break;
				case '(':
					if(
						$method->name &&
						!$searching_for_param_declaration
					) {
						$searching_for_param_declaration = true;
						$method->param_declaration = $token->text;
					} else {
						$method->parseError();
					}
					break;
				case ')':
					if( $searching_for_param_declaration ) {
						$got_param_declaration = true;
						$method->param_declaration .= $token->text;
					} else {
						$method->parseError();
					}
					break;
				case ';':
					if(
						!$method->name ||
						!$got_param_declaration
					) {
						$method->parseError();
					}

					$class->methods[$method->name] = $method;

					$method->end_token = $token;
					$method->declaration_end = $token;

					$class->_last_doc_comment_token = null;
					$class->_static_token = null;
					$class->_private_token = null;
					$class->_protected_token = null;
					$class->_public_token = null;
					$class->_abstract_token = null;
					return;
				default:
					if(
						$searching_for_param_declaration ||
						!$got_param_declaration
					) {

						$method->param_declaration .= $token->text;
					} else {
						$method->parseError();
					}
			}

		} while( true );
	}


	/**
	 *
	 */
	public function debug_showResult(): void
	{
		$parser = $this->parser;

		echo 'Abstract ';

		echo ucfirst( $this->visibility ) . ' Method: ' . $this->name;
		if( $this->doc_comment ) {
			echo PHP_EOL . ' Doc Comment: (token: ' . $this->doc_comment->index . ') ' . $this->doc_comment->text;
		}

		echo PHP_EOL . ' Declaration: ' . $parser->getTokenText( $this->declaration_start, $this->declaration_end );
		echo ' Tokens: ' . $this->declaration_start->index . ' - ' . $this->declaration_end->index;

		echo PHP_EOL . ' Tokens: ' . $this->start_token->index . ' - ' . $this->end_token->index;
	}


}