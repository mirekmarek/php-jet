<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

class Validator_ValidationError extends BaseObject implements BaseObject_Interface_Serializable_JSON
{
	protected Validator $validator;
	
	protected string $code = '';
	protected string $message = '';
	/**
	 * @var array<string,mixed>
	 */
	protected array $error_data = [];
	
	/**
	 * @param Validator $validator
	 * @param string $code
	 * @param string $message
	 * @param array<string,mixed> $error_data
	 */
	public function __construct( Validator $validator, string $code, string $message, array $error_data )
	{
		$this->validator = $validator;
		$this->code = $code;
		$this->message = $message;
		$this->error_data = $error_data;
	}
	
	public function getValidator(): Validator
	{
		return $this->validator;
	}
	
	
	public function getCode(): string
	{
		return $this->code;
	}
	
	public function getMessage(): string
	{
		return $this->message;
	}
	
	/**
	 * @return array<string,mixed>
	 */
	public function getErrorData(): array
	{
		return $this->error_data;
	}
	
	
	
	
	public function toJSON(): string
	{
		$data = $this->jsonSerialize();
		
		return json_encode( $data );
	}
	
	/**
	 * @return array<string,string>
	 */
	public function jsonSerialize() : array
	{
		return [
			'validator' => $this->validator::getType(),
			'message' => $this->message,
			'code' => $this->code,
		];
	}
}