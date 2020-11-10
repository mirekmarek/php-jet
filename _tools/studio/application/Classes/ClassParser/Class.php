<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;
use Jet\SysConf_Jet;

class ClassParser_Class extends ClassParser_Element
{

	/**
	 * @var ClassParser_Token
	 */
	public $_last_doc_comment_token;

	/**
	 * @var ClassParser_Token
	 */
	public $_abstract_token;

	/**
	 * @var ClassParser_Token
	 */
	public $_public_token;

	/**
	 * @var ClassParser_Token
	 */
	public $_private_token;

	/**
	 * @var ClassParser_Token
	 */
	public $_protected_token;

	/**
	 * @var ClassParser_Token
	 */
	public $_static_token;


	/**
	 * @var bool
	 */
	public $is_abstract = false;

	/**
	 * @var string
	 */
	public $name = '';

	/**
	 * @var string
	 */
	public $extends = '';

	/**
	 * @var array
	 */
	public $implements = [];

	/**
	 * @var ClassParser_Token
	 */
	public $doc_comment;

	/**
	 * @var ClassParser_Token
	 */
	public $declaration_start;

	/**
	 * @var ClassParser_Token
	 */
	public $declaration_end;

	/**
	 * @var ClassParser_Token
	 */
	public $body_start;

	/**
	 * @var ClassParser_Token
	 */
	public $body_end;

	/**
	 * @var ClassParser_Class_Constant[]
	 */
	public $constants = [];

	/**
	 * @var ClassParser_Class_Property[]
	 */
	public $properties = [];

	/**
	 * @var ClassParser_Class_Method[]|ClassParser_Class_AbstractMethod[]
	 */
	public $methods = [];

	/**
	 * @param ClassParser $parser
	 */
	public static function parse( ClassParser $parser )
	{
		$class = new static( $parser );

		$token = $parser->tokens[$parser->index];
		$class->start_token = $token;

		$class->declaration_start = $token;

		if( $parser->_abstract_class_token ) {
			$class->start_token = $parser->_abstract_class_token;
			$class->declaration_start = $parser->_abstract_class_token;
		}

		if($parser->_last_doc_comment_token) {
			$class->start_token = $parser->_last_doc_comment_token;
			$class->doc_comment = $parser->_last_doc_comment_token;
		}

		$searching_for_extends = false;
		$searching_for_implements = false;
		$searching_for_implements_index = 0;

		do {
			if( !($token=$class->nextToken()) ) {
				break;
			}

			if($token->ignore()) {
				continue;
			}

			switch( $token->id ) {
				case T_NS_SEPARATOR:
					if($searching_for_implements) {
						if(!isset($class->implements[$searching_for_implements_index])) {
							$class->implements[$searching_for_implements_index] = '';
						}
						$class->implements[$searching_for_implements_index] .= $token->text;
					} else {
						if($searching_for_extends) {
							$class->extends .= $token->text;
						} else {
							$class->parseError();
						}
					}

					break;
				case T_STRING:
					if($searching_for_implements) {
						if(!isset($class->implements[$searching_for_implements_index])) {
							$class->implements[$searching_for_implements_index] = '';
						}

						$class->implements[$searching_for_implements_index] .= $token->text;
					} else {
						if($searching_for_extends) {
							$class->extends .= $token->text;
						} else {
							$class->name = $token->text;
						}

					}


					break;
				case T_EXTENDS:
					if(
						!$class->name ||
						$searching_for_extends ||
						$searching_for_implements
					) {
						$class->parseError();
					}
					$searching_for_extends = true;
					break;
				case T_IMPLEMENTS:
					if(
						!$class->name ||
						$searching_for_implements
					) {
						$class->parseError();
					}
					$searching_for_implements = true;
					break;
				case ',':
					if(!$searching_for_implements) {
						$class->parseError();
					}
					$searching_for_implements_index++;
					break;
				case '{':
					$class->declaration_end = $parser->tokens[$token->index-1];
					$class->body_start = $token;
					break 2;
				default:
					$class->parseError();
			}

		} while( true );


		do {
			if( !($token=$class->nextToken()) ) {
				break;
			}

			if($token->ignore(false)) {
				continue;
			}

			switch( $token->id ) {
				case T_DOC_COMMENT:
					$class->_last_doc_comment_token = $token;
					break;
				case T_PROTECTED:
					if(
						$class->_private_token ||
						$class->_public_token
					) {
						$class->parseError();
					}
					$class->_protected_token = $token;
					break;
				case T_PUBLIC:
					if(
						$class->_private_token ||
						$class->_protected_token
					) {
						$class->parseError();
					}
					$class->_public_token = $token;
					break;
				case T_PRIVATE:
					if(
						$class->_public_token ||
						$class->_protected_token
					) {
						$class->parseError();
					}
					$class->_private_token = $token;
					break;
				case T_STATIC:
					if(
						$class->_static_token
					) {
						$class->parseError();
					}
					$class->_static_token = $token;
					break;
				case T_CONST:
					if(
						$class->_public_token ||
						$class->_protected_token ||
						$class->_private_token ||
						$class->_static_token ||
						$class->_abstract_token

					) {
						$class->parseError();
					}

					ClassParser_Class_Constant::parse( $parser, $class );
					break;
				case T_ABSTRACT:
					if(
						$class->_abstract_token
					) {
						$class->parseError();
					}
					$class->_abstract_token = $token;
					break;
				case T_FUNCTION:
					if( $class->_abstract_token ) {
						ClassParser_Class_AbstractMethod::parse( $parser, $class );
					} else {
						ClassParser_Class_Method::parse( $parser, $class );
					}
					break;
				case T_VARIABLE:
					if( $class->_abstract_token ) {
						$class->parseError();
					}

					ClassParser_Class_Property::parse( $parser, $class );
					break;
				case '}':
					$class->body_end = $token;
					$class->end_token = $token;
					$parser->classes[$class->name] = $class;
					return;
					break;
				default:
					//echo $token->debug_getInfo();
					break;
			}

		} while( true );

	}


