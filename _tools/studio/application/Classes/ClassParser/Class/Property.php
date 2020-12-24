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
class ClassParser_Class_Property extends ClassParser_Class_Element
{


	/**
	 * @var ClassParser_Token
	 */
	public $doc_comment;

	/**
	 * @var bool
	 */
	public $is_static = false;

	/**
	 * @var string
	 */
	public $name = '';

	/**
	 * @var string
	 */
	public $value = null;

	/**
	 * @var string
	 */
	public $visibility = ClassParser::VISIBILITY_PUBLIC;

	/**
	 * @var ClassParser_Token
	 */
	public $declaration_start;

	/**
	 * @var ClassParser_Token
	 */
	public $declaration_end;


	/**
	 * @param ClassParser $parser
	 * @param ClassParser_Class $class
	 */
	public static function parse( ClassParser $parser, ClassParser_Class $class )
	{
		$property = new static( $parser, $class );

		$token = $parser->tokens[$parser->index];
		$property->start_token = $token;

		$property->name = substr($token->text, 1);

		if( $class->_static_token ) {
			$property->is_static = true;
			$property->start_token = $class->_static_token;
		}


		if($class->_public_token) {
			if( $class->_public_token->index < $property->start_token->index ) {
				$property->start_token = $class->_public_token;
			}

			$property->visibility = ClassParser::VISIBILITY_PUBLIC;
		}

		if($class->_private_token) {
			if( $class->_private_token->index < $property->start_token->index ) {
				$property->start_token = $class->_private_token;
			}
			$property->visibility = ClassParser::VISIBILITY_PRIVATE;
		}

		if($class->_protected_token) {
			if( $class->_protected_token->index < $property->start_token->index ) {
				$property->start_token = $class->_protected_token;
			}
			$property->visibility = ClassParser::VISIBILITY_PROTECTED;
		}

		$property->declaration_start = $property->start_token;

		if($class->_last_doc_comment_token) {
			$property->doc_comment = $class->_last_doc_comment_token;
			$property->start_token = $class->_last_doc_comment_token;
		}




		$searching_for_value = false;
		$got_value = false;


		do {
			if( !($token=$property->nextToken()) ) {
				break;
			}

			if($token->ignore()) {
				continue;
			}

			switch( $token->id ) {
				case '=':
					if(!$searching_for_value) {
						$searching_for_value = true;
					} else {
						$property->parseError();
					}
					break;
				case ';':
					if(
						!$property->name
					) {
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
				default:
					if($searching_for_value) {
						if($property->value===null) {
							$property->value = '';
						}

						$property->value .= $token->text;
						$got_value = true;
					} else {
						$property->parseError();
					}
			}

		} while( true );

	}


	/**
	 *
	 */
	public function debug_showResult()
	{
		$parser = $this->parser;

		if($this->is_static) {
			echo 'Static ';
		}
		echo ucfirst($this->visibility).' Property: '.$this->name.' = '.$this->value;
		if($this->doc_comment) {
			echo PHP_EOL.' Doc Comment: (token: '.$this->doc_comment->index.') '.$this->doc_comment->text;
		}
		echo PHP_EOL.' Code: '.$parser->getTokenText( $this->declaration_start, $this->declaration_end );

		echo PHP_EOL.' Tokens: '.$this->start_token->index.' - '.$this->end_token->index;
	}


}