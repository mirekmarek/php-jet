<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
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
			SysConf_Path::DATA()                                     => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			SysConf_Path::TMP()                                      => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			SysConf_Path::CACHE()                                    => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			SysConf_Path::LOGS()                                     => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			SysConf_Path::SITES().Application_Admin::getSiteId().'/' => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			SysConf_Path::SITES().Application_Web::getSiteId().'/'   => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			SysConf_Path::SITES().Application_REST::getSiteId().'/'  => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			SysConf_Path::DICTIONARIES()                             => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			SysConf_Path::CONFIG()                                   => [
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
