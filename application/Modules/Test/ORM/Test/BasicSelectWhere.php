<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\ORM;

use Jet\DataModel_Query_Select_Item_Expression;

/**
 *
 */
class Test_BasicSelectWhere extends Test_Abstract
{
	public function getId(): string
	{
		return 'BasicSelectWhere';
	}
	
	/**
	 * @return string
	 */
	protected function _getTitle() : string
	{
		return 'Complex SELECT without relations';
	}

	/**
	 *
	 */
	public function test() : void
	{
		$where = [
			[
				[
					'id'   => 'xxxx',
					'AND',
					'text' => 'yyyy',
				],
				'OR',
				[
					'id'   => 123,
					'AND',
					'text' => 123,

				],
				'OR',
				[
					'id'   => 3.14,
					'AND',
					'text' => 3.14,

				],
				'OR',
				[
					'id'   => [
						'a',
						'b',
						'c',
						1,
						2,
						3
					],
					'AND',
					'text' => [
						'a',
						'b',
						'c',
						1,
						2,
						3
					],

				],
				'OR',
				[
					'id'   => null,
					'AND',
					'text' => null,

				]

			],
			'AND',
			[
				[
					'id !='   => 'xxxx',
					'AND',
					'text !=' => 'yyyy',
				],
				'OR',
				[
					'id!='    => 123,
					'AND',
					'text !=' => 123,

				],
				'OR',
				[
					'id!='        => 3.14,
					'AND',
					'text     !=' => 3.14,

				],
				'OR',
				[
					'id             !=' => [
						'a',
						'b',
						'c',
						1,
						2,
						3
					],
					'AND',
					'text!='            => [
						'a',
						'b',
						'c',
						1,
						2,
						3
					],

				],
				'OR',
				[
					'id !='   => null,
					'AND',
					'text !=' => null,

				]

			],
			'OR',
			[
				'text ='  => 'test',
				'OR',
				'text *'  => 'test',
				'OR',
				'text !*' => 'test',
				'OR',

				'text <'  => 123,
				'OR',
				'text >'  => 123,

				'OR',
				'text <=' => 123,
				'OR',
				'text >=' => 123,
			]


		];

		$q = Model_A1::createQuery();

		$q->setSelect( [
			'id',
			'text',
			'test_count' => new DataModel_Query_Select_Item_Expression( 'COUNT( %ID% )', ['ID' => 'id'] )
		] );

		$q->setHaving( [
			'test_count < ' => 777,
			'OR',
			'test_count > ' => 111,
		] );

		$q->setOrderBy( [
			'-test_count',
			'text'
		] );

		$q->setGroupBy( ['id'] );

		$q->setWhere( $where );

		echo $q;

	}
}