<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetUI;

use Jet\Http_Request;


/**
 * Class tabs
 * @package JetUI
 */
class tabs extends BaseElement
{
	/**
	 * @var string
	 */
	protected static $default_renderer_script = 'tabs';

	/**
	 * @var tabs_tab[]
	 */
	protected $tabs = [];

	/**
	 * @var string
	 */
	protected $selected_page_id;

	/**
	 * @var string
	 */
	protected $get_parameter = 'p';

	/**
	 * @param array $tabs
	 */
	public function __construct( array $tabs )
	{
		foreach( $tabs as $id => $title ) {
			$this->tabs[$id] = new tabs_tab( $id, $title );
		}

		$this->setGetParameter($this->getGetParameter());
	}

	/**
	 * @return string
	 */
	public function getGetParameter()
	{
		return $this->get_parameter;
	}

	/**
	 * @param string $get_parameter
	 *
	 * @return $this
	 */
	public function setGetParameter( $get_parameter )
	{
		$this->get_parameter = $get_parameter;

		foreach( $this->tabs as $tab ) {
			$tab->setGetParameter($get_parameter);
		}

		$tab_ids = [];
		$default_tab_id = null;
		foreach( $this->tabs as $tab ) {
			if( !$default_tab_id ) {
				$default_tab_id = $tab->getId();
			}
			$tab_ids[] = $tab->getId();

			$tab->setGetParameter($get_parameter);
			$tab->setIsSelected(false);

		}

		$this->selected_page_id = Http_Request::GET()->getString( $this->getGetParameter(), $default_tab_id, $tab_ids );

		$this->tabs[$this->selected_page_id]->setIsSelected( true );


		return $this;
	}

	/**
	 * @param string $id
	 *
	 * @return tabs_tab
	 */
	public function getTab( $id )
	{
		return $this->tabs[$id];
	}

	/**
	 * @return string
	 */
	public function getSelectedPageId()
	{
		return $this->selected_page_id;
	}

	/**
	 * @return tabs_tab[]
	 */
	public function getTabs()
	{
		return $this->tabs;
	}

}