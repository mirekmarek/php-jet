<?php
namespace JetApplicationModule\Admin\Auth\Entity;

use Jet\DataModel_Helper;

DataModel_Helper::create( Administrator::class );
DataModel_Helper::create( Administrator_Roles::class );
DataModel_Helper::create( Role::class );
DataModel_Helper::create( Role_Privilege::class );