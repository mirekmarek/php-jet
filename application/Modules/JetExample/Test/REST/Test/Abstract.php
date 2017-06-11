<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplicationModule\JetExample\Test\REST;

use Jet\Tr;

/**
 *
 */
abstract class Test_Abstract
{
	/**
	 * @var
	 */
	protected $id = '';

	/**
	 * @var Client
	 */
	protected $client;

	/**
	 * @var bool
	 */
	protected $is_selected = false;

	/**
	 * Test_Abstract constructor.
	 *
	 * @param string $id
	 */
	public function __construct( $id )
	{
		$this->id = $id;

		$this->client = new Client();
	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return bool
	 */
	public function isSelected()
	{
		return $this->is_selected;
	}

	/**
	 * @param bool $is_selected
	 */
	public function setIsSelected( $is_selected )
	{
		$this->is_selected = $is_selected;
	}

	/**
	 * @return Client
	 */
	public function getClient()
	{
		return $this->client;
	}

	/**
	 * @return bool
	 */
	public function isEnabled()
	{
		return true;
	}


	/**
	 * @return string
	 */
	public function getTitle()
	{
		return Tr::_( $this->_getTitle() );
	}

	/**
	 * @return string
	 */
	abstract protected function _getTitle();

	/**
	 *
	 */
	abstract public function test();

	/**
	 *
	 */
	public function showResult()
	{
		?>
		<h3><?=Tr::_('Request')?></h3>
		<h4><?=Tr::_('Header')?></h4>
		<pre><?=$this->client->request()?></pre>
		<h4><?=Tr::_('Body')?></h4>
		<pre><?=$this->client->requestBody()?></pre>
		<h4><?=Tr::_('Data')?></h4>
		<pre><?=var_export( $this->client->requestData(), true )?></pre>


		<h3><?=Tr::_('Response')?></h3>
		<h4><?=Tr::_('Header')?></h4>
		<pre><?=$this->client->responseHeader()?></pre>
		<h4><?=Tr::_('Body')?></h4>
		<pre><?=$this->client->responseBody()?></pre>
		<h4><?=Tr::_('Data')?></h4>
		<pre><?=var_export($this->client->responseData(), true)?></pre>
		<?php
	}
}