<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\Images;
use Jet;

class Main extends Jet\Application_Modules_Module_Abstract {
	protected $ACL_actions = array(
		'get_gallery' => 'Get article(s) data',
		'add_gallery' => 'Add new gallery',
		'update_gallery' => 'Update gallery',
		'delete_gallery' => 'Delete gallery',

		'get_image' => 'Get image(s) data',
		'add_image' => 'Add new image',
		'update_image' => 'Update image',
		'delete_image' => 'Delete image',
	);

	/**
	 * Returns module views directory
	 *
	 * @return string
	 */
	public function getViewsDir() {
		$dir = parent::getViewsDir();

		if(Jet\Mvc::getIsAdminUIRequest()) {
			return $dir.'admin/';
		} else {
			return $dir.'public/';
		}
	}

	/**
	 * @param string $service_type
	 *
	 * @return string
	 */
	protected function getControllerClassName( $service_type ) {

		if($service_type!=Jet\Mvc::SERVICE_TYPE_REST) {
            if( Jet\Mvc::getIsAdminUIRequest() ) {
				$controller_suffix = 'Controller_Admin_'.$service_type;

			} else {
				$controller_suffix = 'Controller_Public_'.$service_type;
			}
		} else {
			$controller_suffix = 'Controller_'.$service_type;
		}

		$controller_class_name = $this->module_manifest->getNamespace().$controller_suffix;

		return $controller_class_name;
	}


}