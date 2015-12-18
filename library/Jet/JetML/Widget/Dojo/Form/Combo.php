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

class JetML_Widget_Dojo_Form_Combo extends JetML_Widget_Dojo_Abstract {
	
	/**
	 *
	 * @var string
	 */
	protected $dojo_type = 'dijit.form.ComboBox';

	/**
	 *
	 * @var string
	 */
	protected $widget_container_tag = 'select';

	/**
	 * @var array
	 */
	protected $dojo_props_real_names_map = [
				'fetchProperties'=>'sortByLabel',
				'fetchproperties'=>'fetchProperties',
				'hasdownarrow' => 'hasDownArrow',
			    'invalidmessage' => 'invalidMessage',
				'searchattr' => 'searchAttr',
				'queryexpr' => 'queryExpr',
				'pagesize' => 'pageSize',
				'valueattr' => 'valueAttr',
				'labelattr' => 'labelAttr',
				'labeltype' => 'labelType',
	];

}