<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication\Installer;

use Jet\Locale;
use Jet\Tr;

require_once 'CompatibilityTester/TestResult.php';

/**
 *
 */
class Installer_CompatibilityTester
{
	/**
	 * @var string
	 */
	protected string $PHP_info = '';

	/**
	 * @var Installer_CompatibilityTester_TestResult[]
	 */
	protected array $test_results = [];

	/**
	 * @var bool|null
	 */
	protected bool|null $is_compatible = null;

	/**
	 * @var bool|null
	 */
	protected bool|null $has_warnings = null;

	/**
	 *
	 */
	public function __construct()
	{
		foreach( [
					 'function_exists',
					 'class_exists',
					 'version_compare',
					 'ini_get',
					 'ob_start',
					 'ob_end_clean',
					 'ob_get_contents',
					 'phpinfo',
				 ] as $required_function ) {

			if( !function_exists( $required_function ) ) {
				trigger_error( 'Error: function \'' . $required_function . '\' is required!', E_USER_ERROR );
			}
		}

		ob_start();
		phpinfo();
		$this->PHP_info = ob_get_contents();
		ob_end_clean();

	}

	/**
	 * @param array $tests
	 *
	 * @return bool
	 */
	public function testSystem( array $tests ): bool
	{

		foreach( $tests as $test ) {
			$this->{$test}();
		}

		$this->is_compatible = true;
		$this->has_warnings = false;

		foreach( $this->test_results as $test_result ) {
			if( $test_result->getIsError() ) {
				$this->is_compatible = false;
			}
			if( $test_result->getIsWarning() ) {
				$this->has_warnings = true;
			}
		}


		return $this->is_compatible;
	}

	/**
	 * @return Installer_CompatibilityTester_TestResult[]
	 */
	public function getTestResults(): array
	{
		return $this->test_results;
	}

	/**
	 * @return bool
	 */
	public function isCompatible(): bool
	{
		return $this->is_compatible;
	}

