<?php
/**
 *
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Layout
 */
namespace Jet;

class Mvc_Layout_PackageCreator_CSS extends Mvc_Layout_PackageCreator_CSS_Abstract {

	/**
	 * @var string
	 */
	protected $key = null;

	/**
	 *
	 * @return string
	 */
	public function getKey() {
		if(!$this->key) {
			$this->key = $this->locale.'_'.$this->media.'_'.md5(implode('', $this->URIs));
		}

		return $this->key;
	}


	/**
	 *
	 * @return string
	 */
	public function createPackage() {
		$CSS = '';

		foreach( $this->URIs as $URI ) {

			$CSS_file_data = $this->getFileContent( $URI );
			if(!$CSS_file_data) {
				continue;
			}

			$CSS_file_data = $this->changeUrls( $CSS_file_data, $URI );

			$CSS .= '/* URI: '.$URI.' */'.JET_EOL;
			$CSS .= $CSS_file_data.JET_EOL;
			$CSS .= '/* ------------------------ */ '.JET_EOL;
		}

		return $CSS;
	}

	/**
	 * @param string $CSS_file_data
	 * @param string $URI
	 * @return string
	 */
	public function changeUrls($CSS_file_data, $URI) {
		$base_URI = dirname( $this->normalizeURI( $URI ) ).'/';


		$res = [];
		if(preg_match_all('/url\(([^)]*)\)/', $CSS_file_data, $res, PREG_SET_ORDER)) {
			foreach($res as $r) {
				$orig_str = $r[0];
				$path = trim( $r[1] );

				if($path[0]=='"' || $path[0]=="'") {
					$path = substr($path, 1, -1);
				}


				if($path[0]=='.') {

					$_base_URI = $base_URI;

					$path = explode('/', $path);

					while( $path[0]=='..' ) {
						array_shift($path);
						$_base_URI = dirname($_base_URI);
					}

					$URL = $_base_URI.'/'.implode('/', $path);

				} else {
					$URL = $base_URI.$path;
				}

				$CSS_file_data = str_replace($orig_str, 'url("'.$URL.'")', $CSS_file_data);

			}

		}

		return $CSS_file_data;
	}

	/**
	 * @return string
	 */
	public function getPackageRelativeFileName() {

		return Mvc_Layout::CSS_PACKAGES_DIR_NAME
				.$this->getKey()
				.'.css';
	}

	/**
	 *
	 */
	public function generatePackageFile() {


		$package_path = $this->getPackagePath();
		$package_data_path = $this->getPackageDataPath();

		if(
            !IO_File::exists($package_path) ||
            !IO_File::exists($package_data_path)
        ) {

			IO_File::write(
				$package_path,
				$this->createPackage()
			);

			IO_File::write(
				$package_data_path,
				serialize([
					'omitted_URIs' => $this->omitted_URIs
				])
			);

		} else {
			$data = IO_File::read($package_data_path);
			$data = unserialize( $data );

			$this->omitted_URIs = $data['omitted_URIs'];

		}

	}


}