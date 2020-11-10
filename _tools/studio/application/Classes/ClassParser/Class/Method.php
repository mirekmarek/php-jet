<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

class ClassParser_Class_Method extends ClassParser_Class_Element
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
	public $param_declaration = '';

	/**
	 * @var string 
	 */
	public $body = '';

	/**
	 * @var string
	 */
	public $visibility = ClassCreator_Class::VISIBILITY_PUBLIC;

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


		if($class->_public_token) {
			if( $class->_public_token->index < $method->start_token->index ) {
				$method->start_token = $class->_public_token;
			}

			$method->visibility = ClassCreator_Class::VISIBILITY_PUBLIC;
		}

		if($class->_private_token) {
			if( $class->_private_token->index < $method->start_token->index ) {
				$method->start_token = $class->_private_token;
			}
			$method->visibility = ClassCreator_Class::VISIBILITY_PRIVATE;
		}

		if($class->_protected_token) {
			if( $class->_protected_token->index < $method->start_token->index ) {
				$method->start_token = $class->_protected_token;
			}
			$method->visibility = ClassCreator_Class::VISIBILITY_PROTECTED;
		}

		$method->declaration_start = $method->start_token;

		if($class->_last_doc_comment_token) {
			$method->doc_comment = $class->_last_doc_comment_token;
			$method->start_token = $class->_last_doc_comment_token;
		}




		$searching_for_param_declaration = false;
		$searching_for_body = false;
		$block_index = 0;
		$got_param_declaration = false;


		do {
			if( !($token=$method->nextToken()) ) {
				break;
			}

			if($searching_for_body) {
				switch( $token->id ) {
					case T_STRING:
						$method->body .= $token->text;
						break;
					case '(':
						$method->body .= $token->text;
						break;
					case ')':
						$method->body .= $token->text;
						break;
					case T_CURLY_OPEN:
					case '{':
						$block_index++;
						$method->body .= $token->text;
						break;
					case '}':
						$method->body .= $token->text;

						$block_index--;
						if($block_index<0) {
							$class->methods[$method->name] = $method;
							$method->end_token = $token;
							$method->body_end = $token;

							$class->_last_doc_comment_token = null;
							$class->_private_token = null;
							$class->_protected_token = null;
							$class->_public_token = null;
							$class->_static_token = null;
							return;

						}
						break;
					default:
						$method->body .= $token->text;
						break;
				}

			} else {
				if($token->ignore()) {
					continue;
				}

				switch( $token->id ) {
					case T_STRING:
						if(!$searching_for_param_declaration) {
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
						if($searching_for_param_declaration) {
							$got_param_declaration = true;
							$method->param_declaration .= $token->text;
							$method->declaration_end = $token;
						} else {
							$method->parseError();
						}
						break;
					case '{':
						if($got_param_declaration) {
							$searching_for_body = true;
							$method->body = $token->text;
							$method->body_start = $token;
						} else {
							$method->parseError();
						}


						break;
					case '}':
						$method->parseError();
						break;
					default:
						if(
							$searching_for_param_declaration ||
							!$got_param_declaration
						) {
							$method->param_declaration .= $token->text;
						} else {
							$method->parseError();
						}
					break;
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
		echo ucfirst($this->visibility).' Method: '.$this->name;
		echo SysConf_Jet::EOL().'_________________________________________________________'.SysConf_Jet::EOL();
		if($this->doc_comment) {
			echo SysConf_Jet::EOL().' Doc Comment: (token: '.$this->doc_comment->index.') '.$this->doc_comment->text;
			echo SysConf_Jet::EOL().'_________________________________________________________'.SysConf_Jet::EOL();
		}
		echo SysConf_Jet::EOL().' Declaration: '.$parser->getTokenText( $this->declaration_start, $this->declaration_end );
		echo ' Tokens: '.$this->declaration_start->index.' - '.$this->declaration_end->index;
		echo SysConf_Jet::EOL().'_________________________________________________________'.SysConf_Jet::EOL();

		echo SysConf_Jet::EOL().' Body: '.$parser->getTokenText( $this->body_start, $this->body_end );
		echo ' Tokens: '.$this->body_start->index.' - '.$this->body_end->index;
		echo SysConf_Jet::EOL().'_________________________________________________________'.SysConf_Jet::EOL();

		echo $parser->getTokenText( $this->start_token, $this->end_token );

		echo SysConf_Jet::EOL().' Tokens: '.$this->start_token->index.' - '.$this->end_token->index;
	}

}