	/**
	 * @return bool
	 */
	public function hasWarnings(): bool
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
	public function test( string $title, string $description, callable $test ): bool
	{
		$test_result = new Installer_CompatibilityTester_TestResult( true, $title, $description );
		$test_result->setPassed( $test( $test_result ) );
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
	public function check( string $title, string $description, callable $test ): bool
	{
		$test_result = new Installer_CompatibilityTester_TestResult( false, $title, $description );
		$test_result->setPassed( $test( $test_result ) );
		$this->test_results[] = $test_result;

		return $test_result->getPassed();
	}


	/**
	 *
	 */
	public function test_PHPVersion(): void
	{
		$required_version = '8.0';

		$this->test(
			Tr::_( 'PHP version' ),
			Tr::_( 'PHP %VERSION% or newer is required', ['VERSION' => $required_version] ),
			function( Installer_CompatibilityTester_TestResult $test_result ) use ( $required_version ) {
				$test_result->setResultMessage( Tr::_( 'PHP version: ' ) . PHP_VERSION );

				return version_compare( PHP_VERSION, $required_version, '>=' );
			}
		);

	}

	/**
	 *
	 */
	public function test_PDOExtension(): void
	{
		$this->test(
			Tr::_( 'PDO extension' ),
			Tr::_( 'PHP PDO extension must be activated' ),
			function() {
				return extension_loaded( 'PDO' );
			}
		);
	}

	/**
	 *
	 */
	public function test_MBStringExtension(): void
	{
		$this->test(
			Tr::_( 'Multibyte String extension' ),
			Tr::_( 'PHP Multibyte String extension must be activated' ),
			function() {
				return extension_loaded( 'mbstring' );
			}
		);
	}

	/**
	 *
	 */
	public function test_INTLExtension(): void
	{
		$this->test(
			Tr::_( 'INTL extension' ),
			Tr::_( 'PHP Internationalization Functions extension must be activated' ),
			function( Installer_CompatibilityTester_TestResult $test_result ) {
				return extension_loaded( 'intl' );
			}
		);
	}

	/**
	 *
	 */
	public function test_RequestUriVar(): void
	{
		$this->test(
			Tr::_( '$_SERVER["REQUEST_URI"] value' ),
			Tr::_( 'PHP $_SERVER["REQUEST_URI"] value must be available' ),
			function() {
				return isset( $_SERVER['REQUEST_URI'] );
			}
		);

	}


	/**
	 *
	 */
	public function check_GDExtension(): void
	{
		if( $this->check(
			Tr::_( 'GD extension' ),
			Tr::_( 'PHP GD extension should be activated' ),
			function() {
				return extension_loaded( 'gd' );
			}
		)
		) {
			/** @noinspection SpellCheckingInspection */
			$this->check(
				Tr::_( 'GD Functions' ),
				'imagecreatefromjpeg, imagecreatefromgif, imagecreatefrompng, imagecolorallocatealpha, imagefilledrectangle, imagecopyresampled, imagejpeg, imagegif, imagepng',
				function( Installer_CompatibilityTester_TestResult $test_result ) {

					/** @noinspection SpellCheckingInspection */
					$functions = [
						'imagecreatefromjpeg',
						'imagecreatefromgif',
						'imagecreatefrompng',
						'imagecolorallocatealpha',
						'imagefilledrectangle',
						'imagecopyresampled',
						'imagejpeg',
						'imagegif',
						'imagepng',
					];

					$OK = true;

					$na_function_names = [];

					foreach( $functions as $function_name ) {
						if( !function_exists( $function_name ) ) {
							$na_function_names[] = Tr::_( '<i>%FUNCTION_NAME%</i> is not available', ['FUNCTION_NAME' => $function_name] );
							$OK = false;
						}
					}

					$na_function_names = implode( '<br/>', $na_function_names );
					$test_result->setResultMessage( $na_function_names );

					return $OK;
				}
			);
		}
	}

	/**
	 *
	 */
	public function check_FileInfoExtension(): void
	{
		$this->check(
			Tr::_( 'FileInfo extension' ),
			Tr::_( 'PHP FileInfo extension should be activated' ),
			function() {
				return extension_loaded( 'fileinfo' );
			}
		);
	}

	/**
	 *
	 */
	public function check_MaxUploadFileSize(): void
	{
		$this->check(
			Tr::_( 'PHP configuration: Max upload file size' ),
			'',
			function( Installer_CompatibilityTester_TestResult $test_result ) {

				$post_max_size_cv = ini_get( 'post_max_size' );
				$post_max_size = $this->getAsBytes( $post_max_size_cv );

				if( $post_max_size_cv == '0' ) {
					$post_max_size_cv = Tr::_( 'unlimited' );
					$post_max_size = 99999999999999999999;
				}


				$upload_max_filesize_cv = ini_get( 'upload_max_filesize' );
				$upload_max_filesize = $this->getAsBytes( $upload_max_filesize_cv );

				if( $post_max_size <= $upload_max_filesize ) {
					$test_result->setResultMessage(
						Tr::_(
							'<i>post_max_size</i> (%POST_MAX_SIZE_CV%) should be greater then <i>upload_max_filesize</i> (%UPLOAD_MAX_FILESIZE_CV%)',
							[
								'POST_MAX_SIZE_CV'       => $post_max_size_cv,
								'UPLOAD_MAX_FILESIZE_CV' => $upload_max_filesize_cv
							]
						)
					);

					return false;
				}

				$limit = 1024 * 1024 * 2;

				if( $upload_max_filesize < $limit ) {
					$test_result->setResultMessage(
						Tr::_(
							'<i>upload_max_filesize</i> (%UPLOAD_MAX_FILESIZE_CV%) should be greater then %LIMIT%',
							[
								'UPLOAD_MAX_FILESIZE_CV' => $upload_max_filesize_cv,
								'LIMIT'                  => Locale::size( $limit )
							]
						)
					);

					return false;
				}

				$test_result->setResultMessage(
					Tr::_(
						'<i>post_max_size</i>: %POST_MAX_SIZE_CV%<br/><i>upload_max_filesize</i>:%UPLOAD_MAX_FILESIZE_CV%',
						[
							'POST_MAX_SIZE_CV'       => $post_max_size_cv,
							'UPLOAD_MAX_FILESIZE_CV' => $upload_max_filesize_cv
						]
					)

				);

				return true;
			}
		);

	}

	/**
	 * @param $val
	 *
	 * @return int
	 */
	public function getAsBytes( $val ): int
	{
		$val = trim( $val );

		$last = strtoupper( $val[strlen( $val ) - 1] );
		$val = substr( $val, 0, -1 );

		switch( $last ) {
			/** @noinspection PhpMissingBreakStatementInspection */
			case 'G':
				$val *= 1024;
			/** @noinspection PhpMissingBreakStatementInspection */
			case 'M':
				$val *= 1024;
			case 'K':
				$val *= 1024;
		}
		/** @noinspection PhpUnnecessaryLocalVariableInspection */
		$val = $val * 1;


		return $val;
	}

}
