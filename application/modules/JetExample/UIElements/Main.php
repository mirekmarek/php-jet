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
 * @category JetApplicationModule
 * @package JetApplicationModule\JetExample\UIElements
 */
namespace JetApplicationModule\JetExample\UIElements;
use Jet;

class Main extends Jet\Application_Modules_Module_Abstract {

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
	 * @return Jet\Mvc_View
	 */
	public function getViewInstance() {
		return new Jet\Mvc_View( $this->getViewsDir() );
	}
}