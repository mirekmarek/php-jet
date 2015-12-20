<?php
/**
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
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
			'ID' => 'root',
			'parent_ID' => '',
			'name' => 'Root',
		),
		*/
		'1' => [
			'ID' => '1',
			'parent_ID' => 'root',
			'name' => 'Node 1',
		],
		'1-1' => [
			'ID' => '1-1',
			'parent_ID' => '1',
			'name' => 'Node 1-1',
		],
		'1-2' => [
			'ID' => '1-2',
			'parent_ID' => '1',
			'name' => 'Node 1-2',
		],
		'1-2-1' => [
			'ID' => '1-2-1',
			'parent_ID' => '1-2',
			'name' => 'Node 1-2-1',
		],
		'1-2-2' => [
			'ID' => '1-2-2',
			'parent_ID' => '1-2',
			'name' => 'Node 1-2-2',
		],
		'1-2-2-1' => [
			'ID' => '1-2-2-1',
			'parent_ID' => '1-2-2',
			'name' => 'Node 1-2-2-1',
		],
		'1-2-2-2' => [
			'ID' => '1-2-2-2',
			'parent_ID' => '1-2-2',
			'name' => 'Node 1-2-2-2',
		],
		'1-2-3' => [
			'ID' => '1-2-3',
			'parent_ID' => '1-2',
			'name' => 'Node 1-2-3',
		],
		'1-3' => [
			'ID' => '1-3',
			'parent_ID' => '1',
			'name' => 'Node 1-3',
		],
		'2' => [
			'ID' => '2',
			'parent_ID' => 'root',
			'name' => 'Node 2',
		],
		'3' => [
			'ID' => '3',
			'parent_ID' => 'root',
			'name' => 'Node 3',
		],
		'op_1' => [
			'ID' => 'op_1',
			'parent_ID' => 'non-existent-1',
			'name' => 'orphan 1',
		],
		'op_1_1' => [
			'ID' => 'op_1_1',
			'parent_ID' => 'op_1',
			'name' => 'orphan 1-1',
		],
		'op_1_2' => [
			'ID' => 'op_1_2',
			'parent_ID' => 'op_1',
			'name' => 'orphan 1-2',
		],
		'op_1_2_1' => [
			'ID' => 'op_1_2_1',
			'parent_ID' => 'op_1_2',
			'name' => 'orphan 1-2-1',
		],

		'op_2' => [
			'ID' => 'op_2',
			'parent_ID' => 'non-existent-2',
			'name' => 'orphan 2',
		],
		'op_2_1' => [
			'ID' => 'op_2_1',
			'parent_ID' => 'op_2',
			'name' => 'orphan 2-1',
		],
		'op_2_2' => [
			'ID' => 'op_2_2',
			'parent_ID' => 'op_2',
			'name' => 'orphan 2-2',
		],
		'op_2_2_1' => [
			'ID' => 'op_2_2_1',
			'parent_ID' => 'op_2_2',
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
	 * @covers Jet\Data_Tree::setNodeClassName
	 * @expectedException \Jet\Data_Tree_Exception
	 * @expectedExceptionCode \Jet\Data_Tree_Exception::CODE_INVALID_NODES_CLASS
	 */
	public function testSetNodeClassNameInvalid() {
		$this->object->setNodeClassName('Jet\\TreeTest_Node_Invalid');
	}


	/**
	 * @covers Jet\Data_Tree::setNodeClassName
	 * @covers Jet\Data_Tree::getNodeClassName
	 */
	public function testSetAndGetNodeClassName() {
		$this->object->setNodeClassName('Jet\\TreeTest_Node_Valid');
		$this->assertEquals('Jet\\TreeTest_Node_Valid', $this->object->getNodesClassName());
	}

	/**
	 * @covers Jet\Data_Tree::getIDKey
	 */
	public function testGetIDKey() {
		$this->assertEquals('ID', $this->object->getIDKey() );
	}

	/**
	 * @covers Jet\Data_Tree::getParentIDKey
	 */
	public function testGetParentIDKey() {
		$this->assertEquals('parent_ID', $this->object->getParentIDKey() );
	}

	/**
	 * @covers Jet\Data_Tree::setLabelKey
	 * @covers Jet\Data_Tree::getLabelKey
	 */
	public function testSetGetLabelKey() {
		$this->assertEquals('name' ,$this->object->getLabelKey() );
		$this->object->setLabelKey('label_key_test');

		$this->assertEquals('label_key_test' ,$this->object->label_key );
		$this->assertEquals('label_key_test' ,$this->object->getLabelKey() );
	}


	/**
	 * @covers Jet\Data_Tree::setChildrenKey
	 * @covers Jet\Data_Tree::getChildrenKey
	 */
	public function testSetGetChildrenKey() {
		$this->assertEquals('children' ,$this->object->getChildrenKey() );
		$this->object->setChildrenKey('children_key_test');
		$this->assertEquals('children_key_test' ,$this->object->children_key );
		$this->assertEquals('children_key_test' ,$this->object->getChildrenKey() );
	}

	/**
	 * @covers Jet\Data_Tree::setDepthKey
	 * @covers Jet\Data_Tree::getDepthKey
	 */
	public function testSetGetDepthKey() {
		$this->assertEquals('depth' ,$this->object->getDepthKey() );
		$this->object->setDepthKey('depth_key_test');
		$this->assertEquals('depth_key_test' ,$this->object->depth_key );
		$this->assertEquals('depth_key_test' ,$this->object->getDepthKey() );
	}

	protected function prepareTree() {
		$this->object->getRootNode()->setID('root');
		$this->object->getRootNode()->setLabel('Root');

		$this->object->setData( $this->data );
	}


	/**
	 * @covers Jet\Data_Tree::setData
	 * @covers Jet\Data_Tree::appendNode
	 */
	public function testGetRootNode() {

		$this->prepareTree();

		$this->assertEquals( 'root', $this->object->getRootNode()->getID() );

	}


	/**
	 * @covers Jet\Data_Tree::setData
	 * @covers Jet\Data_Tree::getNodeExists
	 */
	public function testGetNodeExists() {
		$this->prepareTree();

		$this->assertFalse( $this->object->getNodeExists('unknown-node') );
		$this->assertTrue( $this->object->getNodeExists('1-2-2-2') );
	}

	/**
	 * @covers Jet\Data_Tree::setData
	 * @covers Jet\Data_Tree::appendNode
	 * @covers Jet\Data_Tree::getNode
	 * @covers Jet\Data_Tree_Node::getID
	 * @covers Jet\Data_Tree_Node::getParentID
	 * @covers Jet\Data_Tree_Node::getLabel
	 * @covers Jet\Data_Tree_Node::getParent
	 * @covers Jet\Data_Tree_Node::getChildExists
	 *
	 */
	public function testGeneral() {
		$this->prepareTree();

		$node = $this->object->getNode('1-2-2-2');
		$parent_node = $node->getParent();

		$this->assertEquals( '1-2-2-2', $node->getID() );
		$this->assertEquals( '1-2-2', $node->getParentID() );
		$this->assertEquals( 'Node 1-2-2-2', $node->getLabel() );

		$this->assertEquals( '1-2-2', $parent_node->getID() );
		$this->assertEquals( '1-2', $parent_node->getParentID() );
		$this->assertEquals( 'Node 1-2-2', $parent_node->getLabel() );

		$this->assertFalse( $parent_node->getChildExists( 'unknown-child' ) );
		$this->assertTrue( $parent_node->getChildExists( '1-2-2-2' ) );

		$child = $parent_node->getChild('1-2-2-2');
		$this->assertEquals( '1-2-2-2', $child->getID() );
		$this->assertEquals( '1-2-2', $child->getParentID() );
		$this->assertEquals( 'Node 1-2-2-2', $child->getLabel() );

	}

	/**
	 * @covers Jet\Data_Tree::setData
	 * @covers Jet\Data_Tree::appendNode
	 * @covers Jet\Data_Tree::getNodes
	 * @covers Jet\Data_Tree_Node::getID
	 * @covers Jet\Data_Tree_Node::getParentID
	 * @covers Jet\Data_Tree_Node::getLabel
	 */
	public function testGetNodes() {
		$this->prepareTree();
		$nodes = $this->object->getNodes();

		$this->assertEquals( count($this->data)+1, count($nodes) );

		foreach($nodes as $ID=>$node) {
			if($ID=='root') {
				continue;
			}
			$this->assertEquals( $this->data[$ID]['ID'], $node->getID() );
			$this->assertEquals( $this->data[$ID]['parent_ID'], $node->getRealParentID() );
			$this->assertEquals( $this->data[$ID]['name'], $node->getLabel() );
		}
	}

	/**
	 * @covers Jet\Data_Tree::setData
	 * @covers Jet\Data_Tree::getNodesIDs
	 */
	public function testGetNodesIDs() {
		$this->prepareTree();

		$this->assertEquals( array_merge(['root'],array_keys($this->data)), $this->object->getNodesIDs() );
	}


	/**
	 * @covers Jet\Data_Tree::setData
	 * @covers Jet\Data_Tree::appendNode
	 * @covers Jet\Data_Tree::toArray
	 */
	public function testToArray() {
		$this->prepareTree();

		$valid_tree = [
			0 =>
			[
				'ID' => 'root',
				'parent_ID' => '',
				'name' => 'Root',
				'depth' => 0,
				'children' =>
				[
					0 =>
					[
						'ID' => '1',
						'parent_ID' => 'root',
						'name' => 'Node 1',
						'depth' => 1,
						'children' =>
						[
							0 =>
							[
								'ID' => '1-1',
								'parent_ID' => '1',
								'name' => 'Node 1-1',
								'depth' => 2,
							],
							1 =>
							[
								'ID' => '1-2',
								'parent_ID' => '1',
								'name' => 'Node 1-2',
								'depth' => 2,
								'children' =>
								[
									0 =>
									[
										'ID' => '1-2-1',
										'parent_ID' => '1-2',
										'name' => 'Node 1-2-1',
										'depth' => 3,
									],
									1 =>
									[
										'ID' => '1-2-2',
										'parent_ID' => '1-2',
										'name' => 'Node 1-2-2',
										'depth' => 3,
										'children' =>
										[
											0 =>
											[
												'ID' => '1-2-2-1',
												'parent_ID' => '1-2-2',
												'name' => 'Node 1-2-2-1',
												'depth' => 4,
											],
											1 =>
											[
												'ID' => '1-2-2-2',
												'parent_ID' => '1-2-2',
												'name' => 'Node 1-2-2-2',
												'depth' => 4,
											],
										],
									],
									2 =>
									[
										'ID' => '1-2-3',
										'parent_ID' => '1-2',
										'name' => 'Node 1-2-3',
										'depth' => 3,
									],
								],
							],
							2 =>
							[
								'ID' => '1-3',
								'parent_ID' => '1',
								'name' => 'Node 1-3',
								'depth' => 2,
							],
						],
					],
					1 =>
					[
						'ID' => '2',
						'parent_ID' => 'root',
						'name' => 'Node 2',
						'depth' => 1,
					],
					2 =>
					[
						'ID' => '3',
						'parent_ID' => 'root',
						'name' => 'Node 3',
						'depth' => 1,
					],


					3 =>
						[
							'ID' => 'op_1',
							'parent_ID' => 'non-existent-1',
							'name' => 'orphan 1',
							'depth' => 1,
							'children' =>
								[
									0 =>
										[
											'ID' => 'op_1_1',
											'parent_ID' => 'op_1',
											'name' => 'orphan 1-1',
											'depth' => 2,
										],
									1 =>
										[
											'ID' => 'op_1_2',
											'parent_ID' => 'op_1',
											'name' => 'orphan 1-2',
											'depth' => 2,
											'children' =>
												[
													0 =>
														[
															'ID' => 'op_1_2_1',
															'parent_ID' => 'op_1_2',
															'name' => 'orphan 1-2-1',
															'depth' => 3,
														],
												],
										],
								],
						],
					4 =>
						[
							'ID' => 'op_2',
							'parent_ID' => 'non-existent-2',
							'name' => 'orphan 2',
							'depth' => 1,
							'children' =>
								[
									0 =>
										[
											'ID' => 'op_2_1',
											'parent_ID' => 'op_2',
											'name' => 'orphan 2-1',
											'depth' => 2,
										],
									1 =>
										[
											'ID' => 'op_2_2',
											'parent_ID' => 'op_2',
											'name' => 'orphan 2-2',
											'depth' => 2,
											'children' =>
												[
													0 =>
														[
															'ID' => 'op_2_2_1',
															'parent_ID' => 'op_2_2',
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
	 * @covers Jet\Data_Tree::setData
	 * @covers Jet\Data_Tree::appendNode
	 * @covers Jet\Data_Tree::toJSON
	 */
	public function testToJSON() {
		$this->prepareTree();

		$valid_JSON = '{"identifier":"ID","label":"name","items":[{"ID":"root","parent_ID":"","name":"Root","depth":0,"children":[{"ID":"1","parent_ID":"root","name":"Node 1","depth":1,"children":[{"ID":"1-1","parent_ID":"1","name":"Node 1-1","depth":2},{"ID":"1-2","parent_ID":"1","name":"Node 1-2","depth":2,"children":[{"ID":"1-2-1","parent_ID":"1-2","name":"Node 1-2-1","depth":3},{"ID":"1-2-2","parent_ID":"1-2","name":"Node 1-2-2","depth":3,"children":[{"ID":"1-2-2-1","parent_ID":"1-2-2","name":"Node 1-2-2-1","depth":4},{"ID":"1-2-2-2","parent_ID":"1-2-2","name":"Node 1-2-2-2","depth":4}]},{"ID":"1-2-3","parent_ID":"1-2","name":"Node 1-2-3","depth":3}]},{"ID":"1-3","parent_ID":"1","name":"Node 1-3","depth":2}]},{"ID":"2","parent_ID":"root","name":"Node 2","depth":1},{"ID":"3","parent_ID":"root","name":"Node 3","depth":1},{"ID":"op_1","parent_ID":"non-existent-1","name":"orphan 1","depth":1,"children":[{"ID":"op_1_1","parent_ID":"op_1","name":"orphan 1-1","depth":2},{"ID":"op_1_2","parent_ID":"op_1","name":"orphan 1-2","depth":2,"children":[{"ID":"op_1_2_1","parent_ID":"op_1_2","name":"orphan 1-2-1","depth":3}]}]},{"ID":"op_2","parent_ID":"non-existent-2","name":"orphan 2","depth":1,"children":[{"ID":"op_2_1","parent_ID":"op_2","name":"orphan 2-1","depth":2},{"ID":"op_2_2","parent_ID":"op_2","name":"orphan 2-2","depth":2,"children":[{"ID":"op_2_2_1","parent_ID":"op_2_2","name":"orphan 2-2-1","depth":3}]}]}]}]}';

		$this->assertEquals( $valid_JSON, $this->object->toJSON() );

	}

	/**
	 * @covers Jet\Data_Tree::setData
	 * @covers Jet\Data_Tree::appendNode
	 * @covers Jet\Data_Tree::toXML
	 */
	public function testToXML() {
		$this->prepareTree();

		$valid_XML =
			"<tree>
			        <identifier>ID</identifier>
			        <label>name</label>
			        <items>
			                <item>
			                        <ID>root</ID>
			                        <parent_ID></parent_ID>
			                        <name>Root</name>
			                        <depth>0</depth>
			                        <children>
			                                <item>
			                                        <ID>1</ID>
			                                        <parent_ID>root</parent_ID>
			                                        <name>Node 1</name>
			                                        <depth>1</depth>
			                                        <children>
			                                                <item>
			                                                        <ID>1-1</ID>
			                                                        <parent_ID>1</parent_ID>
			                                                        <name>Node 1-1</name>
			                                                        <depth>2</depth>
			                                                </item>
			                                                <item>
			                                                        <ID>1-2</ID>
			                                                        <parent_ID>1</parent_ID>
			                                                        <name>Node 1-2</name>
			                                                        <depth>2</depth>
			                                                        <children>
			                                                                <item>
			                                                                        <ID>1-2-1</ID>
			                                                                        <parent_ID>1-2</parent_ID>
			                                                                        <name>Node 1-2-1</name>
			                                                                        <depth>3</depth>
			                                                                </item>
			                                                                <item>
			                                                                        <ID>1-2-2</ID>
			                                                                        <parent_ID>1-2</parent_ID>
			                                                                        <name>Node 1-2-2</name>
			                                                                        <depth>3</depth>
			                                                                        <children>
			                                                                                <item>
			                                                                                        <ID>1-2-2-1</ID>
			                                                                                        <parent_ID>1-2-2</parent_ID>
			                                                                                        <name>Node 1-2-2-1</name>
			                                                                                        <depth>4</depth>
			                                                                                </item>
			                                                                                <item>
			                                                                                        <ID>1-2-2-2</ID>
			                                                                                        <parent_ID>1-2-2</parent_ID>
			                                                                                        <name>Node 1-2-2-2</name>
			                                                                                        <depth>4</depth>
			                                                                                </item>
			                                                                        </children>
			                                                                </item>
			                                                                <item>
			                                                                        <ID>1-2-3</ID>
			                                                                        <parent_ID>1-2</parent_ID>
			                                                                        <name>Node 1-2-3</name>
			                                                                        <depth>3</depth>
			                                                                </item>
			                                                        </children>
			                                                </item>
			                                                <item>
			                                                        <ID>1-3</ID>
			                                                        <parent_ID>1</parent_ID>
			                                                        <name>Node 1-3</name>
			                                                        <depth>2</depth>
			                                                </item>
			                                        </children>
			                                </item>
			                                <item>
			                                        <ID>2</ID>
			                                        <parent_ID>root</parent_ID>
			                                        <name>Node 2</name>
			                                        <depth>1</depth>
			                                </item>
			                                <item>
			                                        <ID>3</ID>
			                                        <parent_ID>root</parent_ID>
			                                        <name>Node 3</name>
			                                        <depth>1</depth>
			                                </item>

			                <item>
			                        <ID>op_1</ID>
			                        <parent_ID>non-existent-1</parent_ID>
			                        <name>orphan 1</name>
			                        <depth>1</depth>
			                        <children>
			                                <item>
			                                        <ID>op_1_1</ID>
			                                        <parent_ID>op_1</parent_ID>
			                                        <name>orphan 1-1</name>
			                                        <depth>2</depth>
			                                </item>
			                                <item>
			                                        <ID>op_1_2</ID>
			                                        <parent_ID>op_1</parent_ID>
			                                        <name>orphan 1-2</name>
			                                        <depth>2</depth>
			                                        <children>
			                                                <item>
			                                                        <ID>op_1_2_1</ID>
			                                                        <parent_ID>op_1_2</parent_ID>
			                                                        <name>orphan 1-2-1</name>
			                                                        <depth>3</depth>
			                                                </item>
			                                        </children>
			                                </item>
			                        </children>
			                </item>
			                <item>
			                        <ID>op_2</ID>
			                        <parent_ID>non-existent-2</parent_ID>
			                        <name>orphan 2</name>
			                        <depth>1</depth>
			                        <children>
			                                <item>
			                                        <ID>op_2_1</ID>
			                                        <parent_ID>op_2</parent_ID>
			                                        <name>orphan 2-1</name>
			                                        <depth>2</depth>
			                                </item>
			                                <item>
			                                        <ID>op_2_2</ID>
			                                        <parent_ID>op_2</parent_ID>
			                                        <name>orphan 2-2</name>
			                                        <depth>2</depth>
			                                        <children>
			                                                <item>
			                                                        <ID>op_2_2_1</ID>
			                                                        <parent_ID>op_2_2</parent_ID>
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
	 * @covers Jet\Data_Tree::setData
	 * @covers Jet\Data_Tree::appendNode
	 * @covers Jet\Data_Tree::rewind
	 * @covers Jet\Data_Tree::current
	 * @covers Jet\Data_Tree::key
	 * @covers Jet\Data_Tree::next
	 * @covers Jet\Data_Tree::valid
	 *
	 * @covers Jet\Data_Tree_Node::rewind
	 * @covers Jet\Data_Tree_Node::current
	 * @covers Jet\Data_Tree_Node::key
	 * @covers Jet\Data_Tree_Node::next
	 * @covers Jet\Data_Tree_Node::valid
	 * @covers Jet\Data_Tree_Node::toString
	 * @covers Jet\Data_Tree_Node::getLabel
	 *
	 */
	public function testIterator() {
		$this->prepareTree();

		$valid_data = [
			[
				'ID' => 'root',
				'label' => 'Root'
			],
			[
				'ID' => '1',
				'label' => 'Node 1'
			],
			[
				'ID' => '1-1',
				'label' => 'Node 1-1'
			],
			[
				'ID' => '1-2',
				'label' => 'Node 1-2'
			],
			[
				'ID' => '1-2-1',
				'label' => 'Node 1-2-1'
			],
			[
				'ID' => '1-2-2',
				'label' => 'Node 1-2-2'
			],
			[
				'ID' => '1-2-2-1',
				'label' => 'Node 1-2-2-1'
			],
			[
				'ID' => '1-2-2-2',
				'label' => 'Node 1-2-2-2'
			],
			[
				'ID' => '1-2-3',
				'label' => 'Node 1-2-3'
			],
			[
				'ID' => '1-3',
				'label' => 'Node 1-3'
			],
			[
				'ID' => '2',
				'label' => 'Node 2'
			],
			[
				'ID' => '3',
				'label' => 'Node 3'
			],
			[
				'ID' => 'op_1',
				'label' => 'orphan 1'
			],
			[
				'ID' => 'op_1_1',
				'label' => 'orphan 1-1'
			],
			[
				'ID' => 'op_1_2',
				'label' => 'orphan 1-2'
			],
			[
				'ID' => 'op_1_2_1',
				'label' => 'orphan 1-2-1'
			],
			[
				'ID' => 'op_2',
				'label' => 'orphan 2'
			],
			[
				'ID' => 'op_2_1',
				'label' => 'orphan 2-1'
			],
			[
				'ID' => 'op_2_2',
				'label' => 'orphan 2-2'
			],
			[
				'ID' => 'op_2_2_1',
				'label' => 'orphan 2-2-1'
			],
		];

		$i = 0;


		foreach( $this->object as $ID=>$node ) {


			$current_valid_data = $valid_data[$i];
			$this->assertEquals($current_valid_data['ID'], $ID);
			$this->assertEquals($current_valid_data['label'], (string)$node );

			$i++;
		}

	}

}
