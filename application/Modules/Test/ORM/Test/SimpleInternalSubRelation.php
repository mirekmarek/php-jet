<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplicationModule\Test\ORM;

use Jet\DataModel_Query_Select_Item_Expression;

/**
 *
 */
class Test_SimpleInternalSubRelation extends Test_Abstract
{
	/**
	 * @return string
	 */
	protected function _getTitle()
	{
		return 'Simple internal sub relation';
	}

	/**
	 *
	 */
	public function test()
	{
		$where = [];

		$q = Model_A1::createQuery();

		$q->setSelect([
			'id',
			'text',
		    'related_id' => 'model_a1_1toN_sub1toN.id',
		    'related_text' => 'model_a1_1toN_sub1toN.text',
		    'test_count' => new DataModel_Query_Select_Item_Expression( 'COUNT( %RELATED_ID% )', ['RELATED_ID'=>'model_a1_1toN_sub1toN.id'] )
		]);

		$q->setOrderBy(['+model_a1_1toN_sub1toN.text']);

		$q->setWhere( $where );

		echo $q;

	}
}