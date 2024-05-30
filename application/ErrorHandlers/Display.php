<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Debug;
use Jet\Debug_ErrorHandler_Handler;
use Jet\Debug_ErrorHandler_Error;
use PhpToken;

/**
 *
 */
class ErrorHandler_Display extends Debug_ErrorHandler_Handler
{
	protected array $non_fatal_errors = [];
	
	protected bool $non_fatal_displayer_registered = false;
	
	/**
	 * @return string
	 */
	public function getName(): string
	{
		return 'Display';
	}

	/**
	 * @param Debug_ErrorHandler_Error $error
	 */
	public function handle( Debug_ErrorHandler_Error $error ): void
	{
		if($error->isSilenced()) {
			return;
		}

		if( Debug::getOutputIsHTML() ) {
			$this->display( $error );
		} else {
			echo $error->toString();
		}
	}

	/**
	 * @return bool
	 */
	public function errorDisplayed(): bool
	{
		return true;
	}

	/**
	 *
	 * @param Debug_ErrorHandler_Error $error
	 *
	 */
	public function display( Debug_ErrorHandler_Error $error ): void
	{
		if($error->isFatal()) {
			while (ob_get_level())
			{
				ob_end_clean();
			}
			
			$handler = $this;
			
			require __DIR__.'/views/fatal-error.phtml';
			return;
		}
		
		$this->non_fatal_errors[] = $error;

		if( !$this->non_fatal_displayer_registered ) {
			register_shutdown_function( function() {
				$errors = $this->non_fatal_errors;
				$handler = $this;
				
				require __DIR__.'/views/warnings.phtml';
			} );
			
			$this->non_fatal_displayer_registered = true;
		}
	}
	
	/**
	 * @param string $source
	 * @param bool $as_lines
	 * @return string|array
	 */
	public function highlightPhpCode( string $source, bool $as_lines = false): string|array
	{
		$source = str_replace("\r\n", "\n", $source);
		$source = preg_replace('#(__halt_compiler\s*\(\)\s*;).*#is', '$1', $source);
		$source = preg_replace('#/\*sensitive\{\*/.*?/\*}\*/#s', '********', $source);
		
		$result = '';
		$prev_token_class = null;
		
		$replace_map = [
				'<' => '&lt;',
				'>' => '&gt;',
				'&' => '&amp;',
				"\t" => '&nbsp;&nbsp;&nbsp;&nbsp;'
		];
		
		$class_prefix = 'dbg-code-hl-';
		
		$class_map = [
			T_COMMENT     => $class_prefix.'comment',
			T_DOC_COMMENT => $class_prefix.'doc-comment',
			T_INLINE_HTML => $class_prefix.'html',
			
			T_OPEN_TAG             => $class_prefix.'general',
			T_OPEN_TAG_WITH_ECHO   => $class_prefix.'general',
			T_CLOSE_TAG            => $class_prefix.'general',
			T_LINE                 => $class_prefix.'general',
			T_FILE                 => $class_prefix.'general',
			T_DIR                  => $class_prefix.'general',
			T_TRAIT_C              => $class_prefix.'general',
			T_METHOD_C             => $class_prefix.'general',
			T_FUNC_C               => $class_prefix.'general',
			T_NS_C                 => $class_prefix.'general',
			T_CLASS_C              => $class_prefix.'general',
			T_STRING               => $class_prefix.'general',
			T_NAME_FULLY_QUALIFIED => $class_prefix.'general',
			T_NAME_QUALIFIED       => $class_prefix.'general',
			T_NAME_RELATIVE        => $class_prefix.'general',
			T_LNUMBER              => $class_prefix.'general',
			T_DNUMBER              => $class_prefix.'general',
			
			T_ENCAPSED_AND_WHITESPACE  => $class_prefix.'string',
			T_CONSTANT_ENCAPSED_STRING => $class_prefix.'string',
			
			T_VARIABLE => $class_prefix.'variable',
			
			'default' => $class_prefix.'keyword',
		];
		
		foreach ( PhpToken::tokenize($source) as $token) {
			if($token->id!=T_WHITESPACE) {
				$current_token_class = $class_map[$token->id] ?? $class_map['default'];
				
				if ($prev_token_class !== $current_token_class) {
					if($prev_token_class !== null) {
						$result .= '</span>';
					}
					
					$result .= '<span class="'.$current_token_class.'">';
					
					$prev_token_class = $current_token_class;
				}
			}
			
			$token_text = $token->text;
			$token_text = strtr($token_text, $replace_map);
			
			if(isset($current_token_class)) {
				$token_text = str_replace("\n", "</span>\n<span class=\"$current_token_class\">", $token_text);
			}
			
			
			$result .= $token_text;
		}
		
		if($prev_token_class) {
			$result .= '</span>';
		}
		
		if($as_lines) {
			$_result = explode("\n", $result);
			$result = [];
			
			foreach($_result as $line_no=>$line) {
				$line_no++;
				$result[$line_no] = rtrim($line);
			}
			
			return $result;
		}
		
		return nl2br($result);
	}
	

}