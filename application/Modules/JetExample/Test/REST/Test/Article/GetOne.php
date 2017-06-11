<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplicationModule\JetExample\Test\REST;


/**
 *
 */
class Test_Article_GetOne extends Test_Abstract
{

	/**
	 * @var array
	 */
	protected $items = [];

	/**
	 *
	 * @param string $id
	 */
	public function __construct( $id )
	{
		parent::__construct( $id );

		$this->client->get('article');
		$this->items = $this->client->responseData()['items'];

	}

	/**
	 * @return bool
	 */
	public function isEnabled()
	{
		return count($this->items)>0;
	}

	/**
	 * @return string
	 */
	protected function _getTitle()
	{
		return 'Get data';
	}

	/**
	 *
	 */
	public function test()
	{

		$ids = [];
		foreach( $this->items as $item ) {
			$ids[] = $item['id'];
		}

		shuffle($ids);
		$id = $ids[0];

		$this->client->get('article/'.$id);
	}
}
