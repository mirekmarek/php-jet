<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use PhpToken;

class Debug_CodeHighlighter_PHP
{
	/**
	 * @var array<string,string>
	 */
	protected array $replace_map = [
		'<' => '&lt;',
		'>' => '&gt;',
		'&' => '&amp;',
		"\t" => '&nbsp;&nbsp;&nbsp;&nbsp;'
	];
	
	protected string $css_class_prefix = 'dbg-code-hl-';
	
	/**
	 * @var array<string|int,string>
	 */
	protected array $custom_css_class_to_token_map = [];
	
	public function getCssClassPrefix(): string
	{
		return $this->css_class_prefix;
	}
	
	public function setCssClassPrefix( string $css_class_prefix ): void
	{
		$this->css_class_prefix = $css_class_prefix;
	}
	
	/**
	 * @return array<string,string>
	 */
	public function getReplaceMap(): array
	{
		return $this->replace_map;
	}
	
	/**
	 * @param array<string,string> $replace_map
	 * @return void
	 */
	public function setReplaceMap( array $replace_map ): void
	{
		$this->replace_map = $replace_map;
	}
	
	public function addCustomCssClassToTokenMap( int|string $token, string $css_class ) : void
	{
		$this->custom_css_class_to_token_map[$token] = $css_class;
	}
	
	/**
	 * @return array<int|string,string>
	 */
	public function getCssClassToTokenMap() : array
	{
		$class_prefix = $this->css_class_prefix;
		
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
		
		foreach($this->custom_css_class_to_token_map as $toke=>$css_class) {
			$class_map[ $toke ] = $css_class;
		}
		
		return $class_map;
	}
	
	/**
	 * @param string $source
	 * @param bool $as_lines
	 * @return string|array<string>
	 */
	public function highlight( string $source, bool $as_lines = false): string|array
	{
		$source = str_replace("\r\n", "\n", $source);
		$source = preg_replace('#(__halt_compiler\s*\(\)\s*;).*#is', '$1', $source);
		$source = preg_replace('#/\*sensitive\{\*/.*?/\*}\*/#s', '********', $source);
		
		$result = '';
		$prev_token_class = null;
		
		$class_map = $this->getCssClassToTokenMap();
		
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
			$token_text = strtr($token_text, $this->replace_map);
			
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
