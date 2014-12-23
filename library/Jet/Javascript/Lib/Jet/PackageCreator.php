<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Javascript
 * @subpackage Javascript_Lib
 */
namespace Jet;

class Javascript_Lib_Jet_PackageCreator extends Object {

	/**
	 * @var string
	 */
	protected $base_path = '';

	/**
	 * @var Locale
	 */
	protected $locale;

	/**
	 * @var array
	 */
	protected $components = [];


	/**
	 * @param string $base_path
	 * @param array $components
	 * @param Locale $locale
	 */
	public function __construct( $base_path, Locale $locale, $components ) {

		$this->base_path = $base_path;
		$this->locale = $locale;

		array_unshift($components, 'Jet');

		$this->components = array_unique($components);

	}

	/**
	 * @return string
	 */
	public function getKey() {

		$key = '';
		foreach( $this->components as $component ) {
			$key .= $component;
		}
		$key = $this->locale.'-'.md5($key);

		return $key;
	}


	/**
	 * @param string $component
	 *
	 * @return string
	 */
	protected function getScript( $component ) {

		$base_path = $this->base_path;

		$parts = explode('.', $component);
		$path = Data_Text::replaceSystemConstants( $base_path . implode('/', $parts) . '.js' );

		$script = IO_File::read( $path );

		$script = Javascript::translateJavaScript($script);

		return $script;

	}



	/**
	 * @return string
	 */
	public function createPackage() {
		$JS = '';

		foreach($this->components as $component) {

			$JS .= JET_EOL.'// '.$component.JET_EOL;
			//$JS .= JET_EOL.'console.debug("'.$component.'");'.JET_EOL;
			$JS .= $this->getScript($component).JET_EOL;
			$JS .= 'Jet._loaded_components["'.$component.'"]=true;';
			$JS .= JET_EOL.'//-----------------------------'.JET_EOL.JET_EOL;
		}

		return $JS;
	}


	/**
	 * @return string
	 */
	public function getPackageFileName() {
		$key = $this->getKey();
		return Mvc_Layout::JS_PACKAGES_DIR_NAME.'jet-'.$key.'.js';
	}

	/**
	 * @return string
	 */
	public function getPackageFilePath() {
		return JET_PUBLIC_PATH.$this->getPackageFileName();
	}

	/**
	 *
	 */
	public function getPackageURI() {
		return '%JET_PUBLIC_URI%'.$this->getPackageFileName();
	}

	/**
	 *
	 */
	public function generatePackageFile() {

		$package_path = $this->getPackageFilePath();

		if(!IO_File::exists($package_path)) {


			IO_File::write(
				$package_path,
				$this->createPackage()
			);
		}

	}
}