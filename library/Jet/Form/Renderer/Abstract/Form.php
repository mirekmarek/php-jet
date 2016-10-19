<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Form
 */
namespace Jet;

abstract class Form_Renderer_Abstract_Form extends Form_Renderer_Abstract_Tag {

	/**
	 * @var string
	 */
	protected $tag = 'form';

	/**
	 * @var bool
	 */
	protected $is_pair = true;

	/**
	 * @var bool
	 */
	protected $has_content = false;

	/**
	 * @var Form
	 */
	protected $_form;

	/**
	 * Form_Renderer_Bootstrap_Form constructor.
	 *
	 * @param Form $form
	 */
	public function __construct(Form $form)
	{
		$this->_form = $form;
	}

	/**
	 * @return string
	 */
	public function render()
	{
		$form = $this->_form;

		$tag_options = [
			'name' => $form->getName(),
			'id' => $form->getID(),
			'method' => $form->getMethod()
		];

		if($form->getAction()) {
			$tag_options['action'] = $form->getAction();
		}

		if($form->getTarget()) {
			$tag_options['target'] = $form->getTarget();
		}

		if(!$form->getAutocomplete()) {
			$tag_options['autocomplete'] = 'off';
		}

		if($form->getEnctype()) {
			$tag_options['enctype'] = $form->getEnctype();
		}

		if($form->getAcceptCharset()) {
			$tag_options['accept-charset'] = $form->getAcceptCharset();
		}

		if($form->getNovalidate()) {
			$tag_options['novalidate'] = 'novalidate';
		}


		$res =  $this->generate($tag_options);

		if(!$form->getIsReadonly()) {
			$res .= JET_TAB.'<input type="hidden" name="'.Form::FORM_SENT_KEY.'" value="'.$form->getName().'">'.JET_EOL;
		}

		return $res;
	}
}