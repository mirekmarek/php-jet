<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\IO_Dir;

/**
 *
 */
class Installer_Step_DirsCheck_Controller extends Installer_Step_Controller
{

	/**
	 * @var string
	 */
	protected $label = 'Check directories permissions';

	/**
	 * @return bool
	 */
	public function getIsAvailable()
	{
		return !Installer_Step_CreateSite_Controller::sitesCreated();
	}

	/**
	 *
	 */
	public function main()
	{
		$this->catchContinue();

		$dirs = [
			JET_PATH_DATA                              => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			JET_PATH_TMP                               => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			JET_PATH_CACHE                             => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			JET_PATH_LOGS                              => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			JET_PATH_SITES.Application_Admin::getSiteId().'/' => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			JET_PATH_SITES.Application_Web::getSiteId().'/' => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			JET_PATH_SITES.Application_REST::getSiteId().'/' => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			JET_PATH_DICTIONARIES => [
				'is_required'  => true,
				'is_writeable' => false,
			],
			JET_PATH_CONFIG       => [
				'is_required'  => false,
				'is_writeable' => false,
				'comment'      => 'Never mind. In fact, it is better that the directory is not writeable. But you have to complete the installation manually.',
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
