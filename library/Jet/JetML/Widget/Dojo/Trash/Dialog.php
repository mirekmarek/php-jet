<?php
/**
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

class JetML_Widget_Dojo_Trash_Dialog extends JetML_Widget_Dojo_Abstract {
	/**
	 * @var array
	 */
	protected $dojo_type = array('dijit.Dialog', 'dijit.form.Button', 'dojox.form.BusyButton');

	/**
	 * @var array
	 */
	public static $default_texts = array(
		'dialog_title' => 'Delete item?',
		'confirm_msg' => 'Do you really want to delete item?',
		'deleting_msg' => 'Deleting ...',
		'yes_button_label' => 'Yes',
		'no_button_label' => 'NO',
	);

	/**
	 * @return \DOMElement
	 */
	public function getReplacement() {
		$Dojo = $this->parser->getLayout()->requireJavascriptLib('Dojo');
		$Dojo->requireComponent('dijit.Dialog');
		$Dojo->requireComponent('dijit.form.Button');
		$Dojo->requireComponent('dojox.form.BusyButton');

		$dom = $this->parser->getDOMDocument();

		$ID = $this->getNodeAttribute('id');

		$dialog = $dom->createElement('div');

		$dialog->setAttribute('data-dojo-type', 'dijit.Dialog' );
		$dialog->setAttribute('id', $ID.'_dialog' );
		$dialog->setAttribute('title', $this->getNodeAttribute('title', $this->getTranslation(static::$default_texts['dialog_title']) ));

		$confirm_msg = $dom->createElement('div', $this->getNodeAttribute('confirm_msg', $this->getTranslation(static::$default_texts['confirm_msg']) ));
		$dialog->appendChild($confirm_msg);

		$items_area = $dom->createElement('div');
		$items_area->setAttribute('id', $ID.'_items_area');
		$items_area->setAttribute('class', 'trashItemsArea');
		$items_area->setAttribute('style', 'width:300px; height:100px; overflow:auto;');
		$dialog->appendChild($items_area);


		$btn_area = $dom->createElement('div');
		$btn_area->setAttribute('class', 'dijitDialogPaneActionBar');
		$dialog->appendChild($btn_area);

		$yes_button = $dom->createElement('button', $this->getNodeAttribute('yes_button_label', $this->getTranslation(static::$default_texts['yes_button_label']) ));
		$yes_button->setAttribute('data-dojo-type', 'dojox.form.BusyButton');
		$yes_button->setAttribute('data-dojo-props', 'busyLabel:'.str_replace('"', '\'', json_encode($this->getNodeAttribute('deleting_msg', $this->getTranslation(static::$default_texts['deleting_msg'])))) );
		$yes_button->setAttribute('id', $ID.'_submit_button');
		$btn_area->appendChild($yes_button);

		$no_button = $dom->createElement('button', $this->getNodeAttribute('no_button_label', $this->getTranslation(static::$default_texts['no_button_label']) ));
		$no_button->setAttribute('data-dojo-type', 'dijit.form.Button');
		$no_button->setAttribute('class', 'cancel');
		$no_button->setAttribute('id', $ID.'_cancel_button');
		$btn_area->appendChild($no_button);

		return $dialog;

	}

	/**
	 *
	 * @return string
	 */
	public function getHtmlStart() {

	}

}