<?php
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package JetML
 */
namespace Jet;

class JetML_Widget_Dojo_Form_Select extends JetML_Widget_Dojo_Abstract {
	
	/**
	 *
	 * @var string
	 */
	protected $dojo_type = 'dijit.form.FilteringSelect';

	/**
	 *
	 * @var string
	 */
	protected $widget_container_tag = 'select';

	/**
	 * @var array
	 */
	protected $dojo_props_real_names_map = array( 'fetchProperties'=>'sortByLabel', 'fetchproperties'=>'fetchProperties' );

}