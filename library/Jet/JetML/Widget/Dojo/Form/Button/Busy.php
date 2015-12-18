<?php
/**
 *
 *
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

class JetML_Widget_Dojo_Form_Button_Busy extends JetML_Widget_Dojo_Form_Button {

	/**
	 *
	 * @var string
	 */
	protected $dojo_type = 'dojox.form.BusyButton';

	/**
	 * @var array
	 */
	protected $translate_properties = ['title', 'busylabel'];

	/**
	 * @var array
	 */
	protected $dojo_props_real_names_map = [
		'busylabel' => 'busyLabel'
	];

}