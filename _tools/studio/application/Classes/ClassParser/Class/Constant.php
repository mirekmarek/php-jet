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
class ClassParser_Class_Constant extends ClassParser_Class_Element
{

	/**
	 * @var string
	 */
	public $name = '';

	/**
	 * @var string
	 */
	public $value = '';


	/**
	 * @param ClassParser $parser
	 * @param ClassParser_Class $class
	 */
	public static function parse( ClassParser $parser, ClassParser_Class $class )
	{
		$const = new static( $parser, $class );

		$token = $parser->tokens[$parser->index];
		$const->start_token = $token;

		$searching_for_value = false;
		$got_value = false;

		do {
			if( !($token=$const->nextToken()) ) {
				break;
			}

			if($token->ignore()) {
				continue;
			}

			switch( $token->id ) {
				case T_STRING:
					if($searching_for_value) {
						$got_value = true;
						$const->value .= $token->text;
					} else {
						$const->name = $token->text;
					}
					break;
				case '=':
					if($searching_for_value) {
						$got_value = true;
						$const->value .= $token->text;
					} else {
						$searching_for_value = true;
					}
					break;
				case ';':
					if(
						!$const->name ||
						!$got_value
					) {
						$const->parseError();
					}
					$const->value .= $token->text;

					$class->constants[$const->name] = $const;

					$const->end_token = $token;
					return;
				default:
					if($searching_for_value) {
						$const->value .= $token->text;
						$got_value = true;
					} else {
						$const->parseError();
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

		echo 'Constant: '.$this->name.' = '.$this->value;

		echo PHP_EOL.' Code: '.$parser->getTokenText( $this->start_token, $this->end_token );
		echo PHP_EOL.' Tokens: '.$this->start_token->index.' - '.$this->end_token->index;
	}


}