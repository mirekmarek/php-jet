<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication\Installer;

use Error;
use Jet\Exception;
use Jet\IO_Dir;
use Jet\SysConf_Path;

use JetApplication\Application_Admin;
use JetApplication\Application_Exports;
use JetApplication\Application_Services;
use JetApplication\Application_Web;

/**
 *
 */
class Installer_Step_DirsCheck_Controller extends Installer_Step_Controller
{
	
	/**
	 * @var string
	 */
	protected string $icon = 'folder-open';

	/**
	 * @var string
	 */
	protected string $label = 'Check directories permissions';

	/**
	 * @return bool
	 */
	public function getIsAvailable(): bool
	{
		return true;
	}

	/**
	 *
	 */
	public function main(): void
	{
		$this->catchContinue();

		$list = [
			SysConf_Path::getData() => '',
			SysConf_Path::getTmp() => '',
			SysConf_Path::getCache() => '',
			SysConf_Path::getLogs() => '',
			SysConf_Path::getCss() => '',
			SysConf_Path::getJs() => '',
			SysConf_Path::getImages() => '',
			SysConf_Path::getBases() . Application_Admin::getBaseId() . '/' => '',
			SysConf_Path::getBases() . Application_Web::getBaseId() . '/' => '',
			SysConf_Path::getBases() . Application_Exports::getBaseId() . '/' => '',
			SysConf_Path::getBases() . Application_Services::getBaseId() . '/' => '',
			SysConf_Path::getConfig() => '',
		];

		$dirs = [];

		$is_OK = true;
		foreach( $list as $dir => $comment ) {
			$dirs[$dir] = [
				'exists' => false,
				'created' => false,
				'is_writeable' => false,
				'error_message' => '',
				'comment' => $comment,
			];
			
			if(!IO_Dir::exists($dir)) {
				try {
					IO_Dir::create($dir);
					/** @phpstan-ignore catch.neverThrown */
				} catch( Error|Exception $e ) {
					$dirs[$dir]['error_message'] = $e->getMessage();
					
					continue;
				}
			}
			$dirs[$dir]['exists'] = true;
			$dirs[$dir]['created'] = true;
			$dirs[$dir]['is_writeable'] = IO_Dir::isWritable( $dir );
			
			if( !$dirs[$dir]['is_writeable'] ) {
				$is_OK = false;
			}
		}

		$this->view->setVar( 'is_OK', $is_OK );
		$this->view->setVar( 'dirs', $dirs );


		$this->render( 'default' );
	}

}
