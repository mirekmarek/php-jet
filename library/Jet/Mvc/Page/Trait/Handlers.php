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
	 *
	 * @var bool
	 */
	protected $is_direct_output = false;

	/**
	 *
	 * @var string
	 */
	protected $direct_output_file_name = self::DEFAULT_DIRECT_INDEX_FILE_NAME;

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
	 * @return bool
	 */
	public function getIsDirectOutput()
	{
		return $this->is_direct_output;
	}

	/**
	 * @param bool $is_direct_output
	 */
	public function setIsDirectOutput($is_direct_output)
	{
		$this->is_direct_output = $is_direct_output;
	}

	/**
	 * @return string
	 */
	public function getDirectOutputFileName()
	{
		return $this->direct_output_file_name;
	}

	/**
	 * @param string $direct_output_file_name
	 */
	public function setDirectOutputFileName($direct_output_file_name)
	{
		$this->direct_output_file_name = $direct_output_file_name;
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
	 * @return string|null
	 */
	public function getOutput()
	{
		return $this->output;
	}


	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale $locale
	 * @param string $relative_URI
	 * @return Mvc_Page_Interface|null
	 */
	public function getByRelativeURI( Mvc_Site_Interface $site, Locale $locale, $relative_URI ) {
		static::loadPages($site, $locale);

		$str_locale = (string)$locale;

		if(!isset(static::$relative_URIs_map[$site->getId()][$str_locale][$relative_URI])) {
			return null;
		}

		$id_s = static::$relative_URIs_map[$site->getId()][$str_locale][$relative_URI];

		return static::$pages[$id_s];
	}

	/**
	 *
	 * @return bool
	 */
	public function parseRequestURL() {
		/**
		 * @var Mvc_Page_Trait_Handlers|Mvc_Page $this
		 */
		$router = Mvc::getCurrentRouter();

		$path = implode('/', $router->getPathFragments());

		if(strpos($path, '..')==false) {
			$path = $this->getDataDirPath().$path;

			if(IO_File::exists($path)) {
				$router->setIsFile( $path );

				return true;
			}

		}

		foreach($this->getContent() as $content ) {
			$module = Application_Modules::getModuleInstance($content->getModuleName());

			if(!$module) {
				continue;
			}

			$controller = $module->getControllerInstance( $content );

			if($controller->parseRequestURL($content)) {
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
		foreach( $this->http_headers as $header=>$value ) {
			if( is_int($header) ){
				Http_Headers::sendHeader($value);
			} else {
				if(is_array($value)){
					$value = implode('; ', $value);
				}

				Http_Headers::sendHeader( $header.': '.$value);
			}

		}

	}

	/**
	 *
	 */
	public function handleDirectOutput() {
		$file_path = dirname( $this->getDataFilePath() ).'/'.$this->getDirectOutputFileName();

		if(!IO_File::exists($file_path)) {
			throw new Mvc_Page_Exception('Direct output file '.$file_path.' does not exist');
		}

		/** @noinspection PhpIncludeInspection */
		require $file_path;
	}


	/**
	 * @param string $file_path
	 */
	public function handleFile( $file_path ) {

		$ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));

		if(in_array($ext, static::$php_file_extensions)) {

			/** @noinspection PhpIncludeInspection */
			require $file_path;
		} else {
			IO_File::send($file_path);
		}

		Application::end();

	}


	/**
	 * @return string
	 */
	public function render() {
		/**
		 * @var Mvc_Page_Trait_Handlers|Mvc_Page $this
		 */

		if( ($output=$this->getOutput()) ) {
			return $output;
		}

		$this->initializeLayout();

		Debug_Profiler::MainBlockStart('Content dispatch');


		$translator_namespace = Translator::COMMON_NAMESPACE;

		Translator::setCurrentNamespace( $translator_namespace );

		foreach($this->getContent() as $content ) {
			Mvc::setCurrentContent($content);

			$content->dispatch();

			Mvc::unsetCurrentContent();
		}

		Translator::setCurrentNamespace( $translator_namespace );

		$output = Mvc_Layout::getCurrentLayout()->render();

		Debug_Profiler::MainBlockEnd('Content dispatch');

		return $output;

	}

}