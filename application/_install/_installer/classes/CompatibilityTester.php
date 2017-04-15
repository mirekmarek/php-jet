<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Installer
 */
namespace JetExampleApp;

class CompatibilityTester {
	/**
	 * @var string
	 */
	protected $PHP_info = '';

	/**
	 * @var CompatibilityTester_TestResult[]
	 */
	protected $test_results = [];

	/**
	 * @var bool
	 */
	protected $is_compatible;

	/**
	 * @var bool
	 */
	protected $has_warnings;

	/**
	 *
	 */
	public function __construct() {
		foreach([
			        'function_exists',
			        'class_exists',
			        'version_compare',
			        'ini_get',
			        'ob_start',
			        'ob_end_clean',
			        'ob_get_contents',
			        'phpinfo'
		        ] as $required_function) {

			if( !function_exists($required_function) ) {
				trigger_error('Error: function \''.$required_function.'\' is required!', E_USER_ERROR);
			}
		}

		ob_start();
		phpinfo();
		$this->PHP_info = ob_get_contents();
		ob_end_clean();

	}

	/**
	 * @param array $tests
	 * @return bool
	 */
	public function testSystem( array $tests ) {

		foreach( $tests as $test ) {
			$this->{$test}();
		}

		$this->is_compatible = true;
		$this->has_warnings = false;

		foreach($this->test_results as $test_result) {
			if($test_result->getIsError()) {
				$this->is_compatible = false;
			}
			if($test_result->getIsWarning()) {
				$this->has_warnings = true;
			}
		}


		return $this->is_compatible;
	}

	/**
	 * @return CompatibilityTester_TestResult[]
	 */
	public function getTestResults()
	{
		return $this->test_results;
	}

	/**
	 * @return boolean
	 */
	public function isCompatible()
	{
		return $this->is_compatible;
	}

	/**
	 * @return boolean
	 */
	public function hasWarnings()
	{
		return $this->has_warnings;
	}



	/**
	 * @param string $title
	 * @param string $description
	 * @param callable $test
	 *
	 * @return bool
	 */
	public function test( $title, $description, callable $test ) {
		$test_result = new CompatibilityTester_TestResult(true, $title, $description);
		$test_result->setPassed( $test($test_result) );
		$this->test_results[] = $test_result;

		return $test_result->getPassed();
	}

	/**
	 * @param string $title
	 * @param string $description
	 * @param callable $test
	 *
	 * @return bool
	 */
	public function check( $title, $description, callable $test ) {
		$test_result = new CompatibilityTester_TestResult(false, $title, $description);
		$test_result->setPassed( $test($test_result) );
		$this->test_results[] = $test_result;

		return $test_result->getPassed();
	}


	/**
	 *
	 */
	public function test_PHPVersion() {
		$this->test(
			'PHP version',
			'PHP 5.5.4 or newer is required',
			function(CompatibilityTester_TestResult $test_result ) {
				$test_result->setResultMessage('PHP version: '.PHP_VERSION);
				return version_compare(PHP_VERSION, '5.5.4', '>=');
			}
		);

	}

	/**
	 *
	 */
	public function test_PDOExtension() {
		$this->test(
			'PDO extension',
			'PHP PDO extension must be activated',
			function() {
				return extension_loaded('PDO');
			}
		);
	}

	/**
	 *
	 */
	public function check_INTLExtension() {
		$this->check(
			'INTL extension',
			'PHP Internationalization Functions extension must be activated',
			function(CompatibilityTester_TestResult $test_result ) {
				$result = extension_loaded('intl');

				if(!$result) {
					$test_result->setResultMessage( '<b style="color:red">Internationalization support is limited!</b>' );
				}

				return $result;
			}
		);
	}

	/**
	 *
	 */
	public function test_RequestUriVar() {
		$this->test(
			'$_SERVER["REQUEST_URI"] value',
			'PHP $_SERVER["REQUEST_URI"] value must be available',
			function() {
				return isset($_SERVER['REQUEST_URI']);
			}
		);

	}


