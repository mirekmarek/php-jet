<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetUI;
use Jet\BaseObject;
use Jet\Http_Request;


class tabs extends BaseObject
{

	/**
	 * @var tabs_tab[]
	 */
	protected $tabs = [];

	/**
	 * @var string
	 */
	protected $selected_page_id;

	/**
	 * @param array $tabs
	 */
	public function __construct( array $tabs ) {
		$tab_ids = [];
		$default_tab_id = null;
		foreach( $tabs as $id=>$title ) {
			if(!$default_tab_id) {
				$default_tab_id = $id;
			}
			$tab_ids[] = $id;

			$this->tabs[$id] = new tabs_tab($id, $title);
		}

		$this->selected_page_id = Http_Request::GET()->getString('p', $default_tab_id, $tab_ids);

		$this->tabs[$this->selected_page_id]->setIsSelected(true);
	}

	/**
	 * @return string
	 */
	public function getSelectedPageId()
	{
		return $this->selected_page_id;
	}

	/**
	 * @return string
	 */
	public function toString() {
		$result = '<ul class="nav nav-tabs" style="margin-top: 10px;">';
		foreach( $this->tabs as $tab ) {
			$result .= $tab;
		}
		$result .= '</ul>';

		return $result;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->toString();
	}
}