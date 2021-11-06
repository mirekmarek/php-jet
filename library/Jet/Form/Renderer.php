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
	 * @var array
	 */
	protected array $custom_data_attributes = [];

	/**
	 * @var ?callable
	 */
	public $width_css_classes_creator = null;


	/**
	 * Form_RendererTag constructor.
	 *
	 * @param Form $form
	 * @param Form_Field|null $field
	 */
	public function __construct( Form $form, Form_Field $field = null )
	{
		$this->form = $form;
		$this->field = $field;
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
	 * @param bool $as_string
	 *
	 * @return array|string
	 */
	public function getJsActions( bool $as_string = true ): array|string
	{
		if( $as_string ) {
			if(!$this->js_actions) {
				return '';
			}

			$js_actions = [''];

			foreach( $this->js_actions as $vent => $handler ) {
				$js_actions[] = $vent . '="' . $handler . '"';
			}

			return implode( ' ', $js_actions );
		}

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
	 * @param bool $as_string
	 *
	 * @return array|string
	 */
	public function getBaseCssClasses( bool $as_string = true ): array|string
	{
		if( $as_string ) {
			return $this->base_css_class;
		}

		return explode( ' ', $this->base_css_class );
	}

	/**
	 * @param bool $as_string
	 *
	 * @return array|string
	 */
	public function getCustomCssClasses( bool $as_string = true ): array|string
	{
		if( $as_string ) {
			return implode( ' ', $this->custom_css_classes );
		}

		return $this->custom_css_classes;
	}

	/**
	 * @param bool $as_string
	 *
	 * @return array|string
	 */
	public function getWidthCssClasses( bool $as_string = true ): array|string
	{
		$css_classes = [];

		$class_creator = $this->getWidthCssClassesCreator();


		if( $class_creator && $this->width ) {
			foreach( $this->width as $size => $width ) {
				$css_classes[] = $class_creator( $size, $width );
			}
		}

		if( $as_string ) {
			return implode( ' ', $css_classes );
		}

		return $css_classes;

	}


	/**
	 * @param bool $as_string
	 *
	 * @return array|string
	 */
	public function getCssClasses( bool $as_string = true ): array|string
	{
		$css_classes = array_merge(
			$this->getBaseCssClasses( false ),
			$this->getWidthCssClasses( false ),
			$this->getCustomCssClasses( false )
		);


		if( $as_string ) {
			if(!$css_classes) {
				return '';
			}

			return ' class="'.implode( ' ', $css_classes ).'"';
		}

		return $css_classes;
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
	 * @param bool $as_string
	 *
	 * @return array|string
	 */
	public function getCssStyles( bool $as_string = true ): array|string
	{
		if( $as_string ) {
			if(!$this->custom_css_styles) {
				return '';
			}
			return ' style="'.implode( ';', $this->custom_css_styles ).'"';
		}

		return $this->custom_css_styles;
	}

	public function addCustomDataAttribute( $attr, $value ) : static
	{
		$this->custom_data_attributes[$attr] = $value;

		return $this;
	}

	public function getCustomDataAttributes( bool $as_string = true ) : array|string
	{
		if($as_string) {
			if(!$this->custom_data_attributes) {
				return '';
			}

			$attrs = [''];

			foreach($this->custom_data_attributes as $attr=>$value) {
				$attrs[] = 'data-'.$attr.'="'.addslashes(Data_Text::htmlSpecialChars($value)).'"';
			}

			return implode(' ', $attrs);
		}

		return $this->custom_data_attributes;
	}

	public function getMainTagAttributes() : string
	{
		$res = '';
		$res .= $this->getCssClasses();
		$res .= $this->getCssStyles();
		$res .= $this->getJsActions();
		$res .= $this->getCustomDataAttributes();

		return $res;
	}

	public function getStdInputFieldAttributes( string $type ) : array
	{
		$field = $this->getField();

		$attrs = [
			'type' => $type,
			'name' => $field->getTagNameValue(),
			'id' => $field->getId(),
			'value' => $field->getValue()
		];

		if( $field->getPlaceholder() ) {
			$attrs['placeholder'] = Data_Text::htmlSpecialChars( $field->getPlaceholder() );
		}
		if( $field->getIsReadonly() ) {
			$attrs['readonly'] = 'readonly';
		}
		if( $field->getIsRequired() ) {
			$attrs['required'] = 'required';
		}
		if( $field->getValidationRegexp() ) {
			$attrs['pattern'] = $field->getValidationRegexp();
		}

		return $attrs;
	}

	public function renderAttributes( array $attributes ) : string
	{
		$res = $this->getMainTagAttributes();

		foreach($attributes as $attr=>$value) {
			$res .= ' '.$attr.'="'.$value.'"';
		}

		return $res;
	}

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

		$view->setVar( 'element', $this );

		return $view;

	}


}