<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetUI;

use Jet\BaseObject;
use Jet\Data_Tree;
use Jet\Data_Tree_Node;
use Jet\Mvc_View;

/**
 * Class tree
 * @package JetUI
 */
class tree extends BaseObject
{
	/**
	 * @var Data_Tree
	 */
	protected $data;

	/**
	 * @var string
	 */
	protected $selected_id = '';

	/**
	 * @var array
	 */
	protected $root_id = [];

	/**
	 * @var bool
	 */
	protected $show_all = false;

	/**
	 * @var callable
	 */
	protected $selected_display_callback;

	/**
	 * @var callable
	 */
	protected $opened_display_callback;

	/**
	 * @var callable
	 */
	protected $normal_display_callback;

	/**
	 *
	 */
	public function __construct()
	{
	}

	/**
	 * @param Data_Tree_Node $node
	 *
	 * @return bool
	 */
	public function nodeFilter( Data_Tree_Node $node )
	{

		$tree_data = $this->getData();

		$selected_path = $this->getSelectedPath();

		$root_id = $this->getRootId();

		if( $root_id ) {

			$node_path = $tree_data->getPath( $node->getId() );

			if( !in_array( $root_id, $node_path ) ) {
				return false;
			}
		}


		if( !$this->getShowAll()&&$selected_path ) {
			if( !( in_array( $node->getParentId(), $selected_path )||in_array( $node->getId(), $selected_path ) ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @return Data_Tree
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @param Data_Tree $data
	 */
	public function setData( Data_Tree $data )
	{
		$this->data = $data;
	}

	/**
	 * @return array|bool
	 */
	protected function getSelectedPath()
	{

		$selected_id = $this->getSelectedId();

		$tree_data = $this->getData();

		$path = $selected_id ? $tree_data->getPath( $selected_id ) : false;
		$path = $path ? $path : [ $tree_data->getRootNode()->getId() ];

		return $path;
	}

	/**
	 * @return string
	 */
	public function getSelectedId()
	{
		return $this->selected_id;
	}

	/**
	 * @param string $selected_id
	 */
	public function setSelectedId( $selected_id )
	{
		$this->selected_id = $selected_id;
	}

	/**
	 * @return array
	 */
	public function getRootId()
	{
		return $this->root_id;
	}

	/**
	 * @param array $root_id
	 */
	public function setRootId( $root_id )
	{
		$this->root_id = $root_id;
	}

	/**
	 * @return bool
	 */
	public function getShowAll()
	{
		return $this->show_all;
	}

	/**
	 * @param bool $show_all
	 */
	public function setShowAll( $show_all )
	{
		$this->show_all = $show_all;
	}

	/**
	 * @param Data_Tree_Node $node
	 *
	 * @return callable
	 */
	public function nodeDisplayCallback( Data_Tree_Node $node )
	{
		$callback = $this->getNormalDisplayCallback();
		if( $this->nodeSelected( $node ) ) {
			$callback = $this->getSelectedDisplayCallback();
		} else if( $this->nodeOpened( $node ) ) {
			$callback = $this->getOpenedDisplayCallback();
		}

		return $callback;
	}

	/**
	 * @return callable
	 */
	public function getNormalDisplayCallback()
	{
		return $this->normal_display_callback;
	}

	/**
	 * @param callable $normal_display_callback
	 */
	public function setNormalDisplayCallback( callable $normal_display_callback )
	{
		$this->normal_display_callback = $normal_display_callback;
	}

	/**
	 * @param Data_Tree_Node $node
	 *
	 * @return bool
	 */
	public function nodeSelected( Data_Tree_Node $node )
	{
		return ( $node->getId()==$this->getSelectedId() );
	}

	/**
	 * @return callable
	 */
	public function getSelectedDisplayCallback()
	{
		return $this->selected_display_callback;
	}

	/**
	 * @param callable $selected_display_callback
	 */
	public function setSelectedDisplayCallback( callable $selected_display_callback )
	{
		$this->selected_display_callback = $selected_display_callback;
	}

	/**
	 * @param Data_Tree_Node $node
	 *
	 * @return bool
	 */
	public function nodeOpened( Data_Tree_Node $node )
	{
		return in_array( $node->getId(), $this->getSelectedPath() );
	}

	/**
	 * @return callable
	 */
	public function getOpenedDisplayCallback()
	{
		return $this->opened_display_callback;
	}

	/**
	 * @param callable $opened_display_callback
	 */
	public function setOpenedDisplayCallback( callable $opened_display_callback )
	{
		$this->opened_display_callback = $opened_display_callback;
	}

	/**
	 * @return string
	 */
	public function render()
	{
		$view = $this->getView();

		return $view->render( 'tree' );
	}

	/**
	 * @return Mvc_View
	 */
	protected function getView()
	{
		$view = UI::getView();
		$view->setVar( 'tree', $this );

		return $view;
	}


	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString()
	{
		return $this->render();
	}

}