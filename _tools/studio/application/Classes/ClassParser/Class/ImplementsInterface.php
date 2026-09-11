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
class ClassParser_Class_ImplementsInterface extends ClassParser_Class_Element
{
	public string $interface_name = '';
	public string $interface_full_name = '';
	
	
	
	/**
	 * @param ClassParser $parser
	 * @param ClassParser_Class $class
	 */
	public static function parse( ClassParser $parser, ClassParser_Class $class ) : void
	{
		$implements_class = new static( $parser, $class );
		
		$token = $parser->tokens[$parser->index];
		$implements_class->start_token = $token;
		
		$searching_for_interface_name = true;
		
		do {
			if( !($token = $class->nextToken()) ) {
				break;
			}
			
			if( $token->ignore() ) {
				continue;
			}
			
			
			if( $searching_for_interface_name ) {
				switch( $token->id ) {
					case T_STRING:
						$implements_class->interface_name .= $token->text;
						
						$searching_for_interface_name = false;
						break;
					default:
						$parser->parseError();
						break;
				}
				
			} else {
				switch( $token->id ) {
					case ',':
						$implements_class->end_token = $token;
						$implements_class->interface_full_name = $parser->getFullClassName( $implements_class->interface_name );
						$class->implements[$implements_class->interface_name] = $implements_class;
						$searching_for_interface_name = true;
						
						$implements_class = new static( $parser, $class );
						$token = $parser->tokens[$parser->index];
						$implements_class->start_token = $token;
						
						break;
					case '{':
						$parser->index--;
						$implements_class->end_token = $parser->tokens[$token->index-1];
						$implements_class->interface_full_name = $parser->getFullClassName( $implements_class->interface_name );
						$class->implements[$implements_class->interface_name] = $implements_class;
						
						break 2;
					default:
						$parser->parseError();
						break;
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
		
		echo PHP_EOL . '_________________________________________________________' . PHP_EOL;
		echo PHP_EOL . ' Declaration: ' . $parser->getTokenText( $this->start_token, $this->end_token );
		echo ' Tokens: ' . $this->start_token->index . ' - ' . $this->end_token->index;
		echo PHP_EOL . '_________________________________________________________' . PHP_EOL;
		
		echo $parser->getTokenText( $this->start_token, $this->end_token );
		echo PHP_EOL . ' Tokens: ' . $this->start_token->index . ' - ' . $this->end_token->index;
	}
	
}