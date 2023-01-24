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
class Form_Field_CSRFProtection extends Form_Field
{
	/**
	 * @var string
	 */
	protected string $_type = Form_Field::TYPE_CSRF_PROTECTION;
	
	protected ?Session $session = null;
	
	/**
	 * @return array
	 */
	public function getRequiredErrorCodes(): array
	{
		return [];
	}
	
	public function __construct( string $name='', string $label = '' )
	{
		if(!$name) {
			$name = SysConf_Jet_Form::getCSRFProtection_TokenFieldName();
		}
		
		parent::__construct( $name, $label );
	}
	
	/**
	 * @return Session
	 */
	protected function getSession() : Session
	{
		if(!$this->session) {
			$this->session = new Session(
				SysConf_Jet_Form::getCSRFProtection_SessionNamePrefix()
				.$this->getForm()->getName()
			);
		}
		
		return $this->session;
	}
	
	protected function generateToken() : void
	{
		$generator = SysConf_Jet_Form::getCSRFProtection_TokenGenerator();

		$this->getSession()->setValue('token', $generator() );
	}
	
	public function getToken() : string
	{
		return $this->getSession()->getValue( 'token' );
	}
	
	public function render(): string
	{
		$this->generateToken();
		$this->setValue( $this->getToken() );
		
		return (string)$this->input();
	}
	
	public function validate(): bool
	{
		if($this->getValueRaw()!=$this->getToken()) {
			return false;
		}
		
		$this->setIsValid();
		return true;
	}
}