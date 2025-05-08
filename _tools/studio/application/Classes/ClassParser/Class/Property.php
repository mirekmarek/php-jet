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
class ClassParser_Class_Property extends ClassParser_Class_Element
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
	 * @var ?string
	 */
	public ?string $value = null;
	
	public ?string $hooks = null;

	/**
	 * @var string
	 */
	public string $visibility = ClassParser::VISIBILITY_PUBLIC;


	/**
	 * @var ClassParser_Attribute[]
	 */
	public array $attributes = [];


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
	public static function parse( ClassParser $parser, ClassParser_Class $class ): void
	{
		$property = new static( $parser, $class );
		$property->attributes = $parser->__attributes;

		$parser->__attributes = [];

		$token = $parser->tokens[$parser->index];
		$property->start_token = $token;

		$property->name = substr( $token->text, 1 );

		if( $class->_static_token ) {
			$property->is_static = true;
			$property->start_token = $class->_static_token;
		}


		if( $class->_public_token ) {
			if( $class->_public_token->index < $property->start_token->index ) {
				$property->start_token = $class->_public_token;
			}

			$property->visibility = ClassParser::VISIBILITY_PUBLIC;
		}

		if( $class->_private_token ) {
			if( $class->_private_token->index < $property->start_token->index ) {
				$property->start_token = $class->_private_token;
			}
			$property->visibility = ClassParser::VISIBILITY_PRIVATE;
		}

		if( $class->_protected_token ) {
			if( $class->_protected_token->index < $property->start_token->index ) {
				$property->start_token = $class->_protected_token;
			}
			$property->visibility = ClassParser::VISIBILITY_PROTECTED;
		}

		$property->declaration_start = $property->start_token;

		if( $class->_last_doc_comment_token ) {
			$property->doc_comment = $class->_last_doc_comment_token;
			$property->start_token = $class->_last_doc_comment_token;
		}

		foreach( $property->attributes as $attribute ) {
			if( $attribute->start_token->index < $property->start_token->index ) {
				$property->start_token = $attribute->start_token;
			}
		}


		$searching_for_value = false;
		$searching_for_hooks = false;
		$got_value = false;
		
		$hooks_block_stack = 0;


		do {
			if( !($token = $property->nextToken()) ) {
				break;
			}

			if( $token->ignore() ) {
				if($searching_for_hooks) {
					$property->hooks .= $token->text;
				}
				continue;
			}
			
			
			switch( $token->id ) {
				case '=':
					if($searching_for_hooks) {
						$property->hooks .= '=';
					} else {
						if( !$searching_for_value ) {
							$searching_for_value = true;
						} else {
							$property->parseError();
						}
					}
					break;
				case ';':
					if($searching_for_hooks) {
						$property->hooks .= ';';
					} else {
						if( !$property->name ) {
							$property->parseError();
						}
						
						$class->properties[$property->name] = $property;
						
						$property->end_token = $token;
						$property->declaration_end = $token;
						
						$class->_last_doc_comment_token = null;
						$class->_private_token = null;
						$class->_protected_token = null;
						$class->_public_token = null;
						$class->_static_token = null;
						return;
						
					}
					break;
				case '{':
					if(!$searching_for_hooks) {
						if( !$property->name ) {
							$property->parseError();
						}
						
						if($searching_for_value) {
							if( $property->value === null ) {
								$property->value = '';
							}
							
							$property->value .= $token->text;
							$got_value = true;
							
							$searching_for_value = false;
						}
						
						$property->hooks .= ' {';
						$searching_for_hooks = true;
					}
					
					$hooks_block_stack++;
					
					break;
				case '}':
					if( !$searching_for_hooks ) {
						$property->parseError();
					}
					
					$property->hooks .= '}';
					
					$hooks_block_stack--;
					
					if($hooks_block_stack<1) {
						
						$class->properties[$property->name] = $property;
						
						$property->end_token = $token;
						$property->declaration_end = $token;
						
						$class->_last_doc_comment_token = null;
						$class->_private_token = null;
						$class->_protected_token = null;
						$class->_public_token = null;
						$class->_static_token = null;
						
						return;
					}

					break;
				default:
					if($searching_for_hooks) {
						$property->hooks .= $token->text;
					} else {
						if( $searching_for_value ) {
							if( $property->value === null ) {
								$property->value = '';
							}
							
							$property->value .= $token->text;
							$got_value = true;
						} else {
							$property->parseError();
						}
					}
				break;
			}

		} while( true );

	}


	/**
	 *
	 */
	public function debug_showResult(): void
	{
		$parser = $this->parser;

		if( $this->is_static ) {
			echo 'Static ';
		}
		echo ucfirst( $this->visibility ) . ' Property: ' . $this->name . ' = ' . $this->value;
		if( $this->doc_comment ) {
			echo PHP_EOL . ' Doc Comment: (token: ' . $this->doc_comment->index . ') ' . $this->doc_comment->text;
		}
		echo PHP_EOL . ' Code: ' . $parser->getTokenText( $this->declaration_start, $this->declaration_end );

		echo PHP_EOL . ' Tokens: ' . $this->start_token->index . ' - ' . $this->end_token->index;
	}
	
	
	/**
	 * @param string $new_text
	 */
	public function replaceAttributes( string $new_text ): void
	{
		$new_text = trim( $new_text );
		
		$start_token = null;
		$end_token = null;
		foreach($this->attributes as $attribute) {
			if(!$start_token) {
				$start_token = $attribute->start_token;
			}
			
			$end_token = $attribute->end_token;
		}
		
		$this->parser->replaceTokens( $start_token, $end_token, $new_text );
	}
	

}