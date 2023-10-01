<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\ORM;

use Jet\DataModel;
use Jet\DataModel_Definition;
use Jet\DataModel_IDController_UniqueString;
use Jet\Form;
use Jet\Form_Field;
use Jet\DataModel_Fetch_Instances;
use Jet\Data_DateTime;

/**
 *
 */
#[DataModel_Definition(
	name: 'model_a1',
	database_table_name: 'model_a1',
	id_controller_class: DataModel_IDController_UniqueString::class
)]
class Model_A1 extends DataModel
{

	#[DataModel_Definition(
		type: DataModel::TYPE_ID,
		is_id: true
	)]
	protected string $id = '';

	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255
	)]
	protected string $text = '';

	/**
	 * @var Model_A1_1toN[]
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_DATA_MODEL,
		data_model_class: Model_A1_1toN::class
	)]
	protected array $related_1toN = [];

	/**
	 * @var string
	 */ 
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255
	)]
	protected string $aaa = '';

	/**
	 * @var string
	 */ 
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255
	)]
	protected string $ccc = '';

	/**
	 * @var string
	 */ 
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255
	)]
	protected string $eee = '';

	/**
	 * @var string
	 */ 
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255
	)]
	protected string $fff = '';

	/**
	 * @var string
	 */ 
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255
	)]
	protected string $a1 = '';

	/**
	 * @var string
	 */ 
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255
	)]
	protected string $a2 = '';

	/**
	 * @var string
	 */ 
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255
	)]
	protected string $b1 = '';

	/**
	 * @var string
	 */ 
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255
	)]
	protected string $b2 = '';

	/**
	 * @var string
	 */ 
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255
	)]
	protected string $c1 = '';

	/**
	 * @var string
	 */ 
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255
	)]
	protected string $c2 = '';

	/**
	 * @var ?Data_DateTime
	 */ 
	#[DataModel_Definition(
		type: DataModel::TYPE_DATE
	)]
	protected ?Data_DateTime $c3 = null;

	/**
	 * @var ?Data_DateTime
	 */ 
	#[DataModel_Definition(
		type: DataModel::TYPE_DATE_TIME
	)]
	protected ?Data_DateTime $c4 = null;

	/**
	 * @param string $value
	 */
	public function setEee( string $value ) : void
	{
		$this->eee = $value;
	}

	/**
	 * @return string
	 */
	public function getEee() : string
	{
		return $this->eee;
	}

	/**
	 * @param string $value
	 */
	public function setFff( string $value ) : void
	{
		$this->fff = $value;
	}

	/**
	 * @return string
	 */
	public function getFff() : string
	{
		return $this->fff;
	}

	/**
	 * @param string $value
	 */
	public function setB1( string $value ) : void
	{
		$this->b1 = $value;
	}

	/**
	 * @return string
	 */
	public function getB1() : string
	{
		return $this->b1;
	}

	/**
	 * @param string $value
	 */
	public function setB2( string $value ) : void
	{
		$this->b2 = $value;
	}

	/**
	 * @return string
	 */
	public function getB2() : string
	{
		return $this->b2;
	}

	/**
	 * @param string $value
	 */
	public function setC1( string $value ) : void
	{
		$this->c1 = $value;
	}

	/**
	 * @return string
	 */
	public function getC1() : string
	{
		return $this->c1;
	}

	/**
	 * @param string $value
	 */
	public function setC2( string $value ) : void
	{
		$this->c2 = $value;
	}

	/**
	 * @return string
	 */
	public function getC2() : string
	{
		return $this->c2;
	}

	/**
	 * @param Data_DateTime|string|null $value
	 */
	public function setC3( Data_DateTime|string|null $value ) : void
	{
		$this->c3 = Data_DateTime::catchDate( $value );
	}

	/**
	 * @return Data_DateTime|null
	 */
	public function getC3() : Data_DateTime|null
	{
		return $this->c3;
	}

	/**
	 * @param Data_DateTime|string|null $value
	 */
	public function setC4( Data_DateTime|string|null $value ) : void
	{
		$this->c4 = Data_DateTime::catchDateTime( $value );
	}

	/**
	 * @return Data_DateTime|null
	 */
	public function getC4() : Data_DateTime|null
	{
		return $this->c4;
	}



}