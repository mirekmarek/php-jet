<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication\Installer;

use Jet\IO_Dir;
use Jet\SysConf_PATH;

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
	public function getIsAvailable() : bool
	{
		return !Installer_Step_CreateSite_Controller::sitesCreated();
	}

	/**
	 *
	 */
	public function main() : void
	{
		$this->catchContinue();

		$dirs = [
			SysConf_PATH::DATA()                                     => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			SysConf_PATH::TMP()                                      => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			SysConf_PATH::CACHE()                                    => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			SysConf_PATH::LOGS()                                     => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			SysConf_PATH::SITES().Application_Admin::getSiteId().'/' => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			SysConf_PATH::SITES().Application_Web::getSiteId().'/'   => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			SysConf_PATH::SITES().Application_REST::getSiteId().'/'  => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			SysConf_PATH::DICTIONARIES()                             => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			SysConf_PATH::CONFIG()                                   => [
				'is_required'  => false,
				'is_writeable' => false,
			],
		];


		$is_OK = true;
		foreach( $dirs as $dir => $dir_data ) {
			$dirs[$dir]['is_writeable'] = IO_Dir::isWritable( $dir );
			if( !$dirs[$dir]['is_writeable']&&$dir_data['is_required'] ) {
				$is_OK = false;
			}
		}

		$this->view->setVar( 'is_OK', $is_OK );
		$this->view->setVar( 'dirs', $dirs );


		$this->render( 'default' );
	}

}
