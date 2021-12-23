<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication\Installer;

use Jet\IO_Dir;
use Jet\SysConf_Path;

use JetApplication\Application_Admin;
use JetApplication\Application_Web;
use JetApplication\Application_REST;

/**
 *
 */
class Installer_Step_DirsCheck_Controller extends Installer_Step_Controller
{

	/**
	 * @var string
	 */
	protected string $label = 'Check directories permissions';

	/**
	 * @return bool
	 */
	public function getIsAvailable(): bool
	{
		return !Installer_Step_CreateBases_Controller::basesCreated();
	}

	/**
	 *
	 */
	public function main(): void
	{
		$this->catchContinue();

		$dirs = [
			SysConf_Path::getData()   => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			SysConf_Path::getTmp()    => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			SysConf_Path::getCache()  => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			SysConf_Path::getLogs()   => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			SysConf_Path::getCss()    => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			SysConf_Path::getJs()     => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			SysConf_Path::getImages() => [
				'is_required'  => true,
				'is_writeable' => false,
			],

			SysConf_Path::getBases() . Application_Admin::getBaseId() . '/' => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			SysConf_Path::getBases() . Application_Web::getBaseId() . '/'   => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			SysConf_Path::getBases() . Application_REST::getBaseId() . '/'  => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			SysConf_Path::getConfig()                                       => [
				'is_required'  => false,
				'is_writeable' => false,
			],
		];


		$is_OK = true;
		foreach( $dirs as $dir => $dir_data ) {
			$dirs[$dir]['is_writeable'] = IO_Dir::isWritable( $dir );
			if( !$dirs[$dir]['is_writeable'] && $dir_data['is_required'] ) {
				$is_OK = false;
			}
		}

		$this->view->setVar( 'is_OK', $is_OK );
		$this->view->setVar( 'dirs', $dirs );


		$this->render( 'default' );
	}

}
