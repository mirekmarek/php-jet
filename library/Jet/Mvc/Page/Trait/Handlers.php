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
trait Mvc_Page_Trait_Handlers
{


	/**
	 *
	 *
	 * @return bool
	 */
	public function resolve(): bool
	{
		/**
		 * @var Mvc_Page_Trait_Handlers|Mvc_Page $this
		 */

		foreach( $this->getContent() as $content ) {
			/**
			 * @var Mvc_Page_Content $content
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
		 * @var Mvc_Page_Trait_Handlers|Mvc_Page $this
		 */

		foreach( $this->getHttpHeaders() as $header => $value ) {
			if( is_int( $header ) ) {
				Http_Headers::sendHeader( $value );
			} else {
				if( is_array( $value ) ) {
					$value = implode( '; ', $value );
				}

				Http_Headers::sendHeader( $header . ': ' . $value );
			}

		}

	}

	/**
	 * @return string
	 */
	public function render(): string
	{
		/**
		 * @var Mvc_Page_Trait_Handlers|Mvc_Page $this
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
			 * @var Mvc_Page_Content $content
			 */
			$content->dispatch();
		}

		$output = Mvc_Layout::getCurrentLayout()->render();

		Debug_Profiler::blockEnd( 'Content dispatch' );

		return $output;

	}


}