	/**
	 *
	 */
	public function debug_showResult()
	{
		$parser = $this->parser;

		if($this->is_abstract) {
			echo 'Abstract ';
		}
		echo 'Class: '.$this->name;

		//echo PHP_EOL.' Code: '.$parser->getTokenText( $this->start_token, $this->end_token );
		echo PHP_EOL.' Tokens: '.$this->start_token->index.' - '.$this->end_token->index;
		echo PHP_EOL.'_________________________________________________________'.PHP_EOL;
		if($this->doc_comment) {
			echo PHP_EOL.' Doc Comment: (token: '.$this->doc_comment->index.') '.$this->doc_comment->text;
			echo PHP_EOL.'_________________________________________________________'.PHP_EOL;
		}

		echo PHP_EOL.' Declaration: '.$parser->getTokenText( $this->declaration_start, $this->declaration_end );
		echo ' Tokens: '.$this->declaration_start->index.' - '.$this->declaration_end->index;
		echo PHP_EOL.'_________________________________________________________'.PHP_EOL;


		foreach( $this->constants as $constant ) {
			$constant->debug_showResult();
			echo PHP_EOL.'_________________________________________________________'.PHP_EOL;
		}

		foreach( $this->properties as $property ) {
			$property->debug_showResult();
			echo PHP_EOL.'_________________________________________________________'.PHP_EOL;
		}

		foreach( $this->methods as $method ) {
			$method->debug_showResult();
			echo PHP_EOL.'_________________________________________________________'.PHP_EOL;
		}

		echo PHP_EOL.PHP_EOL;
	}

	/**
	 * @param string $code
	 */
	public function addConstant( $code )
	{
		$after = $this->body_start;

		foreach( $this->constants as $constant ) {
			if($constant->end_token->index>$after->index) {
				$after = $constant->end_token;
			}
		}

		$this->parser->insertAfter( $after, $code );
	}

	/**
	 * @param string $code
	 */
	public function addProperty( $code )
	{
		$after = $this->body_start;

		foreach( $this->constants as $constant ) {
			if($constant->end_token->index>$after->index) {
				$after = $constant->end_token;
			}
		}

		foreach( $this->properties as $property ) {
			if($property->end_token->index>$after->index) {
				$after = $property->end_token;
			}
		}

		$this->parser->insertAfter( $after, $code );

	}

	/**
	 * @param string $code
	 */
	public function addMethod( $code )
	{
		$after = $this->body_start;

		foreach( $this->constants as $constant ) {
			if($constant->end_token->index>$after->index) {
				$after = $constant->end_token;
			}
		}

		foreach( $this->properties as $property ) {
			if($property->end_token->index>$after->index) {
				$after = $property->end_token;
			}

		}

		foreach( $this->methods as $method ) {
			if($method->end_token->index>$after->index) {
				$after = $method->end_token;
			}

		}

		$this->parser->insertAfter( $after, $code );
	}
}
