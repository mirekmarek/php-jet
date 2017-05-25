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
	public function parseRequestPath()
	{
		/**
		 * @var Mvc_Page_Trait_Handlers|Mvc_Page $this
		 */
		$path = Mvc::getRouter()->getPath();

		if( strpos( $path, '..' )==false ) {
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
			if(!Application_Modules::getModuleIsActivated($content->getModuleName())) {
				continue;
			}

			$module = Application_Modules::getModuleInstance( $content->getModuleName() );

			if( !$module ) {
				continue;
			}

			$controller = $module->getControllerInstance( $content );

			if( $controller->parseRequestPath( $content ) ) {
				return true;
			}
		}

		return false;
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


		$translator_namespace = Translator::COMMON_NAMESPACE;

		Translator::setCurrentNamespace( $translator_namespace );

		foreach( $this->getContent() as $content ) {
			Mvc::setCurrentContent( $content );

			$content->dispatch();

			Mvc::unsetCurrentContent();
		}

		Translator::setCurrentNamespace( $translator_namespace );

		$output = Mvc_Layout::getCurrentLayout()->render();

		Debug_Profiler::blockEnd( 'Content dispatch' );

		return $output;

	}


}