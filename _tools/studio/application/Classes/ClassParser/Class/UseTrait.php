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
class ClassParser_Class_UseTrait extends ClassParser_Class_Element
{
	public string $trait_name = '';
	public string $trait_full_name = '';
	
	public string $conflict_resolution = '';

	
	
	/**
	 * @param ClassParser $parser
	 * @param ClassParser_Class $class
	 */
	public static function parse( ClassParser $parser, ClassParser_Class $class ) : void
	{
		$use_trait = new static( $parser, $class );
		
		$token = $parser->tokens[$parser->index];
		$use_trait->start_token = $token;
		
		$searching_for_trait_name = true;
		$searching_for_conflict_resolution = false;
		
		
		do {
			if( !($token = $use_trait->nextToken()) ) {
				break;
			}
			
			if( $token->ignore() ) {
				continue;
			}
			
			
			if( $searching_for_trait_name ) {
				switch( $token->id ) {
					case T_STRING:
						$use_trait->trait_name .= $token->text;
						$searching_for_trait_name = false;
						break;
					default:
						$parser->parseError();
						break;
				}
				
			} else {
				switch( $token->id ) {
					case ',':
						$use_trait->end_token = $token;
						$use_trait->trait_full_name = $parser->getFullClassName( $use_trait->trait_name );
						$class->use_traits[$use_trait->trait_name] = $use_trait;
						$searching_for_trait_name = true;
						
						$use_trait = new static( $parser, $class );
						$token = $parser->tokens[$parser->index];
						$use_trait->start_token = $token;
						
						break;
					case ';':
						$use_trait->end_token = $token;
						$use_trait->trait_full_name = $parser->getFullClassName( $use_trait->trait_name );
						$class->use_traits[$use_trait->trait_name] = $use_trait;
						
						break 2;
					case '{':
						$searching_for_conflict_resolution = true;
						$use_trait->conflict_resolution .= $token->text;
						break;
					case '}':
						if(!$searching_for_conflict_resolution) {
							$parser->parseError();
						}
						
						$use_trait->end_token = $token;
						$use_trait->trait_full_name = $parser->getFullClassName( $use_trait->trait_name );
						$class->use_traits[$use_trait->trait_name] = $use_trait;
						
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