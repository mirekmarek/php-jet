<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\ORM;

use Jet\DataModel_Query;
use Jet\DataModel_Query_Select_Item_Expression;

/**
 *
 */
class Test_M2NRelation extends Test_Abstract
{
	/**
	 * @return string
	 */
	protected function _getTitle()
	{
		return 'MtoN relation';
	}

	/**
	 *
	 */
	public function test()
	{
		$where = [];

		$q = Model_A1::createQuery();

		$q->setSelect( [
			'id',
			'text',
			'related_id'   => 'model_b1.id',
			'related_text' => 'model_b1.text',
			'test_count'   => new DataModel_Query_Select_Item_Expression( 'COUNT( %RELATED_ID% )', ['RELATED_ID' => 'model_b1.id'] )
		] );

		$q->getRelation( 'a1_m2n_b1' )->setJoinType( DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN );
		$q->getRelation( 'model_b1' )->setJoinType( DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN );

		$q->setOrderBy( ['-model_b1.text'] );

		$q->setWhere( $where );

		echo $q;

	}
}