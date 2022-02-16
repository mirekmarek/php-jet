<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
abstract class Form_Renderer extends BaseObject
{
	const LJ_SIZE_EXTRA_SMALL = 'xs';
	const LJ_SIZE_SMALL = 'sm';
	const LJ_SIZE_MEDIUM = 'md';
	const LJ_SIZE_LARGE = 'lg';
	
	
	/**
	 * @var array
	 */
	protected array $js_actions = [];

	/**
	 * @var string
	 */
	protected string $base_css_class = '';

	/**
	 * @var array
	 */
	protected array $custom_css_classes = [];

	/**
	 * @var array
	 */
	protected array $custom_css_styles = [];

	/**
	 * @var array|null
	 */
	protected array|null $width = null;

	/**
	 * @var ?callable
	 */
	protected $width_css_classes_creator = null;
	
	/**
	 * @var array
	 */
	protected array $data_attributes = [];
	
	/**
	 * @var array
	 */
	protected array $custom_tag_attributes = [];
	
	
	/**
	 * @var array
	 */
	protected array $_tag_attributes = [];
	
	/**
	 * @var ?string
	 */
	protected string|null $view_dir = null;
	
	/**
	 * @var MVC_View|null
	 */
	protected ?MVC_View $view = null;
	
	
	
	
	/**
	 * @return string
	 */
	public function getViewDir(): string
	{
		return $this->view_dir;
	}
	
	/**
	 * @param string $views_dir
	 */
	public function setViewDir( string $views_dir ): void
	{
		$this->view_dir = $views_dir;
		
		$this->view?->setScriptsDir( $this->view_dir );
		
	}
	
	
	/**
	 * @return MVC_View
	 */
	public function getView(): MVC_View
	{
		if(!$this->view) {
			$this->view = Factory_MVC::getViewInstance( $this->getViewDir() );
			
			$this->view->setVar( 'renderer', $this );
			
		}
		
		return $this->view;
	}
	
	
	

	/**
	 * @return array|null
	 */
	public function getWidth(): array|null
	{
		return $this->width;
	}

	/**
	 * @param array $width
	 */
	public function setWidth( array $width ): void
	{
		$this->width = $width;
	}

	/**
	 * @return callable|null
	 */
	public function getWidthCssClassesCreator(): ?callable
	{
		return $this->width_css_classes_creator;
	}

	/**
	 * @param callable $width_css_classes_creator
	 */
	public function setWidthCssClassesCreator( callable $width_css_classes_creator ): void
	{
		$this->width_css_classes_creator = $width_css_classes_creator;
	}

	/**
	 * @param string $event
	 * @param string $handler_code
	 *
	 * @return $this
	 */
	public function addJsAction( string $event, string $handler_code ): static
	{
		$event = strtolower( $event );

		if( !isset( $this->js_actions[$event] ) ) {
			$this->js_actions[$event] = $handler_code;
		} else {
			$this->js_actions[$event] .= ';' . $handler_code;

		}

		return $this;
	}


	/**
	 *
	 * @return array
	 */
	public function getJsActions(): array
	{
		return $this->js_actions;

	}
	

	/**
	 * @param string $base_css_class
	 *
	 * @return $this
	 */
	public function setBaseCssClass( string $base_css_class ): static
	{
		$this->base_css_class = $base_css_class;

		return $this;
	}

