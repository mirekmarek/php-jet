<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class Form_Renderer_Field_Input_WYSIWYG extends Form_Renderer_Field_Input_Textarea
{
	
	/**
	 * @var array
	 */
	protected array $editor_CSS_files = [];
	
	/**
	 * @var array
	 */
	protected array $editor_JavaScript_files = [];
	
	/**
	 * @var array|null
	 */
	protected array|null $editor_config = null;
	
	/**
	 * @var callable|null
	 */
	protected $editor_initialize_code_generator = null;
	
	
	/**
	 * @param string $URI
	 * @param string $media
	 */
	public function appendWYSIWYGEditorCSSFile( string $URI, string $media = 'screen' ): void
	{
		if( !isset( $this->editor_CSS_files[$media] ) ) {
			$this->editor_CSS_files[$media] = [];
		}
		
		$this->editor_CSS_files[$media][] = $URI;
	}
	
	/**
	 * @return array
	 */
	public function getEditorCSSFiles(): array
	{
		return $this->editor_CSS_files;
	}
	
	/**
	 * @param array $editor_CSS_files
	 */
	public function setEditorCSSFiles( array $editor_CSS_files ): void
	{
		$this->editor_CSS_files = $editor_CSS_files;
	}
	
	/**
	 * @param string $URI
	 */
	public function appendEditorJavaScriptFile( string $URI ): void
	{
		$this->editor_JavaScript_files[] = $URI;
	}
	
	/**
	 * @return array
	 */
	public function getEditorJavaScriptFiles(): array
	{
		return $this->editor_JavaScript_files;
	}
	
	/**
	 * @param array $editor_JavaScript_files
	 */
	public function setEditorJavaScriptFiles( array $editor_JavaScript_files ): void
	{
		$this->editor_JavaScript_files = $editor_JavaScript_files;
	}
	
	/**
	 * @return array|null
	 */
	public function getEditorConfig(): array|null
	{
		return $this->editor_config;
	}
	
	/**
	 * @param array $editor_config
	 */
	public function setEditorConfig( array $editor_config ): void
	{
		$this->editor_config = $editor_config;
	}
	
	
	/**
	 * @return callable|null
	 */
	public function getEditorInitializeCodeGenerator(): callable|null
	{
		return $this->editor_initialize_code_generator;
	}
	
	/**
	 * @param callable $editor_initialize_code_generator
	 */
	public function setEditorInitializeCodeGenerator( callable $editor_initialize_code_generator ): void
	{
		$this->editor_initialize_code_generator = $editor_initialize_code_generator;
	}
	
	
	/**
	 *
	 */
	public function requireEditorCSSandJavaScriptFiles() : void
	{
		foreach( $this->getEditorCSSFiles() as $media => $files ) {
			foreach( $files as $URI ) {
				MVC_Layout::getCurrentLayout()->requireCssFile( $URI, $media );
			}
		}
		foreach( $this->getEditorJavaScriptFiles() as $URI ) {
			MVC_Layout::getCurrentLayout()->requireJavascriptFile( $URI );
		}
		
	}
	
	/**
	 * @return string
	 */
	public function generateEditorInitializeCode() : string
	{
		$this->requireEditorCSSandJavaScriptFiles();
		
		$init_generator = $this->getEditorInitializeCodeGenerator();
		
		return $init_generator( $this->getField(), $this->getEditorConfig() );
	}
	
}