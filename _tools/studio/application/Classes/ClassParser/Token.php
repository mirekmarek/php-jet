<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

/**
 *
 */
class ClassParser_Token {

	/**
	 * @var int
	 */
	public $index = 0;

	/**
	 * @var int
	 */
	public $id    = 0;

	/**
	 * @var string
	 */
	public $text  = '';

	/**
	 * @param bool $include_doc_comment
	 * @return bool
	 */
	public function ignore( $include_doc_comment=true )
	{
		if( $include_doc_comment ) {
			if(
				$this->id==T_OPEN_TAG ||
				$this->id==T_WHITESPACE ||
				$this->id==T_COMMENT ||
				$this->id==T_DOC_COMMENT
			) {
				return true;
			}
		} else {
			if(
				$this->id==T_OPEN_TAG ||
				$this->id==T_WHITESPACE
			) {
				return true;
			}

		}

		return false;
	}


	/**
	 * @return string
	 */
	public function debug_getInfo()
	{
		if(is_string($this->id)) {
			$name = $this->id;
		} else {
			$name = token_name($this->id);
		}

		return $this->index.' '.$name.' :"'.$this->text.'"'.PHP_EOL.PHP_EOL;
	}

}
