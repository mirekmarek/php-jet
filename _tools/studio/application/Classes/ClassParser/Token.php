<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

/**
 *
 */
class ClassParser_Token
{

	/**
	 * @var int
	 */
	public int $index = 0;

	/**
	 * @var int|string
	 */
	public int|string $id = '';

	/**
	 * @var string
	 */
	public string $text = '';

	/**
	 * @param bool $include_doc_comment
	 * @return bool
	 */
	public function ignore( bool $include_doc_comment = true ): bool
	{
		if( $include_doc_comment ) {
			if(
				$this->id == T_OPEN_TAG ||
				$this->id == T_WHITESPACE ||
				$this->id == T_COMMENT ||
				$this->id == T_DOC_COMMENT
			) {
				return true;
			}
		} else {
			if(
				$this->id == T_OPEN_TAG ||
				$this->id == T_WHITESPACE ||
				$this->id == T_COMMENT
			) {
				return true;
			}

		}

		return false;
	}


	/**
	 * @return string
	 */
	public function debug_getInfo(): string
	{
		if( is_string( $this->id ) ) {
			$name = $this->id;
		} else {
			$name = token_name( $this->id );
		}

		return $this->index . ' ' . $name . ' :"' . $this->text . '"' . PHP_EOL . PHP_EOL;
	}

}
