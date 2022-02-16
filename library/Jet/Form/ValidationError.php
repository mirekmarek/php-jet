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
class Form_ValidationError extends BaseObject implements BaseObject_Interface_Serializable_JSON
{
	protected Form_Field $field;
	
	/**
	 * @var string
	 */
	protected string $code = '';
	
	/**
	 * @var string
	 */
	protected string $message = '';
	
	/**
	 * @param Form_Field $field
	 * @param string $code
	 * @param string $message
	 */
	public function __construct( Form_Field $field, string $code, string $message )
	{
		$this->field = $field;
		$this->code = $code;
		$this->message = $message;
	}
	
	/**
	 * @return Form_Field
	 */
	public function getField(): Form_Field
	{
		return $this->field;
	}
	
	/**
	 * @return string
	 */
	public function getCode(): string
	{
		return $this->code;
	}
	
	/**
	 * @return string
	 */
	public function getMessage(): string
	{
		return $this->message;
	}
	
	/**
	 * @return string
	 */
	public function __toString() : string
	{
		return $this->message;
	}
	
	/**
	 * @return string
	 */
	public function toJSON(): string
	{
		$data = $this->jsonSerialize();
		
		return json_encode( $data );
	}
	
	/**
	 * @return array
	 */
	public function jsonSerialize() : array
	{
		return [
			'field' => $this->field->getName(),
			'code' => $this->code,
			'message' => $this->message
		];
	}
}