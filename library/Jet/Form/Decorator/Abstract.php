<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Form
 */
namespace Jet;

abstract class Form_Decorator_Abstract extends Object {
	/**
	 * @var string
	 */
	protected static $__factory_class_name = "Jet\\UI_Factory";
	/**
	 * @var string
	 */
	protected static $__factory_class_method = "getFormDecoratorInstance";
	/**
	 * @var string
	 */
	protected static $__factory_must_be_instance_of_class_name = "Jet\\Form_Decorator_Abstract";

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