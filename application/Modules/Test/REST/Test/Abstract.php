<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplicationModule\Test\REST;

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
	 * @var array
	 */
	protected $data;

	/**
	 * @var bool
	 */
	protected $is_selected = false;

	/**
	 * Test_Abstract constructor.
	 *
	 * @param string $id
	 * @param array  $data
	 */
	public function __construct( $id, $data )
	{
		$this->id = $id;

		$this->client = new Client();

		$this->data = $data;
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
		<?php if($this->client->requestBody()): ?>
		<h4><?=Tr::_('Body')?></h4>
		<pre><?=is_string($this->client->requestBody()) ? $this->client->requestBody(): var_dump($this->client->requestBody())?></pre>
		<h4><?=Tr::_('Data')?></h4>
		<pre><?=var_export( $this->client->requestData(), true )?></pre>
		<?php endif; ?>


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