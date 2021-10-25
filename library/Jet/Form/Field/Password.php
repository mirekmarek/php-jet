<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class Form_Field_Password extends Form_Field
{

	/**
	 * @var string
	 */
	protected static string $default_renderer_script = 'field';

	/**
	 * @var string
	 */
	protected static string $default_row_start_renderer_script = 'field/row/start';

	/**
	 * @var string
	 */
	protected static string $default_row_end_renderer_script = 'field/row/end';

	/**
	 * @var string
	 */
	protected static string $default_input_container_start_renderer_script = 'field/input/container/start';

	/**
	 * @var string
	 */
	protected static string $default_input_container_end_renderer_script = 'field/input/container/end';

	/**
	 * @var string
	 */
	protected static string $default_error_renderer = 'field/error';

	/**
	 * @var string
	 */
	protected static string $default_label_renderer = 'field/label';

	/**
	 * @var string string
	 */
	protected static string $default_input_renderer = 'field/input/password';


	/**
	 * @var string
	 */
	protected string $_type = Form::TYPE_PASSWORD;

	/**
	 * @var bool
	 */
	protected bool $is_required = true;

	/**
	 * @var array
	 */
	protected array $error_messages = [
		self::ERROR_CODE_EMPTY => '',
	];

	/**
	 * @return array
	 */
	public function getRequiredErrorCodes(): array
	{
		$codes = [];

		if( $this->is_required ) {
			$codes[] = self::ERROR_CODE_EMPTY;
		}

		return $codes;
	}

}