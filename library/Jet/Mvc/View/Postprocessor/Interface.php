<?php
/**
 *
 *
 *
 * Sometimes it will probably be necessary to further process the output (filter)
 * This can be done in the following way:
 *
 * Create a class that implements the interface Mvc_View_Postprocessor_Interface.
 * For example:
 *
 * class JetApplicationModule\MyModule\MyViewFilter implements Mvc\View\Postprocessor\Interface {
 *	public function viewPostProcess( &$result, Mvc_View $view ) {
 *		$result = nl2br( $result );
 *	}
 * }
 *
 * Then we can pass to view an instance of this class. For example, the Controller script code:
 *
 *	$this->view->my_output_filter = new JetApplicationModule\MyModule\MyViewFilter();
 *
 *
 * JetApplicationModule\MyModule\MyViewFilter::ViewPostProcess method is automatically called after the rendering output.
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_View
 */
namespace Jet;

interface Mvc_View_Postprocessor_Interface {

	/**
	 *
	 * @param string &$result
	 * @param Mvc_View $view
	 */
	public function viewPostProcess( &$result, Mvc_View $view );

}