<?php 
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Form
 */
namespace Jet;

class Form_Field_Search extends Form_Field_Input {
	/**
	 * @var string
	 */
	protected $_type = Form::TYPE_SEARCH;

	/**
	 * @var string
	 */
	protected $_input_type = 'search';

	/**
	 * @var array
	 */
	protected $error_messages = [];


}