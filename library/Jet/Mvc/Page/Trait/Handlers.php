<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
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
	public function resolve()
	{
		/**
		 * @var Mvc_Page_Trait_Handlers|Mvc_Page $this
		 */
		$path = Mvc::getRouter()->getPath();

		if(
			$path &&
			strpos( $path, '..' )==false
		) {
			if($path==static::getPageDataFileName()) {
				return false;
			}

			$file_path = $this->getDataDirPath().$path;

			if( IO_File::exists( $file_path ) ) {
				Mvc::getRouter()->setIsFile( $file_path );

				return true;
			}
		}

		foreach( $this->getContent() as $content ) {
			/**
			 * @var Mvc_Page_Content $content
			 */
			if($content->getOutput()) {
				continue;
			}

			$controller = $content->getControllerInstance();
			if(!$controller) {
				continue;
			}

			if( ($action=$controller->resolve()) ) {
				if($action!==true) {
					$controller->getContent()->setControllerAction( $action );
				}
			} else {
				$content->skipDispatch();
			}
		}

		return true;
	}

	/**
	 *
	 */
	public function handleHttpHeaders()
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

				Http_Headers::sendHeader( $header.': '.$value );
			}

		}

	}

	/**
	 *
	 */
	public function handleSubApp()
	{
		/**
		 * @var Mvc_Page_Trait_Handlers|Mvc_Page $this
		 */

		$file_path = dirname( $this->getDataFilePath() ).'/'.$this->getSubAppIndexFileName();

		if( !IO_File::exists( $file_path ) ) {
			throw new Mvc_Page_Exception( 'Direct output file '.$file_path.' does not exist' );
		}

		/** @noinspection PhpIncludeInspection */
		require $file_path;
	}

	/**
	 * @param string $file_path
	 */
	public function handleFile( $file_path )
	{
		/**
		 * @var Mvc_Page_Trait_Handlers|Mvc_Page $this
		 */

		$ext = strtolower( pathinfo( $file_path, PATHINFO_EXTENSION ) );

		if( in_array( $ext, $this->getSubAppPhpFileExtensions() ) ) {

			/** @noinspection PhpIncludeInspection */
			require $file_path;
		} else {
			IO_File::send( $file_path );
		}

		Application::end();

	}

	/**
	 * @return string
	 */
	public function render()
	{
		/**
		 * @var Mvc_Page_Trait_Handlers|Mvc_Page $this
		 */

		if( ( $output = $this->getOutput() ) ) {
			if(is_callable($output)) {
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