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
abstract class Form_Renderer extends BaseObject
{
	
	/**
	 * @var ?Form
	 */
	protected ?Form $form = null;

	/**
	 * @var ?Form_Field
	 */
	protected ?Form_Field $field = null;
	
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
	protected array $tag_attributes = [];
	
	/**
	 * @var array
	 */
	protected array $custom_data_attributes = [];
	
	/**
	 * @var array
	 */
	protected array $custom_tag_attributes = [];

	
	
	/**
	 * @return MVC_View
	 */
	public function getView(): MVC_View
	{
		
		$view = $this->field
			?
			$this->field->getView()
			:
			$this->form->getView();
		
		$view->setVar( 'renderer', $this );
		
		return $view;
		
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
	 * @return Form
	 */
	public function getForm(): Form
	{
		return $this->form;
	}

	/**
	 * @return Form_Field
	 */
	public function getField(): Form_Field
	{
		return $this->field;
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


		if( $class_creator && $this->width ) {
			foreach( $this->width as $size => $width ) {
				$css_classes[] = $class_creator( $size, $width );
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

	public function addCustomDataAttribute( $attr, $value ) : static
	{
		$this->custom_data_attributes[$attr] = $value;

		return $this;
	}

	public function getCustomDataAttributes() : array
	{
		return $this->custom_data_attributes;
	}
	
	
	/**
	 *
	 */
	abstract protected function generateTagAttributes_Standard() : void;
	
	
	/**
	 * @param array $custom_attributes
	 */
	public function setCustomTagAttributes(  array $custom_attributes ) : void
	{
		$this->custom_tag_attributes = $custom_attributes;
	}
	
	/**
	 *
	 */
	protected function generateTagAttributes_CssClasses() : void
	{
		$classes = $this->getCssClasses();
		if($classes) {
			$this->tag_attributes['class'] = implode(' ', $classes);
		}
	}
	
	/**
	 *
	 */
	protected function generateTagAttributes_CssStyles() : void
	{
		$styles = $this->getCssStyles();
		if($styles) {
			$this->tag_attributes['style'] = implode(';', $styles);
		}
	}
	
	/**
	 *
	 */
	protected function generateTagAttributes_JsActions() : void
	{
		foreach( $this->getJsActions() as $event => $handler ) {
			$this->tag_attributes[$event] = $handler;
		}
	}
	
	/**
	 *
	 */
	protected function generateTagAttributes_CustomDataAttributes() : void
	{
		foreach($this->getCustomDataAttributes() as $attr=>$value) {
			$this->tag_attributes['data-'.$attr] = addslashes(Data_Text::htmlSpecialChars($value));
		}
		
	}
	
	public function generateTagAttributes() : array
	{
		$this->tag_attributes = [];
		
		$this->generateTagAttributes_Standard();
		$this->generateTagAttributes_CssClasses();
		$this->generateTagAttributes_CssStyles();
		$this->generateTagAttributes_JsActions();
		$this->generateTagAttributes_CustomDataAttributes();
		
		foreach($this->custom_tag_attributes as $key=>$val) {
			$this->tag_attributes[$key] = $val;
		}
		
		return $this->tag_attributes;
	}


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