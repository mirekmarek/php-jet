<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
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
			$js_actions = [];

			foreach( $this->js_actions as $vent => $handler ) {
				$js_actions[] = ' ' . $vent . '="' . $handler . '"';
			}

			return implode( '', $js_actions );
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
	 * @param callable $class_creator
	 * @param bool $as_string
	 *
	 * @return array|string
	 */
	public function getWidthCssClasses( callable $class_creator, bool $as_string = true ): array|string
	{
		$css_classes = [];

		if( $this->width ) {
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
			$this->getCustomCssClasses( false )
		);


		if( $as_string ) {
			return implode( ' ', $css_classes );
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
			return implode( ';', $this->custom_css_styles );
		}

		return $this->custom_css_styles;
	}

	/**
	 * @return Mvc_View
	 */
	public function getView(): Mvc_View
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