<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

require_once 'Content/Interface.php';

/**
 *
 */
class MVC_Page_Content extends BaseObject implements MVC_Page_Content_Interface
{
	const DEFAULT_CONTROLLER_ACTION = 'default';

	/**
	 * @var ?MVC_Page_Interface
	 */
	protected ?MVC_Page_Interface $__page = null;

	/**
	 *
	 * @var string
	 */
	protected string $module_name = '';

	/**
	 *
	 * @var string
	 */
	protected string $controller_name = MVC::MAIN_CONTROLLER_NAME;

	/**
	 * @var string
	 */
	protected string $controller_class = '';

	/**
	 *
	 * @var string|bool
	 */
	protected string|bool $controller_action = '';

	/**
	 *
	 * @var array
	 */
	protected array $parameters = [];

	/**
	 *
	 * @var string|callable
	 */
	protected $output = '';

	/**
	 * @var bool
	 */
	protected bool $is_cacheable = false;

	/**
	 *
	 * @var string
	 */
	protected string $output_position = '';

	/**
	 *
	 * @var int
	 */
	protected int $output_position_order = 0;

	/**
	 * @var bool
	 */
	protected bool $_skip_dispatch = false;

	/**
	 * @var Application_Module|bool|null
	 */
	protected Application_Module|bool|null $__module_instance = null;

	/**
	 *
	 * @var ?MVC_Controller
	 */
	protected ?MVC_Controller $__controller_instance = null;


	/**
	 * @param MVC_Page_Interface $page
	 * @param array $data
	 *
	 * @return MVC_Page_Content_Interface
	 */
	public static function _createByData( MVC_Page_Interface $page, array $data ): MVC_Page_Content_Interface
	{
		/**
		 * @var MVC_Page_Content $content ;
		 */
		$content = Factory_MVC::getPageContentInstance();
		$content->setPage( $page );

		$content->setData( $data );

		return $content;
	}


	/**
	 * @param array $data
	 */
	protected function setData( array $data ): void
	{
		foreach( $data as $key => $val ) {
			$this->{$key} = $val;
		}
	}

	/**
	 * @return string
	 */
	public function getControllerName(): string
	{
		return $this->controller_name;
	}

	/**
	 * @param string $controller_name
	 */
	public function setControllerName( string $controller_name ): void
	{
		$this->controller_name = $controller_name;
	}

	/**
	 * @return string
	 */
	public function getControllerClass(): string
	{
		return $this->controller_class;
	}

	/**
	 * @param string $controller_class
	 */
	public function setControllerClass( string $controller_class ): void
	{
		$this->controller_class = $controller_class;
	}


	/**
	 * @return string
	 */
	public function getOutputPosition(): string
	{
		return $this->output_position;
	}

	/**
	 * @param string $output_position
	 */
	public function setOutputPosition( string $output_position ): void
	{
		$this->output_position = $output_position;
	}


	/**
	 * @return int
	 */
	public function getOutputPositionOrder(): int
	{
		return $this->output_position_order;
	}

	/**
	 * @param int $output_position_order
	 */
	public function setOutputPositionOrder( int $output_position_order ): void
	{
		$this->output_position_order = $output_position_order;
	}

	/**
	 * @param bool $state
	 */
	public function setIsCacheable( bool $state ): void
	{
		$this->is_cacheable = $state;
	}

	/**
	 * @return bool
	 */
	public function isCacheable(): bool
	{
		return $this->is_cacheable;
	}


	/**
	 * @return string|callable
	 */
	public function getOutput(): string|callable
	{
		return $this->output;
	}

	/**
	 * @param string|callable $output
	 */
	public function setOutput( string|callable $output ): void
	{
		$this->output = $output;
	}


	/**
	 * @return MVC_Page_Interface
	 */
	public function getPage(): MVC_Page_Interface
	{
		if( !$this->__page ) {
			return MVC::getPage();
		}

		return $this->__page;
	}

	/**
	 * @param MVC_Page_Interface $page
	 */
	public function setPage( MVC_Page_Interface $page ): void
	{
		$this->__page = $page;
	}

	/**
	 * @return string
	 */
	public function getModuleName(): string
	{
		return $this->module_name;
	}

	/**
	 * @param string $module_name
	 */
	public function setModuleName( string $module_name ): void
	{
		$this->module_name = $module_name;
	}

	/**
	 * @return Application_Module|bool
	 */
	public function getModuleInstance(): Application_Module|bool
	{
		if( $this->__module_instance !== null ) {
			return $this->__module_instance;
		}


		$module_name = $this->getModuleName();

		if( !Application_Modules::moduleIsActivated( $module_name ) ) {
			$this->__module_instance = false;

			return false;
		}

		$this->__module_instance = Application_Modules::moduleInstance( $module_name );
		
		return $this->__module_instance;
	}


	/**
	 * @return string|bool
	 */
	public function getControllerAction(): string|bool
	{
		if( $this->controller_action === false ) {
			return false;
		}

		return $this->controller_action ? : static::DEFAULT_CONTROLLER_ACTION;
	}

