<?php
/**
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package Data
 * @subpackage Data_Tree
 */
namespace Jet;

/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
class TreeTest_Node_Invalid {

}

/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
class TreeTest_Node_Valid extends Data_Tree_Node {

}


/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
class Data_TreeTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var Data_Tree
	 */
	protected $object;

	protected $data = [
		/*
		'root' => array(
			'id' => 'root',
			'parent_id' => '',
			'name' => 'Root',
		),
		*/
		'1' => [
			'id' => '1',
			'parent_id' => 'root',
			'name' => 'Node 1',
		],
		'1-1' => [
			'id' => '1-1',
			'parent_id' => '1',
			'name' => 'Node 1-1',
		],
		'1-2' => [
			'id' => '1-2',
			'parent_id' => '1',
			'name' => 'Node 1-2',
		],
		'1-2-1' => [
			'id' => '1-2-1',
			'parent_id' => '1-2',
			'name' => 'Node 1-2-1',
		],
		'1-2-2' => [
			'id' => '1-2-2',
			'parent_id' => '1-2',
			'name' => 'Node 1-2-2',
		],
		'1-2-2-1' => [
			'id' => '1-2-2-1',
			'parent_id' => '1-2-2',
			'name' => 'Node 1-2-2-1',
		],
		'1-2-2-2' => [
			'id' => '1-2-2-2',
			'parent_id' => '1-2-2',
			'name' => 'Node 1-2-2-2',
		],
		'1-2-3' => [
			'id' => '1-2-3',
			'parent_id' => '1-2',
			'name' => 'Node 1-2-3',
		],
		'1-3' => [
			'id' => '1-3',
			'parent_id' => '1',
			'name' => 'Node 1-3',
		],
		'2' => [
			'id' => '2',
			'parent_id' => 'root',
			'name' => 'Node 2',
		],
		'3' => [
			'id' => '3',
			'parent_id' => 'root',
			'name' => 'Node 3',
		],
		'op_1' => [
			'id' => 'op_1',
			'parent_id' => 'non-existent-1',
			'name' => 'orphan 1',
		],
		'op_1_1' => [
			'id' => 'op_1_1',
			'parent_id' => 'op_1',
			'name' => 'orphan 1-1',
		],
		'op_1_2' => [
			'id' => 'op_1_2',
			'parent_id' => 'op_1',
			'name' => 'orphan 1-2',
		],
		'op_1_2_1' => [
			'id' => 'op_1_2_1',
			'parent_id' => 'op_1_2',
			'name' => 'orphan 1-2-1',
		],

		'op_2' => [
			'id' => 'op_2',
			'parent_id' => 'non-existent-2',
			'name' => 'orphan 2',
		],
		'op_2_1' => [
			'id' => 'op_2_1',
			'parent_id' => 'op_2',
			'name' => 'orphan 2-1',
		],
		'op_2_2' => [
			'id' => 'op_2_2',
			'parent_id' => 'op_2',
			'name' => 'orphan 2-2',
		],
		'op_2_2_1' => [
			'id' => 'op_2_2_1',
			'parent_id' => 'op_2_2',
			'name' => 'orphan 2-2-1',
		],

	];

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->object = new Data_Tree();

		$this->object->setAdoptOrphans(true);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
	}

	/**
	 * @covers Data_Tree::setNodeClassName
	 * @expectedException \Jet\Data_Tree_Exception
	 * @expectedExceptionCode \Jet\Data_Tree_Exception::CODE_INVALID_NODES_CLASS
	 */
	public function testSetNodeClassNameInvalid() {
		$this->object->setNodeClassName('Jet\\TreeTest_Node_Invalid');
	}


	/**
	 * @covers Data_Tree::setNodeClassName
	 * @covers Data_Tree::getNodeClassName
	 */
	public function testSetAndGetNodeClassName() {
		$this->object->setNodeClassName('Jet\\TreeTest_Node_Valid');
		$this->assertEquals('Jet\\TreeTest_Node_Valid', $this->object->getNodesClassName());
	}

	/**
	 * @covers Data_Tree::getIdKey
	 */
	public function testGetIdKey() {
		$this->assertEquals('id', $this->object->getIdKey() );
	}

	/**
	 * @covers Data_Tree::getParentIdKey
	 */
	public function testGetParentIdKey() {
		$this->assertEquals('parent_id', $this->object->getParentIdKey() );
	}

	/**
	 * @covers Data_Tree::setLabelKey
	 * @covers Data_Tree::getLabelKey
	 */
	public function testSetGetLabelKey() {
		$this->assertEquals('name' ,$this->object->getLabelKey() );
		$this->object->setLabelKey('label_key_test');

		$this->assertEquals('label_key_test' ,$this->object->label_key );
		$this->assertEquals('label_key_test' ,$this->object->getLabelKey() );
	}


	/**
	 * @covers Data_Tree::setChildrenKey
	 * @covers Data_Tree::getChildrenKey
	 */
	public function testSetGetChildrenKey() {
		$this->assertEquals('children' ,$this->object->getChildrenKey() );
		$this->object->setChildrenKey('children_key_test');
		$this->assertEquals('children_key_test' ,$this->object->children_key );
		$this->assertEquals('children_key_test' ,$this->object->getChildrenKey() );
	}

	/**
	 * @covers Data_Tree::setDepthKey
	 * @covers Data_Tree::getDepthKey
	 */
	public function testSetGetDepthKey() {
		$this->assertEquals('depth' ,$this->object->getDepthKey() );
		$this->object->setDepthKey('depth_key_test');
		$this->assertEquals('depth_key_test' ,$this->object->depth_key );
		$this->assertEquals('depth_key_test' ,$this->object->getDepthKey() );
	}

	/**
	 *
	 */
	protected function prepareTree() {
		$this->object->getRootNode()->setId('root');
		$this->object->getRootNode()->setLabel('Root');

		$this->object->setData( $this->data );
	}


	/**
	 * @covers Data_Tree::setData
	 * @covers Data_Tree::appendNode
	 */
	public function testGetRootNode() {

		$this->prepareTree();

		$this->assertEquals( 'root', $this->object->getRootNode()->getId() );

	}


	/**
	 * @covers Data_Tree::setData
	 * @covers Data_Tree::getNodeExists
	 */
	public function testGetNodeExists() {
		$this->prepareTree();

		$this->assertFalse( $this->object->getNodeExists('unknown-node') );
		$this->assertTrue( $this->object->getNodeExists('1-2-2-2') );
	}

	/**
	 * @covers Data_Tree::setData
	 * @covers Data_Tree::appendNode
	 * @covers Data_Tree::getNode
	 * @covers Data_Tree_Node::getId
	 * @covers Data_Tree_Node::getParentId
	 * @covers Data_Tree_Node::getLabel
	 * @covers Data_Tree_Node::getParent
	 * @covers Data_Tree_Node::getChildExists
	 *
	 */
	public function testGeneral() {
		$this->prepareTree();

		$node = $this->object->getNode('1-2-2-2');
		$parent_node = $node->getParent();

		$this->assertEquals( '1-2-2-2', $node->getId() );
		$this->assertEquals( '1-2-2', $node->getParentId() );
		$this->assertEquals( 'Node 1-2-2-2', $node->getLabel() );

		$this->assertEquals( '1-2-2', $parent_node->getId() );
		$this->assertEquals( '1-2', $parent_node->getParentId() );
		$this->assertEquals( 'Node 1-2-2', $parent_node->getLabel() );

		$this->assertFalse( $parent_node->getChildExists( 'unknown-child' ) );
		$this->assertTrue( $parent_node->getChildExists( '1-2-2-2' ) );

		$child = $parent_node->getChild('1-2-2-2');
		$this->assertEquals( '1-2-2-2', $child->getId() );
		$this->assertEquals( '1-2-2', $child->getParentId() );
		$this->assertEquals( 'Node 1-2-2-2', $child->getLabel() );

	}

	/**
	 * @covers Data_Tree::setData
	 * @covers Data_Tree::appendNode
	 * @covers Data_Tree::getNodes
	 * @covers Data_Tree_Node::getId
	 * @covers Data_Tree_Node::getParentId
	 * @covers Data_Tree_Node::getLabel
	 */
	public function testGetNodes() {
		$this->prepareTree();
		$nodes = $this->object->getNodes();

		$this->assertEquals( count($this->data)+1, count($nodes) );

		foreach($nodes as $id=>$node) {
			if($id=='root') {
				continue;
			}
			$this->assertEquals( $this->data[$id]['id'], $node->getId() );
			$this->assertEquals( $this->data[$id]['parent_id'], $node->getRealParentId() );
			$this->assertEquals( $this->data[$id]['name'], $node->getLabel() );
		}
	}

	/**
	 * @covers Data_Tree::setData
	 * @covers Data_Tree::getNodesIds
	 */
	public function testGetNodesIds() {
		$this->prepareTree();

		$this->assertEquals( array_merge(['root'],array_keys($this->data)), $this->object->getNodesIds() );
	}


	/**
	 * @covers Data_Tree::setData
	 * @covers Data_Tree::appendNode
	 * @covers Data_Tree::toArray
	 */
	public function testToArray() {
		$this->prepareTree();

		$valid_tree = [
			0 =>
			[
				'id' => 'root',
				'parent_id' => '',
				'name' => 'Root',
				'depth' => 0,
				'children' =>
				[
					0 =>
					[
						'id' => '1',
						'parent_id' => 'root',
						'name' => 'Node 1',
						'depth' => 1,
						'children' =>
						[
							0 =>
							[
								'id' => '1-1',
								'parent_id' => '1',
								'name' => 'Node 1-1',
								'depth' => 2,
							],
							1 =>
							[
								'id' => '1-2',
								'parent_id' => '1',
								'name' => 'Node 1-2',
								'depth' => 2,
								'children' =>
								[
									0 =>
									[
										'id' => '1-2-1',
										'parent_id' => '1-2',
										'name' => 'Node 1-2-1',
										'depth' => 3,
									],
									1 =>
									[
										'id' => '1-2-2',
										'parent_id' => '1-2',
										'name' => 'Node 1-2-2',
										'depth' => 3,
										'children' =>
										[
											0 =>
											[
												'id' => '1-2-2-1',
												'parent_id' => '1-2-2',
												'name' => 'Node 1-2-2-1',
												'depth' => 4,
											],
											1 =>
											[
												'id' => '1-2-2-2',
												'parent_id' => '1-2-2',
												'name' => 'Node 1-2-2-2',
												'depth' => 4,
											],
										],
									],
									2 =>
									[
										'id' => '1-2-3',
										'parent_id' => '1-2',
										'name' => 'Node 1-2-3',
										'depth' => 3,
									],
								],
							],
							2 =>
							[
								'id' => '1-3',
								'parent_id' => '1',
								'name' => 'Node 1-3',
								'depth' => 2,
							],
						],
					],
					1 =>
					[
						'id' => '2',
						'parent_id' => 'root',
						'name' => 'Node 2',
						'depth' => 1,
					],
					2 =>
					[
						'id' => '3',
						'parent_id' => 'root',
						'name' => 'Node 3',
						'depth' => 1,
					],


					3 =>
						[
							'id' => 'op_1',
							'parent_id' => 'non-existent-1',
							'name' => 'orphan 1',
							'depth' => 1,
							'children' =>
								[
									0 =>
										[
											'id' => 'op_1_1',
											'parent_id' => 'op_1',
											'name' => 'orphan 1-1',
											'depth' => 2,
										],
									1 =>
										[
											'id' => 'op_1_2',
											'parent_id' => 'op_1',
											'name' => 'orphan 1-2',
											'depth' => 2,
											'children' =>
												[
													0 =>
														[
															'id' => 'op_1_2_1',
															'parent_id' => 'op_1_2',
															'name' => 'orphan 1-2-1',
															'depth' => 3,
														],
												],
										],
								],
						],
					4 =>
						[
							'id' => 'op_2',
							'parent_id' => 'non-existent-2',
							'name' => 'orphan 2',
							'depth' => 1,
							'children' =>
								[
									0 =>
										[
											'id' => 'op_2_1',
											'parent_id' => 'op_2',
											'name' => 'orphan 2-1',
											'depth' => 2,
										],
									1 =>
										[
											'id' => 'op_2_2',
											'parent_id' => 'op_2',
											'name' => 'orphan 2-2',
											'depth' => 2,
											'children' =>
												[
													0 =>
														[
															'id' => 'op_2_2_1',
															'parent_id' => 'op_2_2',
															'name' => 'orphan 2-2-1',
															'depth' => 3,
														],
												],
										],
								],
						],

				],
			],
		];


		$this->assertEquals( $valid_tree, $this->object->toArray() );
    }


	/**
	 * @covers Data_Tree::setData
	 * @covers Data_Tree::appendNode
	 * @covers Data_Tree::toJSON
	 */
	public function testToJSON() {
		$this->prepareTree();

		$valid_JSON = '{"identifier":"id","label":"name","items":[{"id":"root","parent_id":null,"name":"Root","depth":0,"children":[{"id":"1","parent_id":"root","name":"Node 1","depth":1,"children":[{"id":"1-1","parent_id":"1","name":"Node 1-1","depth":2},{"id":"1-2","parent_id":"1","name":"Node 1-2","depth":2,"children":[{"id":"1-2-1","parent_id":"1-2","name":"Node 1-2-1","depth":3},{"id":"1-2-2","parent_id":"1-2","name":"Node 1-2-2","depth":3,"children":[{"id":"1-2-2-1","parent_id":"1-2-2","name":"Node 1-2-2-1","depth":4},{"id":"1-2-2-2","parent_id":"1-2-2","name":"Node 1-2-2-2","depth":4}]},{"id":"1-2-3","parent_id":"1-2","name":"Node 1-2-3","depth":3}]},{"id":"1-3","parent_id":"1","name":"Node 1-3","depth":2}]},{"id":"2","parent_id":"root","name":"Node 2","depth":1},{"id":"3","parent_id":"root","name":"Node 3","depth":1},{"id":"op_1","parent_id":"non-existent-1","name":"orphan 1","depth":1,"children":[{"id":"op_1_1","parent_id":"op_1","name":"orphan 1-1","depth":2},{"id":"op_1_2","parent_id":"op_1","name":"orphan 1-2","depth":2,"children":[{"id":"op_1_2_1","parent_id":"op_1_2","name":"orphan 1-2-1","depth":3}]}]},{"id":"op_2","parent_id":"non-existent-2","name":"orphan 2","depth":1,"children":[{"id":"op_2_1","parent_id":"op_2","name":"orphan 2-1","depth":2},{"id":"op_2_2","parent_id":"op_2","name":"orphan 2-2","depth":2,"children":[{"id":"op_2_2_1","parent_id":"op_2_2","name":"orphan 2-2-1","depth":3}]}]}]}]}';

		$this->assertEquals( $valid_JSON, $this->object->toJSON() );

	}

	/**
	 * @covers Data_Tree::setData
	 * @covers Data_Tree::appendNode
	 * @covers Data_Tree::toXML
	 */
	public function testToXML() {
		$this->prepareTree();

		$valid_XML =
			"<tree>
			        <identifier>id</identifier>
			        <label>name</label>
			        <items>
			                <item>
			                        <id>root</id>
			                        <parent_id></parent_id>
			                        <name>Root</name>
			                        <depth>0</depth>
			                        <children>
			                                <item>
			                                        <id>1</id>
			                                        <parent_id>root</parent_id>
			                                        <name>Node 1</name>
			                                        <depth>1</depth>
			                                        <children>
			                                                <item>
			                                                        <id>1-1</id>
			                                                        <parent_id>1</parent_id>
			                                                        <name>Node 1-1</name>
			                                                        <depth>2</depth>
			                                                </item>
			                                                <item>
			                                                        <id>1-2</id>
			                                                        <parent_id>1</parent_id>
			                                                        <name>Node 1-2</name>
			                                                        <depth>2</depth>
			                                                        <children>
			                                                                <item>
			                                                                        <id>1-2-1</id>
			                                                                        <parent_id>1-2</parent_id>
			                                                                        <name>Node 1-2-1</name>
			                                                                        <depth>3</depth>
			                                                                </item>
			                                                                <item>
			                                                                        <id>1-2-2</id>
			                                                                        <parent_id>1-2</parent_id>
			                                                                        <name>Node 1-2-2</name>
			                                                                        <depth>3</depth>
			                                                                        <children>
			                                                                                <item>
			                                                                                        <id>1-2-2-1</id>
			                                                                                        <parent_id>1-2-2</parent_id>
			                                                                                        <name>Node 1-2-2-1</name>
			                                                                                        <depth>4</depth>
			                                                                                </item>
			                                                                                <item>
			                                                                                        <id>1-2-2-2</id>
			                                                                                        <parent_id>1-2-2</parent_id>
			                                                                                        <name>Node 1-2-2-2</name>
			                                                                                        <depth>4</depth>
			                                                                                </item>
			                                                                        </children>
			                                                                </item>
			                                                                <item>
			                                                                        <id>1-2-3</id>
			                                                                        <parent_id>1-2</parent_id>
			                                                                        <name>Node 1-2-3</name>
			                                                                        <depth>3</depth>
			                                                                </item>
			                                                        </children>
			                                                </item>
			                                                <item>
			                                                        <id>1-3</id>
			                                                        <parent_id>1</parent_id>
			                                                        <name>Node 1-3</name>
			                                                        <depth>2</depth>
			                                                </item>
			                                        </children>
			                                </item>
			                                <item>
			                                        <id>2</id>
			                                        <parent_id>root</parent_id>
			                                        <name>Node 2</name>
			                                        <depth>1</depth>
			                                </item>
			                                <item>
			                                        <id>3</id>
			                                        <parent_id>root</parent_id>
			                                        <name>Node 3</name>
			                                        <depth>1</depth>
			                                </item>

			                <item>
			                        <id>op_1</id>
			                        <parent_id>non-existent-1</parent_id>
			                        <name>orphan 1</name>
			                        <depth>1</depth>
			                        <children>
			                                <item>
			                                        <id>op_1_1</id>
			                                        <parent_id>op_1</parent_id>
			                                        <name>orphan 1-1</name>
			                                        <depth>2</depth>
			                                </item>
			                                <item>
			                                        <id>op_1_2</id>
			                                        <parent_id>op_1</parent_id>
			                                        <name>orphan 1-2</name>
			                                        <depth>2</depth>
			                                        <children>
			                                                <item>
			                                                        <id>op_1_2_1</id>
			                                                        <parent_id>op_1_2</parent_id>
			                                                        <name>orphan 1-2-1</name>
			                                                        <depth>3</depth>
			                                                </item>
			                                        </children>
			                                </item>
			                        </children>
			                </item>
			                <item>
			                        <id>op_2</id>
			                        <parent_id>non-existent-2</parent_id>
			                        <name>orphan 2</name>
			                        <depth>1</depth>
			                        <children>
			                                <item>
			                                        <id>op_2_1</id>
			                                        <parent_id>op_2</parent_id>
			                                        <name>orphan 2-1</name>
			                                        <depth>2</depth>
			                                </item>
			                                <item>
			                                        <id>op_2_2</id>
			                                        <parent_id>op_2</parent_id>
			                                        <name>orphan 2-2</name>
			                                        <depth>2</depth>
			                                        <children>
			                                                <item>
			                                                        <id>op_2_2_1</id>
			                                                        <parent_id>op_2_2</parent_id>
			                                                        <name>orphan 2-2-1</name>
			                                                        <depth>3</depth>
			                                                </item>
			                                        </children>
			                                </item>
			                        </children>
			                </item>

			                        </children>
			                </item>
			        </items>
			</tree>";


		$test_XML = $this->object->toXML();

		$valid_XML = str_replace("\r", '', $valid_XML);
		$test_XML = str_replace("\r", '', $test_XML);
		$valid_XML = str_replace("\t", '', $valid_XML);
		$test_XML = str_replace("\t", '', $test_XML);
		$valid_XML = str_replace("\n", '', $valid_XML);
		$test_XML = str_replace("\n", '', $test_XML);
		$valid_XML = str_replace(' ', '', $valid_XML);
		$test_XML = str_replace(' ', '', $test_XML);

		$this->assertEquals( $valid_XML, $test_XML );
	}


	/**
	 * @covers Data_Tree::setData
	 * @covers Data_Tree::appendNode
	 * @covers Data_Tree::rewind
	 * @covers Data_Tree::current
	 * @covers Data_Tree::key
	 * @covers Data_Tree::next
	 * @covers Data_Tree::valid
	 *
	 * @covers Data_Tree_Node::rewind
	 * @covers Data_Tree_Node::current
	 * @covers Data_Tree_Node::key
	 * @covers Data_Tree_Node::next
	 * @covers Data_Tree_Node::valid
	 * @covers Data_Tree_Node::toString
	 * @covers Data_Tree_Node::getLabel
	 *
	 */
	public function testIterator() {
		$this->prepareTree();

		$valid_data = [
			[
				'id' => 'root',
				'label' => 'Root'
			],
			[
				'id' => '1',
				'label' => 'Node 1'
			],
			[
				'id' => '1-1',
				'label' => 'Node 1-1'
			],
			[
				'id' => '1-2',
				'label' => 'Node 1-2'
			],
			[
				'id' => '1-2-1',
				'label' => 'Node 1-2-1'
			],
			[
				'id' => '1-2-2',
				'label' => 'Node 1-2-2'
			],
			[
				'id' => '1-2-2-1',
				'label' => 'Node 1-2-2-1'
			],
			[
				'id' => '1-2-2-2',
				'label' => 'Node 1-2-2-2'
			],
			[
				'id' => '1-2-3',
				'label' => 'Node 1-2-3'
			],
			[
				'id' => '1-3',
				'label' => 'Node 1-3'
			],
			[
				'id' => '2',
				'label' => 'Node 2'
			],
			[
				'id' => '3',
				'label' => 'Node 3'
			],
			[
				'id' => 'op_1',
				'label' => 'orphan 1'
			],
			[
				'id' => 'op_1_1',
				'label' => 'orphan 1-1'
			],
			[
				'id' => 'op_1_2',
				'label' => 'orphan 1-2'
			],
			[
				'id' => 'op_1_2_1',
				'label' => 'orphan 1-2-1'
			],
			[
				'id' => 'op_2',
				'label' => 'orphan 2'
			],
			[
				'id' => 'op_2_1',
				'label' => 'orphan 2-1'
			],
			[
				'id' => 'op_2_2',
				'label' => 'orphan 2-2'
			],
			[
				'id' => 'op_2_2_1',
				'label' => 'orphan 2-2-1'
			],
		];

		$i = 0;


		foreach( $this->object as $id=>$node ) {


			$current_valid_data = $valid_data[$i];
			$this->assertEquals($current_valid_data['id'], $id);
			$this->assertEquals($current_valid_data['label'], (string)$node );

			$i++;
		}

	}

}
