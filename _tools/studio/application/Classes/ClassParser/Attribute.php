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
class ClassParser_Attribute extends ClassParser_Element
{
	/**
	 * @var string
	 */
	public $target  = '';

	/**
	 * @var string
	 */
	public $name = '';

	/**
	 * @var array
	 */
	public $arguments = [];

	/**
	 * @param ClassParser $parser
	 */
	public static function parse( ClassParser $parser ) : void
	{
		$attribute = new static( $parser );

		$token = $parser->tokens[$parser->index];
		$attribute->start_token = $token;

		$text = $token->text;

		$text = trim($text);
		$text = trim($text, '#][');

		//TODO:
	}


	public function debug_showResult()
	{
	}
}