	/**
	 * @param string $controller_action
	 */
	public function setControllerAction( string $controller_action ): void
	{
		$this->controller_action = $controller_action;
	}


	/**
	 * @return array
	 */
	public function getParameters(): array
	{
		return $this->parameters;
	}

	/**
	 * @param array $parameters
	 */
	public function setParameters( array $parameters ): void
	{
		$this->parameters = $parameters;
	}

	/**
	 * @param string $key
	 * @param mixed $default_value
	 *
	 * @return mixed
	 */
	public function getParameter( string $key, mixed $default_value = null ): mixed
	{
		if( !array_key_exists( $key, $this->parameters ) ) {
			return $default_value;
		}

		return $this->parameters[$key];
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 */
	public function setParameter( string $key, mixed $value ): void
	{
		$this->parameters[$key] = $value;
	}

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	public function parameterExists( string $key ): bool
	{
		return array_key_exists( $key, $this->parameters );
	}


	/**
	 *
	 * @return MVC_Controller|bool
	 */
	public function getControllerInstance(): MVC_Controller|bool
	{
		if( $this->__controller_instance !== null ) {
			return $this->__controller_instance;
		}

		if( !($controller_class_name = $this->getControllerClass()) ) {
			$module_instance = $this->getModuleInstance();
			if( !$module_instance ) {
				return false;
			}

			$controller_suffix = 'Controller_' . $this->getControllerName();

			$controller_class_name = $module_instance->getModuleManifest()->getNamespace() . $controller_suffix;

		}


		$this->__controller_instance = new $controller_class_name( $this );

		return $this->__controller_instance;
	}

	/**
	 *
	 */
	public function skipDispatch(): void
	{
		$this->_skip_dispatch = true;
	}

	/**
	 *
	 */
	public function dispatch(): void
	{
		if( $this->_skip_dispatch ) {
			return;
		}

		if( ($output = $this->getOutput()) ) {
			if( is_callable( $output ) ) {
				$output = $output( $this->getPage(), $this );
			}

			MVC_Layout::getCurrentLayout()->addOutputPart(
				$output,
				$this->output_position,
				$this->output_position_order
			);

			return;
		}

		$module_name = $this->getModuleName();
		$controller_action = $this->getControllerAction();

		if( $controller_action === false ) {
			return;
		}
		$block_name = $module_name . ':' . $controller_action;


		Debug_Profiler::blockStart( 'Dispatch ' . $block_name );

		if( $this->loadOutputCache() ) {
			Debug_Profiler::message( 'Loaded from cache' );

			Debug_Profiler::blockEnd( 'Dispatch ' . $block_name );
			return;
		}


		$controller = $this->getControllerInstance();

		if( !$controller ) {

			Debug_Profiler::message( 'Module is not installed and/or activated - skipping' );

		} else {
			Debug_Profiler::message( 'Dispatch:' . $this->getPage()->getKey() . '|' . $module_name . ':' . get_class( $controller ) . ':' . $controller_action );

			$translator_dictionary = Translator::getCurrentDictionary();
			Translator::setCurrentDictionary( $module_name );

			$controller->dispatch();

			Translator::setCurrentDictionary( $translator_dictionary );
		}

		Debug_Profiler::blockEnd( 'Dispatch ' . $block_name );

	}

	/**
	 * @param string $output
	 */
	public function saveOutputCache( string $output ): void
	{
		if( $this->is_cacheable ) {
			MVC_Cache::saveContentOutput( $this, $output );
		}
	}

	/**
	 *
	 */
	public function loadOutputCache(): bool
	{
		if( !$this->is_cacheable ) {
			return false;
		}

		$output = MVC_Cache::loadContentOutput( $this );
		if( $output === null ) {
			return false;
		}

		$position = $this->getOutputPosition();
		if( !$position ) {
			$position = MVC_Layout::DEFAULT_OUTPUT_POSITION;
		}

		$position_order = $this->getOutputPositionOrder();

		MVC_Layout::getCurrentLayout()->addOutputPart(
			$output,
			$position,
			$position_order
		);

		return true;
	}


	/**
	 * @param string $output
	 */
	public function output( string $output ): void
	{
		$position = $this->getOutputPosition();
		if( !$position ) {
			$position = MVC_Layout::DEFAULT_OUTPUT_POSITION;
		}


		$position_order = $this->getOutputPositionOrder();

		$this->saveOutputCache( $output );

		MVC_Layout::getCurrentLayout()->addOutputPart(
			$output,
			$position,
			$position_order
		);

	}


	/**
	 * @return array
	 */
	public function toArray(): array
	{
		$data = get_object_vars( $this );
		foreach( $data as $k => $v ) {
			if( $k[0] == '_' ) {
				unset( $data[$k] );
			}
		}

		if( $this->output ) {
			unset( $data['module_name'] );
			unset( $data['controller_name'] );
			unset( $data['controller_class'] );
			unset( $data['controller_action'] );


		} else {
			unset( $data['output'] );

			if( $this->controller_class ) {
				unset( $data['module_name'] );
				unset( $data['controller_name'] );
			} else {
				unset( $data['controller_class'] );
			}
		}

		return $data;
	}
}