	/**
	 * @param string $class
	 *
	 * @return $this
	 */
	public function addCustomCssClass( string $class ): static
	{
		$this->custom_css_classes[] = $class;

		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function getBaseCssClasses(): array
	{
		if(!$this->base_css_class) {
			return [];
		}
		
		return explode( ' ', $this->base_css_class );
	}

	/**
	 *
	 * @return array
	 */
	public function getCustomCssClasses(): array
	{
		return $this->custom_css_classes;
	}

	/**
	 *
	 * @return array
	 */
	public function getWidthCssClasses(): array
	{
		$css_classes = [];

		$class_creator = $this->getWidthCssClassesCreator();


		if( $class_creator && ($width=$this->getWidth()) ) {
			foreach( $width as $size => $w ) {
				$_classes = $class_creator( $size, $w );
				if($_classes) {
					$_classes = explode(' ', $_classes);
					foreach($_classes as $class) {
						if($class) {
							$css_classes[] = $class;
						}
					}
				}
			}
		}

		return $css_classes;

	}


	/**
	 *
	 * @return array
	 */
	public function getCssClasses(): array
	{
		return array_merge(
			$this->getBaseCssClasses(),
			$this->getWidthCssClasses(),
			$this->getCustomCssClasses()
		);
	}

	/**
	 * @param string $style
	 *
	 * @return $this
	 */
	public function addCustomCssStyle( string $style ): static
	{
		$this->custom_css_styles[] = $style;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getCssStyles(): array
	{
		return $this->custom_css_styles;
	}
	
	/**
	 * @param string $attr
	 * @param string $value
	 * @return $this
	 */
	public function setDataAttribute( string $attr, string $value ) : static
	{
		$this->data_attributes[$attr] = $value;

		return $this;
	}
	
	/**
	 * @param string $attr
	 * @return $this
	 */
	public function unsetDataAttribute( string $attr ) : static
	{
		if(isset( $this->data_attributes[$attr])) {
			unset( $this->data_attributes[$attr]);
		}
		
		return $this;
	}
	
	/**
	 * @return array
	 */
	public function getDataAttributes() : array
	{
		return $this->data_attributes;
	}
	
	
	
	/**
	 * @param string $attr
	 * @param string $value
	 *
	 * @return $this
	 */
	public function setCustomTagAttribute( string $attr, string $value ) : static
	{
		$this->custom_tag_attributes[$attr] = $value;
		
		return $this;
	}
	
	/**
	 * @param string $attr
	 *
	 * @return $this
	 */
	public function unsetCustomTagAttribute( string $attr ) : static
	{
		if(isset($this->custom_tag_attributes[$attr])) {
			unset($this->custom_tag_attributes[$attr]);
		}
		
		return $this;
	}
	
	/**
	 * @return array
	 */
	public function getCustomTagAttributes(): array
	{
		return $this->custom_tag_attributes;
	}
	
	
	/**
	 *
	 */
	protected function generateTagAttributes_Standard() : void
	{
	}
	
	
	/**
	 *
	 */
	protected function generateTagAttributes_CssClasses() : void
	{
		$classes = $this->getCssClasses();
		if($classes) {
			$this->_tag_attributes['class'] = implode(' ', $classes);
		}
	}
	
	/**
	 *
	 */
	protected function generateTagAttributes_CssStyles() : void
	{
		$styles = $this->getCssStyles();
		if($styles) {
			$this->_tag_attributes['style'] = implode(';', $styles);
		}
	}
	
	/**
	 *
	 */
	protected function generateTagAttributes_JsActions() : void
	{
		foreach( $this->getJsActions() as $event => $handler ) {
			$this->_tag_attributes[$event] = $handler;
		}
	}
	
	/**
	 *
	 */
	protected function generateTagAttributes_CustomDataAttributes() : void
	{
		foreach( $this->getDataAttributes() as $attr=> $value) {
			$this->_tag_attributes['data-'.$attr] = addslashes(Data_Text::htmlSpecialChars($value));
		}
	}
	
	
	
	/**
	 * @return array
	 */
	public function generateTagAttributes() : array
	{
		$this->_tag_attributes = [];
		
		$this->generateTagAttributes_Standard();
		$this->generateTagAttributes_CssClasses();
		$this->generateTagAttributes_CssStyles();
		$this->generateTagAttributes_JsActions();
		$this->generateTagAttributes_CustomDataAttributes();
		
		foreach($this->custom_tag_attributes as $key=>$val) {
			$this->_tag_attributes[$key] = $val;
		}
		
		return $this->_tag_attributes;
	}
	
	/**
	 * @return string
	 */
	public function renderTagAttributes() : string
	{
		$attributes = $this->generateTagAttributes();
		$res = '';
		foreach($attributes as $attr=>$value) {
			$res .= ' '.$attr.'="'.$value.'"';
		}

		return $res;
	}


}