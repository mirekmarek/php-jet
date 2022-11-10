<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class Data_Forest extends BaseObject implements BaseObject_Interface_IteratorCountable, BaseObject_Interface_Serializable_JSON
{

	/**
	 *
	 * @var Data_Tree[]
	 */
	protected array $trees = [];


	/**
	 *
	 * @param Data_Tree $tree
	 *
	 * @throws Data_Tree_Exception
	 */
	public function appendTree( Data_Tree $tree ): void
	{
		$tree_id = $tree->getRootNode()->getId();

		if( isset( $this->trees[$tree_id] ) ) {
			throw new Data_Tree_Exception(
				'Tree \'' . $tree_id . '\' already exists in the forest', Data_Tree_Exception::CODE_TREE_ALREADY_IN_FOREST
			);
		}

		$this->trees[$tree_id] = $tree;
	}

	/**
	 *
	 * @return Data_Tree[]
	 */
	public function getTrees(): array
	{
		return $this->trees;
	}

	/**
	 *
	 * @param string $tree_id
	 *
	 * @return Data_Tree
	 */
	public function getTree( string $tree_id ): Data_Tree
	{
		return $this->trees[$tree_id];
	}

	/**
	 *
	 * @param string $tree_id
	 */
	public function removeTree( string $tree_id ): void
	{
		if( !isset( $this->trees[$tree_id] ) ) {
			return;
		}
		unset( $this->trees[$tree_id] );
	}

	/**
	 *
	 * @param string $tree_id
	 *
	 * @return bool
	 */
	public function getTreeExists( string $tree_id ): bool
	{
		return isset( $this->trees[$tree_id] );
	}


	/**
	 *
	 * @param int|null $max_depth (optional)
	 *
	 * @return array
	 */
	public function toArray( int|null $max_depth = null ): array
	{

		$output = [];
		foreach( $this->trees as $tree ) {
			if( $max_depth ) {
				$tree->getRootNode()->setMaxDepth( $max_depth );
			}

			$output = array_merge( $output, $tree->toArray() );
		}

		return $output;
	}


	/**
	 * @return string
	 */
	public function toJSON(): string
	{

		$data = $this->jsonSerialize();

		return json_encode( $data );
	}

	/**
	 * @return array
	 */
	public function jsonSerialize(): array
	{
		$data = [];

		foreach( $this->trees as $tree ) {
			$data = array_merge( $data, $tree->toArray() );
		}

		return $data;

	}


	//- Iterator ----------------------------------------------------------------------------------
	//- Iterator ----------------------------------------------------------------------------------
	//- Iterator ----------------------------------------------------------------------------------
	//- Iterator ----------------------------------------------------------------------------------
	//- Iterator ----------------------------------------------------------------------------------

	/**
	 * @return Data_Tree_Node
	 */
	public function current(): Data_Tree_Node
	{
		$current_tree_id = key( $this->trees );

		return $this->trees[$current_tree_id]->current();
	}

	/**
	 *
	 */
	public function next(): void
	{
		$current_tree_id = key( $this->trees );

		$this->trees[$current_tree_id]->next();


		if( $this->trees[$current_tree_id]->valid() ) {
			return;
		}

		$this->trees[$current_tree_id]->rewind();
		$next_tree = next( $this->trees );
		if( !$next_tree ) {
			return;
		}
		$next_tree->rewind();
	}

	/**
	 *
	 * @return string
	 */
	public function key(): string
	{
		$current_tree_id = key( $this->trees );

		return $this->trees[$current_tree_id]->key();
	}

	/**
	 *
	 * @return bool
	 */
	public function valid(): bool
	{
		if( !key( $this->trees ) ) {
			return false;
		}
		$current_tree_id = key( $this->trees );

		return $this->trees[$current_tree_id]->valid();
	}

	/**
	 *
	 */
	public function rewind(): void
	{
		foreach( $this->trees as $tree ) {
			$tree->rewind();
		}
		reset( $this->trees );
	}

	//- Countable ---------------------------------------------------------------------------------
	//- Countable ---------------------------------------------------------------------------------
	//- Countable ---------------------------------------------------------------------------------
	//- Countable ---------------------------------------------------------------------------------
	/**
	 * @return int
	 */
	public function count(): int
	{
		$result = 0;

		foreach( $this->trees as $tree ) {
			$result += count( $tree );
		}

		return $result;
	}

}