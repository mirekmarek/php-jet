<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication\Installer;

use Jet\BaseObject;
use Jet\Db_Backend_Config;
use Jet\Form;
use Jet\Db_Config;
use Jet\DataModel_Config;
use Jet\Db_Backend_PDO_Config;


/**
 *
 */
abstract class Installer_DbDriverConfig extends BaseObject
{
	/**
	 * @var ?Db_Backend_PDO_Config
	 */
	protected ?Db_Backend_Config $connection_config;


	/**
	 * @var ?Form
	 */
	protected ?Form $_form = null;

	/**
	 *
	 * @param Db_Backend_Config|null $connection_config
	 */
	public function __construct( Db_Backend_Config $connection_config = null )
	{
		$this->connection_config = $connection_config;
	}

	/**
	 * @param Db_Config $db_config
	 * @param DataModel_Config $data_model_config
	 *
	 * @return Db_Backend_Config|Db_Backend_PDO_Config
	 */
	abstract public function initialize( Db_Config $db_config, DataModel_Config $data_model_config ) : Db_Backend_Config|Db_Backend_PDO_Config;


	/**
	 * @return Form
	 */
	abstract public function getForm() : Form;

	/**
	 * @return bool
	 */
	abstract public function catchForm() : bool;

}