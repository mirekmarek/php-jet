<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Form
 */
namespace Jet;

/**
 * Class Form_Decorator_Abstract
 *
 * @JetFactory:class = 'UI_Factory'
 * @JetFactory:method = 'getFormDecoratorInstance'
 * @JetFactory:mandatory_parent_class = 'Form_Decorator_Abstract'
 */
abstract class Form_Decorator_Abstract extends Object {

	/**
	 * @var Form
	 */
	protected $form;

	/**
	 * @var Form_Field_Abstract
	 */
	protected $field;

	/**
	 * @param Form $form
	 * @param Form_Field_Abstract $field
	 */
	public function __construct( Form $form, Form_Field_Abstract $field ) {
		$this->form = $form;
		$this->field = $field;
	}

	/**
	 * @abstract
	 *
	 * @param Form_Parser_TagData $tag_data
	 *
	 */
	abstract function decorate( Form_Parser_TagData $tag_data );
}