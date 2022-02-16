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
trait MVC_Page_Trait_Handlers
{

	/**
	 *
	 *
	 * @return bool
	 */
	public function resolve(): bool
	{
		/**
		 * @var MVC_Page_Trait_Handlers|MVC_Page $this
		 */

		foreach( $this->getContent() as $content ) {
			/**
			 * @var MVC_Page_Content $content
			 */
			if( $content->getOutput() ) {
				continue;
			}

			$controller = $content->getControllerInstance();
			if( !$controller ) {
				continue;
			}

			$profiler_block = 'Resolve controller ' . $content->getModuleName() . ':' . $content->getControllerName();
			Debug_Profiler::blockStart( $profiler_block );

			if( ($action = $controller->resolve()) ) {
				if( $action !== true ) {
					$controller->getContent()->setControllerAction( $action );
				}
			} else {
				$content->skipDispatch();
			}
			Debug_Profiler::blockEnd( $profiler_block );
		}

		return true;
	}

	/**
	 *
	 */
	public function handleHttpHeaders(): void
	{
		/**
		 * @var MVC_Page_Trait_Handlers|MVC_Page $this
		 */

		Http_Headers::response(
			code: Http_Headers::CODE_200_OK,
			headers: $this->getHttpHeaders()
		);
	}

	/**
	 * @return string
	 */
	public function render(): string
	{
		/**
		 * @var MVC_Page_Trait_Handlers|MVC_Page $this
		 */

		if( ($output = $this->getOutput()) ) {
			if( is_callable( $output ) ) {
				return $output( $this );
			}

			return $output;
		}

		$this->initializeLayout();

		Debug_Profiler::blockStart( 'Content dispatch' );

		foreach( $this->getContent() as $content ) {
			/**
			 * @var MVC_Page_Content $content
			 */
			$content->dispatch();
		}

		$output = MVC_Layout::getCurrentLayout()->render();

		Debug_Profiler::blockEnd( 'Content dispatch' );

		return $output;

	}


}