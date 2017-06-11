<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplicationModule\JetExample\Test\REST;
use Jet\Data_DateTime;
use Jet\Mvc_Site;


/**
 *
 */
class Test_Article_Delete extends Test_Abstract
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
		return 'Delete - valid';
	}

	/**
	 *
	 */
	public function test()
	{
		$id = $this->items[0]['id'];

		$this->client->delete('article/'.$id);

	}
}
