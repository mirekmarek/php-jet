<?php
/**
 *
 * @copyright
 * @license
 * @author
 */
namespace JetApplicationModule\Web\Auth\PasswordReset;

use Jet\DataModel;
use Jet\DataModel_Definition;
use Jet\DataModel_IDController_AutoIncrement;
use Jet\Data_DateTime;
use Jet\Http_Request;
use JetApplication\Auth_Visitor_User as User;

#[DataModel_Definition(
	name: 'visitor_password_reset_token',
	database_table_name: 'visitor_password_reset_token',
	id_controller_class: DataModel_IDController_AutoIncrement::class,
	id_controller_options: [
		'id_property_name' => 'id'
	]
)]
class PasswordResetToken extends DataModel
{
	protected const CODE_LEN = 6;
	protected const TOKEN_TTL = '10 minutes';
	
	#[DataModel_Definition(
		type: DataModel::TYPE_ID_AUTOINCREMENT,
		is_id: true
	)]
	protected int $id = 0;
	
	#[DataModel_Definition(
		type: DataModel::TYPE_INT,
		is_key: true
	)]
	protected int $user_id = 0;
	
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		is_key: true,
		max_len: 255
	)]
	protected string $code = '';
	
	#[DataModel_Definition(
		type: DataModel::TYPE_DATE_TIME
	)]
	protected ?Data_DateTime $generated_date_time = null;
	
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255
	)]
	protected string $generated_by_ip = '';
	
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255
	)]
	protected string $generated_by_user_agent = '';
	
	#[DataModel_Definition(
		type: DataModel::TYPE_DATE_TIME,
		is_key: true
	)]
	protected ?Data_DateTime $valid_till = null;
	
	#[DataModel_Definition(
		type: DataModel::TYPE_BOOL,
		is_key: true
	)]
	protected bool $used = false;
	
	#[DataModel_Definition(
		type: DataModel::TYPE_DATE_TIME
	)]
	protected ?Data_DateTime $used_date_time = null;
	
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255
	)]
	protected string $used_by_ip = '';
	
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255
	)]
	protected string $used_by_user_agent = '';
	
	
	#[DataModel_Definition(
		type: DataModel::TYPE_BOOL,
		is_key: true
	)]
	protected bool $devalidated = false;
	
	public function getId() : int
	{
		return $this->id;
	}
	
	public function getUserId() : int
	{
		return $this->user_id;
	}
	
	public function getCode() : string
	{
		return $this->code;
	}
	
	public function getGeneratedDateTime() : Data_DateTime|null
	{
		return $this->generated_date_time;
	}
	
	
	public function getGeneratedByIp() : string
	{
		return $this->generated_by_ip;
	}
	
	
	public function getGeneratedByUserAgent() : string
	{
		return $this->generated_by_user_agent;
	}
	
	
	public function getValidTill() : Data_DateTime|null
	{
		return $this->valid_till;
	}
	
	
	public function getUsed() : bool
	{
		return $this->used;
	}
	
	
	public function getUsedDateTime() : Data_DateTime|null
	{
		return $this->used_date_time;
	}
	
	
	public function getUsedByIp() : string
	{
		return $this->used_by_ip;
	}
	
	
	public function getUsedByUserAgent() : string
	{
		return $this->used_by_user_agent;
	}
	
	
	public static function generate( User $user ) : static
	{
		static::updateData(
			data: [
				'devalidated' => true
			],
			where: [
				'user_id' => $user->getId(),
				'AND',
				'devalidated' => false
			]
		);
		
		$token = new static();
		
		$token->user_id = $user->getId();
		$token->code = '';
		
		for( $c=0; $c < static::CODE_LEN ; $c++ ) {
			srand();
			
			$token->code .= rand(1, 9);
		}
		
		$token->generated_date_time = Data_DateTime::now();
		$token->generated_by_ip = Http_Request::clientIP();
		$token->generated_by_user_agent = Http_Request::clientUserAgent();
		
		$token->valid_till = new Data_DateTime( date('Y-m-d H:i:s', strtotime('+'.static::TOKEN_TTL)) );
		
		$token->save();
		
		return $token;
	}
	
	public function isValid() : bool
	{
		return (
			!$this->used &&
			!$this->devalidated &&
			$this->valid_till>Data_DateTime::now()
		);
	}
	
	public function used() : void
	{
		$this->used = true;
		$this->used_date_time = Data_DateTime::now();
		$this->used_by_ip = Http_Request::clientIP();
		$this->used_by_user_agent = Http_Request::clientUserAgent();
		
		$this->save();
	}
	
	public static function getValidToken( int $user_id ) : ?static
	{
		$token = static::load([
			'user_id' => $user_id,
			'AND',
			'used' => false,
			'AND',
			'devalidated' => false
		]);
		
		if(
			$token &&
			$token->isValid()
		) {
			return $token;
		}
		
		return null;
	}
	
}