	/**
	 *
	 */
	public function check_GDExtension() {
		if( $this->check(
			'GD extension',
			'PHP GD extension should be activated', function() {
			return extension_loaded('gd');
		}
		) ) {
			/** @noinspection SpellCheckingInspection */
			$this->check(
				'GD Functions',
				'imagecreatefromjpeg, imagecreatefromgif, imagecreatefrompng, imagecolorallocatealpha, imagefilledrectangle, imagecopyresampled, imagejpeg, imagegif, imagepng',
				function(CompatibilityTester_TestResult $test_result ) {
					/** @noinspection SpellCheckingInspection */
					$functions = ['imagecreatefromjpeg','imagecreatefromgif','imagecreatefrompng','imagecolorallocatealpha','imagefilledrectangle','imagecopyresampled','imagejpeg','imagegif','imagepng'];

					$OK = true;

					$na_function_names = [];

					foreach( $functions as $function_name ) {
						if(!function_exists($function_name)) {
							$na_function_names[] = '<i>'.$function_name.'</i> is not available';
							$OK = false;
						}
					}

					$na_function_names = implode('<br/>', $na_function_names);
					$test_result->setResultMessage($na_function_names);

					return $OK;
				}
			);
		}
	}

	/**
	 *
	 */
	public function check_FileInfoExtension() {
		$this->check(
			'FileInfo extension',
			'PHP FileInfo extension should be activated',
			function() {
				return extension_loaded('fileinfo');
			}
		);
	}

	/**
	 *
	 */
	public function check_MaxUploadFileSize() {
		$this->check(
			'PHP configuration: Max upload file size',
			'',
			function(CompatibilityTester_TestResult $test_result ) {
				$post_max_size_cv = $post_max_size = ini_get('post_max_size');
				$upload_max_filesize_cv = $upload_max_filesize = ini_get('upload_max_filesize');


				$post_max_size_unit = substr($post_max_size, -1);
				$upload_max_filesize_unit = substr($upload_max_filesize, -1);

				$units = ['' => 1, 'K'=>1024, 'M'=>1048576, 'G'=>1073741824];

				$post_max_size = $post_max_size*$units[$post_max_size_unit];
				$upload_max_filesize = $upload_max_filesize*$units[$upload_max_filesize_unit];

				if($post_max_size<=$upload_max_filesize) {
					$test_result->setResultMessage( '<i>post_max_size</i> ('.$post_max_size_cv.') should be greater then <i>upload_max_filesize</i> ('.$upload_max_filesize_cv.')' );
					return false;
				}

				if($upload_max_filesize<2097152) {
					$test_result->setResultMessage('<i>upload_max_filesize</i> ('.$upload_max_filesize_cv.') should be greater then 2MB');
					return false;
				}

				$test_result->setResultMessage( '<i>post_max_size</i>: '.$post_max_size_cv.'<br/><i>upload_max_filesize</i>:'.$upload_max_filesize_cv );

				return true;

			}
		);

	}

	/**
	 *
	 */
	public function check_PHPConfigPaths() {
		if( $this->check(
			'PHP configurations - paths',
			'open_basedir, upload_tmp_dir and session.save_path',
			function(CompatibilityTester_TestResult $test_result ) {
				$open_basedir = ini_get('open_basedir');

				if(!$open_basedir) {
					$test_result->setResultMessage( '<b style="color:red"><i>open_basedir</i> should be set!</b>' );
					return false;
				}
				return true;
			}
		)) {


			$this->test(
				'open_basedir vs upload_tmp_dir',
				'',
				function(CompatibilityTester_TestResult $test_result ) {
					$open_base_dirs = explode(':', ini_get('open_basedir'));
					$upload_tmp_dir = realpath( ini_get('upload_tmp_dir') );

					$result = false;

					foreach( $open_base_dirs as $open_base_dir ) {
						if(strpos($upload_tmp_dir, $open_base_dir )===0) {
							$result = true;
							break;
						}

					}


					if(!$result) {
						$test_result->setResultMessage('upload_tmp_dir is '.$upload_tmp_dir.' but open_basedir is '.$open_base_dirs);
					}

					return $result;
				}
			);

			$this->test(
				'open_basedir vs session.save_path',
				'',
				function(CompatibilityTester_TestResult $test_result ) {
					$open_base_dirs = explode(':', ini_get('open_basedir'));
					$session_save_path = realpath( ini_get('session.save_path') );

					$result = false;

					foreach( $open_base_dirs as $open_base_dir ) {
						if(strpos($session_save_path, $open_base_dir )===0) {
							$result = true;
							break;
						}

					}

					if(!$result) {
						$test_result->setResultMessage('session.save_path is '.$session_save_path.' but open_basedir is '.$open_base_dirs);
					}

					return $result;
				}
			);


		}

	}



}
