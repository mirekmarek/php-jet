<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
trait MVC_Page_Trait_Parameters
{
	/**
	 *
	 * @var array
	 */
	protected array $parameters = [];


	/**
	 * @return array
	 */
	public function getParameters(): array
	{
		return $this->parameters;
	}

	/**
	 * @param array $parameters
	 */
	public function setParameters( array $parameters ): void
	{
		$this->parameters = $parameters;
	}

	/**
	 * @param string $key
	 * @param mixed $default_value
	 *
	 * @return mixed
	 */
	public function getParameter( string $key, mixed $default_value = null ): mixed
	{
		if( !array_key_exists( $key, $this->parameters ) ) {
			return $default_value;
		}

		return $this->parameters[$key];
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 */
	public function setParameter( string $key, mixed $value ): void
	{
		$this->parameters[$key] = $value;
	}

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	public function parameterExists( string $key ): bool
	{
		return array_key_exists( $key, $this->parameters );
	}

}