<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\REST;

use Jet\Tr;

/**
 *
 */
abstract class Test_Abstract
{
	/**
	 * @var string
	 */
	protected string $id = '';

	/**
	 * @var ?Client
	 */
	protected ?Client $client = null;

	/**
	 * @var array|null
	 */
	protected array|null $data = null;

	/**
	 * @var bool
	 */
	protected bool $is_selected = false;

	/**
	 * Test_Abstract constructor.
	 *
	 * @param array $data
	 */
	public function __construct( array $data )
	{
		$id = explode( '\\', static::class);

		$id = substr($id[count($id)-1], 5);

		$this->id = $id;

		$this->client = new Client();

		$this->data = $data;
	}

	/**
	 * @return string
	 */
	public function getId(): string
	{
		return $this->id;
	}

	/**
	 * @return bool
	 */
	public function isSelected(): bool
	{
		return $this->is_selected;
	}

	/**
	 * @param bool $is_selected
	 */
	public function setIsSelected( bool $is_selected ): void
	{
		$this->is_selected = $is_selected;
	}

	/**
	 * @return Client
	 */
	public function getClient(): Client
	{
		return $this->client;
	}

	/**
	 * @return bool
	 */
	public function isEnabled(): bool
	{
		return true;
	}


	/**
	 * @return string
	 */
	public function getTitle(): string
	{
		return Tr::_( $this->_getTitle() );
	}

	/**
	 * @return string
	 */
	abstract protected function _getTitle(): string;

	/**
	 *
	 */
	abstract public function test(): void;

	/**
	 *
	 */
	public function showResult(): void
	{
		?>
		<h3><?= Tr::_( 'Request' ) ?></h3>
		<h4><?= Tr::_( 'Header' ) ?></h4>
		<pre><?= $this->client->request() ?></pre>
		<?php if( $this->client->requestBody() ): ?>
		<h4><?= Tr::_( 'Body' ) ?></h4>
		<pre><?= is_string( $this->client->requestBody() ) ? $this->client->requestBody() : print_r( $this->client->requestBody(), true ) ?></pre>
		<h4><?= Tr::_( 'Data' ) ?></h4>
		<pre><?= var_export( $this->client->requestData(), true ) ?></pre>
		<?php endif; ?>


		<h3><?= Tr::_( 'Response' ) ?></h3>
		<h4><?= Tr::_( 'Header' ) ?></h4>
		<pre><?= $this->client->responseHeader() ?></pre>
		<h4><?= Tr::_( 'Body' ) ?></h4>
		<pre><?= $this->client->responseBody() ?></pre>
		<h4><?= Tr::_( 'Data' ) ?></h4>
		<pre><?= var_export( $this->client->responseData(), true ) ?></pre>
		<?php
	}
}