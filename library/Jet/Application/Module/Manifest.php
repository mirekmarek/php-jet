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
class Application_Module_Manifest extends BaseObject
{
	/**
	 *
	 * @var string
	 */
	protected string $_name = '';

	/**
	 * @var string
	 */
	protected string $vendor = '';

	/**
	 * @var string
	 */
	protected string $version = '';


	/**
	 *
	 * @var string
	 */
	protected string $label = '';

	/**
	 *
	 * @var string
	 */
	protected string $description = '';

	/**
	 * @var array
	 */
	protected array $ACL_actions = [];

	/**
	 * @var bool
	 */
	protected bool $is_mandatory = false;

	/**
	 * @var callable
	 */
	protected static $compatibility_checker;

	/**
	 * @return callable
	 */
	public static function getCompatibilityChecker(): callable
	{
		return static::$compatibility_checker;
	}

	/**
	 * @param callable $compatibility_checker
	 */
	public static function setCompatibilityChecker( callable $compatibility_checker ) : void
	{
		static::$compatibility_checker = $compatibility_checker;
	}

	/**
	 * @param ?string $module_name
	 *
	 * @throws Application_Modules_Exception
	 */
	public function __construct( ?string $module_name = null )
	{
		if( !$module_name ) {
			return;
		}

		$this->_name = $module_name;

		$manifest_data = Application_Modules::readManifestData( $module_name );
		$this->checkManifestData( $manifest_data );
		$this->setupProperties( $manifest_data );


	}

	/**
	 * @param array $manifest_data
	 *
	 * @throws Application_Modules_Exception
	 */
	protected function checkManifestData( array $manifest_data )
	{
		if( empty( $manifest_data['label'] ) ) {
			throw new Application_Modules_Exception(
				'Module label not set! (\'label\' array key does not exist, or is empty) (Module: \'' . $this->_name . '\')',
				Application_Modules_Exception::CODE_MANIFEST_NONSENSE
			);
		}
	}

	/**
	 *
	 * @param array $manifest_data
	 *
	 * @throws Application_Modules_Exception
	 */
	protected function setupProperties( array $manifest_data )
	{

		foreach( $manifest_data as $key => $val ) {
			if( !$this->objectHasProperty( $key ) ) {
				throw new Application_Modules_Exception(
					'Unknown manifest property \'' . $key . '\' (Module: \'' . $this->_name . '\') ',
					Application_Modules_Exception::CODE_MANIFEST_NONSENSE
				);
			}

			$this->{$key} = $val;

		}
	}


	/**
	 *
	 * @return string
	 */
	public function getModuleDir(): string
	{
		return Application_Modules::getModuleDir( $this->_name );
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->_name;
	}

	/**
	 * @return string
	 */
	public function getNamespace(): string
	{
		return SysConf_Jet_Modules::getModuleRootNamespace() . '\\' . str_replace( '.', '\\', $this->_name ) . '\\';
	}

	/**
	 * @return string
	 */
	public function getVendor(): string
	{
		return $this->vendor;
	}

	/**
	 * @return string
	 */
	public function getVersion(): string
	{
		return $this->version;
	}

	/**
	 * @return string
	 */
	public function getLabel(): string
	{
		return $this->label;
	}

	/**
	 * @return string
	 */
	public function getDescription(): string
	{
		return $this->description;
	}

	/**
	 * @param bool $translate_description
	 * @param ?Locale $translate_locale
	 *
	 * @return array
	 */
	public function getACLActions( bool $translate_description = true, ?Locale $translate_locale = null ): array
	{
		if( !$translate_description ) {
			return $this->ACL_actions;
		}

		$res = [];

		foreach( $this->ACL_actions as $action => $description ) {
			$res[$action] = Tr::_( $description, [], $this->getName(), $translate_locale );
		}

		return $res;
	}

	/**
	 * @param string $action
	 *
	 * @return bool
	 */
	public function hasACLAction( string $action ): bool
	{
		return array_key_exists( $action, $this->ACL_actions );
	}

	/**
	 * @return bool
	 */
	public function isCompatible(): bool
	{
		if( !static::$compatibility_checker ) {
			return true;
		}

		$checker = static::$compatibility_checker;

		return $checker( $this );
	}

	/**
	 * @return bool
	 */
	public function isMandatory(): bool
	{
		return $this->is_mandatory;
	}

	/**
	 * @return bool
	 */
	public function isInstalled(): bool
	{
		return Application_Modules::moduleIsInstalled( $this->_name );
	}

	/**
	 * @return bool
	 */
	public function isActivated(): bool
	{
		return Application_Modules::moduleIsActivated( $this->_name );
	}

	/**
	 * @return array
	 */
	public function toArray(): array
	{
		$res = [
			'vendor'       => $this->getVendor(),
			'version'      => $this->getVersion(),
			'label'        => $this->getLabel(),
			'description'  => $this->getDescription(),
			'is_mandatory' => $this->isMandatory()
		];

		foreach( $this->getACLActions( false ) as $action => $description ) {
			if( !isset( $res['ACL_actions'] ) ) {
				$res['ACL_actions'] = [];
			}

			$res['ACL_actions'][$action] = $description;
		}

		return $res;
	}

	/**
	 *
	 */
	public function saveDatafile(): void
	{
		Application_Modules::saveManifest( $this );
	}
	
}