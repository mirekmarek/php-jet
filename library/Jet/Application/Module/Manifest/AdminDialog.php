<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Application_Module_Manifest_AdminDialog extends BaseObject
{
	/**
	 * @var string
	 */
	protected $dialog_id = '';

	/**
	 * @var string
	 */
	protected $title = '';

	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @var string
	 */
	protected $relative_path_fragment = '';

	/**
	 * @var string
	 */
	protected $layout_script_name = '';

	/**
	 * @var string
	 */
	protected $icon = '';

	/**
	 * @var string
	 */
	protected $action = '';


	/**
	 * @param string $dialog_id
	 * @param array $data
	 *
	 * @return Application_Module_Manifest_AdminDialog
	 */
	public static function create( $dialog_id, array $data )
	{
		$i = Application_Factory::getModuleManifestAdminDialogInstance();

		$i->dialog_id = $dialog_id;

		foreach( $data as $k=>$v ) {
			$i->{$k} = $v;
		}

		return $i;
	}

	/**
	 * @return string
	 */
	public function getDialogId()
	{
		return $this->dialog_id;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @return string
	 */
	public function getRelativePathFragment()
	{
		return $this->relative_path_fragment;
	}

	/**
	 * @return string
	 */
	public function getIcon()
	{
		return $this->icon;
	}

	/**
	 * @return string
	 */
	public function getLayoutScriptName()
	{
		return $this->layout_script_name;
	}

	/**
	 * @return string
	 */
	public function getAction()
	{
		if(!$this->action) {
			$this->action = str_replace('-', '_', $this->getDialogId());
		}

		return $this->action;
	}



	/**
	 * @return array
	 */
	public function asArray()
	{
		return get_object_vars( $this );
	}

}