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
	 * @JetDataModel:default_order_by = ['property_name','-next_property_name', '+some_property_name']
	 */

/**
 *
 * @JetDataModel:id_class_name = 'DataModel_Id_Passive'
 */
abstract class DataModel_Related_MtoN extends BaseObject implements DataModel_Related_MtoN_Interface
{
	use DataModel_Related_MtoN_Trait;

	/**
	 *
	 */
	public function afterLoad()
	{

	}

	/**
	 *
	 */
	public function beforeSave()
	{

	}

	/**
	 *
	 */
	public function afterAdd()
	{

	}

	/**
	 *
	 */
	public function afterUpdate()
	{

	}

	/**
	 *
	 */
	public function afterDelete()
	{

	}

}