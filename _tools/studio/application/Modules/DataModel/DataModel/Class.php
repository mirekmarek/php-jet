<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\DataModel;

use JetStudio\ClassMetaInfo;
use Jet\DataModel_Definition_Model_Main as Jet_DataModel_Definition_Model_Main;
use Jet\DataModel_Definition_Model_Related_1to1 as Jet_DataModel_Definition_Model_Related_1to1;
use Jet\DataModel_Definition_Model_Related_1toN as Jet_DataModel_Definition_Model_Related_1toN;

class DataModel_Class extends ClassMetaInfo
{

	protected
	Jet_DataModel_Definition_Model_Main|
	Jet_DataModel_Definition_Model_Related_1to1|
	Jet_DataModel_Definition_Model_Related_1toN|
	DataModel_Definition_Model_Main|
	DataModel_Definition_Model_Related_1to1|
	DataModel_Definition_Model_Related_1toN|
	null $definition = null;


	public function getDefinition():
		Jet_DataModel_Definition_Model_Main|
		Jet_DataModel_Definition_Model_Related_1to1|
		Jet_DataModel_Definition_Model_Related_1toN|
		DataModel_Definition_Model_Main|
		DataModel_Definition_Model_Related_1to1|
		DataModel_Definition_Model_Related_1toN
	{
		return $this->definition;
	}

	public function setDefinition(
		Jet_DataModel_Definition_Model_Main|
		Jet_DataModel_Definition_Model_Related_1to1|
		Jet_DataModel_Definition_Model_Related_1toN|
		DataModel_Definition_Model_Main|
		DataModel_Definition_Model_Related_1to1|
		DataModel_Definition_Model_Related_1toN
		$definition
	) : void
	{
		if(
			$definition instanceof DataModel_Definition_Model_Main ||
			$definition instanceof DataModel_Definition_Model_Related_1to1 ||
			$definition instanceof DataModel_Definition_Model_Related_1toN
		) {
			$definition->setClass( $this );
		}

		$this->definition = $definition;
	}
	
	public static function get( string $class_name ): ?static
	{
		return DataModels::getClass( $class_name );
	}
}
