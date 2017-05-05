<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

class Data_Tree_ForestTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var Data_Tree_Forest
	 */
	protected $object;

	protected $tree1_data = [
		't1-1'          => [
			'id' => 't1-1', 'parent_id' => 'root1', 'name' => 'Node T1 1',
		], 't1-1-1'     => [
			'id' => 't1-1-1', 'parent_id' => 't1-1', 'name' => 'Node T1 1-1',
		], 't1-1-2'     => [
			'id' => 't1-1-2', 'parent_id' => 't1-1', 'name' => 'Node T1 1-2',
		], 't1-1-2-1'   => [
			'id' => 't1-1-2-1', 'parent_id' => 't1-1-2', 'name' => 'Node T1 1-2-1',
		], 't1-1-2-2'   => [
			'id' => 't1-1-2-2', 'parent_id' => 't1-1-2', 'name' => 'Node T1 1-2-2',
		], 't1-1-2-2-1' => [
			'id' => 't1-1-2-2-1', 'parent_id' => 't1-1-2-2', 'name' => 'Node T1 1-2-2-1',
		], 't1-1-2-2-2' => [
			'id' => 't1-1-2-2-2', 'parent_id' => 't1-1-2-2', 'name' => 'Node T1 1-2-2-2',
		], 't1-1-2-3'   => [
			'id' => 't1-1-2-3', 'parent_id' => 't1-1-2', 'name' => 'Node T1 1-2-3',
		], 't1-1-3'     => [
			'id' => 't1-1-3', 'parent_id' => 't1-1', 'name' => 'Node T1 1-3',
		], 't1-2'       => [
			'id' => 't1-2', 'parent_id' => 'root1', 'name' => 'Node T1 2',
		], 't1-3'       => [
			'id' => 't1-3', 'parent_id' => 'root1', 'name' => 'Node T1 3',
		],
	];
	protected $tree2_data = [
		't2-1'          => [
			'id' => 't2-1', 'parent_id' => 'root2', 'name' => 'Node T2 1',
		], 't2-1-1'     => [
			'id' => 't2-1-1', 'parent_id' => 't2-1', 'name' => 'Node T2 1-1',
		], 't2-1-2'     => [
			'id' => 't2-1-2', 'parent_id' => 't2-1', 'name' => 'Node T2 1-2',
		], 't2-1-2-1'   => [
			'id' => 't2-1-2-1', 'parent_id' => 't2-1-2', 'name' => 'Node T2 1-2-1',
		], 't2-1-2-2'   => [
			'id' => 't2-1-2-2', 'parent_id' => 't2-1-2', 'name' => 'Node T2 1-2-2',
		], 't2-1-2-2-1' => [
			'id' => 't2-1-2-2-1', 'parent_id' => 't2-1-2-2', 'name' => 'Node T2 1-2-2-1',
		], 't2-1-2-2-2' => [
			'id' => 't2-1-2-2-2', 'parent_id' => 't2-1-2-2', 'name' => 'Node T2 1-2-2-2',
		], 't2-1-2-3'   => [
			'id' => 't2-1-2-3', 'parent_id' => 't2-1-2', 'name' => 'Node T2 1-2-3',
		], 't2-1-3'     => [
			'id' => 't2-1-3', 'parent_id' => 't2-1', 'name' => 'Node T2 1-3',
		], 't2-2'       => [
			'id' => 't2-2', 'parent_id' => 'root2', 'name' => 'Node T2 2',
		], 't2-3'       => [
			'id' => 't2-3', 'parent_id' => 'root2', 'name' => 'Node T2 3',
		],
	];
	protected $tree3_data = [
		't3-1'          => [
			'id' => 't3-1', 'parent_id' => 'root3', 'name' => 'Node T3 1',
		], 't3-1-1'     => [
			'id' => 't3-1-1', 'parent_id' => 't3-1', 'name' => 'Node T3 1-1',
		], 't3-1-2'     => [
			'id' => 't3-1-2', 'parent_id' => 't3-1', 'name' => 'Node T3 1-2',
		], 't3-1-2-1'   => [
			'id' => 't3-1-2-1', 'parent_id' => 't3-1-2', 'name' => 'Node T3 1-2-1',
		], 't3-1-2-2'   => [
			'id' => 't3-1-2-2', 'parent_id' => 't3-1-2', 'name' => 'Node T3 1-2-2',
		], 't3-1-2-2-1' => [
			'id' => 't3-1-2-2-1', 'parent_id' => 't3-1-2-2', 'name' => 'Node T3 1-2-2-1',
		], 't3-1-2-2-2' => [
			'id' => 't3-1-2-2-2', 'parent_id' => 't3-1-2-2', 'name' => 'Node T3 1-2-2-2',
		], 't3-1-2-3'   => [
			'id' => 't3-1-2-3', 'parent_id' => 't3-1-2', 'name' => 'Node T3 1-2-3',
		], 't3-1-3'     => [
			'id' => 't3-1-3', 'parent_id' => 't3-1', 'name' => 'Node T3 1-3',
		], 't3-2'       => [
			'id' => 't3-2', 'parent_id' => 'root3', 'name' => 'Node T3 2',
		], 't3-3'       => [
			'id' => 't3-3', 'parent_id' => 'root3', 'name' => 'Node T3 3',
		],
	];

	/**
	 * @covers \Jet\Data_Tree_Forest::appendTree
	 */
	public function testAppendTree()
	{
		$this->object->appendTree( $this->getTree1() );
		$this->object->appendTree( $this->getTree2() );
		$this->object->appendTree( $this->getTree3() );
	}

	/**
	 * @return Data_Tree
	 */
	protected function getTree1()
	{
		$tree = new Data_Tree();

		$tree->getRootNode()->setId( 'root1' );
		$tree->getRootNode()->setLabel( 'Root T1' );


		$tree->setData( $this->tree1_data );

		return $tree;
	}

	/**
	 * @return Data_Tree
	 */
	protected function getTree2()
	{
		$tree = new Data_Tree();
		$tree->getRootNode()->setId( 'root2' );
		$tree->getRootNode()->setLabel( 'Root T2' );

		$tree->setData( $this->tree2_data );

		return $tree;
	}

	/**
	 * @return Data_Tree
	 */
	protected function getTree3()
	{
		$tree = new Data_Tree();
		$tree->getRootNode()->setId( 'root3' );
		$tree->getRootNode()->setLabel( 'Root T3' );

		$tree->setData( $this->tree3_data );

		return $tree;
	}

	/**
	 * @covers \Jet\Data_Tree_Forest::appendTree
	 *
	 * @expectedException \Jet\Data_Tree_Exception
	 * @expectedExceptionCode \Jet\Data_Tree_Exception::CODE_TREE_ALREADY_IN_FOREST
	 */
	public function testAppendTreeFault()
	{
		$this->object->appendTree( $this->getTree1() );
		$this->object->appendTree( $this->getTree1() );
	}

	/**
	 * @covers \Jet\Data_Tree_Forest::setLabelKey
	 * @covers \Jet\Data_Tree_Forest::getLabelKey
	 */
	public function testSetGetLabelKey()
	{
		$tree = $this->getTree1();

		$this->object->appendTree( $tree );
		$this->assertEquals( $tree->getLabelKey(), $this->object->getLabelKey() );

		$this->object->setLabelKey( 'test' );
		$this->assertEquals( 'test', $this->object->getLabelKey() );
	}

	/**
	 * @covers \Jet\Data_Tree_Forest::getIdKey
	 * @covers \Jet\Data_Tree_Forest::setIdKey
	 */
	public function testSetGetIdKey()
	{
		$tree = $this->getTree1();

		$this->object->appendTree( $tree );
		$this->assertEquals( $tree->getIdKey(), $this->object->getIdKey() );

		$this->object->setIdKey( 'test' );
		$this->assertEquals( 'test', $this->object->getIdKey() );
	}

	/**
	 * @covers \Jet\Data_Tree_Forest::getTrees
	 */
	public function testGetTrees()
	{
		$trees = $this->appendTrees();

		$this->assertEquals( $trees, $this->object->getTrees() );
	}

	/**
	 * @return array
	 */
	protected function appendTrees()
	{
		$trees = [
			'root1' => $this->getTree1(), 'root2' => $this->getTree2(), 'root3' => $this->getTree3(),
		];

		$this->object->appendTree( $trees['root1'] );
		$this->object->appendTree( $trees['root2'] );
		$this->object->appendTree( $trees['root3'] );

		return $trees;
	}

	/**
	 * @covers \Jet\Data_Tree_Forest::getTree
	 */
	public function testGetTree()
	{
		$trees = $this->appendTrees();

		$this->assertEquals( $trees['root2'], $this->object->getTree( 'root2' ) );

	}

	/**
	 * @covers \Jet\Data_Tree_Forest::removeTree
	 */
	public function testRemoveTree()
	{
		$trees = $this->appendTrees();

		unset( $trees['root2'] );
		$this->object->removeTree( 'root2' );

		$this->assertEquals( $trees, $this->object->getTrees() );
	}

	/**
	 * @covers \Jet\Data_Tree_Forest::getTreeExists
	 */
	public function testGetTreeExists()
	{
		$this->appendTrees();

		$this->assertTrue( $this->object->getTreeExists( 'root2' ) );

		$this->object->removeTree( 'root2' );

		$this->assertFalse( $this->object->getTreeExists( 'root2' ) );
	}

	/**
	 * @covers \Jet\Data_Tree_Forest::toArray
	 */
	public function testToArray()
	{
		$this->appendTrees();

		$valid = [
			0    => [
				'id' => 'root1', 'parent_id' => '', 'name' => 'Root T1', 'depth' => 0, 'children' => [
					0    => [
						'id' => 't1-1', 'parent_id' => 'root1', 'name' => 'Node T1 1', 'depth' => 1, 'children' => [
							0    => [
								'id' => 't1-1-1', 'parent_id' => 't1-1', 'name' => 'Node T1 1-1', 'depth' => 2,
							], 1 => [
								'id'       => 't1-1-2', 'parent_id' => 't1-1', 'name' => 'Node T1 1-2', 'depth' => 2,
								'children' => [
									0    => [
										'id'    => 't1-1-2-1', 'parent_id' => 't1-1-2', 'name' => 'Node T1 1-2-1',
										'depth' => 3,
									], 1 => [
										'id'    => 't1-1-2-2', 'parent_id' => 't1-1-2', 'name' => 'Node T1 1-2-2',
										'depth' => 3, 'children' => [
											0    => [
												'id'   => 't1-1-2-2-1', 'parent_id' => 't1-1-2-2',
												'name' => 'Node T1 1-2-2-1', 'depth' => 4,
											], 1 => [
												'id'   => 't1-1-2-2-2', 'parent_id' => 't1-1-2-2',
												'name' => 'Node T1 1-2-2-2', 'depth' => 4,
											],
										],
									], 2 => [
										'id'    => 't1-1-2-3', 'parent_id' => 't1-1-2', 'name' => 'Node T1 1-2-3',
										'depth' => 3,
									],
								],
							], 2 => [
								'id' => 't1-1-3', 'parent_id' => 't1-1', 'name' => 'Node T1 1-3', 'depth' => 2,
							],
						],
					], 1 => [
						'id' => 't1-2', 'parent_id' => 'root1', 'name' => 'Node T1 2', 'depth' => 1,
					], 2 => [
						'id' => 't1-3', 'parent_id' => 'root1', 'name' => 'Node T1 3', 'depth' => 1,
					],
				],
			], 1 => [
				'id' => 'root2', 'parent_id' => '', 'name' => 'Root T2', 'depth' => 0, 'children' => [
					0    => [
						'id' => 't2-1', 'parent_id' => 'root2', 'name' => 'Node T2 1', 'depth' => 1, 'children' => [
							0    => [
								'id' => 't2-1-1', 'parent_id' => 't2-1', 'name' => 'Node T2 1-1', 'depth' => 2,
							], 1 => [
								'id'       => 't2-1-2', 'parent_id' => 't2-1', 'name' => 'Node T2 1-2', 'depth' => 2,
								'children' => [
									0    => [
										'id'    => 't2-1-2-1', 'parent_id' => 't2-1-2', 'name' => 'Node T2 1-2-1',
										'depth' => 3,
									], 1 => [
										'id'    => 't2-1-2-2', 'parent_id' => 't2-1-2', 'name' => 'Node T2 1-2-2',
										'depth' => 3, 'children' => [
											0    => [
												'id'   => 't2-1-2-2-1', 'parent_id' => 't2-1-2-2',
												'name' => 'Node T2 1-2-2-1', 'depth' => 4,
											], 1 => [
												'id'   => 't2-1-2-2-2', 'parent_id' => 't2-1-2-2',
												'name' => 'Node T2 1-2-2-2', 'depth' => 4,
											],
										],
									], 2 => [
										'id'    => 't2-1-2-3', 'parent_id' => 't2-1-2', 'name' => 'Node T2 1-2-3',
										'depth' => 3,
									],
								],
							], 2 => [
								'id' => 't2-1-3', 'parent_id' => 't2-1', 'name' => 'Node T2 1-3', 'depth' => 2,
							],
						],
					], 1 => [
						'id' => 't2-2', 'parent_id' => 'root2', 'name' => 'Node T2 2', 'depth' => 1,
					], 2 => [
						'id' => 't2-3', 'parent_id' => 'root2', 'name' => 'Node T2 3', 'depth' => 1,
					],
				],
			], 2 => [
				'id' => 'root3', 'parent_id' => '', 'name' => 'Root T3', 'depth' => 0, 'children' => [
					0    => [
						'id' => 't3-1', 'parent_id' => 'root3', 'name' => 'Node T3 1', 'depth' => 1, 'children' => [
							0    => [
								'id' => 't3-1-1', 'parent_id' => 't3-1', 'name' => 'Node T3 1-1', 'depth' => 2,
							], 1 => [
								'id'       => 't3-1-2', 'parent_id' => 't3-1', 'name' => 'Node T3 1-2', 'depth' => 2,
								'children' => [
									0    => [
										'id'    => 't3-1-2-1', 'parent_id' => 't3-1-2', 'name' => 'Node T3 1-2-1',
										'depth' => 3,
									], 1 => [
										'id'    => 't3-1-2-2', 'parent_id' => 't3-1-2', 'name' => 'Node T3 1-2-2',
										'depth' => 3, 'children' => [
											0    => [
												'id'   => 't3-1-2-2-1', 'parent_id' => 't3-1-2-2',
												'name' => 'Node T3 1-2-2-1', 'depth' => 4,
											], 1 => [
												'id'   => 't3-1-2-2-2', 'parent_id' => 't3-1-2-2',
												'name' => 'Node T3 1-2-2-2', 'depth' => 4,
											],
										],
									], 2 => [
										'id'    => 't3-1-2-3', 'parent_id' => 't3-1-2', 'name' => 'Node T3 1-2-3',
										'depth' => 3,
									],
								],
							], 2 => [
								'id' => 't3-1-3', 'parent_id' => 't3-1', 'name' => 'Node T3 1-3', 'depth' => 2,
							],
						],
					], 1 => [
						'id' => 't3-2', 'parent_id' => 'root3', 'name' => 'Node T3 2', 'depth' => 1,
					], 2 => [
						'id' => 't3-3', 'parent_id' => 'root3', 'name' => 'Node T3 3', 'depth' => 1,
					],
				],
			],
		];

		$this->assertEquals( $valid, $this->object->toArray() );
	}

	/**
	 * @covers \Jet\Data_Tree_Forest::toJSON
	 */
	public function testToJSON()
	{
		$this->appendTrees();

		$valid_JSON = '{"identifier":"id","label":"name","items":[{"id":"root1","parent_id":null,"name":"Root T1","depth":0,"children":[{"id":"t1-1","parent_id":"root1","name":"Node T1 1","depth":1,"children":[{"id":"t1-1-1","parent_id":"t1-1","name":"Node T1 1-1","depth":2},{"id":"t1-1-2","parent_id":"t1-1","name":"Node T1 1-2","depth":2,"children":[{"id":"t1-1-2-1","parent_id":"t1-1-2","name":"Node T1 1-2-1","depth":3},{"id":"t1-1-2-2","parent_id":"t1-1-2","name":"Node T1 1-2-2","depth":3,"children":[{"id":"t1-1-2-2-1","parent_id":"t1-1-2-2","name":"Node T1 1-2-2-1","depth":4},{"id":"t1-1-2-2-2","parent_id":"t1-1-2-2","name":"Node T1 1-2-2-2","depth":4}]},{"id":"t1-1-2-3","parent_id":"t1-1-2","name":"Node T1 1-2-3","depth":3}]},{"id":"t1-1-3","parent_id":"t1-1","name":"Node T1 1-3","depth":2}]},{"id":"t1-2","parent_id":"root1","name":"Node T1 2","depth":1},{"id":"t1-3","parent_id":"root1","name":"Node T1 3","depth":1}]},{"id":"root2","parent_id":null,"name":"Root T2","depth":0,"children":[{"id":"t2-1","parent_id":"root2","name":"Node T2 1","depth":1,"children":[{"id":"t2-1-1","parent_id":"t2-1","name":"Node T2 1-1","depth":2},{"id":"t2-1-2","parent_id":"t2-1","name":"Node T2 1-2","depth":2,"children":[{"id":"t2-1-2-1","parent_id":"t2-1-2","name":"Node T2 1-2-1","depth":3},{"id":"t2-1-2-2","parent_id":"t2-1-2","name":"Node T2 1-2-2","depth":3,"children":[{"id":"t2-1-2-2-1","parent_id":"t2-1-2-2","name":"Node T2 1-2-2-1","depth":4},{"id":"t2-1-2-2-2","parent_id":"t2-1-2-2","name":"Node T2 1-2-2-2","depth":4}]},{"id":"t2-1-2-3","parent_id":"t2-1-2","name":"Node T2 1-2-3","depth":3}]},{"id":"t2-1-3","parent_id":"t2-1","name":"Node T2 1-3","depth":2}]},{"id":"t2-2","parent_id":"root2","name":"Node T2 2","depth":1},{"id":"t2-3","parent_id":"root2","name":"Node T2 3","depth":1}]},{"id":"root3","parent_id":null,"name":"Root T3","depth":0,"children":[{"id":"t3-1","parent_id":"root3","name":"Node T3 1","depth":1,"children":[{"id":"t3-1-1","parent_id":"t3-1","name":"Node T3 1-1","depth":2},{"id":"t3-1-2","parent_id":"t3-1","name":"Node T3 1-2","depth":2,"children":[{"id":"t3-1-2-1","parent_id":"t3-1-2","name":"Node T3 1-2-1","depth":3},{"id":"t3-1-2-2","parent_id":"t3-1-2","name":"Node T3 1-2-2","depth":3,"children":[{"id":"t3-1-2-2-1","parent_id":"t3-1-2-2","name":"Node T3 1-2-2-1","depth":4},{"id":"t3-1-2-2-2","parent_id":"t3-1-2-2","name":"Node T3 1-2-2-2","depth":4}]},{"id":"t3-1-2-3","parent_id":"t3-1-2","name":"Node T3 1-2-3","depth":3}]},{"id":"t3-1-3","parent_id":"t3-1","name":"Node T3 1-3","depth":2}]},{"id":"t3-2","parent_id":"root3","name":"Node T3 2","depth":1},{"id":"t3-3","parent_id":"root3","name":"Node T3 3","depth":1}]}]}';
		$this->assertEquals( $valid_JSON, $this->object->toJSON() );
	}

	/**
	 * @covers \Jet\Data_Tree_Forest::toXML
	 */
	public function testToXML()
	{
		$this->appendTrees();

		$valid_XML = '<tree>
			        <identifier>id</identifier>
			        <label>name</label>
			        <items>
			                <item>
			                        <id>root1</id>
			                        <parent_id></parent_id>
			                        <name>Root T1</name>
			                        <depth>0</depth>
			                        <children>
			                                <item>
			                                        <id>t1-1</id>
			                                        <parent_id>root1</parent_id>
			                                        <name>Node T1 1</name>
			                                        <depth>1</depth>
			                                        <children>
			                                                <item>
			                                                        <id>t1-1-1</id>
			                                                        <parent_id>t1-1</parent_id>
			                                                        <name>Node T1 1-1</name>
			                                                        <depth>2</depth>
			                                                </item>
			                                                <item>
			                                                        <id>t1-1-2</id>
			                                                        <parent_id>t1-1</parent_id>
			                                                        <name>Node T1 1-2</name>
			                                                        <depth>2</depth>
			                                                        <children>
			                                                                <item>
			                                                                        <id>t1-1-2-1</id>
			                                                                        <parent_id>t1-1-2</parent_id>
			                                                                        <name>Node T1 1-2-1</name>
			                                                                        <depth>3</depth>
			                                                                </item>
			                                                                <item>
			                                                                        <id>t1-1-2-2</id>
			                                                                        <parent_id>t1-1-2</parent_id>
			                                                                        <name>Node T1 1-2-2</name>
			                                                                        <depth>3</depth>
			                                                                        <children>
			                                                                                <item>
			                                                                                        <id>t1-1-2-2-1</id>
			                                                                                        <parent_id>t1-1-2-2</parent_id>
			                                                                                        <name>Node T1 1-2-2-1</name>
			                                                                                        <depth>4</depth>
			                                                                                </item>
			                                                                                <item>
			                                                                                        <id>t1-1-2-2-2</id>
			                                                                                        <parent_id>t1-1-2-2</parent_id>
			                                                                                        <name>Node T1 1-2-2-2</name>
			                                                                                        <depth>4</depth>
			                                                                                </item>
			                                                                        </children>
			                                                                </item>
			                                                                <item>
			                                                                        <id>t1-1-2-3</id>
			                                                                        <parent_id>t1-1-2</parent_id>
			                                                                        <name>Node T1 1-2-3</name>
			                                                                        <depth>3</depth>
			                                                                </item>
			                                                        </children>
			                                                </item>
			                                                <item>
			                                                        <id>t1-1-3</id>
			                                                        <parent_id>t1-1</parent_id>
			                                                        <name>Node T1 1-3</name>
			                                                        <depth>2</depth>
			                                                </item>
			                                        </children>
			                                </item>
			                                <item>
			                                        <id>t1-2</id>
			                                        <parent_id>root1</parent_id>
			                                        <name>Node T1 2</name>
			                                        <depth>1</depth>
			                                </item>
			                                <item>
			                                        <id>t1-3</id>
			                                        <parent_id>root1</parent_id>
			                                        <name>Node T1 3</name>
			                                        <depth>1</depth>
			                                </item>
			                        </children>
			                </item>
			                <item>
			                        <id>root2</id>
			                        <parent_id></parent_id>
			                        <name>Root T2</name>
			                        <depth>0</depth>
			                        <children>
			                                <item>
			                                        <id>t2-1</id>
			                                        <parent_id>root2</parent_id>
			                                        <name>Node T2 1</name>
			                                        <depth>1</depth>
			                                        <children>
			                                                <item>
			                                                        <id>t2-1-1</id>
			                                                        <parent_id>t2-1</parent_id>
			                                                        <name>Node T2 1-1</name>
			                                                        <depth>2</depth>
			                                                </item>
			                                                <item>
			                                                        <id>t2-1-2</id>
			                                                        <parent_id>t2-1</parent_id>
			                                                        <name>Node T2 1-2</name>
			                                                        <depth>2</depth>
			                                                        <children>
			                                                                <item>
			                                                                        <id>t2-1-2-1</id>
			                                                                        <parent_id>t2-1-2</parent_id>
			                                                                        <name>Node T2 1-2-1</name>
			                                                                        <depth>3</depth>
			                                                                </item>
			                                                                <item>
			                                                                        <id>t2-1-2-2</id>
			                                                                        <parent_id>t2-1-2</parent_id>
			                                                                        <name>Node T2 1-2-2</name>
			                                                                        <depth>3</depth>
			                                                                        <children>
			                                                                                <item>
			                                                                                        <id>t2-1-2-2-1</id>
			                                                                                        <parent_id>t2-1-2-2</parent_id>
			                                                                                        <name>Node T2 1-2-2-1</name>
			                                                                                        <depth>4</depth>
			                                                                                </item>
			                                                                                <item>
			                                                                                        <id>t2-1-2-2-2</id>
			                                                                                        <parent_id>t2-1-2-2</parent_id>
			                                                                                        <name>Node T2 1-2-2-2</name>
			                                                                                        <depth>4</depth>
			                                                                                </item>
			                                                                        </children>
			                                                                </item>
			                                                                <item>
			                                                                        <id>t2-1-2-3</id>
			                                                                        <parent_id>t2-1-2</parent_id>
			                                                                        <name>Node T2 1-2-3</name>
			                                                                        <depth>3</depth>
			                                                                </item>
			                                                        </children>
			                                                </item>
			                                                <item>
			                                                        <id>t2-1-3</id>
			                                                        <parent_id>t2-1</parent_id>
			                                                        <name>Node T2 1-3</name>
			                                                        <depth>2</depth>
			                                                </item>
			                                        </children>
			                                </item>
			                                <item>
			                                        <id>t2-2</id>
			                                        <parent_id>root2</parent_id>
			                                        <name>Node T2 2</name>
			                                        <depth>1</depth>
			                                </item>
			                                <item>
			                                        <id>t2-3</id>
			                                        <parent_id>root2</parent_id>
			                                        <name>Node T2 3</name>
			                                        <depth>1</depth>
			                                </item>
			                        </children>
			                </item>
			                <item>
			                        <id>root3</id>
			                        <parent_id></parent_id>
			                        <name>Root T3</name>
			                        <depth>0</depth>
			                        <children>
			                                <item>
			                                        <id>t3-1</id>
			                                        <parent_id>root3</parent_id>
			                                        <name>Node T3 1</name>
			                                        <depth>1</depth>
			                                        <children>
			                                                <item>
			                                                        <id>t3-1-1</id>
			                                                        <parent_id>t3-1</parent_id>
			                                                        <name>Node T3 1-1</name>
			                                                        <depth>2</depth>
			                                                </item>
			                                                <item>
			                                                        <id>t3-1-2</id>
			                                                        <parent_id>t3-1</parent_id>
			                                                        <name>Node T3 1-2</name>
			                                                        <depth>2</depth>
			                                                        <children>
			                                                                <item>
			                                                                        <id>t3-1-2-1</id>
			                                                                        <parent_id>t3-1-2</parent_id>
			                                                                        <name>Node T3 1-2-1</name>
			                                                                        <depth>3</depth>
			                                                                </item>
			                                                                <item>
			                                                                        <id>t3-1-2-2</id>
			                                                                        <parent_id>t3-1-2</parent_id>
			                                                                        <name>Node T3 1-2-2</name>
			                                                                        <depth>3</depth>
			                                                                        <children>
			                                                                                <item>
			                                                                                        <id>t3-1-2-2-1</id>
			                                                                                        <parent_id>t3-1-2-2</parent_id>
			                                                                                        <name>Node T3 1-2-2-1</name>
			                                                                                        <depth>4</depth>
			                                                                                </item>
			                                                                                <item>
			                                                                                        <id>t3-1-2-2-2</id>
			                                                                                        <parent_id>t3-1-2-2</parent_id>
			                                                                                        <name>Node T3 1-2-2-2</name>
			                                                                                        <depth>4</depth>
			                                                                                </item>
			                                                                        </children>
			                                                                </item>
			                                                                <item>
			                                                                        <id>t3-1-2-3</id>
			                                                                        <parent_id>t3-1-2</parent_id>
			                                                                        <name>Node T3 1-2-3</name>
			                                                                        <depth>3</depth>
			                                                                </item>
			                                                        </children>
			                                                </item>
			                                                <item>
			                                                        <id>t3-1-3</id>
			                                                        <parent_id>t3-1</parent_id>
			                                                        <name>Node T3 1-3</name>
			                                                        <depth>2</depth>
			                                                </item>
			                                        </children>
			                                </item>
			                                <item>
			                                        <id>t3-2</id>
			                                        <parent_id>root3</parent_id>
			                                        <name>Node T3 2</name>
			                                        <depth>1</depth>
			                                </item>
			                                <item>
			                                        <id>t3-3</id>
			                                        <parent_id>root3</parent_id>
			                                        <name>Node T3 3</name>
			                                        <depth>1</depth>
			                                </item>
			                        </children>
			                </item>
			        </items>
			</tree>';

		$test_XML = $this->object->toXML();

		$valid_XML = str_replace( "\r", '', $valid_XML );
		$test_XML = str_replace( "\r", '', $test_XML );
		$valid_XML = str_replace( "\t", '', $valid_XML );
		$test_XML = str_replace( "\t", '', $test_XML );
		$valid_XML = str_replace( "\n", '', $valid_XML );
		$test_XML = str_replace( "\n", '', $test_XML );
		$valid_XML = str_replace( ' ', '', $valid_XML );
		$test_XML = str_replace( ' ', '', $test_XML );

		$this->assertEquals( $valid_XML, $test_XML );
	}

	/**
	 * @covers \Jet\Data_Tree_Forest::rewind
	 * @covers \Jet\Data_Tree_Forest::current
	 * @covers \Jet\Data_Tree_Forest::key
	 * @covers \Jet\Data_Tree_Forest::next
	 * @covers \Jet\Data_Tree_Forest::valid
	 *
	 * @covers \Jet\Data_Tree::setData
	 * @covers \Jet\Data_Tree::appendNode
	 * @covers \Jet\Data_Tree::rewind
	 * @covers \Jet\Data_Tree::current
	 * @covers \Jet\Data_Tree::key
	 * @covers \Jet\Data_Tree::next
	 * @covers \Jet\Data_Tree::valid
	 *
	 * @covers \Jet\Data_Tree_Node::rewind
	 * @covers \Jet\Data_Tree_Node::current
	 * @covers \Jet\Data_Tree_Node::key
	 * @covers \Jet\Data_Tree_Node::next
	 * @covers \Jet\Data_Tree_Node::valid
	 * @covers \Jet\Data_Tree_Node::toString
	 * @covers \Jet\Data_Tree_Node::getLabel
	 *
	 */
	public function testIterator()
	{
		$this->appendTrees();

		$valid_data = [
			'root1'      => 'Root T1', 't1-1' => 'Node T1 1', 't1-1-1' => 'Node T1 1-1', 't1-1-2' => 'Node T1 1-2',
			't1-1-2-1'   => 'Node T1 1-2-1', 't1-1-2-2' => 'Node T1 1-2-2', 't1-1-2-2-1' => 'Node T1 1-2-2-1',
			't1-1-2-2-2' => 'Node T1 1-2-2-2', 't1-1-2-3' => 'Node T1 1-2-3', 't1-1-3' => 'Node T1 1-3',
			't1-2'       => 'Node T1 2', 't1-3' => 'Node T1 3', 'root2' => 'Root T2', 't2-1' => 'Node T2 1',
			't2-1-1'     => 'Node T2 1-1', 't2-1-2' => 'Node T2 1-2', 't2-1-2-1' => 'Node T2 1-2-1',
			't2-1-2-2'   => 'Node T2 1-2-2', 't2-1-2-2-1' => 'Node T2 1-2-2-1', 't2-1-2-2-2' => 'Node T2 1-2-2-2',
			't2-1-2-3'   => 'Node T2 1-2-3', 't2-1-3' => 'Node T2 1-3', 't2-2' => 'Node T2 2', 't2-3' => 'Node T2 3',
			'root3'      => 'Root T3', 't3-1' => 'Node T3 1', 't3-1-1' => 'Node T3 1-1', 't3-1-2' => 'Node T3 1-2',
			't3-1-2-1'   => 'Node T3 1-2-1', 't3-1-2-2' => 'Node T3 1-2-2', 't3-1-2-2-1' => 'Node T3 1-2-2-1',
			't3-1-2-2-2' => 'Node T3 1-2-2-2', 't3-1-2-3' => 'Node T3 1-2-3', 't3-1-3' => 'Node T3 1-3',
			't3-2'       => 'Node T3 2', 't3-3' => 'Node T3 3',
		];

		$test_data = [];


		foreach( $this->object as $id => $item ) {
			$test_data[$id] = (string)$item;
		}

		$this->assertEquals( $valid_data, $test_data );

	}

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->object = new Data_Tree_Forest();
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
	}
}
