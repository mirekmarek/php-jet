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
namespace Jet;

class Installer_Step_DirsCheck_Controller extends Installer_Step_Controller {

	public function main() {
		if(Http_Request::POST()->exists('go')) {
			$this->installer->goNext();
		}

		$dirs = [
			JET_DATA_PATH => [
				'is_required' => true,
				'is_writeable' => false
			],
			JET_TMP_PATH => [
				'is_required' => true,
				'is_writeable' => false
			],
			JET_LOGS_PATH => [
				'is_required' => true,
				'is_writeable' => false
			],
			JET_SITES_PATH => [
				'is_required' => true,
				'is_writeable' => false
			],
			JET_CONFIG_PATH => [
				'is_required' => false,
				'is_writeable' => false,
				'comment' => 'Never mind. In fact, it is better that the directory is not writeable. But you have to complete the installation manually.'
			]
		];


		$is_OK = true;
		foreach( $dirs as $dir=>$dir_data ) {
			$dirs[$dir]['is_writeable'] = IO_Dir::isWritable( $dir );
			if(!$dirs[$dir]['is_writeable'] && $dir_data['is_required']) {
				$is_OK = false;
			}
		}

		$this->view->setVar('is_OK', $is_OK);
		$this->view->setVar('dirs', $dirs);



		$this->render('default');
	}

	public function getLabel() {
		return Tr::_('Check directories permissions', [], 'DirsCheck');
	}

}
