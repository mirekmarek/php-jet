<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;


/**
 *
 */
class UI_tree extends BaseObject
{
	/**
	 * @var string
	 */
	protected static $default_renderer_script = 'tree';

	/**
	 * @var string
	 */
	protected $renderer_script;

	/**
	 * @var Data_Tree
	 */
	protected $data;

	/**
	 * @var string
	 */
	protected $selected_id = '';

	/**
	 * @var string
	 */
	protected $root_id = [];

	/**
	 * @var bool
	 */
	protected $show_all = false;

	/**
	 * @var callable
	 */
	protected $renderer_selected;

	/**
	 * @var callable
	 */
	protected $renderer_opened;

	/**
	 * @var callable
	 */
	protected $renderer_normal;

	/**
	 * @return string
	 */
	public static function getDefaultRendererScript()
	{
		return static::$default_renderer_script;
	}

	/**
	 * @param string $default_renderer_script
	 */
	public static function setDefaultRendererScript( $default_renderer_script )
	{
		static::$default_renderer_script = $default_renderer_script;
	}

	/**
	 * @return string
	 */
	public function getRendererScript()
	{
		if(!$this->renderer_script) {
			$this->renderer_script = static::getDefaultRendererScript();
		}

		return $this->renderer_script;
	}

	/**
	 * @param string $renderer_script
	 *
	 */
	public function setRendererScript( $renderer_script )
	{
		$this->renderer_script = $renderer_script;
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


		if(
			!$this->getShowAll() &&
			$selected_path
		) {
			if(
				!(
					in_array( $node->getParentId(), $selected_path ) ||
					in_array( $node->getId(), $selected_path )
				)
			) {
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
	 * @return string
	 */
	public function getRootId()
	{
		return $this->root_id;
	}

	/**
	 * @param string $root_id
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
	 * @return bool
	 */
	public function nodeSelected( Data_Tree_Node $node )
	{
		return ( $node->getId()==$this->getSelectedId() );
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
	 * @param Data_Tree_Node $node
	 *
	 * @return callable
	 *
	 * @throws Exception
	 */
	public function getNodeRenderer( Data_Tree_Node $node )
	{
		$renderer = $this->getRendererNormal();
		if(!$renderer) {
			throw new Exception('Renderer for normal tree node is not defined');
		}

		if( $this->nodeSelected( $node ) ) {
			$renderer = $this->getRendererSelected();
			if(!$renderer) {
				throw new Exception('Renderer for selected tree node is not defined');
			}
		} else if( $this->nodeOpened( $node ) ) {
			$renderer = $this->getRendererOpened();
			if(!$renderer) {
				throw new Exception('Renderer for opened tree node is not defined');
			}
		}

		return $renderer;
	}

	/**
	 * @return callable
	 */
	public function getRendererNormal()
	{
		return $this->renderer_normal;
	}

	/**
	 * @param callable $renderer
	 */
	public function setRendererNormal( callable $renderer )
	{
		$this->renderer_normal = $renderer;
	}

	/**
	 * @return callable
	 */
	public function getRendererSelected()
	{
		return $this->renderer_selected;
	}

	/**
	 * @param callable $renderer
	 */
	public function setRendererSelected( callable $renderer )
	{
		$this->renderer_selected = $renderer;
	}

	/**
	 * @return callable
	 */
	public function getRendererOpened()
	{
		return $this->renderer_opened;
	}

	/**
	 * @param callable $renderer
	 */
	public function setRendererOpened( callable $renderer )
	{
		$this->renderer_opened = $renderer;
	}

	/**
	 * @return Mvc_View
	 */
	public function getView()
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

	/**
	 * @return string
	 */
	public function render()
	{
		return $this->getView()->render( $this->getRendererScript() );
	}

}