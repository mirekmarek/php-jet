<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Form_Renderer_Abstract_Tag
 * @package Jet
 */
abstract class Form_Renderer_Abstract_Tag extends BaseObject
{

	/**
	 * @var string
	 */
	protected $tag = '';

	/**
	 * @var bool
	 */
	protected $is_pair = true;

	/**
	 * @var bool
	 */
	protected $has_content = false;

	/**
	 * @var array
	 */
	protected $js_actions = [];

	/**
	 * @var array
	 */
	protected $custom_css_classes = [];

	/**
	 * @var array
	 */
	protected $custom_css_styles = [];

	/**
	 * @var string
	 */
	protected $base_css_class = '';

	/**
	 * @var callable
	 */
	protected $custom_renderer;

	/**
	 * @param string $event
	 * @param string $handler_code
	 *
	 * @return $this
	 */
	public function addJsAction( $event, $handler_code )
	{
		$event = strtolower( $event );

		if( !isset( $this->js_actions[$event] ) ) {
			$this->js_actions[$event] = $handler_code;
		} else {
			$this->js_actions[$event] .= ';'.$handler_code;

		}

		return $this;
	}

	/**
	 * @param string $class
	 *
	 * @return $this
	 */
	public function addCustomCssClass( $class )
	{
		$this->custom_css_classes[] = $class;

		return $this;
	}

	/**
	 * @param string $style
	 *
	 * @return $this
	 */
	public function addCustomCssStyle( $style )
	{
		$this->custom_css_styles[] = $style;

		return $this;
	}

	/**
	 *
	 */
	public function end()
	{
		if( !$this->is_pair ) {
			return '';
		}

		return '</'.$this->getTag().'>'.JET_EOL;
	}

	/**
	 * @return string
	 */
	public function getTag()
	{
		return $this->tag;
	}

	/**
	 * @param callable $custom_renderer
	 */
	public function setCustomRenderer( $custom_renderer )
	{
		$this->custom_renderer = $custom_renderer;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		if( $this->custom_renderer ) {
			$cr = $this->custom_renderer;

			return $cr( $this );
		}

		return $this->render();
	}

	/**
	 * @return string
	 */
	abstract public function render();

	/**
	 * @param array  $tag_options
	 * @param string $content
	 *
	 * @return string
	 */
	protected function generate( array $tag_options = [], $content = '' )
	{

		$result = '<'.$this->getTag().$this->generateTagOptions( $tag_options );

		foreach( $tag_options as $option => $val ) {
			$result .= ' '.$option.'="'.$val.'"';
		}

		if( $this->getHasContent() ) {
			$result .= '>';
			$result .= $content;
			$result .= '</'.$this->getTag().'>';
		} else {
			if( !$this->isPair() ) {
				$result .= '/>';
			} else {
				$result .= '>';
			}
		}

		return $result.JET_EOL;
	}

	/**
	 * @param array $tag_options
	 *
	 * @return string
	 */
	protected function generateTagOptions( $tag_options )
	{

		$css_class = [];
		if( $this->getBaseCssClass() ) {
			$css_class[] = $this->getBaseCssClass();
		}

		if( $this->getCustomCssClasses() ) {
			$css_class = array_merge( $css_class, $this->getCustomCssClasses() );
		}

		$css_class = implode( ' ', $css_class );

		if( $css_class ) {
			$tag_options['class'] = $css_class;
		}

		if( $this->getCustomCssStyles() ) {
			$tag_options['style'] = implode( ';', $this->getCustomCssStyles() );
		}

		foreach( $this->getJsActions() as $action => $code ) {
			$tag_options[$action] = $code;
		}

		$this->initTagOptions( $tag_options );

		$result = '';

		foreach( $tag_options as $option => $val ) {
			$result .= ' '.$option.'="'.$val.'"';
		}

		return $result;
	}

	/**
	 * @return string
	 */
	public function getBaseCssClass()
	{
		return $this->base_css_class;
	}

	/**
	 * @param string $base_css_class
	 *
	 * @return $this
	 */
	public function setBaseCssClass( $base_css_class )
	{
		$this->base_css_class = $base_css_class;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getCustomCssClasses()
	{
		return $this->custom_css_classes;
	}

	/**
	 * @return array
	 */
	public function getCustomCssStyles()
	{
		return $this->custom_css_styles;
	}

	/**
	 * @return array
	 */
	public function getJsActions()
	{
		return $this->js_actions;
	}

	/**
	 * @param array &$tag_options
	 */
	protected function initTagOptions( array &$tag_options )
	{

	}

	/**
	 * @return bool
	 */
	public function getHasContent()
	{
		return $this->has_content;
	}

	/**
	 * @return bool
	 */
	public function isPair()
	{
		return $this->is_pair;
	}

}