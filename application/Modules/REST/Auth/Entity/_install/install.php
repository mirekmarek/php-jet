<?php
namespace JetApplicationModule\REST\Auth\Entity;

use Jet\DataModel_Helper;

DataModel_Helper::create( APIUser::class );
DataModel_Helper::create( APIUser_Roles::class );
DataModel_Helper::create( Role::class );
DataModel_Helper::create( Role_Privilege::class );