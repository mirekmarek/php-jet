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
class UI_searchField extends BaseObject
{

	/**
	 * @var string|null
	 */
	protected ?string $view_script = null;

	/**
	 * @var string
	 */
	protected string $placeholder = '';

	/**
	 * @var string
	 */
	protected string $name = '';
	/**
	 * @var string|null
	 */
	protected ?string $value = null;


	/**
	 * @param string $name
	 * @param string $value
	 */
	public function __construct( string $name, string $value )
	{
		$this->name = $name;
		$this->value = $value;
	}


	/**
	 * @return string
	 */
	public function getViewScript(): string
	{
		if( !$this->view_script ) {
			$this->view_script = SysConf_Jet_UI_DefaultViews::get('search-field');
		}

		return $this->view_script;
	}


	/**
	 * @param string $view_script
	 */
	public function setViewScript( string $view_script ): void
	{
		$this->view_script = $view_script;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getValue(): string
	{
		return $this->value;
	}

	/**
	 * @return string
	 */
	public function getPlaceholder(): string
	{
		if( !$this->placeholder ) {
			return '';
		}

		return UI::_( $this->placeholder );
	}

	/**
	 * @param string $placeholder
	 *
	 * @return static
	 */
	public function setPlaceholder( string $placeholder ): static
	{
		$this->placeholder = $placeholder;

		return $this;
	}


	/**
	 * @return MVC_View
	 */
	public function getView(): MVC_View
	{

		$view = UI::getView();
		$view->setVar( 'element', $this );

		return $view;
	}

	/**
	 * @return string
	 */
	public function getAction(): string
	{
		return Http_Request::currentURI();
	}

	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString(): string
	{
		return $this->getView()->render( $this->getViewScript() );
	}

}