<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Form
 */
namespace Jet;

class Form_Decorator_Dojo_DateTime extends Form_Decorator_Dojo_Abstract {
	/**
	 * @var array
	 */
	protected $decoratable_tags = [
		'field' => [
			'dojo_type' => 'dijit.form.DateTextBox'
		],
		'field_time' => [
			'dojo_type' => 'dijit.form.TimeTextBox'
		]
	];
}