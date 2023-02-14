<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication\Installer;

use Jet\MVC_Layout;
use Jet\MVC_View;
use Jet\Tr;
use Jet\Http_Request;
use Jet\UI;
use Jet\UI_icon;

/**
 *
 */
abstract class Installer_Step_Controller
{
	/**
	 * @var string
	 */
	protected string $name = '';
	
	/**
	 * @var string
	 */
	protected string $icon = '';

	/**
	 * @var string
	 */
	protected string $label = '';

	/**
	 * @var ?MVC_Layout
	 */
	protected ?MVC_Layout $layout = null;
	/**
	 * @var ?MVC_View
	 */
	protected ?MVC_View $view = null;

	/**
	 * @var bool
	 */
	protected bool $is_past = false;

	/**
	 * @var bool
	 */
	protected bool $is_previous = false;

	/**
	 * @var bool
	 */
	protected bool $is_current = false;

	/**
	 * @var bool
	 */
	protected bool $is_coming = false;

	/**
	 * @var bool
	 */
	protected bool $is_future = false;

	/**
	 * @var bool
	 */
	protected bool $is_last = false;


	/**
	 *
	 * @param string $name
	 * @param string $step_base_path
	 */
	public function __construct( string $name, string $step_base_path )
	{
		$this->name = $name;

		$this->view = new MVC_View( $step_base_path . 'view/' );
		$this->view->setVar( 'controller', $this );

	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}
	
	/**
	 * @param string $size
	 *
	 * @return string
	 */
	public function getIcon( string $size = UI_icon::SIZE_NORMAL ): string
	{
		if(!$this->icon) {
			return '';
		}
		
		return UI::icon($this->icon)->setSize( $size );
	}
	
	/**
	 * @param string $icon
	 */
	public function setIcon( string $icon ): void
	{
		$this->icon = $icon;
	}
	
	

	/**
	 *
	 */
	abstract public function main(): void;

	/**
	 * @param string $name
	 *
	 */
	public function render( string $name ): void
	{
		$output = $this->view->render( $name );

		Installer::getLayout()->addOutputPart(
			$output
		);
	}

	/**
	 * @return bool
	 */
	public function getIsCurrent(): bool
	{
		return $this->is_current;
	}

	/**
	 * @param bool $is_current
	 */
	public function setIsCurrent( bool $is_current ): void
	{
		$this->is_current = $is_current;
	}

	/**
	 * @return bool
	 */
	public function getIsFuture(): bool
	{
		return $this->is_future;
	}

	/**
	 * @param bool $is_future
	 */
	public function setIsFuture( bool $is_future ): void
	{
		$this->is_future = $is_future;
	}

	/**
	 * @return bool
	 */
	public function getIsPast(): bool
	{
		return $this->is_past;
	}

	/**
	 * @param bool $is_past
	 */
	public function setIsPast( bool $is_past ): void
	{
		$this->is_past = $is_past;
	}

	/**
	 * @return bool
	 */
	public function getIsLast(): bool
	{
		return $this->is_last;
	}

	/**
	 * @param bool $is_last
	 *
	 */
	public function setIsLast( bool $is_last ): void
	{
		$this->is_last = $is_last;
	}

	/**
	 * @return bool
	 */
	public function getIsPrevious(): bool
	{
		return $this->is_previous;
	}

	/**
	 * @param bool $is_previous
	 */
	public function setIsPrevious( bool $is_previous ): void
	{
		$this->is_previous = $is_previous;
	}

	/**
	 * @return bool
	 */
	public function getIsComing(): bool
	{
		return $this->is_coming;
	}

	/**
	 * @param bool $is_coming
	 */
	public function setIsComing( bool $is_coming ): void
	{
		$this->is_coming = $is_coming;
	}

	/**
	 * @return string
	 */
	public function getURL(): string
	{
		return '?step=' . $this->name;
	}

	/**
	 * @return bool
	 */
	public function getIsSubStep(): bool
	{
		return false;
	}

	/**
	 * @return bool|array
	 */
	public function getStepsAfter(): bool|array
	{
		return false;
	}

	/**
	 * @return bool
	 */
	public function getIsAvailable(): bool
	{
		return true;
	}

	/**
	 * @return string
	 */
	public function getLabel(): string
	{
		return Tr::_( text: $this->label, dictionary: $this->name );
	}

	/**
	 *
	 */
	public function catchContinue(): void
	{
		if( Http_Request::GET()->exists( 'go' ) ) {
			Installer::goToNext();
		}

	}

}
