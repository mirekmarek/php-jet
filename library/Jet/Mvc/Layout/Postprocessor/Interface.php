<?php
/**
 *
 *
 *
 * Sometimes it will probably be necessary to further process the output (filter)
 * This can be done in the following way:
 *
 * <code>
 * class JetApplicationModule_MyModule_MyLayoutFilter implements Mvc_Layout_Postprocessor_Interface {
 *	public function finalPostProcess( &$result, Mvc_Layout $layout ) {
 *		$tidy = new tidy;
 *		$tidy->parseString($result, array());
 *		$tidy->cleanRepair();
 *		$result = (string)$tidy;
 *	}
 * }
 * </code>
 *
 * Then we can pass to layout an instance of this class. For example, the Controller script code:
 *
 * <code>
 *	$layout->my_output_filter = new JetApplicationModule_MyModule_MyLayoutFilter();
 * </code>
 *
 * JetApplicationModule_MyModule_MyLayoutFilter::finalPostProcess method is automatically called after the rendering output.
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Layout
 */
namespace Jet;

interface Mvc_Layout_Postprocessor_Interface {

	/**
	 * This method is called before positions processing
	 *
	 * @param string &$result
	 * @param Mvc_Layout $layout
	 * @param Mvc_Layout_OutputPart[] $output_parts
	 */
	public function layoutPostProcess( &$result, Mvc_Layout $layout, array $output_parts );

	/**
	 * This method is called when processing is completed and the content is placed in its positions
	 *
	 * @param string &$result
	 * @param Mvc_Layout $layout
	 */
	public function finalPostProcess( &$result, Mvc_Layout $layout );

}