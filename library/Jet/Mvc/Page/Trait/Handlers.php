<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
trait Mvc_Page_Trait_Handlers
{
	/**
	 * @var array
	 */
	protected static $php_file_extensions = [ 'php', 'phtml', 'php3', 'php4', 'php5', 'php6', 'php7' ];

	/**
	 *
	 * @var bool
	 */
	protected $is_sub_app = false;

	/**
	 *
	 * @var string
	 */
	protected $sub_app_index_file_name = 'index.php';

	/**
	 *
	 * @var array
	 */
	protected $http_headers = [];

	/**
	 *
	 * @var string
	 */
	protected $output;

	/**
	 * @return array
	 */
	public static function getPhpFileExtensions()
	{
		return static::$php_file_extensions;
	}

	/**
	 * @param array $php_file_extensions
	 */
	public static function setPhpFileExtensions( $php_file_extensions )
	{
		static::$php_file_extensions = $php_file_extensions;
	}


	/**
	 * @return bool
	 */
	public function getIsSubapp()
	{
		return $this->is_sub_app;
	}

	/**
	 * @param bool $is_sub_app
	 */
	public function setIsSubapp( $is_sub_app )
	{
		$this->is_sub_app = $is_sub_app;
	}

	/**
	 * @return array
	 */
	public function getHttpHeaders()
	{
		return $this->http_headers;
	}

	/**
	 * @param array $http_headers
	 */
	public function setHttpHeaders( array $http_headers )
	{
		$this->http_headers = $http_headers;
	}

	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale             $locale
	 * @param string             $relative_URI
	 *
	 * @return Mvc_Page_Interface|null
	 */
	public function getByRelativeURI( Mvc_Site_Interface $site, Locale $locale, $relative_URI )
	{

		static::loadPages( $site, $locale );

		$str_locale = (string)$locale;

		if( !isset( static::$relative_URIs_map[$site->getId()][$str_locale][$relative_URI] ) ) {
			return null;
		}

		return static::$relative_URIs_map[$site->getId()][$str_locale][$relative_URI];
	}

	/**
	 *
	 * @return bool
	 */
	public function parseRequestURL()
	{
		/**
		 * @var Mvc_Page_Trait_Handlers|Mvc_Page $this
		 */
		$router = Mvc::getRouter();

		$path = implode( '/', $router->getPathFragments() );

		if( strpos( $path, '..' )==false ) {
			if($path==static::getPageDataFileName()) {
				return false;
			}

			$path = $this->getDataDirPath().$path;

			if( IO_File::exists( $path ) ) {
				$router->setIsFile( $path );

				return true;
			}

		}

		foreach( $this->getContent() as $content ) {
			$module = Application_Modules::getModuleInstance( $content->getModuleName() );

			if( !$module ) {
				continue;
			}

			$controller = $module->getControllerInstance( $content );

			if( $controller->parseRequestURL( $content ) ) {
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
		foreach( $this->http_headers as $header => $value ) {
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
		$file_path = dirname( $this->getDataFilePath() ).'/'.$this->getSubAppIndexFileName();

		if( !IO_File::exists( $file_path ) ) {
			throw new Mvc_Page_Exception( 'Direct output file '.$file_path.' does not exist' );
		}

		/** @noinspection PhpIncludeInspection */
		require $file_path;
	}

	/**
	 * @return string
	 */
	public function getSubAppIndexFileName()
	{
		return $this->sub_app_index_file_name;
	}

	/**
	 * @param string $index_file_name
	 */
	public function setSubAppIndexFileName( $index_file_name )
	{
		$this->sub_app_index_file_name = $index_file_name;
	}

	/**
	 * @param string $file_path
	 */
	public function handleFile( $file_path )
	{

		$ext = strtolower( pathinfo( $file_path, PATHINFO_EXTENSION ) );

		if( in_array( $ext, static::$php_file_extensions ) ) {

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
			return $output;
		}

		$this->initializeLayout();

		Debug_Profiler::mainBlockStart( 'Content dispatch' );


		$translator_namespace = Translator::COMMON_NAMESPACE;

		Translator::setCurrentNamespace( $translator_namespace );

		foreach( $this->getContent() as $content ) {
			Mvc::setCurrentContent( $content );

			$content->dispatch();

			Mvc::unsetCurrentContent();
		}

		Translator::setCurrentNamespace( $translator_namespace );

		$output = Mvc_Layout::getCurrentLayout()->render();

		Debug_Profiler::mainBlockEnd( 'Content dispatch' );

		return $output;

	}

	/**
	 * @return string|null
	 */
	public function getOutput()
	{
		return $this->output;
	}

}