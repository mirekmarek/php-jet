<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\UIElements;

use Jet\Mvc_View;
use Jet\Application_Modules_Module_Abstract;

class Main extends Application_Modules_Module_Abstract {

	/**
	 * @return DataGrid
	 */
	public function getDataGridInstance() {
		return new DataGrid( $this );
	}

	/**
	 * @return Tree
	 */
	public function getTreeInstance() {
		return new Tree( $this );
	}

	/**
	 * @return Mvc_View
	 */
	public function getViewInstance() {
		return new Mvc_View( $this->getViewsDir() );
	}
}