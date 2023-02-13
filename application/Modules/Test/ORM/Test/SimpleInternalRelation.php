<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\ORM;

use Jet\DataModel_Query;
use Jet\DataModel_Query_Select_Item_Expression;

/**
 *
 */
class Test_SimpleInternalRelation extends Test_Abstract
{
	public function getId(): string
	{
		return 'SimpleInternalRelation';
	}
	
	/**
	 * @return string
	 */
	protected function _getTitle() : string
	{
		return 'Simple internal relation';
	}

	/**
	 *
	 */
	public function test() : void
	{
		$where = [];

		$q = Model_A1::createQuery();

		$q->setSelect( [
			'id',
			'text',
			'related_id'   => 'model_a1_1toN.id',
			'related_text' => 'model_a1_1toN.text',
			'test_count'   => new DataModel_Query_Select_Item_Expression( 'COUNT( %RELATED_ID% )', ['RELATED_ID' => 'model_a1_1toN.id'] )
		] );

		$q->getRelation( 'model_a1_1toN' )->setJoinType( DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN );

		$q->setOrderBy( ['-model_a1_1toN.text'] );

		$q->setWhere( $where );

		echo $q;

	}
}